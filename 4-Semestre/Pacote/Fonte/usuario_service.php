<?php

    //CRUD
    class UsuarioService {

        private $conexao;
        private $usuario;

        public function __construct(Conexao $conexao, Usuario $usuario) {
            $this->conexao = $conexao->conectar();
            $this->usuario = $usuario;
        }

        // ---------------- Validar Login ----------------
        public function validarLogin() {
            $query = "SELECT id, nome, email FROM usuarios WHERE email = :email AND senha = :senha";
            $stmt = $this->conexao->prepare($query); 
            $stmt->bindValue(':email', $this->usuario->__get('email'));
            $stmt->bindValue(':senha', $this->usuario->__get('senha'));
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        // ---------------- Inserir dados do cadastro ----------------
        public function inserir() { 
            $query = 'INSERT INTO usuarios (nome, cpf, dt_nascimento, telefone, email, senha, rua, numero, bairro, cidade, estado, modelo_moto, pneu_utilizado, modelo_pneu, tp_medio_troca)
            VALUES (:nome, :cpf, :dt_nascimento, :telefone, :email, :senha, :rua, :numero, :bairro, :cidade, :estado, :modelo_moto, :pneu_utilizado, :modelo_pneu, :tp_medio_troca)';
            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':nome', $this->usuario->__get('nome'));
            $stmt->bindValue(':cpf', $this->usuario->__get('cpf'));
            $stmt->bindValue(':dt_nascimento', $this->usuario->__get('dt_nascimento'));
            $stmt->bindValue(':telefone', $this->usuario->__get('telefone'));
            $stmt->bindValue(':email', $this->usuario->__get('email'));
            $stmt->bindValue(':senha', $this->usuario->__get('senha'));
            $stmt->bindValue(':rua', $this->usuario->__get('rua'));
            $stmt->bindValue(':numero', $this->usuario->__get('numero'));
            $stmt->bindValue(':bairro', $this->usuario->__get('bairro'));
            $stmt->bindValue(':cidade', $this->usuario->__get('cidade'));
            $stmt->bindValue(':estado', $this->usuario->__get('estado'));
            $stmt->bindValue(':modelo_moto', $this->usuario->__get('modelo_moto'));
            $stmt->bindValue(':pneu_utilizado', $this->usuario->__get('pneu_utilizado'));
            $stmt->bindValue(':modelo_pneu', $this->usuario->__get('modelo_pneu'));
            $stmt->bindValue(':tp_medio_troca', $this->usuario->__get('tp_medio_troca'));
            $stmt->execute();
        }

        // ---------------- Recuperar dados do usuário ----------------
        public function recuperar() {
            $query = "SELECT nome, cpf, dt_nascimento, telefone, email, senha, rua, numero, bairro, cidade, estado, modelo_moto, pneu_utilizado, modelo_pneu, tp_medio_troca
            FROM usuarios WHERE id = :id";
            $stmt = $this->conexao->prepare($query); 
            $stmt->bindValue(':id', $this->usuario->__get('id'));
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } 

    }

?>

