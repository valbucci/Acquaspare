<?php
	session_start();
	require_once $_SERVER['DOCUMENT_ROOT'].'/connect.php';
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
?>
<!--Navbar -->
<nav class="navbar navbar-default">
	<div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="index.php">
				<?php
					if(isset($_SESSION['id'])){
						$sql = "SELECT * FROM utenti WHERE id='".$_SESSION['id']."'";
						if($res = $mysqli->query($sql)) {
							$utente = $res->fetch_assoc();
							echo $utente['username'];
						}else{
							echo $mysqli->error();
						}

					}else{
						echo 'Acquaspare';
					}
				?>
			</a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li>
					<a href="../index.php">
						<i class="fa fa-home" aria-hidden="true"></i>
						<span> Home</span>
					</a>
				</li>
				<li>
					<a href="../carrello.php">
						<i class="fa fa-shopping-cart" aria-hidden="true"></i>
						<span> Carrello</span>
					</a>
				</li>
				<li>
					<a href="../lista_prodotti.php">
						<i class="fa fa-archive" aria-hidden="true"></i>
						<span> Prodotti</span>
					</a>
				</li>
			</ul>
			<form class="navbar-form navbar-left" method="post" role="search" action="lista_prodotti.php">
				<div class="input-group">
					<input type="text" name="ricerca" class="form-control" placeholder="Ricerca Prodotto">
					<div class="input-group-btn">
						<button type="submit" class="btn btn-default">
							<i class="fa fa-search" aria-hidden="true"></i>
						</button>
					</div>
				</div>
			</form>
			<ul class="nav navbar-nav navbar-right">
				<li>
					<a href="../contact_us.php">
						<i class="fa fa-envelope" aria-hidden="true"></i>
						<span> Contattaci</span>
					</a>
				</li>
				<?=
					// If isset $_SESSION['id'] print the logout button and check if user is an Admin
					(isset($_SESSION['id'])) ?
						($_SESSION['id']!=1) ?:
							('<li>
								<a href="../pannello_controllo.php">
									<i class="fa fa-wrench" aria-hidden="true"></i>
									<span> Pannello di Controllo</span>
								</a>
							</li>').
						'<li><a href="../close_session.php"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>'
					: (
						'<li><a href="../login.php"><i class="fa fa-sign-in" aria-hidden="true"></i> Accedi</a></li>'.
						'<li><a href="../registrazione.php"><i class="fa fa-user" aria-hidden="true"></i> Registrati</a></li>'
					)
				?>
			</ul>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav><!--Of navbar -->
