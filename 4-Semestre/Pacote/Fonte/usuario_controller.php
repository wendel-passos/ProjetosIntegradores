<?php

    require "conexao.php";
    require "usuario_model.php";
    require "usuario_service.php";

    session_start();

    // ---------------- Validar Login ----------------
    if(isset($_POST['login'])) {

        if(!empty($_POST['email']) && !empty($_POST['senha'])) {

            $usuario = new Usuario();
            $usuario->__set('email', $_POST['email']);
            $usuario->__set('senha', $_POST['senha']);

            $conexao = new Conexao();

            $usuarioService = new UsuarioService($conexao, $usuario);
            $existeUsuario = $usuarioService->validarLogin();

            if($existeUsuario['id'] != '' && $existeUsuario['nome'] != '') {
                $_SESSION['autenticado'] = "SIM";
                $_SESSION['id'] = $existeUsuario['id'];
                header('location: home.php');
            } else {
                $_SESSION['autenticado'] = "NAO";
                header('Location: login.php?login=erro');
            } 

        } else {
            header('Location: login.php?login=erro');
        }


    // ---------------- Inserir dados do cadastro ----------------
    } else if(isset($_POST['submit'])) {

        $usuario = new Usuario();
        $usuario->__set('nome', $_POST['nome']);
        $usuario->__set('cpf', $_POST['cpf']);
        $usuario->__set('dt_nascimento', $_POST['dt_nascimento']);
        $usuario->__set('telefone', $_POST['telefone']);
        $usuario->__set('email', $_POST['email']);
        $usuario->__set('senha', $_POST['senha']);
        $usuario->__set('rua', $_POST['rua']);
        $usuario->__set('numero', $_POST['numero']);
        $usuario->__set('bairro', $_POST['bairro']);
        $usuario->__set('cidade', $_POST['cidade']);
        $usuario->__set('estado', $_POST['estado']);
        $usuario->__set('modelo_moto', $_POST['modelo_moto']);
        $usuario->__set('pneu_utilizado', $_POST['pneu_utilizado']);
        $usuario->__set('modelo_pneu', $_POST['modelo_pneu']);
        $usuario->__set('tp_medio_troca', $_POST['tp_medio_troca']);

		$conexao = new Conexao();

        $UsuarioService = new UsuarioService($conexao, $usuario);
		$UsuarioService->inserir();

        header('location: login.php?cadastro=sucesso');
    

    // ---------------- Recuperar dados do usuário ----------------
    } else if(isset($_GET['perfil'])) {	    

		$usuario = new Usuario();
        $usuario->__set('id', $_SESSION['id']);

		$conexao = new Conexao();

        $usuarioService = new UsuarioService($conexao, $usuario);
        $recuperar = $usuarioService->recuperar();

        $_SESSION['nome'] = $recuperar['nome'];
        $_SESSION['cpf'] = $recuperar['cpf'];
        $_SESSION['dt_nascimento'] = $recuperar['dt_nascimento'];
        $_SESSION['telefone'] = $recuperar['telefone'];
        $_SESSION['email'] = $recuperar['email'];
        $_SESSION['senha'] = $recuperar['senha'];
        $_SESSION['rua'] = $recuperar['rua'];
        $_SESSION['numero'] = $recuperar['numero'];
        $_SESSION['bairro'] = $recuperar['bairro'];
        $_SESSION['cidade'] = $recuperar['cidade'];
        $_SESSION['estado'] = $recuperar['estado'];
        $_SESSION['modelo_moto'] = $recuperar['modelo_moto'];
        $_SESSION['pneu_utilizado'] = $recuperar['pneu_utilizado'];
        $_SESSION['modelo_pneu'] = $recuperar['modelo_pneu'];
        $_SESSION['tp_medio_troca'] = $recuperar['tp_medio_troca'];

        header('location: perfil.php');
     
        
    // ---------------- Encerra a sessão do usuário ----------------
    } else if(isset($_GET['sair'])) {	

        session_destroy();

        header('location: index.html');
    }

?>