</md-content>
<script src="<?php echo base_url('assets/lib/jquery/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/js/Ciuis.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/lib/moment.js/min/moment.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/lib/bootstrap/dist/js/bootstrap.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/lib/jquery.gritter/js/jquery.gritter.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/lib/angular-datepicker/src/js/angular-datepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/lib/chartjs/dist/Chart.bundle.js'); ?>" type="text/javascript"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.js'></script>
<script src="<?php echo base_url('assets/lib/highcharts/highcharts.js')?>"></script>
<script src="<?php echo base_url('assets/lib/material/angular-material.min.js')?>"></script>
<script src="<?php echo base_url('assets/lib/currency-format/currency-format.min.js')?>"></script>
<script src="<?php echo base_url('assets/lib/angular-datetimepicker/angular-material-datetimepicker.min.js')?>"></script>
<script src="<?php echo base_url('assets/lib/scheduler/scheduler.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/CiuisAngular.js'); ?>"></script>
<?php include_once(APPPATH . 'views/inc/templates.php'); ?>
<script type="text/javascript">
	<?php $newreminder = $this->Report_Model->newreminder();?>
	<?php $openticket = $this->Report_Model->otc();?>
	<?php $settings = $this->Settings_Model->get_settings_ciuis(); ?>
	
	function speak(CiuisVoiceNotification) {
	  var s = new SpeechSynthesisUtterance();
		s.volume = 0.5;
		s.rate = 1;
		s.pitch = 1; 
		s.lang = VOICENOTIFICATIONLANG;
		s.text = CiuisVoiceNotification;
		  window.speechSynthesis.speak(s);
	}
	var voice = document.querySelectorAll('body');
	var reminder = '<?php echo $message = sprintf( lang( 'reminder_voice' ), $newreminder)  ?>';
	var oepnticket = '<?php echo $message = sprintf( lang( 'open_ticket_voice' ), $openticket)  ?>';
	<?php if ( $this->session->flashdata('ntf1')) {?>
	$.gritter.add( {
		title: '<b><?php echo lang('notification')?></b>',
		text: '<?php echo $this->session->flashdata('ntf1'); ?>',
		class_name: 'color success'
	} );
	<?php }?>
	<?php if ( $this->session->flashdata('ntf2')) {?>
	$.gritter.add( {
		title: '<b><?php echo lang('notification')?></b>',
		text: '<?php echo $this->session->flashdata('ntf2'); ?>',
		class_name: 'color primary'
	} );
	<?php }?>
	<?php if ( $this->session->flashdata('ntf3')) {?>
	$.gritter.add( {
		title: '<b><?php echo lang('notification')?></b>',
		text: '<?php echo $this->session->flashdata('ntf3'); ?>',
		class_name: 'color warning'
	} );
	<?php }?>
	<?php if ( $this->session->flashdata('ntf4')) {?>
	$.gritter.add( {
		title: '<b><?php echo lang('notification')?></b>',
		text: '<?php echo $this->session->flashdata('ntf4'); ?>',
		class_name: 'color danger'
	} );
	<?php }?>
	<?php  if ($this->session->flashdata('login_notification')) {if ($this->session->userdata('admin')) {?>
	$.gritter.add({
		title: 'Vuuv! <?php echo $this->session->userdata('staffname'); ?>',
		text: '<?php echo $this->session->userdata('admin_notification'); ?>',
		image: '<?php echo base_url('assets/img/root_avatar.gif') ?>',
		class_name: 'img-rounded',
		time: '',
	});
	<?php } ?>
	$.gritter.add({
		title: '<?php echo lang('crmwelcome');?>',
		text: "<?php echo lang('welcomemessage');?>",
		image: '<?php echo base_url(); ?>uploads/images/<?php echo $this->session->userdata('staffavatar'); ?>',
		time: '',
		class_name: 'img-rounded'
	});
	var staffname = "<?php echo $message = sprintf( lang( 'welcome_once_message' ), $this->session->userdata('staffname'))?> ";
	<?php if($settings['voicenotification'] == 1){echo 'speak(staffname);';}?>
	<?php if($newreminder > 0 && $settings['voicenotification'] == 1){echo 'speak(reminder);';}?>
	<?php if($openticket > 0 && $settings['voicenotification'] == 1){echo 'speak(oepnticket);';}?>
	<?php } ?>
</script>
<script type="text/ng-template" id="timerTasks.html">
	<md-dialog id="timerTasks" style="box-shadow:none;padding:unset;min-width: 25%;">
		<md-toolbar class="toolbar-white" ng-show="!taskTimer.loader">
	        <div class="md-toolbar-tools">
	          <h2 ng-show="!taskTimer.loader && taskTimer.stop"><strong class="text-success"><?php echo lang('stoptimer') ?></strong></h2>
	          <h2 ng-show="!taskTimer.loader &&  taskTimer.assign"><strong class="text-success"><?php echo lang('assign_task') ?></strong></h2>
	          <span flex></span>
	          <md-button class="md-icon-button" ng-click="close()">
	            <md-icon class="ion-close-round" aria-label="Close dialog" style="color:black"></md-icon>
	            <md-tooltip md-direction="left"><?php echo lang('close') ?></md-tooltip>
	          </md-button>
	        </div>
	      </md-toolbar>
		<md-dialog-content layout-padding aria-label="wait" style="text-align: center;">
			<div layout-align="center center">
				<md-progress-circular ng-if="taskTimer.loader == true || taskTimer.loader == 'true'" md-mode="indeterminate" md-diameter="30"></md-progress-circular>
				<p ng-show="taskTimer.loader" style="font-size: 15px;margin-bottom: 5%;">
					<span>
						<?php echo lang('please_wait') ?> <br>
						<small><strong><?php echo lang('loading'). ' '. lang('tasks').'...' ?></strong></small>
					</span>
				</p>
			</div>
			<div layput-padding ng-show="!taskTimer.loader" style="text-align: left;">
				<md-input-container>
					<label><?php echo lang('task') ?></label>
					<md-select required ng-model="stopTimer.task" md-on-close="clearSearchTerm()" data-md-container-class="selectdemoSelectHeader" style="min-width: 250px;">
						<md-select-header class="demo-select-header">
							<input ng-model="searchTerm" type="search" placeholder="<?php echo lang('search_task') ?>" class="demo-header-searchbox md-text">
						</md-select-header>
						<md-optgroup label="data">
							<md-option ng-value="task.id" ng-repeat="task in timerTasks | filter:searchTerm">{{task.name}}
							</md-option>
						</md-optgroup>
					</md-select>
				</md-input-container>
				<md-input-container class="md-block">
					<label><?php echo lang('note') ?></label>
					<textarea required name="description" ng-model="stopTimer.note" placeholder="<?php echo lang('typeSomething');?>" class="form-control"></textarea>
				</md-input-container>
				<section layout="row" layout-sm="column" layout-align="center center" layout-wrap>
					<md-button ng-click="timerStopConfirm();" class="start-button"><?php echo lang('confirm');?></md-button>
				</section>
			</div>
		</md-dialog-content>
	</md-dialog>
</script>
 <?php echo file_get_contents(base_url('assets/js/custom_footer_js.txt')); ?>
</body>
</html>