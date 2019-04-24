<?php include_once(APPPATH . 'views/inc/header.php'); ?>
<?php $appconfig = get_appconfig(); ?>
<div class="ciuis-body-content" ng-controller="Appointments_Controller">
	<md-content class="main-content container-fluid col-md-12">
		<md-content class="bg-white" style="height:630px;">
			<div class="bg-white col-md-2">
				<ciuisscheduler-navigator id="navi" ciuisscheduler-config="navigatorConfig" ></ciuisscheduler-navigator>
			</div>
			<md-content class="bg-white col-md-7" layout-padding>
			<div class="space">
				<md-button class="md-raised md-primary" ng-click="showDay()"><?php echo lang('day') ?></md-button>
				<md-button class="md-raised md-primary" ng-click="showWeek()"><?php echo lang('week') ?></md-button>
			</div>
			<ciuisscheduler-calendar id="day" ciuisscheduler-config="dayConfig" ciuisscheduler-events="events" ></ciuisscheduler-calendar>
			<ciuisscheduler-calendar id="week" ciuisscheduler-config="weekConfig" ciuisscheduler-events="events" ></ciuisscheduler-calendar>
			</md-content>
			<md-content class="bg-white col-md-3">
			<md-toolbar class="toolbar-white">
				<div class="md-toolbar-tools">
					<h2 flex md-truncate ><strong><?php echo lang('appointments') ?></strong></h2>
				</div>
			</md-toolbar>
			<md-content class="appointments appointments_xs calendar-appointments" style="margin-top: 0px">
				<md-subheader class="md-no-sticky event-subheader"><i class="mdi mdi-calendar-alt"></i> <?php echo lang('requested_appointments') ?></md-subheader>
				<ul style="padding: 0px 20px 0px 20px;">
					<li ng-repeat="appointment in requested_appointments" class="{{appointment.status_class}}" ng-click="ShowAppointment(appointment.id)">
						<label class="date"> <span class="weekday" ng-bind="appointment.day"></span><span class="day" ng-bind="appointment.aday"></span> </label>
						<h3 ng-bind="appointment.title"></h3>
						<p>
							<span class='duration' ng-bind="appointment.start_iso_date | date : 'MMM d, y h:mm:ss a'"></span>
							<span class='location' ng-bind="appointment.staff"></span>
						</p>
					</li>
				</ul>
				<md-content ng-show="!requested_appointments.length" class="text-center bg-white">
				<h1 class="text-success"><i class="mdi mdi-calendar-note"></i></h1>
				<span class="text-muted"><?php echo lang('no_requested_appointment') ?></span>
				</md-content>
			</md-content>
			<md-content class="appointments appointments_xs calendar-appointments" style="margin-top: 0px">
				<md-subheader class="md-no-sticky event-subheader"><i class="mdi mdi-calendar-alt"></i> <?php echo lang('today_appointments') ?></md-subheader>
				<ul style="padding: 0px 20px 0px 20px;">
					<li ng-repeat="appointment in today_appointments" class="{{appointment.status_class}}" ng-click="ShowAppointment(appointment.id)">
						<label class="date"> <span class="weekday" ng-bind="appointment.day"></span><span class="day" ng-bind="appointment.aday"></span> </label>
						<h3 ng-bind="appointment.title"></h3>
						<p>
							<span class='duration' ng-bind="appointment.start_iso_date | date : 'MMM d, y h:mm:ss a'"></span>
							<span class='location' ng-bind="appointment.staff"></span>
						</p>
					</li>
				</ul>
				<md-content ng-show="!today_appointments.length" class="text-center bg-white">
				<h1 class="text-success"><i class="mdi mdi-calendar-note"></i></h1>
				<span class="text-muted"><?php echo lang('no_appointment_today') ?></span>
				</md-content>
			</md-content>
			</md-content>
		</md-content>
	</md-content>
<div style="visibility: hidden">
<div ng-repeat="appointment in appointments" class="md-dialog-container" id="Appointment-{{appointment.id}}">
<md-dialog aria-label="Appointment Detail">
  <form>
    <md-toolbar class="toolbar-white">
      <div class="md-toolbar-tools">
        <h2>{{appointment.title}}</h2>
        <span flex></span>
        <md-button class="md-icon-button" ng-click="close()">
          <md-icon class="ion-close-round" aria-label="Close dialog" style="color:black"></md-icon>
        </md-button>
      </div>
    </md-toolbar>
    <md-dialog-content style="max-width:800px;max-height:810px; ">
       <md-content class="bg-white">
      	<md-list flex>
			<md-list-item>
				<md-icon class="ion-person"></md-icon>
				<p ng-bind="appointment.contact"></p>
			</md-list-item>
			<md-divider></md-divider>
			<md-content layout-padding>
      	<h3 class="md-mt-0" ng-bind="appointment.start_iso_date | date : 'MMM d, y h:mm:ss a'"></h3>
      </md-content>
			<md-list-item>
				<md-icon class="ion-flag"></md-icon>
				<p ng-if="appointment.status == '0'"><strong class="text-warning"><?php echo lang('requested') ?></strong></p>
				<p ng-if="appointment.status == '1'"><strong class="text-success"><?php echo lang('confirmed') ?></strong></p>
				<p ng-if="appointment.status == '2'"><strong class="text-danger"><?php echo lang('declined') ?></strong></p>
				<p ng-if="appointment.status == '3'"><strong class="text-success"><?php echo lang('done') ?></strong></p>
			</md-list-item>
		</md-list>
      </md-content>     
    </md-dialog-content>
    <md-dialog-actions layout="row">
      <md-button ng-click='MarkAsDoneAppointment(appointment.id)' aria-label="Done">
       	<?php echo lang('mark_as_done')?>
      </md-button>
      <span flex></span>
      <md-button ng-click='DeclineAppointment(appointment.id)' aria-label="Decline">
        <?php echo lang('decline')?> <i class="ion-close-round"></i>
      </md-button>
      <md-button ng-click="ConfirmAppointment(appointment.id)" style="margin-right:20px;" aria-label="Confirm">
        <?php echo lang('confirm')?> <i class="ion-checkmark-round"></i>
      </md-button>
    </md-dialog-actions>
  </form>
</md-dialog>
</div>
</div>
</div>
<?php include_once( APPPATH . 'views/inc/footer.php' );?>
</body> 
</html>