<?php include_once(APPPATH . 'views/inc/header.php'); ?>
<?php $appconfig = get_appconfig(); ?>
<div class="ciuis-body-content" ng-controller="Reports_Controller">
	<md-content class="main-content container-fluid col-xs-12 col-md-12 col-lg-12">
	<md-toolbar class="toolbar-white">
	  <div class="md-toolbar-tools">
		<md-button class="md-icon-button" aria-label="Settings" ng-disabled="true">
		  <md-icon><i class="ico-ciuis-leads text-warning"></i></md-icon>
		</md-button>
		<h2 flex md-truncate><?php echo lang('reports') ?></h2>
	  </div>
	</md-toolbar>
	<md-content class="">
		<md-tabs md-dynamic-height md-border-bottom md-selected="ctrl.selectedIndex">
		  <md-tab label="<?php echo lang('overview') ?>">
		  	<div ng-show="overview.loader" layout-align="center center" class="text-center" id="circular_loader">
		  		<md-progress-circular md-mode="indeterminate" md-diameter="40"></md-progress-circular>
				<p style="font-size: 15px;margin-bottom: 5%;">
					<span>
						<?php echo lang('please_wait') ?> <br>
						<small><strong><?php echo lang('loading'). ' '. lang('overview').'...' ?></strong></small>
					</span>
				</p>
			</div>
			<md-content class="md-padding"  ng-show="!overview.loader">
				<md-content class="widget-fullwidth ciuis-body-loading">
					<md-card flex-xs flex-gt-xs="100" layout="column">
						<div layout-xs="column" layout="row" class="bg-white">
							<md-card flex-xs flex-gt-xs="20" layout="column" class="text-center card1">
								<a href="tickets">
									<md-card-title>
										<md-card-title-text>
											<span class="md-headline"><strong ng-bind="report.totalTickets"></strong></span>
											<span class="md-subhead"><?php echo lang('total_tickets') ?></span>
										</md-card-title-text>
									</md-card-title>
								</a>
							</md-card>
							<md-card flex-xs flex-gt-xs="20" layout="column" class="text-center card2">
								<a href="customers">
									<md-card-title>
										<md-card-title-text>
											<span class="md-headline"><strong ng-bind="report.totalCustomers"></strong></span>
											<span class="md-subhead"><?php echo lang('total_customers') ?></span>
										</md-card-title-text>
									</md-card-title>
								</a>
							</md-card>
							<md-card flex-xs flex-gt-xs="20" layout="column" class="text-center card3">
								<a href="leads">
									<md-card-title>
										<md-card-title-text>
											<span class="md-headline"><strong ng-bind="report.totalLeads"></strong></span>
											<span class="md-subhead"><?php echo lang('total_leads') ?></span>
										</md-card-title-text>
									</md-card-title>
								</a>
							</md-card>
							<md-card flex-xs flex-gt-xs="20" layout="column" class="text-center card4">
								<a href="projects">
									<md-card-title>
										<md-card-title-text>
											<span class="md-headline"><strong ng-bind="report.totalProjects"></strong></span>
											<span class="md-subhead"><?php echo lang('total_projects') ?></span>
										</md-card-title-text>
									</md-card-title>
								</a>
							</md-card>
							<md-card flex-xs flex-gt-xs="20" layout="column" class="text-center card5">
								<a href="products">
									<md-card-title>
										<md-card-title-text>
											<span class="md-headline"><strong ng-bind="report.totalProducts"></strong></span>
											<span class="md-subhead"><?php echo lang('total_products') ?></span>
										</md-card-title-text>
									</md-card-title>
								</a>
							</md-card>
						</div>
					</md-card>
					<div layout-xs="column" layout="row" class="">
						<div flex-xs flex-gt-xs="45" layout="column">
							<md-card>
								<div class="widget-chart-container">
									<div class="widget-counter-group widget-counter-group-right">
										<div style="width: auto;" class="pull-left">
											<i style="font-size: 38px;color: #eaeaea;margin-right: 10px" class="ion-stats-bars pull-left"></i>
											<div class="pull-right" style="text-align: left;margin-top: 10px;line-height: 10px;">
												<h4 style="padding: 0px;margin: 0px;"><b><?php echo lang('payments_vs_expenses');?></b></h4>
												<small><?php echo lang('payments_vs_expenses_chart');?></small>
											</div>
										</div>
										<div class="pull-right">
											<md-input-container class="md-block">
												<md-select ng-model="paymentsExpensesByYear" ng-change="getPaymentsExpensesByYear(paymentsExpensesByYear)" style="min-width: 100px;">
													<md-option ng-value="2020">2020</md-option>
													<md-option ng-value="2019" ng-selected="true">2019</md-option>
													<md-option ng-value="2018">2018</md-option>
													<md-option ng-value="2017">2017</md-option>
													<md-option ng-value="2016">2016</md-option>
													<md-option ng-value="2015">2015</md-option>
													<md-option ng-value="2014">2014</md-option>
												</md-select>
											</md-input-container>
										</div>
									</div>
									<div class="my-2">
										<div class="chart-wrapper" style="">
											<canvas style="" id="incomingsvsoutgoins"></canvas>
										</div>
									</div>
								</div>
							</md-card>
						</div>
						<div flex-xs flex-gt-xs="50" layout="column">
							<md-card>
								<div class="widget-chart-container">
									<div class="widget-counter-group widget-counter-group-right">
										<div style="width: auto;" class="pull-left">
											<i style="font-size: 38px;color: #eaeaea;margin-right: 10px" class="ion-stats-bars pull-left"></i>
											<div class="pull-right" style="text-align: left;margin-top: 10px;line-height: 10px;">
												<h4 style="padding: 0px;margin: 0px;"><b><?php echo lang('payments_vs_expenses_weekly');?></b></h4>
												<small><?php echo lang('payments_vs_expenses_chart_weekly');?></small>
											</div>
										</div>
									</div>
									<div class="my-2">
										<div id="incomingsvsoutgoins_weekly" style="">
										</div>
									</div>
								</div>
							</md-card>
						</div>
					</div>
					<md-card flex-xs flex-gt-xs="100" layout="column" class="text-center">
						<h3><?php echo lang('sales_data') ?></h3>
						<div layout-xs="column" layout="row">
							<md-card flex-xs flex-gt-xs="20" layout="column" class="text-center card6">
								<a href="invoices">
									<md-card-title>
										<md-card-title-text>
											<span class="md-headline"><strong ng-bind="report.totalInvoices"></strong></span>
											<span class="md-subhead"><?php echo lang('total_invoices') ?></span>
										</md-card-title-text>
									</md-card-title>
								</a>
							</md-card>
							<md-card flex-xs flex-gt-xs="20" layout="column" class="text-center card7">
								<a href="#">
									<md-card-title>
										<md-card-title-text>
											<span class="md-headline"><strong ng-bind="report.totalQotations">0</strong></span>
											<span class="md-subhead"><?php echo lang('total_qotations') ?></span>
										</md-card-title-text>
									</md-card-title>
								</a>
							</md-card>
							<md-card flex-xs flex-gt-xs="20" layout="column" class="text-center card1">
								<a href="orders">
									<md-card-title>
										<md-card-title-text>
											<span class="md-headline"><strong ng-bind="report.totalOrders"></strong></span>
											<span class="md-subhead"><?php echo lang('total_orders') ?></span>
										</md-card-title-text>
									</md-card-title>
								</a>
							</md-card>
							<md-card flex-xs flex-gt-xs="20" layout="column" class="text-center card8">
								<a href="leads">
									<md-card-title>
										<md-card-title-text>
											<span class="md-headline"><strong ng-bind="report.totalLeads"></strong></span>
											<span class="md-subhead"><?php echo lang('total_leads') ?></span>
										</md-card-title-text>
									</md-card-title>
								</a>
							</md-card>
							<md-card flex-xs flex-gt-xs="20" layout="column" class="text-center card9">
								<a href="tasks">
									<md-card-title>
										<md-card-title-text>
											<span class="md-headline"><strong ng-bind="report.totalTasks"></strong></span>
											<span class="md-subhead"><?php echo lang('total_tasks') ?></span>
										</md-card-title-text>
									</md-card-title>
								</a>
							</md-card>
						</div>
					</md-card>
				</md-content>
				<div layout-xs="column" layout="row" class="">
					<div flex-xs flex-gt-xs="45" layout="column">
						<md-card>
							<div class="widget-chart-container">
								<div class="widget-counter-group widget-counter-group-right">
									<div style="width: auto;" class="pull-left">
										<i style="font-size: 38px;color: #eaeaea;margin-right: 10px" class="ion-stats-bars pull-left"></i>
										<div class="pull-right" style="text-align: left;margin-top: 10px;line-height: 10px;">
											<h4 style="padding: 0px;margin: 0px;"><b><?php echo lang('incomingsvsoutgoings');?></b></h4>
											<small><?php echo lang('currentyearstats');?></small>
										</div>
									</div>
								</div>
								<div class="my-2">
									<div class="chart-wrapper" style="height:auto">
										<canvas style="padding-top: 25px;" id="expensesbycategories"></canvas>
									</div>
								</div>
							</div>
						</md-card>
					</div>
					<div flex-xs flex-gt-xs="45" layout="column">
						<md-card>
							<div class="widget-chart-container">
								<div class="widget-counter-group widget-counter-group-right">
									<div style="width: auto;" class="pull-left">
										<i style="font-size: 38px;color: #eaeaea;margin-right: 10px" class="ion-stats-bars pull-left"></i>
										<div class="pull-right" style="text-align: left;margin-top: 10px;line-height: 10px;">
											<h4 style="padding: 0px;margin: 0px;"><b><?php echo lang('staffbasedgraphics');?></b></h4>
											<small><?php echo lang('currentweekstats');?></small>
										</div>
									</div>
								</div>
								<div class="my-2">
									<div class="chart-wrapper" style="height:auto">
										<canvas style="padding-top: 25px;" id="top_selling_staff_chart"></canvas>
									</div>
								</div>
							</div>
						</md-card>
					</div>
				</div>
			</md-content>
		  </md-tab>
		  <md-tab label="<?php echo lang('invoicessummary') ?>">
		  	<md-content class="md-padding">
			  	<div layout-xs="column" layout="row" class="">
			  		<div flex-xs flex-gt-xs="45" layout="column">
			  			<md-card>
							<div class="widget-counter-group widget-counter-group-right">
								<div style="width: auto" class="pull-left">
									<i style="font-size: 38px;color: #eaeaea;margin-right: 10px" class="ion-stats-bars pull-left"></i>
									<div class="pull-right" style="text-align: left;margin-top: 10px;line-height: 10px;">
										<h4 style="padding: 0px;margin: 0px;"><b><?php echo lang('invoicesbystatuses');?></b></h4>
										<small><?php echo lang('billsbystatus');?></small>
									</div>
								</div>
							</div>
							<div class="my-2">
								<div class="chart-wrapper">
									<canvas id="invoice_chart_by_status"></canvas>
								</div>
							</div>
						</md-card>
					</div>
				</div>
			</md-content>
		  </md-tab>
		  <md-tab label="<?php echo lang('customersummary') ?>">
		  	<md-content class="md-padding">
			  	<div layout-xs="column" layout="row" class="">
			  		<div flex-xs flex-gt-xs="100" layout="column">
			  			<md-card>
							<div layout="row" layout-align="start" flex>
								<md-input-container flex="50">
									<?php
									echo '<md-select ng-model="CustomerReportMonth" placeholder="Select a state" ng-change="CustomerMonthChanged()">' . PHP_EOL;
									for ( $m = 1; $m <= 12; $m++ ) {
										$_selected = '';
										if ( $m == date( 'm' ) ) {
											$_selected = ' selected';
										}
										echo '<md-option ng-value="' . $m . '"' . $_selected . '>' . ( date( 'F', mktime( 0, 0, 0, $m, 1 ) ) ) . '</md-option>' . PHP_EOL;
									}
									echo '</md-select>' . PHP_EOL;
									?>
								</md-input-container>
							</div>
							<canvas class="customergraph_ciuis-xe chart mtop20" id="customergraph_ciuis-xe" height="400px" style="height: 400px"></canvas>
						</md-card>
					</div>
				</div>
			</md-content>
		  </md-tab>
		  <md-tab label="<?php echo lang('lead') ?>">
		  	<md-content class="md-padding">
			  	<div layout-xs="column" layout="row" class="">
			  		<div flex-xs flex-gt-xs="100" layout="column">
			  			<md-card>
							<div layout-align="start" flex>
								<md-input-container flex="50">
									<?php
									echo '<md-select ng-model="LeadReportMonth" placeholder="Select a state" ng-change="LeadMonthChanged()">' . PHP_EOL;
									for ( $m = 1; $m <= 12; $m++ ) {
										$_selected = '';
										if ( $m == date( 'm' ) ) {
											$_selected = ' selected';
										}
										echo '<md-option ng-value="' . $m . '"' . $_selected . '>' . ( date( 'F', mktime( 0, 0, 0, $m, 1 ) ) ) . '</md-option>' . PHP_EOL;
									}
									echo '</md-select>' . PHP_EOL;
									?>
								</md-input-container>
							</div>
							<canvas class="lead_graph chart mtop20" id="lead_graph" height="300px" style="height: 300px"></canvas>
						</md-card>
					</div>
				</div>
				<div layout-xs="column" layout="row" class="">
					<div flex-xs flex-gt-xs="40" layout="column">
			  			<md-card>
			  				<div class="widget-chart-container">
								<div class="widget-counter-group widget-counter-group-right">
									<div style="width: auto" class="pull-left">
										<i style="font-size: 38px;color: #eaeaea;margin-right: 10px" class="ion-stats-bars pull-left"></i>
										<div class="pull-right" style="text-align: left;margin-top: 10px;line-height: 10px;">
											<h4 style="padding: 0px;margin: 0px;"><b><?php echo lang('leads_by_source') ?></b></h4>
											<small><?php echo lang('billsbystatus') ?></small>
										</div>
									</div>
								</div>
								<div class="my-2">
									<div class="chart-wrapper">
										<canvas id="leads_by_leadsource"></canvas>
									</div>
								</div>
							</div>
						</md-card>
					</div>
					<div flex-xs flex-gt-xs="40" layout="column">
			  			<md-card>
			  				<div class="widget-chart-container">
								<div class="widget-counter-group widget-counter-group-right">
									<div style="width: auto" class="pull-left">
										<i style="font-size: 38px;color: #eaeaea;margin-right: 10px" class="ion-stats-bars pull-left"></i>
										<div class="pull-right" style="text-align: left;margin-top: 10px;line-height: 10px;">
											<h4 style="padding: 0px;margin: 0px;"><b><?php echo lang('leads_win_source') ?></b></h4>
											<small><?php echo lang('billsbystatus') ?></small>
										</div>
									</div>
								</div>
								<div class="my-2">
									<div class="chart-wrapper">
										<canvas id="leads_to_win_by_leadsource"></canvas>
									</div>
								</div>
							</div>
						</md-card>
					</div>
				</div>
			</md-content>
		</md-tab>
		<md-tab label="<?php echo lang('timesheet') ?>" ng-click="getTimesheet()" id="timesheetTab">
			<md-content class="md-padding">
				<md-content class="widget-fullwidth ciuis-body-loading">
					<div ng-show="timesheet.loader" layout-align="center center" class="text-center" id="circular_loader">
						<md-progress-circular md-mode="indeterminate" md-diameter="40"></md-progress-circular>
						<p style="font-size: 15px;margin-bottom: 5%;">
							<span>
								<?php echo lang('please_wait') ?> <br>
								<small><strong><?php echo lang('loading'). ' '. lang('timesheet').'...' ?></strong></small>
							</span>
						</p>
					</div>
					<md-card flex-xs flex-gt-xs="100" layout="column" ng-show="!timesheet.loader">
						<md-content class="md-pt-0">
							<h3 class="md-padding"><?php echo lang('timesheet_data') ?></h3>
					        <ul class="custom-ciuis-list-body" style="padding: 0px;cursor: auto;">
					          <li ng-repeat="timesheet in timesheets | filter: FilteredData | filter:search | pagination : currentPage*itemsPerPage | limitTo: 5" class="ciuis-custom-list-item ciuis-special-list-item">
					            <ul class="list-item-for-custom-list">
					              <li class="ciuis-custom-list-item-item col-md-12">
					                <div data-toggle="tooltip" data-placement="bottom" data-container="body" title="" data-original-title="Assigned: {{lead.assigned}}" class="assigned-staff-for-this-lead user-avatar"><a ng-href="<?php echo base_url('staff/staffmember/')?>{{timesheet.staff_id}}"> <img src="<?php echo base_url('uploads/images/{{timesheet.avatar}}')?>" alt="{{timesheet.staff}}"></a> </div>
					                <div class="pull-left col-md-4"><a ng-href="<?php echo base_url('staff/staffmember/')?>{{timesheet.staff_id}}"><strong ng-bind="timesheet.staff"></strong></a><br>
					                  <small ng-bind="timesheet.note"></small> </div>
					                <div class="col-md-8">
					                	<div class="col-md-3"><span class="date-start-task"><small class="text-muted text-uppercase"><?php echo lang('task') ?> <i class="ion-ios-stopwatch-outline"></i></small><br>
					                    <a ng-href="<?php echo base_url('tasks/task/')?>{{timesheet.relation_id}}"><strong ng-bind="timesheet.name"></strong></a></span></div>
					                  	<div class="col-md-3"><span class="date-start-task"><small class="text-muted text-uppercase"><?php echo lang('start_time') ?> <i class="ion-ios-stopwatch-outline"></i></small><br>
					                    <strong ng-bind="timesheet.start_time"></strong></span></div>
					                    <div class="col-md-3"><span class="date-start-task"><small class="text-muted text-uppercase"><?php echo lang('end_time') ?> <i class="ion-ios-stopwatch-outline"></i></small><br>
					                    	<strong ng-bind="timesheet.end_time"></strong>
					                    	<span class="badge ng-binding" style="border-color: #fff;background-color: #26c281;" ng-if="!timesheet.end_time"><?php echo lang('in_progress') ?></span>
					                    	</span>
					                    </div>
					                    <div class="col-md-3">
					                    	<span class="date-start-task">
						                    	<small class="text-muted text-uppercase"><?php echo lang('timeCaptured') ?> 
						                    	<i class="ion-ios-stopwatch-outline"></i>
						                    </small>
						                    <br>
						                    <strong ng-bind="timesheet.total_time"></strong>
						                    </span>
					                	</div>
					                </div>
					              </li>
					            </ul>
					          </li>
					        </ul>
					        <div class="ciuis-custom-list-item-item col-md-12">
					        	<div class="pull-right col-md-2">
					        		<span class="text-muted"><?php echo lang('time_captured') ?>: </span>
					        		<md-tooltip md-direction="bottom" ng-bind='lang.time_format'></md-tooltip>
					        		 <strong ng-bind="total_time"></strong>
					        	</div>
					        </div>
					        <div ng-hide="timesheets.length < 5" class="pagination-div">
					          <ul class="pagination">
					            <li ng-class="DisablePrevPage()"> <a href ng-click="prevPage()"><i class="ion-ios-arrow-back"></i></a> </li>
					            <li ng-repeat="n in range()" ng-class="{active: n == currentPage}" ng-click="setPage(n)"> <a href="#" ng-bind="n+1"></a> </li>
					            <li ng-class="DisableNextPage()"> <a href ng-click="nextPage()"><i class="ion-ios-arrow-right"></i></a> </li>
					          </ul>
					        </div>
					        <md-content ng-show="!timesheets.length" class="md-padding no-item-data"><?php echo lang('notdata') ?></md-content>
					      </md-content>
					</md-card>
				</md-content>
			</md-content>
		</md-tab>
		</md-tabs>
	  </md-content>		
	</md-content>
</div>
<?php include_once( APPPATH . 'views/inc/footer.php' );?>
<script type="text/javascript">
	var lang = {};
	lang.payments = '<?php echo lang('payments') ?>';
	lang.expenses = '<?php echo lang('expenses') ?>';
</script>