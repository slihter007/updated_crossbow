<?php $rebrand = load_config(); 
?>
<!DOCTYPE html>
<html lang="tr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="<?php echo $rebrand['meta_description'] ?>">
	<meta name="keywords" content="<?php echo $rebrand['meta_keywords'] ?>">
	<link rel="shortcut icon" href="<?php echo base_url('assets/img/images/'.$rebrand['favicon_icon'].''); ?>">
	<title><?php echo lang('loginsystem')?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/lib/perfect-scrollbar/css/perfect-scrollbar.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/lib/material-design-icons/css/material-design-iconic-font.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/lib/jquery.gritter/css/jquery.gritter.css"/>
	<link rel="stylesheet" href="<?php echo base_url('assets/css/ciuis.css'); ?>" type="text/css"/>
</head>
<style>
	.loginsol {
		position: fixed;
		height: 100%;
		background-color: #929292;
		background-image: url(<?php echo base_url();
		?>/assets/img/login.jpg);
		background-blend-mode: multiply;
		background-position: center center;
		background-size: cover !important;
	}

	.lg-content {
		margin-top: 30%;
		text-align: center;
		padding: 0 50px;
		color: azure;
	}

	.lg-content p {
		color: azure;
	}
</style>
<body class="ciuis-body-splash-screen">
	<div class="ciuis-body-wrapper ciuis-body-login"> 
	  <div class="ciuis-body-content">
	    <div class="col-md-4 login-left hide-xs hide-sm" style="background-image: url(<?php echo base_url('assets/img/images/'.$rebrand['client_login_image'].''); ?>) !important;">
	      <div class="lg-content">
	      	<h2><?php echo $rebrand['title']?></h2>
	      	<p class="text-muted"><?php echo $rebrand['client_login_text'] ?></p> 
	      	<?php if($rebrand['enable_support_button_on_client'] == '1') { ?>
	      	<a href="<?php echo $rebrand['support_button_link'] ?>" class="btn btn-warning p-l-20 p-r-20"><?php echo $rebrand['support_button_title'] ?></a>
	      <?php } ?>
	      </div>
			</div>
			<div class="main-content container-fluid col-md-8 login_page_right_block">
				<div class="splash-container">
					<md-card flex-xs flex-gt-xs="100" layout="column">
					<div class="panel panel-default">
						<div class="panel-heading"><img src="<?php echo base_url('uploads/ciuis_settings/'.$rebrand['nav_logo'].''); ?>" alt="logo" class="logo-img nav-logo"> <?php echo $rebrand['title']?><span class="splash-description"><?php echo lang('logindescription')?></span>
						</div>
						<div class="panel-body">
							<?php echo form_open() ?>
							<div class="form-group">
								<input id="email" type="email" placeholder="<?php echo lang('loginemail')?>" name="email" autocomplete="off" class="form-control">
							</div>
							<div class="form-group"> 
								<input id="password" type="password" name="password" placeholder="<?php echo lang('loginpassword')?>" class="form-control">
							</div>
							<div class="form-group row login-tools">
								<div class="col-xs-6 login-remember">
									<div class="ciuis-body-checkbox">
										<input type="checkbox" id="remember">
										<label for="remember"><?php echo lang('loginremember')?></label>
									</div>
								</div>
								<div class="col-xs-6 login-forgot-password"><a href="<?php echo base_url('area/login/forgot')?>"><?php echo lang('loginforget')?></a>
								</div>
							</div>
							<div class="form-group login-submit">
								<button data-dismiss="modal" type="submit" class="login-button btn btn-ciuis btn-xl"><?php echo lang('loginbutton')?></button>
							</div>
							<label for="text-danger">
								<?php if (validation_errors()) : ?>
								<div class="alert alert-danger" role="alert">
									<?= validation_errors() ?>
								</div>
							<?php endif; ?>
							<?php if (isset($error)) : ?>
								<div class="alert alert-danger" role="alert">
									<?= $error ?>
								</div>
							<?php endif; ?>
							</label>
						</div>
					</div>
				</md-card>
				</div>
			</div>
		</div>
	</div>
<script src="<?php echo base_url(); ?>assets/lib/jquery/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/lib/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/Ciuis.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/lib/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/lib/jquery.gritter/js/jquery.gritter.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/lib/select2/js/select2.min.js" type="text/javascript"></script>
<?php if ( $this->session->flashdata('ntf1')) {?>
<script type="text/javascript">
    $.gritter.add( {
      title: '<b><?php echo lang('notification')?></b>',
      text: '<?php echo $this->session->flashdata('ntf1'); ?>',
      class_name: 'color success'
    } );
  </script>
<?php }?>
<?php if ( $this->session->flashdata('ntf2')) {?>
<script type="text/javascript">
    $.gritter.add( {
      title: '<b><?php echo lang('notification')?></b>',
      text: '<?php echo $this->session->flashdata('ntf2'); ?>',
      class_name: 'color primary'
    } );
  </script>
<?php }?>
<?php if ( $this->session->flashdata('ntf3')) {?>
<script type="text/javascript">
    $.gritter.add( {
      title: '<b><?php echo lang('notification')?></b>',
      text: '<?php echo $this->session->flashdata('ntf3'); ?>',
      class_name: 'color warning'
    } );
  </script>
<?php }?>
<?php if ( $this->session->flashdata('ntf4')) {?>
<script type="text/javascript">
    $.gritter.add( {
      title: '<b><?php echo lang('notification')?></b>',
      text: '<?php echo $this->session->flashdata('ntf4'); ?>',
      class_name: 'color danger'
    } );
  </script>
<?php }?>
</body>
</html>
