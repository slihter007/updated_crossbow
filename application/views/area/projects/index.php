<?php include_once(APPPATH . 'views/area/inc/header.php'); ?>
<?php $appconfig = get_appconfig(); ?>
<div class="ciuis-body-content" ng-controller="Projects_Controller">
	<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
		<div class="row">
			<div id="ciuisprojectcard" style="padding-left: 15px;padding-right: 5px;">
				<div ng-repeat="project in projects | pagination : currentPage*itemsPerPage | limitTo: 6" class="col-md-4 {{project.status_class}}" style="padding-left: 0px;padding-right: 10px;">
					<div id="project-card" class="ciuis-project-card">
						<div class="ciuis-project-content">
							<div class="ciuis-content-header">
								<a href="<?php echo base_url('/area/projects/project/') ?>{{project.id}}">
					                <div class="pull-left">
					                  <p ng-attr-title="{{project.name}}" class="md-m-0" style="font-size: 14px;font-weight: 900;margin: unset;">
					                    <strong><?php echo $appconfig['project_prefix'] ?>{{project.id}}<?php echo $appconfig['project_suffix'] ?></strong>  
					                    {{ project.name | limitTo: 30 }}{{project.name.length > 32 ? '...' : ''}}
					                  </p>
					                  <small ng-attr-title="{{project.customer}}">{{ project.customer | limitTo: 30 }}{{project.customer.length > 32 ? '...' : ''}}</small>
					                </div>
					              </a>
								<div class="pull-right md-pr-10">
									<img data-toggle="tooltip" data-placement="left" data-container="body" title="" data-original-title="{{project.status}}" class="pull-right md-mr-5" height="32" src="{{IMAGESURL}}{{project.status_icon}}">
								</div>
							</div>
							<div class="ciuis-project-dates">
								<div class="ciuis-project-start text-uppercase"><strong><?php echo lang('start'); ?></strong><b ng-bind="project.startdate"></b></div>
								<div class="ciuis-project-end text-uppercase"><strong><?php echo lang('deadline'); ?></strong><b ng-bind="project.leftdays"></b></div>
							</div>
							<div class="ciuis-project-stat col-md-12">
								<div class="col-md-6 bottom-left">
									<div class="progress-widget">
										<div class="progress-data text-left"><span class="progress-value" ng-bind="project.progress+'%'"></span>
										<span class="name" ng-bind="project.status"></span>
										</div>
										<div ng-hide="project.status_class == 'cancelled'" class="progress" style="height: 7px">
											<div ng-hide="project.progress == 100" style="width: {{project.progress}}%;" class="progress-bar progress-bar-primary"></div>
											<div ng-show="project.progress == 100" style="width: {{project.progress}}%;" class="progress-bar progress-bar-success"></div>
										</div>
									</div>
								</div>
								<div class="col-md-6 md-p-0 bottom-right text-right">
									<ul class="more-avatar">
										<li ng-repeat="member in project.members" data-toggle="tooltip" data-placement="left" data-container="body" title="" data-original-title="{{member.staffname}}">
											<div style=" background: lightgray url({{UPIMGURL}}{{member.staffavatar}}) no-repeat center / cover;"></div>
										</li>
										<div class="assigned-more-pro hidden"><i class="ion-plus-round"></i>2</div>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div>
			<div class="pagination-div">
				<ul class="pagination">
					<li ng-class="DisablePrevPage()">
						<a href ng-click="prevPage()"><i class="ion-ios-arrow-back"></i></a>
					</li>
					<li ng-repeat="n in range()" ng-class="{active: n == currentPage}" ng-click="setPage(n)">
						<a href="#" ng-bind="n+1"></a>
					</li>
					<li ng-class="DisableNextPage()">
						<a href ng-click="nextPage()"><i class="ion-ios-arrow-right"></i></a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<?php include_once(APPPATH . 'views/area/inc/sidebar.php'); ?>
	
</div>
<?php include_once(APPPATH . 'views/area/inc/footer.php'); ?>