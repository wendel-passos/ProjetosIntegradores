<?php

namespace Sts\Models;

if (!defined('URL')) {
    header("Location: /");
    exit();
}

class StsStartMap
{
    private $data;

    public function __construct($data = NULL)
    {
        $this->data = $data;
    }

    // ********************************************************************
    // FUNÇÃO PARA PEGAR BORRACHARIAS CADASTRADAS
    public function getBorracharias()
    {
        $pdoSelect = new \Helper\Read();
        $pdoSelect->fullRead(
            "SELECT borracharia.nome, borracharia.cnpj, borracharia.email,
                    telefone.telefone,
                    endereco.coords_lat, endereco.coords_lat, endereco.coords_lng, endereco.cep, endereco.cidade, endereco.estado, endereco.bairro, endereco.rua, endereco.numero, endereco.complemento
            FROM sts_estabelecimento AS borracharia 
            LEFT JOIN sts_telefone_estabelecimento AS telefone ON telefone.id_telefone_estabelecimento = borracharia.id_estabelecimento 
            LEFT JOIN sts_endereco_estabelecimento AS endereco ON endereco.id_endereco_estabelecimento = borracharia.id_estabelecimento
            WHERE fk_estabelecimento_status = :status AND fk_id_tipo = :type",
            "status=2&type=1"
        );

        $this->data['result'] = $pdoSelect->getResult();

        if (isset($this->data['result']) or !empty($this->data['result'])) {

            $f = new \Helper\Format;
            $this->data['result'][0]['cnpj'] = $f->maskAllData($this->data['result'][0]['cnpj'], 'cnpj');
            $this->data['result'][0]['telefone'] = $f->maskAllData($this->data['result'][0]['telefone'], 'tel');
            $this->data['result'][0]['cep'] = $f->maskAllData($this->data['result'][0]['cep'], 'cep');

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
                "msg" => 'Erro S500: Falha ao carregar borracharias. Se o erro persistir entre em contato com nosso atendimento.',
            );

            echo json_encode($return, JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
}
