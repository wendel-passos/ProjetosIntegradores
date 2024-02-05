<?php

namespace Sts\Models;

if (!defined('URL')) {
    header("Location: /");
    exit();
}

class StsCompraColetiva
{
    private $data;

    public function __construct($data = NULL)
    {
        $this->data = $data;
    }

    // ********************************************************************
    // FUNÇÃO PARA PEGAR LISTA DE COMPRAS COLETIVA
    public function getProducts()
    {
        $pdoSelect = new \Helper\Read();

        $query = '
            SELECT 
                lista_compra.id_lista_compra, lista_compra.quantidade_finalizar_compra, lista_compra.quantidade_atual_compra,
                produto.id_produto, produto.descricao, produto.valor, produto.imagem, 
                estabelecimento.nome AS nome_estabelecimento,
                endereco_estabelecimento.cep, endereco_estabelecimento.cidade, endereco_estabelecimento.estado, endereco_estabelecimento.bairro, endereco_estabelecimento.rua, endereco_estabelecimento.numero, endereco_estabelecimento.complemento
            FROM sts_lista_compra AS lista_compra
            INNER JOIN sts_produto AS produto ON produto.id_produto = lista_compra.fk_id_produto
            INNER JOIN sts_estabelecimento AS estabelecimento ON estabelecimento.id_estabelecimento = lista_compra.fk_id_estabelecimento
            INNER JOIN sts_endereco_estabelecimento AS endereco_estabelecimento ON endereco_estabelecimento.fk_id_estabelecimento = estabelecimento.id_estabelecimento
        ';

        if (isset($this->data['id_lista_compra']) or !empty($this->data['id_lista_compra'])) {
            $query .= "WHERE lista_compra.id_lista_compra =" . $this->data['id_lista_compra'];
        } else {
            $query .= 'WHERE lista_compra.quantidade_atual_compra < lista_compra.quantidade_finalizar_compra';
        }

        $pdoSelect->fullRead($query);

        $this->data['result'] = $pdoSelect->getResult();

        if (isset($this->data['result']) or !empty($this->data['result'])) {

            $f = new \Helper\Format;
            foreach ($this->data['result'] as $key => $value) {
                $this->data['result'][$key]['valorUS'] = $value['valor'];
                $this->data['result'][$key]['valor'] = $f->formatCoin($value['valor'], 'real', true);
                $this->data['result'][$key]['restam'] = $this->data['result'][$key]['quantidade_finalizar_compra'] - $this->data['result'][$key]['quantidade_atual_compra'];
            }
            $return = array(
                "cod" => 0,
                "msg" => 'Pesquisa realizada com sucesso!',
                "res" => $this->data['result']
            );

            echo json_encode($return, JSON_UNESCAPED_UNICODE);
            exit;
        } else {
            $return = array(
                "cod" => 600,
                "msg" => 'Erro S600: Falha ao carregar dados. Se o erro persistir entre em contato com nosso atendimento.',
            );

            echo json_encode($return, JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    // ********************************************************************
    // VALIDA SE OS CAMPOS ESTÃO CERTO E SE O E-MAIL OU CPF JÁ EXISTE
    public function cadOrder()
    {
        $u = new \Helper\Utils;
        if ($u->valNumberThree($this->data['quantidade']) and  $u->valNumberThree($this->data['id_lista_compra'])) {

            $f = new \Helper\Format;
            $this->data['quantidade'] = $f->onlyNumbers($this->data['quantidade']);
            $this->data['id_lista_compra'] = $f->onlyNumbers($this->data['id_lista_compra']);

            $pdoSelect = new \Helper\Read();
            $pdoSelect->fullRead(
                "SELECT 
                        lista_compra.quantidade_finalizar_compra, lista_compra.quantidade_atual_compra, lista_compra.fk_id_estabelecimento, lista_compra.fk_id_produto,
                        produto.valor
                        FROM sts_lista_compra AS lista_compra
                        INNER JOIN sts_produto AS produto ON produto.id_produto = lista_compra.fk_id_produto
                        WHERE id_lista_compra = :id_lista_compra",
                "id_lista_compra={$this->data['id_lista_compra']}"
            );

            $this->data['result'] = $pdoSelect->getResult();

            if (isset($this->data['result'][0]) or !empty($this->data['result'][0])) {
                if ($this->data['result'][0]['quantidade_finalizar_compra'] > $this->data['result'][0]['quantidade_atual_compra']) {
                    $this->setOrder();
                } else {

                    $return = array(
                        "cod" => 610,
                        "msg" => 'Erro S610: Essa lista atingiu o limite máximo!',
                    );

                    echo json_encode($return, JSON_UNESCAPED_UNICODE);
                    exit;
                }
            } else {

                $return = array(
                    "cod" => 620,
                    "msg" => 'Erro S620: Lista não encontrada!',
                );

                echo json_encode($return, JSON_UNESCAPED_UNICODE);
                exit;
            }
        } else {
            $return = array(
                "cod" => 630,
                "msg" => 'Erro S630: Dados inválidos, tente novamente.',
            );

            echo json_encode($return, JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    // ********************************************************************
    // INSERT DADOS DO PEDIDO
    public function setOrder()
    {
        $this->data['insert_order'] = [
            "quantidade" => $this->data['quantidade'],
            "valor" => $this->data['result'][0]['valor'] * $this->data['quantidade'],
            "fk_id_produto" => $this->data['result'][0]['fk_id_produto'],
            "fk_id_usuario" => $_SESSION['id_usuario'],
            "fk_id_lista_compra" => $this->data['id_lista_compra'],
            "fk_id_estabelecimento" => $this->data['result'][0]['fk_id_estabelecimento'],
            "fk_id_status" => 1,
            "dt_created" => date("Y-m-d H:i:s")
        ];

        $pdoCreate = new \Helper\Create();
        $pdoCreate->exeCreate("sts_pedido", $this->data['insert_order']);

        if ($pdoCreate->getResult() != NULL) {
            $this->updateList();
        } else {
            $return = array(
                "cod" => 640,
                "msg" => 'Erro S640: Tente novamente. Se o erro persistir entre em contato com nosso atendimento',
            );

            echo json_encode($return, JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    // ********************************************************************
    // ATUALIZA LISTA DE COMPRAS
    public function updateList()
    {
        $this->data['update_list'] = [
            "quantidade_atual_compra" => $this->data['quantidade'] + $this->data['result'][0]['quantidade_atual_compra'],
        ];

        $pdoUpdate = new \Helper\Update();
        $pdoUpdate->exeUpdate(
            "sts_lista_compra",
            $this->data['update_list'],
            "WHERE id_lista_compra = :id_lista_compra",
            "id_lista_compra={$this->data['id_lista_compra']}"
        );

        $this->data['result'] = $pdoUpdate->getResult();

        if ($this->data['result']) {
            $return = array(
                "cod" => 0,
                "msg" => 'Pedido realizo com sucesso! Você pode verificar o status da compra na seção "Pedidos".',
            );

            echo json_encode($return, JSON_UNESCAPED_UNICODE);
            exit;
        } else {

            $return = array(
                "cod" => 650,
                "msg" => 'Erro S650: Tente novamente. Se o erro persistir entre em contato com nosso atendimento',
            );

            echo json_encode($return, JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
}
