<?php $rebrand = load_config(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="<?php echo $rebrand['meta_description'] ?>">
	<meta name="keywords" content="<?php echo $rebrand['meta_keywords'] ?>">
	<link rel="shortcut icon" href="<?php echo base_url('assets/img/images/'.$rebrand['favicon_icon'].''); ?>">
	<title>
		<?php echo lang('loginsystem')?>
	</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/lib/perfect-scrollbar/css/perfect-scrollbar.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/lib/material-design-icons/css/material-design-iconic-font.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/lib/jquery.gritter/css/jquery.gritter.css"/>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/ciuis.css" type="text/css"/>

</head>
<?php
$arr = $this->session->flashdata();
if ( !empty( $arr[ 'ntf1' ] ) ) {
	$html = '<div class="bg-warning container flash-message">';
	$html .= $arr[ 'ntf1' ];
	$html .= '</div>';
	echo $html;
}
?>
<body class="ciuis-body-splash-screen">
	<div class="ciuis-body-wrapper ciuis-body-login"> 
	  <div class="ciuis-body-content">
	    <div class="main-content container-fluid col-md-12 center login_page_right_block">
	      <div class="splash-container">
	        <md-card flex-xs flex-gt-xs="100" layout="column">
	          <div class="panel panel-default">
	            <div class="panel-heading">
	            	<h3><?php echo lang('resetyourpassword')?></h3>
	            	<h6><?php echo lang('hello')?><span><?php echo $firstName; ?></span>, <?php echo lang('resetyourpasswordsub')?></h6>
	            </div>
	            <div class="panel-body">
	              <?php $fattr = array('class' => 'form-signin'); 
	              echo form_open(site_url().'login/reset_password/token/'.$token, $fattr); ?>
	              <div class="form-group">
	                <input id="password" type="password" placeholder="<?php echo lang('password')?>" name="password" autocomplete="off" class="form-control">
	                <?php echo form_error('password') ?>
	              </div>
	              <div class="form-group">
	                <input id="passconf" type="password" placeholder="<?php echo lang('confirmpassword')?>" name="passconf" autocomplete="off" class="form-control">
	                <?php echo form_error('passconf') ?>
	              </div>
	              <div class="form-group login-submit">
	                <button data-dismiss="modal" type="submit" class="login-button btn btn-ciuis btn-xl"><?php echo lang('resetpassword') ?></button>
	              </div>
	              <?php echo form_close(); ?>
	            </div>
	          </div>
	        </md-card>
	      </div>
	    </div>
	  </div>
	</div>
</body>
<script src="<?php echo base_url(); ?>assets/lib/jquery/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/lib/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/main.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/lib/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/app-dashboard.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/lib/jquery.gritter/js/jquery.gritter.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/app-ui-notifications.js" type="text/javascript"></script> 
<script src="<?php echo base_url(); ?>assets/lib/select2/js/select2.min.js" type="text/javascript"></script>
<script type="text/javascript">
      $(document).ready(function(){
      	//initialize the javascript
      	App.init();
      	App.dashboard();
      });	
</script>
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
