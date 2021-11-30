<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">

    <!-- Estilo customizado --> 
	<link rel="stylesheet" type="text/css" href="css/estilo.css">

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/54ed41f2b2.js" crossorigin="anonymous"></script>

    <title>PNEWS - Cadastro</title>
    <link rel="icon" href="img/logo-titulo.png">
  </head>

  <body>

	<div class="container">
        <div class="row vh-100">

            <!-- Logo -->
            <div class="col-5 d-none d-lg-block align-self-center">
                <div class="row justify-content-center">
                    <img src="img/logo-pnews-login.svg" alt="Logo Pnews">
                </div>

                <div class="row justify-content-center">
					<img src="img/mascote.svg" width="509" height="475" alt="Mascote Pnews">
				</div>
            </div>

            <!-- Formulário -->
            <div class="col col-lg-7 cor-fundo">
                <div class="container">

                    <div class="row mt-3 mt-sm-5 justify-content-center">
                            <h4 class="text-center col mt-sm-5 mb-sm-4"><strong>Insira seus dados para realizar o cadastro</strong></h2>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-10 col-sm-8 text-center align-self-center">

                            <form class="mb-5" action="usuario_controller.php" method="POST">
                                <input class="form-control my-2 borda" type="text" name="nome" placeholder="Nome" required>

                                <div class="row my-2">
                                    <div class=" col-md-8 col-lg-7 col-xl-8">
                                        <input class="form-control borda" type="text" name="cpf" placeholder="CPF" required>
                                    </div>

                                    <div class="mt-2 mt-md-0 col-md-4 col-lg-5 col-xl-4">
                                        <input class="form-control borda" type="text" name="dt_nascimento" placeholder="Nascimento" required>
                                    </div>
                                </div>

                                <input class="form-control my-2 borda" type="text" name="telefone" placeholder="Telefone" required>

                                <input class="form-control my-2 borda" type="text" name="email" placeholder="E-mail" required>

                                <input class="form-control my-2 borda" type="password" name="senha" placeholder="Senha" required>

                                <div class="row my-2">
                                    <div class="col-md-9">
                                        <input class="form-control borda" type="text" name="rua" placeholder="Rua" required>
                                    </div>

                                    <div class="mt-2 mt-md-0 col-md-3">
                                        <input class="form-control borda" type="text" name="numero" placeholder="Nº" required>
                                    </div>
                                </div>

                                <input class="form-control my-2 borda" type="text" name="bairro" placeholder="Bairro" required>

                                <div class="row my-2">
                                    <div class="col-md-7">
                                        <input class="form-control borda" type="text" name="cidade" placeholder="Cidade" required>
                                    </div>

                                    <div class="mt-2 mt-md-0 col-md-5">
                                        <input class="form-control borda" type="text" name="estado" placeholder="Estado" required>
                                    </div>
                                </div>

                                <input class="form-control my-2 borda" type="text" name="modelo_moto" placeholder="Modelo da moto" required>

                                <input class="form-control my-2 borda" type="text" name="pneu_utilizado" placeholder="Marca de pneu utilizado" required>

                                <input class="form-control my-2 borda" type="text" name="modelo_pneu" placeholder="Modelo do pneu" required>

                                <input class="form-control my-2 borda" type="text" name="tp_medio_troca" placeholder="Tempo médio de troca" required>

                                <input class="btn btn-outline-danger btn-lg mt-4 borda" type="submit" name="submit" value="Continuar" >
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    </footer>
        <p class="bg-black bg-opacity-50 text-black text-center mb-0 fixed-bottom">2021 © Copyright GRUPO 9 SI technologies.</p>
    <footer>

  </body>
</html>