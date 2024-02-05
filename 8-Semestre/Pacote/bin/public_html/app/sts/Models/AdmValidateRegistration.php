<?php

namespace Sts\Models;

if (!defined('URL')) {
    header("Location: /");
    exit();
}

class AdmValidateRegistration
{
    private $data;

    public function __construct($data = NULL)
    {
        $this->data = $data;
    }

    // ********************************************************************
    // FUNÇÃO PARA PEGAR OS REGISTROS PENDENTES
    public function getRegisters()
    {
        $pdoSelect = new \Helper\Read();
        $pdoSelect->fullRead(
            "SELECT borracharia.id_estabelecimento, borracharia.nome, borracharia.cnpj, borracharia.email, borracharia.fk_id_tipo,
                    telefone.telefone,
                    endereco.coords_lat, endereco.coords_lat, endereco.coords_lng, endereco.cep, endereco.cidade, endereco.estado, endereco.bairro, endereco.rua, endereco.numero, endereco.complemento
            FROM sts_estabelecimento AS borracharia 
            LEFT JOIN sts_telefone_estabelecimento AS telefone ON telefone.id_telefone_estabelecimento = borracharia.id_estabelecimento 
            LEFT JOIN sts_endereco_estabelecimento AS endereco ON endereco.id_endereco_estabelecimento = borracharia.id_estabelecimento
            WHERE fk_estabelecimento_status = :status",
            "status=1"
        );

        $this->data['result'] = $pdoSelect->getResult();

        if (isset($this->data['result']) or !empty($this->data['result'])) {

            $f = new \Helper\Format;
            foreach ($this->data['result'] as $key => $value) {
                $this->data['result'][$key]['cnpj'] = $f->maskAllData($value['cnpj'], 'cnpj');
                $this->data['result'][$key]['telefone'] = $f->maskAllData($value['telefone'], 'tel');
                $this->data['result'][$key]['cep'] = $f->maskAllData($value['cep'], 'cep');
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
                "cod" => 500,
                "msg" => 'Erro S500: Falha ao carregar os estabelecimentos. Se o erro persistir entre em contato com nosso atendimento.',
            );

            echo json_encode($return, JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    // ********************************************************************
    // FUNÇÃO PARA RECUSAR O REGISTRO
    public function refuseRegister($id)
    {
        $this->data['update'] = [
            "fk_estabelecimento_status" => 3,
        ];

        $pdoUpdate = new \Helper\Update();
        $pdoUpdate->exeUpdate(
            "sts_estabelecimento",
            $this->data['update'],
            "WHERE id_estabelecimento = :id",
            "id={$id}"
        );

        $this->data['result'] = $pdoUpdate->getResult();

        if ($this->data['result']) {

            $return = array(
                "cod" => 0,
                "msg" => 'Estabelecimento recusado com sucesso!'
            );

            echo json_encode($return, JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    // ********************************************************************
    // FUNÇÃO PARA ACEITAR O REGISTRO
    public function acceptRegister($id)
    {
        $this->data['update'] = [
            "fk_estabelecimento_status" => 2,
        ];

        $pdoUpdate = new \Helper\Update();
        $pdoUpdate->exeUpdate(
            "sts_estabelecimento",
            $this->data['update'],
            "WHERE id_estabelecimento = :id",
            "id={$id}"
        );

        $this->data['result'] = $pdoUpdate->getResult();

        if ($this->data['result']) {

            $return = array(
                "cod" => 0,
                "msg" => 'Estabelecimento aprovado com sucesso!'
            );

            echo json_encode($return, JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
}
