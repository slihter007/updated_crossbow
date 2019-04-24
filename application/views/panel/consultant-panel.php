<?php $appconfig = get_appconfig(); ?>
<div class="ciuis-body-content">
	<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
		<md-content class="bg-white ciuis-home-summary-top">
			<div class="col-md-3 col-sm-3 col-lg-3 nopadding">
			<md-toolbar class="toolbar-white" style="border-right:1px solid #e0e0e0">
				<div class="md-toolbar-tools">
					<h4 class="text-muted" flex md-truncate ><strong ng-bind='lang.panelsummary'></strong></h4>					
					<md-button class="md-icon-button" aria-label="Actions">
						<md-icon><span class="ion-flag text-muted"></span></md-icon>
					</md-button>
				</div>
			</md-toolbar>
				<md-content class="bg-white ciuis-summary-two" ng-controller="Consultant_Controller">
					<div class="ciuis-dashboard-box-b1-xs-ca-body">
							<div class="ciuis-dashboard-box-stats ciuis-dashboard-box-stats-main">
								<div class="ciuis-dashboard-box-stats-amount" ng-bind="consultant.totalInvoices"></div>
								<div class="ciuis-dashboard-box-stats-caption" ng-bind="lang.total_invoices"></div>
								<div class="ciuis-dashboard-box-stats-change">
									<div class="ciuis-dashboard-box-stats-value ciuis-dashboard-box-stats-value--positive" ng-bind="'+' + consultant.invoices_thisweek">+</div>
									<div class="ciuis-dashboard-box-stats-period" ng-bind='lang.thisweek'></div>
								</div>
							</div>
							<div class="ciuis-dashboard-box-stats">
								<div class="ciuis-dashboard-box-stats-amount" ng-bind="consultant.expenses"></div>
								<div class="ciuis-dashboard-box-stats-caption" ng-bind="lang.total_expenses"></div>
								<div class="ciuis-dashboard-box-stats-change">
									<div class="ciuis-dashboard-box-stats-value ciuis-dashboard-box-stats-value--positive" ng-bind="'+' + consultant.expenses_thisweek">+</div>
									<div class="ciuis-dashboard-box-stats-period" ng-bind='lang.thisweek'></div>
								</div>
							</div>
						</div>
					<div class="hidden-xs" ng-controller="Consultant_Controller">
						<h4 class="text-center" ng-bind='lang.monthlyexpenses'></h4>
					<div id="monthlyexpenses" style="height: 145px;display: block;bottom: 14px;position: absolute;width: 100%;border-bottom-left-radius: 5px;"></div>
					</div>
				</md-content>
			</div>
			<div class="col-sm-9 xs-p-0">
				<md-toolbar class="toolbar-white">
					<div class="md-toolbar-tools">
						<h4 class="text-muted" flex md-truncate ><strong ng-bind='lang.welcome'></strong></h4>
					</div>
				</md-toolbar>
				<md-content layout-padding class="bg-white ciuis-summary-two" style="overflow: hidden;">
					<div class="brr-5 trr-5">
						<div class="col-sm-4 nopadding">
							<div class="col-md-12 nopadding">
								<div class="hpanel stats">
									<div style="padding-top: 0px;line-height: 0.99;margin-right: 10px;" class="panel-body h-200 xs-p-0">
										<div class="col-md-12 xs-mb-20 md-pl-0" style="line-height: 28px;">
										<div class="col-md-12 text-center">
										<p><img ng-src="{{appurl + 'assets/img/' + stats.dayimage}}" on-error-src="{{appurl + 'assets/img/placeholder.png'}}"></p>
										<h4 ng-bind="stats.daymessage"></h4>
										<span ng-bind="user.staffname"></span>
										</div>
										<div class="col-md-12  md-pt-10 xs-pt-20 text-center" style="border-top: 1px solid #e0e0e0;" ng-bind='lang.haveaniceday'></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<hr class="hidden-sm hidden-md hidden-lg">
						<div class="col-sm-8 nopadding">
							<div class="widget widget-fullwidth ciuis-body-loading">
								<div class="widget-chart-container">
									<div class="my-2" ng-controller="Consultant_Controller">
										<div class="chart-wrapper" style="height:270px;">
											<div id="consultantchart" style="min-width: 310px; height: 340px; margin: 0 auto"></div>
										</div>
									</div>
								</div>
								<div class="ciuis-body-spinner">
									<svg width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
										<circle fill="none" stroke-width="4" stroke-linecap="round" cx="33" cy="33" r="30" class="circle"></circle>
									</svg>
								</div>
							</div>
						</div>
					</div>
				</md-content>
			</div>
		</md-content>
	</div>
	<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
		<?php include(APPPATH . 'views/inc/widgets/panel_bottom_summary.php'); ?>
	</div>
	<ciuis-sidebar></ciuis-sidebar>
</div>