<?php $rebrand = load_config(); ?>
<!DOCTYPE html>
<html ng-app="Ciuis" lang="<?php echo lang('lang_code');?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="<?php echo $rebrand['meta_description'] ?>">
<meta name="keywords" content="<?php echo $rebrand['meta_keywords'] ?>">
<meta name="author" content="">
<link rel="shortcut icon" href="<?php echo base_url('assets/img/images/'.$rebrand['favicon_icon'].''); ?>">
<title><?php echo lang('loginsystem')?></title>
<script src="<?php echo base_url('assets/lib/angular/angular.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/lib/angular/angular-animate.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/lib/angular/angular-aria.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/lib/angular/i18n/angular-locale_'.lang('lang_code_dash').'.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/ciuis.css'); ?>" type="text/css"/>
<script>var BASE_URL = "<?php echo base_url(); ?>",ACTIVESTAFF = "<?php echo $this->session->userdata('usr_id'); ?>",SHOW_ONLY_ADMIN = "false",CURRENCY = "false",LOCATE_SELECTED = "<?php echo lang('lang_code');?>",UPIMGURL = "<?php echo base_url('uploads/images/'); ?>",IMAGESURL = "<?php echo base_url('assets/img/'); ?>",SETFILEURL = "<?php echo base_url('uploads/ciuis_settings/') ?>",NTFTITLE = "<?php echo lang('notification')?>",EVENTADDEDMSG = "<?php echo lang('eventadded')?>",TODOADDEDMSG = "<?php echo lang('todoadded')?>",TODODONEMSG = "<?php echo lang('tododone')?>",REMINDERREAD = "<?php echo lang('remindermarkasread')?>",INVMARKCACELLED = "<?php echo lang('invoicecancelled')?>",TICKSTATUSCHANGE = "<?php echo lang('ticketstatuschanced')?>",LEADMARKEDAS = "<?php echo lang('leadmarkedas')?>",LEADUNMARKEDAS = "<?php echo lang('leadunmarkedas')?>",TODAYDATE = "<?php echo date('Y.m.d ')?>",LOGGEDINSTAFFID = "<?php echo $this->session->userdata('usr_id'); ?>",LOGGEDINSTAFFNAME = "<?php echo $this->session->userdata('staffname'); ?>",LOGGEDINSTAFFAVATAR = "<?php echo $this->session->userdata('staffavatar'); ?>",VOICENOTIFICATIONLANG = "<?php echo lang('lang_code_dash');?>",initialLocaleCode = "<?php echo lang('initial_locale_code');?>";</script>
</head>
<body class="ciuis-body-splash-screen">
<div class="ciuis-body-wrapper ciuis-body-login"> 
  <div class="ciuis-body-content">
    <div class="col-md-4 login-left hide-xs hide-sm" style="background-image: url(<?php echo base_url('assets/img/images/'.$rebrand['admin_login_image'].''); ?>) !important;">
      <div class="lg-content">
        <h2><?php echo $rebrand['title'] ?></h2>
        <p class="text-muted"><?php echo $rebrand['admin_login_text'] ?></p> 
        <md-button href="area" class="btn btn-warning md-raised md-warn p-l-20 p-r-20"><?php echo lang('clientarea')?></md-button>
      </div>
    </div>
    <div class="main-content container-fluid col-md-8 login_page_right_block">
      <div class="splash-container">
        <md-card flex-xs flex-gt-xs="100" layout="column">
          <div class="panel panel-default"> 
            <div class="panel-heading"><img src="<?php echo base_url('uploads/ciuis_settings/'.$rebrand['nav_logo'].''); ?>" alt="logo" class="logo-img nav-logo"> <?php echo $rebrand['title'] ?><span class="splash-description"><?php echo lang('logindescription')?></span>
            </div>
            <div class="panel-body">
              <?php echo form_open('login/auth',array('name' => 'userForm')) ?>
              <div class="form-group">
                <input id="email" type="email" placeholder="<?php echo lang('loginemail')?>" name="email" autocomplete="off" class="form-control">
              </div>
              <div class="form-group"> 
                <input id="password" type="password" name="password" placeholder="<?php echo lang('loginpassword')?>" class="form-control">
              </div>
              <div class="form-group row login-tools">
                <div class="col-xs-6 login-remember">
                  <div class="ciuis-body-checkbox">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember"><?php echo lang('loginremember')?></label>
                  </div>
                </div>
                <div class="col-xs-6 login-forgot-password"><a href="<?php echo base_url('login/forgot')?>"><?php echo lang('loginforget')?></a> </div>
              </div>
              <div class="form-group">
                <label><b><?php echo lang('select_language')?>:</b></label>
                <select class="form-control" name="language" required="" style="padding: 4px;height: 30px;">
                  <?php foreach ($languages as $language) { ?>
                    <option value="<?php echo $language['foldername'] ?>" <?php if($language['foldername'] == LANG) echo 'selected="selected"';?>>
                      <?php echo $language['name'] ?>
                    </option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group login-submit">
                <button data-dismiss="modal" type="submit" class="login-button btn btn-ciuis btn-xl"><?php echo lang('loginbutton')?></button>
              </div>
              <?php echo '<label class="text-danger">' . $this->session->flashdata( "error" ) . '</label>';?>
            </div>
          </div>
        </md-card>
      </div>
    </div>
  </div>
</div>
<script src="<?php echo base_url('assets/lib/jquery/jquery.min.js'); ?>" type="text/javascript"></script> 
<script src="<?php echo base_url('assets/lib/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js'); ?>" type="text/javascript"></script> 
<script src="<?php echo base_url('assets/lib/hoverIntent/hoverIntent.js')?>"></script> 
<script src="<?php echo base_url('assets/js/Ciuis.js'); ?>" type="text/javascript"></script> 
<script src="<?php echo base_url('assets/lib/moment.js/min/moment.min.js'); ?>" type="text/javascript"></script> 
<script src="<?php echo base_url('assets/lib/bootstrap/dist/js/bootstrap.min.js'); ?>" type="text/javascript"></script> 
<script src="<?php echo base_url('assets/lib/jquery.gritter/js/jquery.gritter.js'); ?>" type="text/javascript"></script> 
<script src="<?php echo base_url('assets/lib/datetimepicker/js/bootstrap-datetimepicker.min.js'); ?>" type="text/javascript"></script> 
<script src="<?php echo base_url('assets/lib/angular-datepicker/src/js/angular-datepicker.js'); ?>" type="text/javascript"></script> 
<script src="<?php echo base_url('assets/lib/select2/js/select2.min.js'); ?>" type="text/javascript"></script> 
<script src="<?php echo base_url('assets/lib/select2/js/select2.full.min.js'); ?>" type="text/javascript"></script> 
<script src="<?php echo base_url('assets/lib/material/angular-material.min.js')?>"></script> 
<script src="<?php echo base_url('assets/lib/currency-format/currency-format.min.js')?>"></script> 
<script src="<?php echo base_url('assets/lib/angular-datetimepicker/angular-material-datetimepicker.min.js')?>"></script> 
<script src="<?php echo base_url('assets/lib/scheduler/scheduler.min.js'); ?>"></script> 
<script src="<?php echo base_url('assets/js/CiuisAngular.js'); ?>"></script>
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
