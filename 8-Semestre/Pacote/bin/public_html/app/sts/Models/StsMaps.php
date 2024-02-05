<?php

namespace Sts\Models;

if (!defined('URL')) {
    header("Location: /");
    exit();
}

class StsMaps
{
    private $data;

    public function __construct($data = NULL)
    {
        $this->data = $data;
    }

    // ********************************************************************
    // FUNÇÃO PARA PEGAR BORRACHARIAS CADASTRADAS
    public function getEstablishment($type)
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
            "status=2&type={$type}"
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
                "msg" => 'Erro S500: Falha ao carregar estabelecimentos. Se o erro persistir entre em contato com nosso atendimento.',
            );

            echo json_encode($return, JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    // ********************************************************************
    // VALIDA SE O E-MAIL, TELEFONE OU COORDENADAS JÁ EXISTE NA BASE
    public function cadEstablishment()
    {
        $u = new \Helper\Utils;
        if ($u->valStringTwo($this->data['nome'], 60)) {

            if ($u->valPhone($this->data['telefone']) or $u->valEmail($this->data['email'])) {

                $f = new \Helper\Format;
                $this->data['telefone'] = $f->onlyNumbers($this->data['telefone']);
                $this->data['email'] = strtolower($this->data['email']);
                $this->data['coords'] = $f->formatCoords($this->data['coords']);

                $pdoSelect = new \Helper\Read();
                $pdoSelect->fullRead(
                    "SELECT borracharia.email, borracharia.fk_estabelecimento_status,
                            telefone.telefone,
                            endereco.coords_lat, endereco.coords_lng
                     FROM sts_estabelecimento AS borracharia
                     LEFT JOIN sts_telefone_estabelecimento AS telefone ON telefone.id_telefone_estabelecimento  = borracharia.id_estabelecimento
                     LEFT JOIN sts_endereco_estabelecimento AS endereco ON endereco.id_endereco_estabelecimento = borracharia.id_estabelecimento
                     WHERE borracharia.email = :email 
                     OR telefone.telefone = :telefone 
                     OR endereco.coords_lat = :coords_lat 
                     OR endereco.coords_lng = :coords_lng",
                    "email={$this->data['email']}&telefone={$this->data['telefone']}&coords_lat={$this->data['coords'][0]}&coords_lng={$this->data['coords'][1]}"
                );

                $this->data['result'] = $pdoSelect->getResult();

                if (!isset($this->data['result'][0]) or empty($this->data['result'][0])) {
                    $this->setEstablishment();
                } else {

                    foreach ($this->data['result'] as $index) {
                        extract($index);

                        if ($this->data['email'] == $email) {
                            $erro = 510;
                            $msg = 'Erro S510: E-mail e ou telefone já cadastrado no sistema!';
                        } else if ($this->data['telefone'] == $telefone) {
                            $erro = 520;
                            $msg = 'Erro S520: E-mail e ou telefone já cadastrado no sistema!';
                        } else if ($this->data['coords'][0] = $coords_lat or $this->data['coords'][1] = $coords_lng) {

                            if ($this->data['coords'][0] !== $coords_lat or $this->data['coords'][1] !== $coords_lng) {
                                $this->setEstablishment();
                                break;
                            }

                            $erro = 530;
                            $msg = 'Erro S530: Coordenadas já cadastrada em nosso sistema!';
                        }

                        if (isset($msg) or !empty($msg)) {
                            if (isset($fk_estabelecimento_status)) {
                                $msg .= '<br>STATUS: Aguardando confirmação de e-mail.';
                            } else if ($fk_estabelecimento_status == 1) {
                                $msg .= '<br>STATUS: Em fase de aprovação.';
                            }

                            $msg .= ' Em caso de dúvidas entre em contato com nosso atendimento.';
                        }
                    }

                    $return = array(
                        "cod" => $erro,
                        "msg" => $msg,
                    );

                    echo json_encode($return, JSON_UNESCAPED_UNICODE);
                    exit;
                }
            } else {
                $return = array(
                    "cod" => 540,
                    "msg" => 'Erro S540: Preencha pelo menos um dos campos para contato de forma correta.',
                );

                echo json_encode($return, JSON_UNESCAPED_UNICODE);
                exit;
            }
        } else {
            $return = array(
                "cod" => 550,
                "msg" => 'Erro S550: Preencha o campo nome sem caracteres especiais e ate 60 dígitos.',
            );

            echo json_encode($return, JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    // ********************************************************************
    // INSERT DADOS USUÁRIO
    public function setEstablishment()
    {
        $this->data['insert_borracharia'] = [
            "nome" => $this->data['nome'],
            "email" => $this->data['email'],
            "fk_id_tipo" => $this->data['type'],
            "fk_estabelecimento_status" => 1,
            "dt_created" => date("Y-m-d H:i:s")
        ];

        $pdoCreate = new \Helper\Create();
        $pdoCreate->exeCreate("sts_estabelecimento", $this->data['insert_borracharia']);

        if ($pdoCreate->getResult() != NULL) {
            $this->data['id'] = $pdoCreate->getResult();
            $this->setPhone();
        } else {
            $return = array(
                "cod" => 560,
                "msg" => 'Erro S560: Tente novamente. Se o erro persistir entre em contato com nosso atendimento',
            );

            echo json_encode($return, JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    // ********************************************************************
    // INSERT DADOS TELEFONE
    public function setPhone()
    {
        $this->data['insert_phone'] = [
            "telefone" => $this->data['telefone'],
            "id_telefone_estabelecimento" => $this->data['id'],
            "fk_id_estabelecimento" => $this->data['id'],
            "fk_telefone_status" => 1,
            "dt_created" => date("Y-m-d H:i:s")
        ];

        $pdoCreate = new \Helper\Create();
        $pdoCreate->exeCreate("sts_telefone_estabelecimento", $this->data['insert_phone']);

        if ($pdoCreate->getResult() != NULL) {
            $this->setAdress();
        } else {

            $return = array(
                "cod" => 570,
                "msg" => 'Erro S570: Tente novamente. Se o erro persistir entre em contato com nosso atendimento',
            );

            echo json_encode($return, JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    // ********************************************************************
    // INSERT DADOS ENDERECO
    public function setAdress()
    {
        $this->data['insert_adress'] = [
            "coords_lat" => $this->data['coords'][0],
            "coords_lng" => $this->data['coords'][1],
            "id_endereco_estabelecimento" => $this->data['id'],
            "fk_id_estabelecimento" => $this->data['id'],
            "fk_endereco_status" => 2,
            "dt_created" => date("Y-m-d H:i:s")
        ];

        $pdoCreate = new \Helper\Create();
        $pdoCreate->exeCreate("sts_endereco_estabelecimento", $this->data['insert_adress']);

        if ($pdoCreate->getResult() != NULL) {
            $return = array(
                "cod" => 0,
                "msg" => 'Cadastro realizado com sucesso! O estabelecimento cadastrado passará por um processo de aprovação que irá durar até 72h úteis.',
            );

            echo json_encode($return, JSON_UNESCAPED_UNICODE);
            exit;
        } else {

            $return = array(
                "cod" => 580,
                "msg" => 'Erro S580: Tente novamente. Se o erro persistir entre em contato com nosso atendimento',
            );

            echo json_encode($return, JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
}
