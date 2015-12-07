<!DOCTYPE html>
<html lang="en">
<?php
	
	if ( !isset($_SESSION['sessao']) ) {
		session_start(); // Inicia a sessão
		$_SESSION['sessao'] = new MongoClient();
		$m = $_SESSION['sessao'];
	}
	
	
?>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Trabalho Final - Banco de dados Avançados</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/stylish-portfolio.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <style>
    input{
		color:black;
	}
	select{
		color:black;
	}
    </style>
    <!-- Navigation -->
    <a id="menu-toggle" href="#" class="btn btn-dark btn-lg toggle"><i class="fa fa-bars"></i></a>
    <nav id="sidebar-wrapper">
        <ul class="sidebar-nav">
            <a id="menu-close" href="#" class="btn btn-light btn-lg pull-right toggle"><i class="fa fa-times"></i></a>
            <li class="sidebar-brand">
                <a href="#top"  onclick = $("#menu-close").click(); >Start Bootstrap</a>
            </li>
            <li>
                <a href="#top" onclick = $("#menu-close").click(); >Home</a>
            </li>
            <li>
                <a href="#about" onclick = $("#menu-close").click(); >Pessoas por estados(Lista)</a>
            </li>
            <li>
                <a href="#services" onclick = $("#menu-close").click(); >Pessoas por estados(Quantidade)</a>
            </li>
            <li>
                <a href="#portfolio" onclick = $("#menu-close").click(); >Portfolio</a>
            </li>
            <li>
                <a href="#contact" onclick = $("#menu-close").click(); >Contact</a>
            </li>
        </ul>
    </nav>

    <!-- Header -->
    <header id="top" class="header">
        <div class="text-vertical-center">
            <h1>Trabalho Final - Banco de dados Avançados</h1>
            <h2>Aplicação com MongoDB</h2>
            <h3>Autores: <b>Aldo Henrique</b> e <b>João Bachiega</b></h3>
            <br>
           
        </div>
    </header>

    <!-- About -->
    <section id="about" class="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
					<h1>Pessoas por estado</h1>
					<form method="GET" action="index.php#about">
						<h3><select name='estado'>
							<option value="">Selecione</option>
							<option value="AC">Acre</option>
							<option value="AL">Alagoas</option>
							<option value="AP">Amapá</option>
							<option value="AM">Amazonas</option>
							<option value="BA">Bahia</option>
							<option value="CE">Ceará</option>
							<option value="DF">Distrito Federal</option>
							<option value="ES">Espirito Santo</option>
							<option value="GO">Goiás</option>
							<option value="MA">Maranhão</option>
							<option value="MS">Mato Grosso do Sul</option>
							<option value="MT">Mato Grosso</option>
							<option value="MG">Minas Gerais</option>
							<option value="PA">Pará</option>
							<option value="PB">Paraíba</option>
							<option value="PR">Paraná</option>
							<option value="PE">Pernambuco</option>
							<option value="PI">Piauí</option>
							<option value="RJ">Rio de Janeiro</option>
							<option value="RN">Rio Grande do Norte</option>
							<option value="RS">Rio Grande do Sul</option>
							<option value="RO">Rondônia</option>
							<option value="RR">Roraima</option>
							<option value="SC">Santa Catarina</option>
							<option value="SP">São Paulo</option>
							<option value="SE">Sergipe</option>
							<option value="TO">Tocantins</option>
						</select>
						Selecionar periodo 
								<?php
									$db = $m->selectDB("bolsafamilia");
									$collection = $db->mes102014;
														
									$cursor = $db->getCollectionNames();
									echo "<select name='collection'>";
									foreach ($cursor as $collectionName) {
										echo "<option value='$collectionName'>$collectionName</option>" ;
									}
									echo "</select><br>";
										
								?></h3>
						<input type="submit" name="buscar" value="Buscar"/>
					</form>
					<?php
					if(isset($_GET['estado']) ){
						$collectionSelect=$_GET['collection'];
						$estado=$_GET['estado'];
							$db = $m->selectDB("bolsafamilia");
							$collection = $db->selectCollection($collectionSelect);
							$page  = isset($_GET['page']) ? (int) $_GET['page'] : 1;
							$limit = 12;
							$skip  = ($page - 1) * $limit;
							$next  = ($page + 1);
							$prev  = ($page - 1);
							$sort  = array('_id' => -1);
							$cursor3 = $collection->find(array("UF"=>$estado))->skip($skip)->limit($limit)->sort($sort);
							$cursor2 = $collection->find()->skip($skip)->limit($limit)->sort($sort);
							
							echo "<br>";
							foreach ($cursor3 as $document) {
								echo $document["UF"] ." - ". $document["Nome Favorecido"] . " - R$ ". $document["Valor Parcela"] ."<br>";
							}
							$total= $cursor2->count();
							if($page > 1){
								echo '<a href="?page=' . $prev . '&estado='.$estado.'&collection='.$collectionSelect.'">Previous</a>';
								if($page * $limit < $total) {
									echo ' <a href="?page=' . $next . '&estado='.$estado.'&collection='.$collectionSelect.'">Next</a>';
								}
							} else {
								if($page * $limit < $total) {
									echo ' <a href="?page=' . $next . '&estado='.$estado.'&collection='.$collectionSelect.'">Next</a>';
								}
							}
						}
					?>

					
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container -->
    </section>

    <!-- Services -->
    <!-- The circle icons use Font Awesome's stacked icon classes. For more information, visit http://fontawesome.io/examples/ -->
    <style>
		#services a{
			color: black;
			}
    </style>
    <section id="services" class="services bg-primary">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-10 col-lg-offset-1">
                    <h2>Total de pessoas que recebem por estados</h2>
                                              
                       
                        <form method="GET" action="index.php#services">
						<h3><select name='estadoPessoa'>
							<option value="">Selecione</option>
							<option value="AC">Acre</option>
							<option value="AL">Alagoas</option>
							<option value="AP">Amapá</option>
							<option value="AM">Amazonas</option>
							<option value="BA">Bahia</option>
							<option value="CE">Ceará</option>
							<option value="DF">Distrito Federal</option>
							<option value="ES">Espirito Santo</option>
							<option value="GO">Goiás</option>
							<option value="MA">Maranhão</option>
							<option value="MS">Mato Grosso do Sul</option>
							<option value="MT">Mato Grosso</option>
							<option value="MG">Minas Gerais</option>
							<option value="PA">Pará</option>
							<option value="PB">Paraíba</option>
							<option value="PR">Paraná</option>
							<option value="PE">Pernambuco</option>
							<option value="PI">Piauí</option>
							<option value="RJ">Rio de Janeiro</option>
							<option value="RN">Rio Grande do Norte</option>
							<option value="RS">Rio Grande do Sul</option>
							<option value="RO">Rondônia</option>
							<option value="RR">Roraima</option>
							<option value="SC">Santa Catarina</option>
							<option value="SP">São Paulo</option>
							<option value="SE">Sergipe</option>
							<option value="TO">Tocantins</option>
						</select> 
						 Selecionar periodo 
								<?php
									
									$db = $m->selectDB("bolsafamilia");
														
									$cursor = $db->getCollectionNames();
									echo "<select name='collection'>";
									foreach ($cursor as $collectionName) {
										echo "<option value='$collectionName'>$collectionName</option>" ;
									}
									echo "</select><br>";
										
								?>
							</h3>
						<input type="submit" name="buscar" value="Buscar"/>
					</form>
                        	<?php
                        	if(isset($_GET['estadoPessoa'])){
								$collectionSelect=$_GET['collection'];
								
								$estadoPessoa=$_GET['estadoPessoa'];
								$db = $m->selectDB("bolsafamilia");
								$collection = $db->selectCollection($collectionSelect);
													
								$cursor = $collection->find(array("UF"=>$estadoPessoa))->count();
								echo "<h1>São no total $cursor pessoas</h1>";
								echo "<br>";
							}		
							?>
                      
                    <!-- /.row (nested) -->
                </div>
                <!-- /.col-lg-10 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container -->
    </section>

    <!-- Callout -->

    <aside id="buscaMaiorValor" class="callout">
        <div class="text-vertical-center">
			<h1>Busca por valores</h1><br>
			<form method="GET" action="index.php#buscaMaiorValor">
				<h3><input type="text" name="dados1" valor=0/> < Valor < <input type="text" name="dados2" valor=500/> 
				 Selecionar periodo 
								<?php
									$db = $m->selectDB("bolsafamilia");
									$collection = $db->mes102014;
														
									$cursor = $db->getCollectionNames();
									echo "<select name='collection'>";
									foreach ($cursor as $collectionName) {
										echo "<option value='$collectionName'>$collectionName</option>" ;
									}
									echo "</select><br>";
										
								?></h3><br>
                <input type="submit" value="Buscar"/><br>
			</form>
            <?php
					if(isset($_GET['dados1'])){
						$dado1=(int)$_GET['dados1'];
						$dado2=(int)$_GET['dados2'];
						
						$estado=$_GET['estado'];
							$db = $m->selectDB("bolsafamilia");
							$collectionSelect=$_GET['collection'];
							$collection = $db->selectCollection($collectionSelect);
							$page  = isset($_GET['page']) ? (int) $_GET['page'] : 1;
							$limit = 12;
							$skip  = ($page - 1) * $limit;
							$next  = ($page + 1);
							$prev  = ($page - 1);
							$sort  = array('_id' => -1);
							$cursor = $collection->find(array("Valor Parcela" => array('$gt' => $dado1, '$lte' => $dado2)))->skip($skip)->limit($limit)->sort($sort);
							$cont = $collection->find(array("Valor Parcela" => array('$gt' => $dado1, '$lte' => $dado2)))->count();
							echo "<h2 style='text-shadow: 0 0 20px #000, 0 -10px 20px #000, 0 10px 20px #000, -20px 0 40px #000, 20px 0 40px #000;'>São no total $cont bolsas nesse intervalo de valor</h2>";
							$cursor2 = $collection->find()->skip($skip)->limit($limit)->sort($sort);
							$total= $cursor2->count();
							echo "<br>";
							foreach ($cursor as $document) {
								echo $document["UF"] ." - ". $document["Nome Favorecido"] . " - R$ ". $document["Valor Parcela"] ."<br>";
							}
							
							if($page > 1){
								echo '<a href="?page=' . $prev . '&dados1='.$dado1.'&dados2='.$dado2.'&collection='.$collectionSelect.'">Previous</a>';
								if($page * $limit < $total) {
									echo ' <a href="?page=' . $next . '&dados1='.$dado1.'&dados2='.$dado2.'&collection='.$collectionSelect.'">Next</a>';
								}
							} else {
								if($page * $limit < $total) {
									echo ' <a href="?page=' . $next . '&dados1='.$dado1.'&dados2='.$dado2.'&collection='.$collectionSelect.'">Next</a>';
								}
							}
						}
					?>
        </div>
    </aside>

    <!-- Portfolio -->
    <section id="portfolio" class="portfolio">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-lg-offset-1 text-center">
                    <h2>Valores por estado</h2>
                    <hr class="small">
                    <div class="row">
						<form method="POST" action="index.php#portfolio">
						<h3><select name='estadoMaior'>
							<option value="">Selecione</option>
							<option value="BR">Brasíl (todos os estados)</option>
							<option value="AC">Acre</option>
							<option value="AL">Alagoas</option>
							<option value="AP">Amapá</option>
							<option value="AM">Amazonas</option>
							<option value="BA">Bahia</option>
							<option value="CE">Ceará</option>
							<option value="DF">Distrito Federal</option>
							<option value="ES">Espirito Santo</option>
							<option value="GO">Goiás</option>
							<option value="MA">Maranhão</option>
							<option value="MS">Mato Grosso do Sul</option>
							<option value="MT">Mato Grosso</option>
							<option value="MG">Minas Gerais</option>
							<option value="PA">Pará</option>
							<option value="PB">Paraíba</option>
							<option value="PR">Paraná</option>
							<option value="PE">Pernambuco</option>
							<option value="PI">Piauí</option>
							<option value="RJ">Rio de Janeiro</option>
							<option value="RN">Rio Grande do Norte</option>
							<option value="RS">Rio Grande do Sul</option>
							<option value="RO">Rondônia</option>
							<option value="RR">Roraima</option>
							<option value="SC">Santa Catarina</option>
							<option value="SP">São Paulo</option>
							<option value="SE">Sergipe</option>
							<option value="TO">Tocantins</option>
						</select> Selecionar Opção 
						<select name='opcao' required='required'>
							<option value="">Selecione</option>
							<option value="-1">MAX</option>
							<option value="1">MIN</option>
						</select>
						 Selecionar periodo 
								<?php
									$db = $m->selectDB("bolsafamilia");
														
									$cursor = $db->getCollectionNames();
									echo "<select name='collection' required='required'>";
									foreach ($cursor as $collectionName) {
										echo "<option value='$collectionName'>$collectionName</option>" ;
									}
									echo "</select><br>";
										
								?></h3><br>
						<input type="submit" name="buscar" value="Buscar"/>
					</form>
                        	<?php
                        	
                        	if(isset($_POST['estadoMaior'])){
								
									$opcao = (int) $_POST['opcao'];
								$estado=$_POST['estadoMaior'];
								$db = $m->selectDB("bolsafamilia");
								$collectionSelect=$_POST['collection'];
								$collection = $db->selectCollection($collectionSelect);
								if($estado=="BR")				
									$cursor = $collection->find(array(), array("UF" => 1, "Valor Parcela" => $opcao, "Nome Favorecido" => 1))->sort(array('Valor Parcela' => $opcao))->limit(1);
								else
									$cursor = $collection->find(array("UF"=>$estado), array("UF" => 1, "Valor Parcela" => $opcao, "Nome Favorecido" => 1))->sort(array('Valor Parcela' => $opcao))->limit(1);
								echo "<br>";
								foreach ($cursor as $document) {
									echo "<h1>".$document["Nome Favorecido"] ." R$". $document["Valor Parcela"] ."</h1><br>";
								}
							}	
							?>
                        
                    </div>
                </div>
                <!-- /.col-lg-10 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container -->
    </section>

    <!-- Call to Action -->
    <section id="container1" class="container1">
		<aside class="call-to-action bg-primary">
			<div class="container">
				<div class="row">
					<div class="col-lg-12 text-center">
						<h1>Total de pessoas que recebem bolsas acima do valor informado</h1>
						<form method="POST" action="index.php#container1">
							<h3>Valor: <input type="num" name="dadosTotal" valor=0/>
						 Selecionar periodo 
								<?php
									$db = $m->selectDB("bolsafamilia");
														
									$cursor = $db->getCollectionNames();
									echo "<select name='collection'>";
									foreach ($cursor as $collectionName) {
										echo "<option value='$collectionName'>$collectionName</option>" ;
									}
									echo "</select><br>";
										
								?></h3> <br>
							<input type="submit" value="Buscar"/><br>
						</form>
							<?php
							if(isset($_POST['dadosTotal'])){
								$dadosTotal=(int)$_POST['dadosTotal'];
								$db = $m->selectDB("bolsafamilia");
								$collectionSelect=$_POST['collection'];
								
								$collection = $db->selectCollection($collectionSelect);
												
								$cursor = $collection->find(array("Valor Parcela" => array('$gt' => $dadosTotal)))->count();
								echo "<br><h1>São no total de $cursor pessoas</h1>";
								echo "<br>";
							}
							?>	
					</div>
				</div>
			</div>
		</aside>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-lg-offset-1 text-center">
					<h1>Aplicação com MongoDB</h1>
                    <h4><strong>Trabalho Final - Banco de dados Avançados</strong>
                   
                    <hr class="small">
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script>
    // Closes the sidebar menu
    $("#menu-close").click(function(e) {
        e.preventDefault();
        $("#sidebar-wrapper").toggleClass("active");
    });

    // Opens the sidebar menu
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#sidebar-wrapper").toggleClass("active");
    });

    // Scrolls to the selected menu item on the page
    $(function() {
        $('a[href*=#]:not([href=#])').click(function() {
            if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') || location.hostname == this.hostname) {

                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    $('html,body').animate({
                        scrollTop: target.offset().top
                    }, 1000);
                    return false;
                }
            }
        });
    });
    </script>

</body>

</html>
<?php 
if (isset($_SESSION['sessao']) ) {
	$mongodb->close();
	unset($_SESSION['sessao']); // Deleta uma variável da sessão
	session_destroy(); // Destrói toda sessão 
}
?>

