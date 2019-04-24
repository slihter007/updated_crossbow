<?php $rebrand = load_config(); 
$appconfig = get_appconfig();
?>
<!DOCTYPE html>
<html ng-app="Ciuis" lang="<?php echo lang('lang_code');?>">
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
<script src="<?php echo base_url('assets/lib/angular/i18n/angular-locale_'.lang('lang_code_dash').'.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/ciuis.css'); ?>" type="text/css"/>
<script>var BASE_URL = "<?php echo base_url(); ?>", update_error = "<?php echo lang('update_error'); ?>", email_error = "<?php echo lang('email_error'); ?>",ACTIVESTAFF = "<?php echo $this->session->userdata('usr_id'); ?>",SHOW_ONLY_ADMIN = "<?php if (!if_admin) {echo 'true';} else echo 'false';?>",CURRENCY = "<?php echo currency ?>",LOCATE_SELECTED = "<?php echo lang('lang_code');?>",UPIMGURL = "<?php echo base_url('uploads/images/'); ?>",NTFTITLE = "<?php echo lang('notification')?>",INVMARKCACELLED = "<?php echo lang('invoicecancelled')?>",TICKSTATUSCHANGE = "<?php echo lang('ticketstatuschanced')?>",LEADMARKEDAS = "<?php echo lang('leadmarkedas')?>",LEADUNMARKEDAS = "<?php echo lang('leadunmarkedas')?>",TODAYDATE = "<?php echo date('Y.m.d ')?>",LOGGEDINSTAFFID = "<?php echo $this->session->userdata('usr_id'); ?>",LOGGEDINSTAFFNAME = "<?php echo $this->session->userdata('staffname'); ?>",LOGGEDINSTAFFAVATAR = "<?php echo $this->session->userdata('staffavatar'); ?>",VOICENOTIFICATIONLANG = "<?php echo lang('lang_code_dash');?>",initialLocaleCode = "<?php echo lang('initial_locale_code');?>";
var new_item = "<?php echo lang('new'); ?>";
var item_unit = "<?php echo lang('unit'); ?>";
</script>
<style type="text/css">
  <?php echo file_get_contents(base_url('assets/css/custom_css.css')); ?>
</style>
<?php echo file_get_contents(base_url('assets/js/custom_header_js.txt')); ?>
</head>
<?php $settings = $this->Settings_Model->get_settings_ciuis();
 ?>
<body ng-controller="Ciuis_Controller">
<div id="ciuisloader"></div>
<md-toolbar class="toolbar-ciuis-top"> 
  <div class="md-toolbar-tools"> 
    <!-- CRM NAME -->
    <div md-truncate class="crm-name crm-nm"><span ng-bind="settings.crm_name"></span></div>
    <md-button ng-click="OpenMenu()" class="md-icon-button hidden-lg hidden-md" aria-label="Menu">
      <md-icon><i class="ion-navicon-round text-muted"></i></md-icon>
    </md-button>
    <!-- CRM NAME --> 
    <!-- NAVBAR MENU -->
    <ul flex class="ciuis-v3-menu hidden-xs">
      <li ng-repeat="nav in navbar  | orderBy:'order_id'"><a ng-show="(nav.url != '#') || (nav.sub_menu.length > 0)" href="{{nav.url}}" ng-bind="nav.name"></a>
        <ul ng-show="nav.sub_menu.length">
          <li ng-repeat="submenu in nav.sub_menu | orderBy:'order_id'"><a ng-href="{{submenu.url}}"> <i class="icon {{submenu.icon}}"></i> <span class="title" ng-bind="submenu.name"></span> <span class="descr" ng-bind="submenu.description"></span> </a></li>
        </ul>
      </li>
    </ul>
    <!-- NAVBAR MENU -->
    <md-button class="md-icon-button" ng-click="searchNav()" aria-label="search">
      <md-tooltip md-direction="left" ng-bind='lang.search'></md-tooltip>
      <md-icon><i class="ion-search text-muted"></i></md-icon>
    </md-button>
    <?php if (!$this->session->userdata('other')) { ?>
    <div class="dropdown-container timer">
      <md-button ng-click="getTimer()" id="getTimer" class="md-icon-button dropdown-toggle" data-toggle="dropdown" aria-label="search">
        <md-tooltip md-direction="left" ng-bind='lang.timer'></md-tooltip>
        <md-icon><i class="ion-ios-clock text-muted" id="timerStart" ng-show="timer.start == true || timer.start == 'true'"></i><i id="timerStarted" ng-hide="timer.start" class="ion-ios-clock text-success"></i></md-icon>
      </md-button>
      <div class="dropdown-menu dropdown-menu-center" role="menu" layout-padding>
        <md-progress-circular ng-if="timer.loading" class="" md-mode="indeterminate" md-diameter="25"> </md-progress-circular>
        <p ng-show="timer.found" class="text-muted bottom-border"><?php echo lang('no_timer') ?></p>
        <p ng-show="timer.start">
          <md-button class="start" ng-click="startTimer('start')"><md-icon><i class="ion-ios-clock-outline"></i></md-icon> <?php echo lang('start') ?></md-button>
        </p>
        <p ng-show="timer.stop">
          <a ng-show="timer.task_id" href="{{appurl + 'tasks/task/' + timer.task_id}}" class="assigned"><strong ng-bind="timer.task"></strong></a>
          <a ng-show="!timer.task_id" class="label label-info assign" ng-click="stopTimerWithTask('assign')"><?php echo lang('assign_task') ?> &nbsp;&nbsp;<i class="ion-compose"></i></a>
          <br>
          <span class="text-muted"><?php echo lang('started_at') ?>: <span ng-bind="timer.started"></span></span><br>
          <span class="time-captured"><?php echo lang('time_captured') ?>: <span ng-bind="timer.total"></span></span><br>
        </p>
        <p ng-show="timer.stop">
          <md-button class="complete" ng-click="startTimer('stop')"><md-icon><i class="mdi mdi-timer-off"></i></md-icon> <?php echo lang('stop') ?></md-button>
        </p>
        <p class="top-border">
          <a class="cursor" title="<?php echo lang('view_timesheet') ?>" ng-href="<?php echo base_url('timesheets') ?>"><?php echo lang('view_timesheet') ?></a>
        </p>
      </div>
    </div>
  <?php } ?>
    <md-button ng-hide="ONLYADMIN != 'true'" class="md-icon-button" ng-href="{{appurl + 'settings'}}" aria-label="Settings">
      <md-tooltip md-direction="left" ng-bind='lang.settings'></md-tooltip>
      <md-icon><i class="ion-gear-a text-muted"></i></md-icon>
    </md-button>
    <md-button ng-click="Todo()" class="md-icon-button" aria-label="Todo">
      <md-tooltip md-direction="left" ng-bind='lang.todo'></md-tooltip>
      <md-icon><i class="ion-clipboard text-muted"></i></md-icon>
    </md-button>
    <md-button ng-click="Notifications()" class="md-icon-button" aria-label="Notifications">
      <md-tooltip md-direction="left" ng-bind='lang.notifications'></md-tooltip>
      <div ng-show="stats.newnotification == true" class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
      <md-icon><i class="ion-ios-bell text-muted"></i></md-icon>
    </md-button>
    <md-button ng-click="Profile()" class="md-icon-button avatar-button-ciuis" aria-label="User Profile"> <img height="100%" ng-src="<?php echo base_url('uploads/images/{{user.avatar}}')?>" class="md-avatar" alt="{{user.name}}" /> </md-button>
    <div ng-click="Profile()" md-truncate class="user-informations hidden-xs"> <span class="user-name-in" ng-bind="user.name"></span><br>
      <span class="user-email-in"><?php echo $this->session->userdata('email'); ?></span> </div>
  </div>
</md-toolbar>
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
            <span ng-repeat="nav in navbar  | orderBy:'order_id'">
              <ul ng-if="nav.url != '#'">
                <li class="nav-item">
                  <div class="mobile-menu-item"><a href="{{nav.url}}" ng-bind="nav.name"></a></div>
                </li>
              </ul>
              <ul>
                <li ng-repeat="submenu in nav.sub_menu | orderBy:'order_id'" class="nav-item">
                  <div class="mobile-menu-item"><a href="{{submenu.url}}" ng-bind="submenu.name"></a></div>
                </li>
              </ul>
            </span>
          </div>
          <div class="clear"></div>
        </div>
      </div>
    </div>
  </md-content>
</md-content> 
<header id="mainHeader" role="banner" class="hidden-xs">
  <nav role="navigation">
    <div class="top-header">
      <div class="navBurger"><a href="{{appurl + 'panel'}}"><img class="transform_logo" width="34px" height="34px" ng-src="{{appurl + 'uploads/ciuis_settings/' + applogo}}"></a></div>
    </div>
    <ul id="menu-vertical-menu icon" class="nav">
      <li ng-repeat="menu in menu" class="material-icons {{menu.icon}}" ng-if="menu.show_staff == '0'"><a href="{{menu.url}}">{{menu.title}}</a></li>
      <?php if (!$this->session->userdata('other')) { ?>
      <li class="material-icons ico-ciuis-tasks"><a href="{{appurl + 'tasks'}}" ng-bind='lang.tasks'></a></li>
      <li ng-repeat="pinned in projects | limitTo:1" class="profile-util hidden" style="z-index: 1; padding: 10px; width: 100%; height: 65px; margin-bottom: 0px; display: flex; border-top: 1px solid rgba(0,0,0,0.87);">
        <div class="chart">
          <div class="donut">
            <desc>
              <progress max="100" value="{{pinned.progress}}"></progress>
            </desc>
          </div>
        </div>
        <div>
          <a class="nav_pinned_project" href="{{appurl + 'projects/project/' + pinned.id}}" ng-bind="pinned.name"></a>
        </div>
      </li>
      <?php } ?>
    </ul>
  </nav>
</header>
<md-sidenav class="md-sidenav-left md-whiteframe-4dp" md-component-id="PickUpTo"  ng-cloak></md-sidenav>
<md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="SetOnsiteVisit"  ng-cloak>
  <md-toolbar class="md-theme-light" style="background:#262626">
    <div class="md-toolbar-tools">
      <md-button ng-click="close()" class="md-icon-button" aria-label="Close"> <i class="ion-android-arrow-forward"></i></md-button>
      <md-truncate ng-bind='lang.set_onsite_visit'></md-truncate>
    </div>
  </md-toolbar>
  <md-content layout-padding="">
    <md-content layout-padding>
      <md-input-container class="md-block">
        <label ng-bind='lang.title'></label>
        <input ng-model="onsite_visit.title">
      </md-input-container>
      <md-input-container class="md-block" flex-gt-xs>
        <label ng-bind='lang.customer'></label>
        <md-select required placeholder="{{lang.choisecustomer}}" ng-model="onsite_visit.customer_id" style="min-width: 200px;" aria-label='Customer'>
          <md-option ng-value="customer.id" ng-repeat="customer in all_customers">{{customer.name}}</md-option>
        </md-select>
      </md-input-container>
      <md-input-container class="md-block">
        <label ng-bind='lang.assigned'></label>
        <md-select placeholder="{{lang.choosestaff}}" ng-model="onsite_visit.staff_id" style="min-width: 200px;" aria-label='Staff'>
          <md-option ng-value="staff.id" ng-repeat="staff in staff">{{staff.name}}</md-option>
        </md-select>
      </md-input-container>
      <br>
      <md-input-container class="md-block">
        <label ng-bind='lang.start'></label>
        <input mdc-datetime-picker="" date="true" time="true" type="text" id="datetime" placeholder="{{lang.chooseadate}}" show-todays-date="" minutes="true" min-date="date" show-icon="true" ng-model="onsite_visit.start" class=" dtp-no-msclear dtp-input md-input">
      </md-input-container>
      <md-input-container class="md-block">
        <label ng-bind='lang.end'></label>
        <input mdc-datetime-picker="" date="true" time="true" type="text" id="datetime" placeholder="{{lang.chooseadate}}" show-todays-date="" minutes="true" min-date="date" show-icon="true" ng-model="onsite_visit.end" class=" dtp-no-msclear dtp-input md-input">
      </md-input-container>
      <md-input-container class="md-block">
        <label ng-bind='lang.description'></label>
        <textarea required ng-model="onsite_visit.description" placeholder="<?php echo lang('typeSomething'); ?>" class="form-control note-description"></textarea>
      </md-input-container>
      <div class="pull-right">
        <md-button ng-click="AddOnsiteVisit()" ng-bind='lang.set' aria-label='Add Onsite Visit'></md-button>
      </div>
    </md-content>
  </md-content>
</md-sidenav>

<md-sidenav class="md-sidenav-right md-whiteframe-5dp" md-component-id="searchNav" md-disable-close-events style="width: 650px;"  ng-cloak>
  <md-toolbar class="md-theme-light" style="background:#262626">
    <div class="md-toolbar-tools">
      <md-button ng-click="close()" class="md-icon-button" aria-label="Close"> <i class="ion-android-arrow-forward"></i></md-button>
      <md-truncate ng-bind='lang.search'></md-truncate>
    </div>
  </md-toolbar> 
  <md-content><br>
    <md-input-container ng-submit="searchInput(search_input)" class="md-block" style="margin-bottom: unset;">
      <label><?php echo lang('searchhere'); ?></label>
      <input ng-submit="searchInput(search_input)" name="search" ng-model="search_input" ng-keyup="searchInput(search_input)">
    </md-input-container>
    <p class="text-center text-muted" ng-show="searchResult == 1"><?php echo lang('not_found'); ?></p>
    <p class="text-center text-muted" ng-show="searchInputMsg == 1"><?php echo lang('type_something'); ?></p>
    <div ng-show="searchLoader == 1">
      <md-progress-circular md-mode="indeterminate" md-diameter="20" style="margin-left: auto;margin-right: auto;">
      </md-progress-circular>
      <p class="text-center">
        <strong><?php echo lang('searching'); ?></strong>
      </p>
    </div>
    <section ng-show="searchStaff.length > 0">
      <md-subheader class="md-accent"><span class="material-icons ico-ciuis-staff search-icon pull-right"></span> <?php echo lang('staff_members'); ?></md-subheader>
      <md-list layout-padding>
        <md-list-item class="md-3-line search-item" ng-repeat = "staff in searchStaff">
          <a href="{{appurl + 'staff/staffmember/' + staff.staff_id}}">
            <div class="md-list-item-text">
              <h4>{{ staff.name | limitTo: 20 }}{{staff.name.length > 20 ? '...' : ''}}</h4>
              <p>{{ staff.email | limitTo: 20 }}{{staff.email.length > 20 ? '...' : ''}}</p>
            </div>
          </a>
          <md-divider inset></md-divider>
        </md-list-item>
      </md-list>
    </section>
    <section ng-show="searchProjects.length > 0">
      <md-subheader class="md-accent">
        <span class="material-icons ico-ciuis-projects search-icon pull-right"></span> 
        <?php echo lang('projects'); ?>
      </md-subheader>
      <md-list layout-padding>
        <md-list-item class="md-3-line search-item" ng-repeat="project in searchProjects">
          <a href="{{appurl + 'projects/project/' + project.id}}">
            <div class="md-list-item-text">
              <h4>{{ project.name | limitTo: 20 }}{{project.name.length > 20 ? '...' : ''}}</h4>
              <p>
                <span ng-switch="project.status">
                  <span ng-switch-when="1"><?php echo lang( 'notstarted' ); ?></span>
                  <span ng-switch-when="2"><?php echo lang( 'started' ); ?></span>
                  <span ng-switch-when="3"><?php echo lang( 'percentage' ); ?></span>
                  <span ng-switch-when="4"><?php echo lang( 'cancelled' ); ?></span>
                  <span ng-switch-when="5"><?php echo lang( 'complete' ); ?></span>
                </span>
              </p>
            </div>
          </a>
          <md-divider inset></md-divider>
        </md-list-item>
      </md-list>
    </section>
    <section ng-show="searchInvoices.length > 0">
      <md-subheader class="md-accent"><span class="material-icons ico-ciuis-invoices search-icon pull-right"></span> <?php echo lang('invoices'); ?></md-subheader>
      <md-list layout-padding>
        <md-list-item class="md-3-line search-item" ng-repeat="invoice in searchInvoices">
          <a href="{{appurl + 'invoices/invoice/' + invoice.invoice_id}}">
            <div class="md-list-item-text">
              <h4><?php echo $appconfig['inv_prefix'] . '' . str_pad( (string)'{{invoice.invoice_id}}', 6, 0, STR_PAD_LEFT) .$appconfig['inv_suffix']. '' ?></h4>
              <p><strong>Customer:</strong> <span>{{ invoice.namesurname | limitTo: 20 }}{{invoice.namesurname.length > 20 ? '...' : ''}}</span></p>
            </div>
          </a>
          <md-divider inset></md-divider>
        </md-list-item>
      </md-list>
    </section>
    <section ng-show="searchProposals.length > 0">
      <md-subheader class="md-accent"><span class="material-icons ico-ciuis-proposals search-icon pull-right"></span> <?php echo lang('proposals'); ?></md-subheader>
      <md-list layout-padding>
        <md-list-item class="md-3-line search-item" ng-repeat="proposal in searchProposals">
          <a href="{{appurl + 'proposals/proposal/' + proposal.proposal_id}}">
            <div class="md-list-item-text">
              <h4>{{ proposal.subject | limitTo: 20 }}{{proposal.subject.length > 20 ? '...' : ''}}</h4>
              <p><strong>Customer:</strong> <span>{{ proposal.email | limitTo: 20 }}{{proposal.email.length > 20 ? '...' : ''}}</span></p>
            </div>
          </a>
          <md-divider inset></md-divider>
        </md-list-item>
      </md-list>
    </section>
    <section ng-show="searchCustomers.length > 0">
      <md-subheader class="md-accent"><span class="material-icons ico-ciuis-customers search-icon pull-right"></span> <?php echo lang('customers'); ?></md-subheader>
      <md-list layout-padding>
        <md-list-item class="md-3-line search-item" ng-repeat="customer in searchCustomers">
          <a href="{{appurl + 'customers/customer/' + customer.id}}">
            <div class="md-list-item-text">
              <h4>{{ customer.name | limitTo: 20 }}{{customer.name.length > 20 ? '...' : ''}}</h4>
              <p><span>{{ customer.email | limitTo: 20 }}{{customer.email.length > 20 ? '...' : ''}}</span></p>
            </div>
          </a>
          <md-divider inset></md-divider>
        </md-list-item>
      </md-list>
    </section>
    <section ng-show="searchLeads.length > 0">
      <md-subheader class="md-accent"><span class="material-icons ico-ciuis-leads search-icon pull-right"></span> <?php echo lang('leads'); ?></md-subheader>
      <md-list layout-padding>
        <md-list-item class="md-3-line search-item" ng-repeat="lead in searchLeads">
          <a href="{{appurl + 'leads/lead/' + lead.id}}">
            <div class="md-list-item-text">
              <h4>{{ lead.name | limitTo: 20 }}{{lead.name.length > 20 ? '...' : ''}}</h4>
              <p><span>{{ lead.company | limitTo: 20 }}{{lead.company.length > 20 ? '...' : ''}}</span></p>
            </div>
          </a>
          <md-divider inset></md-divider>
        </md-list-item>
      </md-list>
    </section>
    <section ng-show="searchExpenses.length > 0">
      <md-subheader class="md-accent"><span class="material-icons ico-ciuis-expenses search-icon pull-right"></span> <?php echo lang('expenses'); ?></md-subheader>
      <md-list layout-padding>
        <md-list-item class="md-3-line search-item" ng-repeat="expense in searchExpenses">
          <a href="{{appurl + 'expenses/receipt/' + expense.id}}">
            <div class="md-list-item-text">
              <h4><?php echo $appconfig['expense_prefix'] . '' . str_pad( (string)'{{expense.id}}', 6, 0, STR_PAD_LEFT).$appconfig['expense_suffix'] . '' ?></h4>
              <p><span>{{ expense.title | limitTo: 20 }}{{expense.title.length > 20 ? '...' : ''}}</span></p>
            </div>
          </a>
          <md-divider inset></md-divider>
        </md-list-item>
      </md-list>
    </section>
    <section ng-show="searchProducts.length > 0">
      <md-subheader class="md-accent"><span class="material-icons ico-ciuis-products search-icon pull-right"></span> <?php echo lang('products'); ?></md-subheader>
      <md-list layout-padding>
        <md-list-item class="md-3-line search-item" ng-repeat="product in searchProducts">
          <a href="{{appurl + 'products/product/' + product.id}}">
            <div class="md-list-item-text">
              <h4>{{ product.name | limitTo: 20 }}{{product.name.length > 20 ? '...' : ''}}</h4>
              <p>
                <span>{{ product.description | limitTo: 20 }}{{product.description.length > 20 ? '...' : ''}}</span>
              </p>
            </div>
          </a>
          <md-divider inset></md-divider>
        </md-list-item>
      </md-list>
    </section>
    <section ng-show="searchTickets.length > 0">
      <md-subheader class="md-accent"><span class="material-icons ico-ciuis-supports search-icon pull-right"></span> <?php echo lang('tickets'); ?></md-subheader>
      <md-list layout-padding>
        <md-list-item class="md-3-line search-item" ng-repeat="ticket in searchTickets">
          <a href="{{appurl + 'tickets/ticket/' + ticket.id}}">
            <div class="md-list-item-text">
              <h4>{{ ticket.subject | limitTo: 20 }}{{ticket.subject.length > 20 ? '...' : ''}}</h4>
              <p>
                <span>Message: {{ ticket.message | limitTo: 20 }}{{ticket.message.length > 20 ? '...' : ''}}</span>
              </p>
            </div>
          </a>
          <md-divider inset></md-divider>
        </md-list-item>
      </md-list>
    </section>
    <section ng-show="searchTasks.length > 0">
      <md-subheader class="md-accent"><span class="material-icons ico-ciuis-tasks search-icon pull-right"></span> <?php echo lang('tasks'); ?></md-subheader>
      <md-list layout-padding>
        <md-list-item class="md-3-line search-item" ng-repeat="task in searchTasks">
          <a href="{{appurl + 'tasks/task/' + task.id}}">
            <div class="md-list-item-text">
              <h4>{{ task.name | limitTo: 20 }}{{task.name.length > 20 ? '...' : ''}}</h4>
              <p>
                <span><strong>Assigned: </strong>{{ task.staff | limitTo: 20 }}{{task.staff.length > 20 ? '...' : ''}}</span>
              </p>
            </div>
          </a>
          <md-divider inset></md-divider>
        </md-list-item>
      </md-list>
    </section>
    <section ng-show="searchOrders.length > 0">
      <md-subheader class="md-accent"><span class="material-icons ico-ciuis-orders search-icon pull-right"></span> <?php echo lang('orders'); ?></md-subheader>
      <md-list layout-padding>
        <md-list-item class="md-3-line search-item" ng-repeat="order in searchOrders">
          <a href="{{appurl + 'orders/order/' + order.id}}">
            <div class="md-list-item-text">
              <h4>{{ order.subject | limitTo: 20 }}{{order.subject.length > 20 ? '...' : ''}}</h4>
              <p>
                <span><strong>Customer: </strong>{{ order.name | limitTo: 20 }}{{order.name.length > 20 ? '...' : ''}}</span>
              </p>
            </div>
          </a>
          <md-divider inset></md-divider>
        </md-list-item>
      </md-list>
    </section>
  </md-content>
</md-sidenav>
<md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="EventForm"  ng-cloak>
  <md-toolbar class="md-theme-light" style="background:#262626">
    <div class="md-toolbar-tools">
      <md-button ng-click="close()" class="md-icon-button" aria-label="Close"> <i class="ion-android-arrow-forward"></i> </md-button>
      <md-truncate ng-bind='lang.addevent'></md-truncate>
    </div>
  </md-toolbar>
  <md-content layout-padding="">
    <md-content layout-padding>
      <md-input-container class="md-block">
        <label ng-bind='lang.title'></label>
        <input ng-model="event_title">
      </md-input-container>
      <md-input-container class="md-block">
        <label ng-bind='lang.start'></label>
        <input mdc-datetime-picker="" date="true" time="true" type="text" id="datetime" placeholder="{{lang.chooseadate}}" show-todays-date="" minutes="true" min-date="date" show-icon="true" ng-model="event_start" class=" dtp-no-msclear dtp-input md-input">
      </md-input-container>
      <md-input-container class="md-block">
        <label ng-bind='lang.end'></label>
        <input mdc-datetime-picker="" date="true" time="true" type="text" id="datetime" minutes="true" min-date="date" show-icon="true" ng-model="event_end" class="dtp-no-msclear dtp-input md-input">
      </md-input-container>
      <md-input-container class="md-block">
        <label ng-bind='lang.description'></label>
        <textarea required name="detail" ng-model="event_detail" placeholder="<?php echo lang('typeSomething'); ?>" class="form-control note-description"></textarea>
      </md-input-container>
      <div class="ciuis-body-checkbox has-primary pull-left">
        <input ng-model="event_public" name="public" class="ci-public-check" id="public" type="checkbox" value="1">
        <label for="public" ng-bind='lang.publicevent'></label>
      </div>
      <div class="pull-right">
        <md-button ng-click="AddEvent()" ng-bind='lang.addevent' aria-label='Add Event'></md-button>
      </div>
    </md-content>
  </md-content>
</md-sidenav>
<md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="Todo"  ng-cloak>
  <md-content layout-padding="">
    <md-content layout-padding="">
      <md-input-container class="md-icon-float md-icon-right md-block">
        <textarea ng-model="tododetail" placeholder="<?php echo lang('type_todo') ?>" class="tododetail"></textarea> 
        <md-icon ng-show="addingTodo == true" class="" aria-label='Add Todo'>
          <md-tooltip md-direction="bottom"><?php echo lang('add') ?></md-tooltip>
          <md-progress-circular md-mode="indeterminate" md-diameter="18"></md-progress-circular>
        </md-icon>
        <md-icon ng-hide="addingTodo == true" aria-label='Add Todo' ng-click="AddTodo()" class="ion-ios-checkmark text-success"></md-icon>
      </md-input-container>
      <h4 md-truncate class=" text-muted text-uppercase"><strong ng-bind='lang.new'></strong></h4>
      <md-content layout-padding="">
        <ul class="todo-item">
          <li ng-repeat="todo in todos" class="todo-alt-item todo">
            <div class="todo-c" style="display: grid;margin-top: 10px;">
              <div class="todo-item-header">
                <div class="btn-group-sm btn-space pull-right">
                  <button data-id='{{todo.id}}' ng-click='TodoAsDone($index)' class="btn btn-default btn-sm ion-checkmark">
                    <md-tooltip md-direction="top"><?php echo lang('mark_as_done') ?></md-tooltip>
                  </button>
                  <button data-id='{{todo.id}}' ng-click='DeleteTodo($index)' class="btn btn-default btn-sm ion-trash-a">
                    <md-tooltip md-direction="top"><?php echo lang('delete') ?></md-tooltip>
                  </button>
                </div>
                <span style="padding:5px;" class="pull-left label label-default" ng-bind="todo.date | date : 'MMM d, y h:mm:ss a'"></span> </div>
                <br>
              <p class="todo-desc" ng-bind="todo.description"></p>
            </div>
          </li>
        </ul>
      </md-content>
      <h4 md-truncate class=" text-success"><strong ng-bind='lang.donetodo'></strong></h4>
      <md-content layout-padding="">
        <ul class="todo-item-done">
          <li ng-class="{ 'donetodo-x' : todo.done }" ng-repeat="done in tododone" class="todo-alt-item-done todo">
            <div class="todo-c" style="display: grid;margin-top: 10px;">
              <div class="todo-item-header">
                <div class="btn-group-sm btn-space pull-right">
                  <button data-id='{{todo.id}}' ng-click='TodoAsUnDone($index)' class="btn btn-default btn-sm ion-refresh">
                    <md-tooltip md-direction="top"><?php echo lang('mark_as_undone') ?></md-tooltip>
                  </button>
                  <button data-id='{{todo.id}}' ng-click='DeleteTodoDone($index)' class="btn btn-default btn-sm ion-trash-a">
                    <md-tooltip md-direction="top"><?php echo lang('delete') ?></md-tooltip>
                  </button>
                </div>
                <span style="padding:5px;" class="pull-left label label-success" ng-bind="done.date | date : 'MMM d, y h:mm:ss a'"></span></div>
                <br>
              <p class="todo-desc" ng-bind="done.description"></p>
            </div>
          </li>
        </ul>
      </md-content>
    </md-content>
  </md-content>
</md-sidenav>
<md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="Notifications" ng-cloak>
  <md-toolbar class="toolbar-white">
    <div class="md-toolbar-tools">
      <md-button ng-click="close()" class="md-icon-button" aria-label="Close"><i class="ion-android-arrow-forward"></i></md-button>
      <md-truncate ng-bind='lang.notifications'></md-truncate>
      <md-button class="md-mini" aria-label="Undread Notifications"><span ng-bind="stats.tbs"></span></md-button>
    </div>
  </md-toolbar>
  <md-content>
    <md-list flex>
      <md-list-item class="md-3-line" ng-repeat="ntf in notifications" ng-click="NotificationRead($index)" ng-class="{new_notification: ntf.read == true}" aria-label="Read"> <img ng-src="{{appurl + 'uploads/images/' + ntf.avatar}}" class="md-avatar" alt="NTF" on-error-src="<?php echo base_url('assets/img/placeholder.png')?>"/>
        <div class="md-list-item-text" layout="column">
          <h4 ng-bind="ntf.detail"></h4>
          <p ng-bind="ntf.date"></p>
        </div>
      </md-list-item>
    </md-list>
  </md-content>
</md-sidenav>
<md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="Profile" ng-cloak>
  <md-content>
    <md-tabs md-dynamic-height md-border-bottom>
      <md-tab label="Profile">
        <md-content layout-padding class="md-mt-10 text-center" style="line-height: 0px;height:200px"> <img style="border-radius: 50%; box-shadow: 0 0 20px 0px #00000014;" height="100px" width="auto" ng-src="{{appurl + 'uploads/images/' + user.avatar}}" class="md-avatar" alt="{{user.name}}" />
          <h3><strong ng-bind="user.name"></strong></h3>
          <br>
          <span ng-bind="user.email"></span></md-content>
        <md-content class="md-mt-30 text-center">
          <md-button ng-show="ONLYADMIN != 'true'" ng-href="{{appurl + 'staff/profile'}}" class="md-raised" ng-bind='lang.profile' aria-label='Profile'></md-button>
          <md-button ng-show="ONLYADMIN == 'true'" ng-href="{{appurl + 'staff/staffmember/' + activestaff}}" class="md-raised" ng-bind='lang.profile' aria-label='Profile'></md-button>
          <md-button ng-href="{{appurl + 'login/logout'}}" class="md-raised" ng-bind='lang.logout' aria-label='LogOut'></md-button>
        </md-content>
        <?php if (!$this->session->userdata('other')) { ?>
        <md-content layout-padding>
          <md-switch ng-model="appointment_availability" ng-change="ChangeAppointmentAvailability(user.id,appointment_availability)" aria-label="Status"><strong class="text-muted" ng-bind='lang.appointment_availability'></strong></md-switch>
        </md-content>
        <md-content>
          <md-list class="md-dense" flex>
            <md-subheader class="md-no-sticky text-uppercase"><span ng-bind='lang.appointments'></span></md-subheader>
            <md-list-item class="md-3-line" ng-repeat="appointment in dashboard_appointments">
              <div class="md-avatar a64"><span ng-bind="appointment.day"></span><br>
                <span class="a65" ng-bind="appointment.aday"></span></div>
              <div class="md-list-item-text" layout="column">
                <h3 ng-bind="appointment.title"></h3>
                <p><span ng-bind="appointment.start_iso_date | date : 'MMM d, y h:mm:ss a'"></span><br>
                  <span ng-bind="appointment.staff"></span></p>
              </div>
            </md-list-item>
          </md-list>
        </md-content>
      <?php } ?>
      </md-tab>
      <?php if (!$this->session->userdata('other')) { ?>
      <md-tab label="<?php echo lang('onsite_visits')?>">
        <md-list class="md-dense" flex>
          <md-subheader class="text-uppercase"><span ng-bind='lang.onsite_visits'></span></md-subheader>
          <md-list-item class="md-3-line" ng-repeat="meet in meetings">
            <div class="md-list-item-text" layout="column">
              <h3 ng-bind="meet.title"></h3>
              <h4 ng-bind="meet.customer"></h4>
              <p><span ng-bind="meet.date | date : 'MMM d, y h:mm:ss a'"></span><br>
                <span ng-bind="meet.staff"></span></p>
            </div>
            <md-divider></md-divider>
          </md-list-item>
        </md-list>
      </md-tab>
    <?php } ?>
    </md-tabs>
  </md-content>
</md-sidenav>
<md-content class="ciuis-body-wrapper ciuis-body-fixed-sidebar" ciuis-ready>