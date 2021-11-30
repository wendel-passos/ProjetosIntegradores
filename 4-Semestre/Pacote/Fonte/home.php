<?php 

	require 'usuario_controller.php';

	if(!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] != 'SIM' ){
		header('location: login.php');
	}	

?>

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

    <title>PNEWS - Home</title>
	<link rel="icon" href="img/logo-titulo.png">
  </head>

  <body>
		
		<header><!-- Início cabeçalho -->

			<nav class="navbar navbar-expand-md navbar-light cor-fundo border-bottom py-0">
				<div class="container">

					<!-- Logo -->
					<a href="#" class="navbar-brand py-0">
						<img src="img/logo-pnews-home.svg" alt="Logo Pnews">
					</a>

					<!-- Menu Hamburguer -->
					<button class="navbar-toggler" data-toggle="collapse" data-target="#nav-target">
						<span class="navbar-toggler-icon"></span>
					</button>

					<!-- Navegação -->
					<div class="collapse navbar-collapse justify-content-end" id="nav-target">			
						<ul class="navbar-nav">
							
							<li class="nav-item">
								<a href="usuario_controller.php?perfil=1" class="nav-link text-black">
									<strong>Perfil</strong>
								</a>
							</li>
							
							<li class="divisor-nav align-self-center collapse navbar-collapse"></li>

							<li class="nav-item">
								<a href="usuario_controller.php?sair=1" class="nav-link text-black">
									<strong>Sair</strong>
								</a>
							</li>
						</ul>

					</div>
				</div>
			</nav>


		</header><!-- Fim cabeçalho -->

		<main class="full-height">

			<div class="container h-100">
				<div class="row justify-content-center h-100">
					<div class="col col-lg-9 col-xl-7 align-self-center">
						<div class="botao">
							<a href="borracharias.php"> 
								<img src="img/botao-procure-borracharias.svg" class="img-fluid" alt="Procurar Borracharias">
							</a>
						</div>
					</div>
				</div>
			</div>

        </main>

    </footer>
        <p class="bg-black bg-opacity-50 text-black text-center mb-0 fixed-bottom">2021 © Copyright GRUPO 9 SI technologies.</p>
    <footer>

	<!-- JavaScript utilizado para fazer o menu hamburguer -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
		

  </body>
</html>