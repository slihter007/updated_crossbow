<?php $rebrand = load_config(); ?>
<!DOCTYPE html>
<html ng-app="Ciuis" lang="<?php echo lang('lang_code'); ?>">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="<?php echo $rebrand['meta_description'] ?>">
	<meta name="keywords" content="<?php echo $rebrand['meta_keywords'] ?>">
	<link rel="shortcut icon" href="<?php echo base_url('assets/img/images/'.$rebrand['favicon_icon'].''); ?>">
	<title><?php echo $title; ?></title>
	<script src="<?php echo base_url('assets/lib/angular/angular.min.js'); ?>"></script>
   	<script src="<?php echo base_url('assets/lib/angular/angular-animate.min.js'); ?>"></script>
   	<script src="<?php echo base_url('assets/lib/angular/angular-aria.min.js'); ?>"></script>
   	<script src="<?php echo base_url('assets/lib/angular/i18n/angular-locale_en-us.js'); ?>"></script>
   	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/ciuis.css'); ?>" type="text/css"/>
	<script>
    var BASE_URL = "<?php echo base_url(); ?>";
    var ACTIVESTAFF = "<?php echo $_SESSION[ 'logged_in' ] ?>";
	<?php switch ( $_SESSION[ 'admin' ] ) {case 0:$isAdmin = 'false';break;case 1:$isAdmin = 'true';break;}?>
	var ISADMIN = "<?php echo $isAdmin ?>";
	var HAS_MENU_PERMISSION = "false";
	var SHOW_ONLY_ADMIN = "false";
	var ACTIVE_CONTACT_NAME = "<?php echo $_SESSION[ 'name' ] ?> <?php echo $_SESSION[ 'surname' ] ?>";
    </script>
</head>
<?php $settings = $this->Settings_Model->get_settings_ciuis(); ?>
<?php $newnotification = $this->Area_Model->newnotification(); ?>
<body ng-controller="Area_Controller">
<div id="ciuisloader"></div>
<md-toolbar class="toolbar-ciuis-top">
	<div class="md-toolbar-tools">
		<!-- CRM NAME -->
		<div md-truncate class="crm-name"><span ng-bind="settings.crm_name"></span></div>
		<md-button ng-click="OpenMenu()" class="md-icon-button hidden-lg hidden-md" aria-label="Menu">
			<md-tooltip md-direction="left"><?php echo lang('menu') ?></md-tooltip>
			<md-icon><i class="ion-navicon-round text-muted"></i></md-icon>
		</md-button>
		<!-- CRM NAME -->
		<!-- NAVBAR MENU -->
		<ul flex class="ciuis-v3-menu hidden-xs">
		<li ng-repeat="nav in areamenu"><a href="{{nav.url}}" ng-bind="nav.title"></a></li>
		</ul>
		<md-button ng-click="Appointment()" class="md-icon-button" aria-label="Appointment">
			<md-tooltip md-direction="left"><?php echo lang('new_appointment') ?></md-tooltip>
			<md-icon><i class="ion-plus-round text-muted"></i></md-icon>
		</md-button>
		<md-button ng-click="Notifications()" class="md-icon-button" aria-label="Notifications">
			<md-tooltip md-direction="left"><?php echo lang('notifications') ?></md-tooltip>
			<div ng-show="stats.newnotification == true" class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
			<md-icon><i class="ion-ios-bell text-muted"></i></md-icon>
		</md-button>
		<md-button ng-click="Profile()" class="md-icon-button avatar-button-ciuis" aria-label="User Profile">
			<img height="100%" ng-src="<?php echo base_url('assets/img/customer_avatar.png'); ?>" class="md-avatar" on-error-src="<?php echo base_url('assets/img/placeholder.png')?>"/>
		</md-button>
		<div ng-click="Profile()" md-truncate class="user-informations hidden-xs">
			<span class="user-name-in"><?php echo $_SESSION[ 'name' ] ?> <?php echo $_SESSION[ 'surname' ] ?></span><br>
			<span class="user-email-in"><?php echo $this->session->userdata('email'); ?></span>
		</div>
	</div>
</md-toolbar>
<header id="mainHeader" role="banner" class="hidden-xs">
	<nav role="navigation">
	<div class="top-header">
		<div class="navBurger">
			<a href="{{appurl}}area"><img class="transform_logo" width="34px" height="34px" ng-src="{{appurl}}uploads/ciuis_settings/{{settings.logo}}"  on-error-src="<?php echo base_url('assets/img/placeholder.png')?>"></a>
		</div>
	</div>
	<ul id="menu-vertical-menu icon" class="nav">
		<li ng-repeat="menu in areamenu" class="material-icons {{menu.icon}}" ng-if="menu.show_staff == '0'"><a href="{{menu.url}}">{{menu.title}}</a></li>
	</ul>
	</nav>
</header>
<md-content id="mobile-menu" class="" style="left: 0px; opacity: 1; display: none">
  <md-toolbar class="toolbar-white">
    <div class="md-toolbar-tools">
      <div flex md-truncate class="crm-name"><span ng-bind="settings.crm_name"></span></div>
      <md-button ng-click="close()" class="md-icon-button" aria-label="Close">
        <md-icon><i class="ion-close-circled text-muted"></i></md-icon>
      </md-button>
    </div>
  </md-toolbar>
  <md-content class="mobile-menu-box bg-white">
    <div class="mobile-menu-wrapper-inner">
      <div class="mobile-menu-wrapper">
        <div class="mobile-menu-slider" style="left: 0px;">
          <div class="mobile-menu">
            <ul>
              <li ng-repeat="menu in areamenu" class="nav-item" ng-if="menu.show_staff == '0'">
                <div class="mobile-menu-item"><a href="{{menu.url}}" ng-bind="menu.title"></a></div>
              </li>
            </ul>
          </div>
          <div class="clear"></div>
        </div>
      </div>
    </div>
  </md-content>
</md-content> 
<md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="Notifications"  ng-cloak>
	<md-toolbar class="toolbar-white">
		<div class="md-toolbar-tools">
			<md-button ng-click="close()" class="md-icon-button" aria-label="Close"><i class="ion-android-arrow-forward"></i></md-button>
			<md-truncate><?php echo lang('notifications')?></md-truncate>
			<md-button class="md-mini" aria-label="Undread Notifications"><span ng-bind="notifications.length"></span></md-button>
		  </div>
	</md-toolbar>
	<md-content>
	<md-list flex>
		<md-list-item class="md-3-line" ng-repeat="ntf in notifications" ng-click="NotificationRead($index)" ng-class="{new_notification: ntf.read == true}" aria-label="Read"> 
			<img ng-src="<?php echo base_url('assets/img/reminder.png')?>" class="md-avatar" alt="NTF" />
			<div class="md-list-item-text" layout="column">
				<h4 ng-bind="ntf.detail"></h4>
				<p ng-bind="ntf.date"></p>
			</div>
		</md-list-item>
	</md-list>
	</md-content>
</md-sidenav>
<md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="Profile"  ng-cloak>
	<md-content class="text-center">
		<md-content layout-padding class="md-mt-20" style="line-height: 0px;">
			<img style="border-radius: 50%; box-shadow: 0 0 20px 0px #00000014;" height="100px" width="auto" ng-src="<?php echo base_url('assets/img/customer_avatar.png'); ?>" class="md-avatar"/>
			<h3><strong><?php echo $_SESSION[ 'name' ] ?> <?php echo $_SESSION[ 'surname' ] ?></strong></h3><br>
			<span><?php echo $this->session->userdata('email'); ?></span>
		</md-content>
		<md-content class="md-mt-30">
      		<md-button ng-href="<?php echo base_url('area/login/logout');?>" class="md-raised"><?php echo lang('logout')?></md-button>
		</md-content>
	</md-content>
</md-sidenav>
<!-- Appointment -->
<md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="Appointment"  ng-cloak>
	<md-toolbar class="toolbar-white" style="background:#262626">
	  <div class="md-toolbar-tools">
		<md-button ng-click="close()" class="md-icon-button" aria-label="Close">
			 <i class="ion-android-arrow-forward"></i>
		</md-button>
		<md-truncate><?php echo lang('new_appointment')?></md-truncate>
	  </div>
	</md-toolbar>
	<md-subheader ng-if="!AppointmentStaff" class="md-no-sticky"><?php echo lang('staff_for_appointment')?></md-subheader>
	<md-list-item ng-if="!AppointmentStaff" class="md-3-line" ng-repeat="staff in available_staff" ng-click="SelectedAppointmentStaff(staff)">
	  <img ng-src="<?php echo base_url('uploads/images/{{staff.avatar}}'); ?>" class="md-avatar" alt="{{staff.avatar}}" />
	  <div class="md-list-item-text" layout="column">
		<h4>{{ staff.name }}</h4>
		<p>{{ staff.name }}</p>
	  </div>
	</md-list-item>
	<md-content ng-hide="available_staff.length" class="md-padding text-center">
		<h1 style="font-size: 6em" class="ion-sad-outline text-danger"></h1><br>
		<span><?php echo lang('no_available_staff') ?></span>
		</md-content>
	<div ng-if="AppointmentStaff">
	<calendar selected="day"></calendar>
	<md-button ng-show="Available_Times != false" ng-init="colorGreen = false; TimeName = time" ng-class="{'md-raised md-selected': colorGreen}" ng-click="SelectedAppointmentTime(time);colorGreen = true;TimeName = 'Selected';" ng-repeat="time in Available_Times" >{{TimeName}}</md-button>
	<md-content ng-hide="Available_Times != false" class="md-padding text-center">
		<h1 style="font-size: 6em" class="mdi mdi-calendar-close text-danger"></h1><br>
		<span><?php echo lang('day_closed_to_appointments') ?></span>
	</md-content>
	<md-content layout-padding="">
		<md-content layout-padding ng-show="available_staff.length">
			<div class="text-center">
				<h4><strong><?php echo lang('appointment_date')?></strong></h4><br>
				<strong>{{day.format('dddd, MMMM Do YYYY')}} <strong class="text-warning">{{AppointmentTime}}</strong></strong>
			</div>
		</md-content>
		<section layout="row" layout-sm="column" layout-align="center center" layout-wrap>
			<md-button ng-click="ResetAppointment()"> <i class="ion-ios-refresh"></i> <?php echo lang('reset')?></md-button>
			<md-button ng-click="ConfirmAppointment(day.format('YYYY-MM-DD'))"> <i class="ion-android-checkmark-circle"></i> <?php echo lang('confirm')?></md-button>
		</section>
	 </md-content>
	</div>
</md-sidenav>
<!-- Appointment -->
<md-content class="ciuis-body-wrapper ciuis-body-fixed-sidebar" ciuis-ready>