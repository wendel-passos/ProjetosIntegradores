<?php

namespace Sts\Models;

if (!defined('URL')) {
    header("Location: /");
    exit();
}

class StsPedido
{
    private $data;

    public function __construct($data = NULL)
    {
        $this->data = $data;
    }

    // ********************************************************************
    // SELECT TODOS DADOS DO USUÁRIO
    public function getOrders()
    {
        $pdoSelect = new \Helper\Read();
        $pdoSelect->fullRead(
            "SELECT 
                    pedido.id_pedido, pedido.quantidade, pedido.valor AS valor_total, pedido.dt_created,
                    produto.descricao, produto.valor, 
                    estabelecimento.nome AS nome_estabelecimento,
                    endereco_estabelecimento.cep, endereco_estabelecimento.cidade, endereco_estabelecimento.estado, endereco_estabelecimento.bairro, endereco_estabelecimento.rua, endereco_estabelecimento.numero, endereco_estabelecimento.complemento,
                    telefone_estabelecimento.telefone,
                    status_pedido.desc_status AS status
                FROM sts_pedido AS pedido
                INNER JOIN sts_produto AS produto ON produto.id_produto = pedido.fk_id_produto
                INNER JOIN sts_status AS status_pedido ON status_pedido.id_status = pedido.fk_id_status
                INNER JOIN sts_estabelecimento AS estabelecimento ON estabelecimento.id_estabelecimento = pedido.fk_id_estabelecimento
                INNER JOIN sts_endereco_estabelecimento AS endereco_estabelecimento ON endereco_estabelecimento.fk_id_estabelecimento = estabelecimento.id_estabelecimento
                INNER JOIN sts_telefone_estabelecimento AS telefone_estabelecimento ON telefone_estabelecimento.fk_id_estabelecimento = estabelecimento.id_estabelecimento
                WHERE pedido.fk_id_usuario = :id ORDER BY pedido.dt_created DESC",
            "id={$_SESSION['id_usuario']}"
        );

        $this->data['result'] = $pdoSelect->getResult();

        if (isset($this->data['result']) or !empty($this->data['result'])) {

            $f = new \Helper\Format;
            foreach ($this->data['result'] as $key => $value) {
                $this->data['result'][$key]['dt_created'] = $f->formatDateBr($value['dt_created']);
                $this->data['result'][$key]['telefone'] = $f->maskAllData($value['telefone'], 'tel');
                $this->data['result'][$key]['cep'] = $f->maskAllData($value['cep'], 'cep');
                $this->data['result'][$key]['valor_total'] = $f->formatCoin($value['valor_total'], 'real', true);
                $this->data['result'][$key]['valor'] = $f->formatCoin($value['valor'], 'real', true);
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
                "cod" => 700,
                "msg" => 'Erro S700: Falha ao carregar dados. Se o erro persistir entre em contato com nosso atendimento.',
            );

            echo json_encode($return, JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
}
