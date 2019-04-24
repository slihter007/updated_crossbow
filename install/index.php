<?php include './app/Config.php'; ?>
<!DOCTYPE html>
<html>
	<head>
		<title>Crossbow | Installation Wizard
		<?= (!empty($title) ? $title : null) ?>
		</title>
		<!-- Favicon -->
		<link rel="icon" href="../assets/img/logo-fav.png" type="image/png">
		<!-- Bootstrap -->
		<link rel="stylesheet" href="public/css/bootstrap.min.css">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="public/css/font-awesome.min.css">
		<!-- Custom Style -->
		<link rel="stylesheet" href="public/css/style.css">
		<link rel="stylesheet" type="text/css" href="../assets/lib/jquery.gritter/css/jquery.gritter.css"/>
		<link rel="stylesheet" type="text/css" href="../assets/lib/select2/css/select2.min.css"/>
		<link rel="stylesheet" type="text/css" href="../assets/css/ciuis.css" type="text/css"/>
		<link rel="stylesheet" type="text/css" href="../assets/css/animate.css" type="text/css"/>
		<link rel="stylesheet" type="text/css" href="../assets/lib/ionicons/css/ionicons.min.css" rel="stylesheet" >
		<link rel="stylesheet" type="text/css" href="../assets/lib/bootstrap-slider/css/bootstrap-slider.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
		<style>
			body{
				background: white;
			}
			.ins_nav li{
				display: inline-block;
				background-color: #ffbc00;
				margin: 30px;
				border-radius: 50%;
				width: 30px;
				height: 30px;
			}
			li span{
				vertical-align: -webkit-baseline-middle;
				color: white;
			}
			ul{
				list-style-type: none;
				width: 100%;
				max-width: 450px; /* a width has to be defined for margin: auto 0 to work */
				margin: 0 auto; /* centers the ul */
				text-align: center; /* centers the li */
			}
			.active{
				border: 2px solid green;
			}
		</style>
	</head>
	<body>
		<!-- BACK TO TOP  -->
		<a name="top"></a>
		<!-- BEGIN CONTAINER -->
		<div class="container">
			<!-- BEGIN ROW -->
			<div class="row">
				<div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1">
					<!-- MAIN WRAPPER -->
					<div class="main_wrapper">
						<!-- BEGIN HEADER -->
						<!-- ENDS HEADER -->
						<div class="row panel borderten md-p-30">
							<!-- BEGIN LEFT SIDEBAR -->
<!-- 							<div class="col-sm-3" style="padding: 0">
								<div class="list-group">
									<a href="#" class="list-group-item <?//= (($title == 'Requirements') ? " active " : null) ?>"><span class="text-warning mdi mdi-fire icon"></span> Requirements</a>
									<a href="#" class="list-group-item <?//= (($title == 'Installation') ? " active " : null) ?>"><span class="text-warning mdi mdi-puzzle-piece icon"></span> Installation</a>
									<a href="#" class="list-group-item <?//= (($title == 'Complete') ? " active " : null) ?>"><span class="text-warning mdi mdi-settings icon"></span>Complete</a>
								</div>
							</div> -->
							<!-- ENDS LEFT SIDEBAR -->
							<!-- BEGIN CONTENT -->
<!-- 							<div class="col-sm-9">
								<div class="content">
									<?php //include($content) ?>
								</div>
							</div> -->
							<div align="center">
								<h1>Crossbow Setup</h1>
									<ul class="ins_nav">
										<li <?php if (!isset($_GET['step']) || $_GET['step'] == 'requirements'){ echo'class="active"';}?>><span>1</span></li>
										<li <?php if (isset($_GET['step']) && $_GET['step'] == 'installation'){ echo'class="active"';}?>><span>2</span></li>
										<li <?php if (isset($_GET['step']) && $_GET['step'] == 'complete'){ echo'class="active"';}?>><span>3</span></li>
									</ul>
							</div>

								<?php include($content) ?>
							<!-- ENDS CONTENT -->
						</div>
					</div>
					<!-- END MAIN WRAPPER -->
				</div>
			</div>
			<!-- ENDS ROW -->
		</div>
		<!-- ENDS OF CONTAINER -->
		<!-- ALL SCRIPTS/JS -->
		<script src="public/js/jquery.min.js"></script>
		<script src="public/js/script.js"></script>
	</body>
</html>