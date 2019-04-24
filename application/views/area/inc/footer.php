</md-content>
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
<script src="<?php echo base_url('assets/lib/chartjs/dist/Chart.bundle.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/lib/highcharts/highcharts.js')?>"></script>
<script src="<?php echo base_url('assets/lib/material/angular-material.min.js')?>"></script>
<script src="<?php echo base_url('assets/lib/currency-format/currency-format.min.js')?>"></script>
<script src="<?php echo base_url('assets/lib/angular-datetimepicker/angular-material-datetimepicker.min.js')?>"></script>
<script type="text/ng-template" id="calendar.html">
	<div class="header">
		<i class="fa fa-angle-left ion-chevron-left" ng-click="previous()"></i>
		<span>{{month.format("MMMM, YYYY")}}</span>
		<i class="fa fa-angle-right ion-chevron-right" ng-click="next()"></i>
	</div>
	<div class="week names">
		<span class="day">Sun</span>
		<span class="day">Mon</span>
		<span class="day">Tue</span>
		<span class="day">Wed</span>
		<span class="day">Thu</span>
		<span class="day">Fri</span>
		<span class="day">Sat</span>
	</div>
	<div class="week" ng-repeat="week in weeks">
		<span class="day" ng-class="{ today: day.isToday, 'different-month': !day.isCurrentMonth, selected: day.date.isSame(selected) }" ng-click="select(day)" ng-repeat="day in week.days">{{day.number}}</span>
	</div>
</script>
<script src="<?php echo base_url('assets/js/AreaAngular.js'); ?>"></script>
<script>
	var CURRENCY = "<?php echo currency ?>";
	var UPIMGURL = "<?php echo base_url('uploads/images/'); ?>";
	var IMAGESURL = "<?php echo base_url('assets/img/'); ?>";
	var SETFILEURL = "<?php echo base_url('uploads/ciuis_settings/') ?>";
	var NTFTITLE = "<?php echo lang('notification')?>";
	var TODAYDATE = "<?php echo date('Y.m.d ')?>";
	var LOCATE_SELECTED = "en_us";
</script>
</body>
</html>