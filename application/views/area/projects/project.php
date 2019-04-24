<?php include_once(APPPATH . 'views/area/inc/header.php'); ?>
<?php $appconfig = get_appconfig(); ?>
<div class="ciuis-body-content" ng-controller="Project_Controller">
	<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
		<md-content class="bg-white">
			<md-toolbar class="toolbar-white">
				<div class="md-toolbar-tools">
					<md-progress-circular md-mode="determinate" value="{{project.progress}}" class="md-hue-2" md-diameter="20px"></md-progress-circular>
					<h2 class="md-pl-10" flex md-truncate>
						<strong><?php echo $appconfig['project_prefix'] ?>{{project.id}}<?php echo $appconfig['project_suffix'] ?></strong>
						{{ project.name}}
					</h2>
				</div>
			</md-toolbar>
			<md-content class="bg-white">
				<div id="project-details" class="on-schedule projects-top">
				<div layout="row" layout-wrap>
		            <div flex-sm="33" flex-xs="50" flex-lg="16" flex-gt-sm="16" class="text-center">
		              <h5><?php echo lang('deadline') ?></h5>
		              <h3 ng-bind="project.deadline"></h3>
		            </div>
		            <div flex-sm="33" flex-xs="50" flex-lg="16" flex-gt-sm="16" class="text-center">
		              <h5><?php echo lang('status') ?> <span class="status-indicator on-schedule"></span></h5>
		              <h3 class="on-schedule" ng-bind="project.status"></h3>
		            </div>
		            <div flex-sm="33" flex-xs="50" flex-lg="16" flex-gt-sm="16" class="text-center">
		              <h5><?php echo lang('clocked').' '.lang('time') ?></h5>
		              <h3 ng-bind="getTotal() | time:'mm':'hhh mmm':false"></h3>
		            </div>
		            <div flex-sm="33" flex-xs="50" flex-lg="16" flex-gt-sm="16" class="text-center">
		              <h5><?php echo lang('billed') ?></h5>
		              <h3><span ng-bind="project.billed"></span> <a ng-hide="project.billed != '<?php echo lang( 'yes' ) ?>'" class="label label-success" href="<?php echo base_url('invoices/invoice/'.$projects['invoice_id'].'')?>"><?php echo $appconfig['inv_prefix'],'',str_pad($projects['invoice_id'], 6, '0', STR_PAD_LEFT).$appconfig['inv_suffix'] ?></a></h3>
		            </div>
		            <div flex-sm="33" flex-xs="50" flex-lg="16" flex-gt-sm="16" class="text-center">
		              <h5><?php echo lang('clocked').' '.lang('amount') ?></h5>
		              <h3 ng-bind-html="ProjectTotalAmount() | currencyFormat:cur_code:null:true:cur_lct"></h3>
		            </div>
		            <div flex-sm="33" flex-xs="50" flex-lg="16" flex-gt-sm="16" class="text-center">
		              <h5><?php echo lang('opentasks') ?></h5>
		              <h3 ng-bind="project.opentasks"></h3>
		            </div>
		          </div>
		      </div>
			</md-content>
			<md-tabs md-dynamic-height md-border-bottom>
				<md-tab label="<?php echo lang('summary') ?>">
					<h4 layout-padding class="m-xs text-success text-bold" ng-bind="project.customer"></h4>
					<md-divider></md-divider>
					<md-content class="bg-white">
						<div id="project-details" class="on-schedule projects-top">
			              <div layout="row" layout-wrap>
			                <div flex-sm="33" flex-xs="50" flex-lg="16" flex-gt-sm="16" class="text-center">
			                  <h4><strong ng-bind="project.ldt"></strong> <i class="ion-ios-stopwatch-outline"></i></h4>
			                <span class="stat-label text-muted"> <?php echo lang('daysleft') ?> </span> 
			                </div>
			                <div flex-sm="33" flex-xs="50" flex-lg="16" flex-gt-sm="16" class="text-center">
			                  <h4><span><span ng-bind="project.progress+'%'"></span></span></h4>
			                  <span class="stat-label"> <?php echo lang('progresscompleted') ?> </span> 
			                </div>
			                <div flex-sm="33" flex-xs="50" flex-lg="16" flex-gt-sm="16" class="text-center">
			                  <h4><span><span ng-bind-html="TotalExpenses() | currencyFormat:cur_code:null:true:cur_lct"></span></span></h4>
			                  <span class="stat-label"> <?php echo lang('totalexpenses').' '.lang('amount') ?> </span> 
			                </div>
			                <div flex-sm="33" flex-xs="50" flex-lg="16" flex-gt-sm="16" class="text-center">
			                  <h4><span><span ng-bind-html="BilledExpensesTotal() | currencyFormat:cur_code:null:true:cur_lct"></span></span></h4>
			                  <span class="stat-label"> <?php echo lang('billedexpenses') ?> </span> 
			                </div>
			                <div flex-sm="33" flex-xs="50" flex-lg="16" flex-gt-sm="16" class="text-center">
			                  <h4><span><span ng-bind-html="UnBilledExpensesTotal() | currencyFormat:cur_code:null:true:cur_lct"></span></span></h4>
			                  <span class="stat-label"> <?php echo lang('unbilledexpenses') ?> </span> 
			                </div>
			                <div flex-sm="33" flex-xs="50" flex-lg="16" flex-gt-sm="16" class="text-center">
			                  <h4><span><span ng-bind="expenses.length"></span></span></h4>
			                  <span class="stat-label"> <?php echo lang('total_s').' '.lang('expenses') ?> </span> 
			                </div>
			              </div>
			            </div>			  
					</md-content>
					<md-content class="md-padding bg-white">
						<h2><?php echo lang('description') ?></h2>
						<div ng-bind-html="project.description"></div>
				  	</md-content>
				</md-tab>
				<md-tab label=" <?php echo lang('notes') ?>">
		          <md-content class="md-padding bg-white">
		            <section class="md-pb-30">
		              <md-input-container class="md-block">
		                <label><?php echo lang('description') ?></label>
		                <textarea name="description" ng-model="note" placeholder="<?php echo lang('typeSomething'); ?>" class="form-control note-description"></textarea>
		              </md-input-container>
		              <div class="form-group pull-right">
		                <md-button ng-click="AddNote()" class="template-button pull-right" ng-disabled="addNote == true">
		                  <span ng-hide="addNote == true"><?php echo lang('addnote');?></span>
		                  <md-progress-circular class="white" ng-show="addNote == true" md-mode="indeterminate" md-diameter="20"></md-progress-circular>
		                </md-button>
		              </div>
		            </section>
		            <section class="ciuis-notes show-notes">
		              <article ng-repeat="note in notes" class="ciuis-note-detail">
		                <div class="ciuis-note-detail-img"> <img src="<?php echo base_url('assets/img/note.png') ?>" alt="" width="50" height="50"/> </div>
		                <div class="ciuis-note-detail-body">
		                	<p ng-bind="note.description"></p>
		                  <p class="attribution"> <?php echo lang('addedby') ?> <strong><span ng-bind="note.noteby"></span></strong> <?php echo lang('at') ?> <span ng-bind="note.date"></span> </p>
		                </div>
		              </article>
		            </section>
		          </md-content>
		        </md-tab>
				<md-tab label="<?php echo lang('timelogs') ?>">
					<md-content class="md-padding bg-white">
						<article class="time-log-project">
							<div class="panel panel-default panel-table">
								<div class="panel-body" style="overflow: scroll;height: 410px;">
									<table id="table2" class="table table-striped table-hover table-fw-widget">
										<thead>
											<tr>
												<th>
													<?php echo lang('id') ?> </th>
												<th>
													<?php echo lang('start') ?> </th>
												<th>
													<?php echo lang('end') ?> </th>
												<th>
													<?php echo lang('staff') ?> </th>
												<th>
													<?php echo lang('timed') ?> </th>
												<th>
													<?php echo lang('amount') ?> </th>
											</tr>
										</thead>
										<tr ng-repeat="timelog in timelogs">
											<td ng-bind="timelog.id"></td>
											<td ng-bind="timelog.start"></td>
											<td ng-bind="timelog.end"></td>
											<td ng-bind="timelog.staff"></td>
											<td ng-bind="timelog.timed | time:'mm':'hhh mmm':false"></td>
											<td><span><span ng-bind-html="timelog.amount | currencyFormat:cur_code:null:true:cur_lct"></span></span>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</article>
					</md-content>
				</md-tab>
				<md-tab label="<?php echo lang('projectactivities') ?>">
					<md-content class="md-padding bg-white">
						<ul class="user-timeline">
							<li ng-repeat="log_project in project.project_logs">
								<div class="user-timeline-title" ng-bind="log_project.date"></div>
								<div class="user-timeline-description" ng-bind="log_project.detail|trustAsHtml"></div>
							</li>
						</ul>
					</md-content>
				</md-tab>
			</md-tabs>
		</md-content>
	</div>
<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-3 project-sidebar">
	<md-toolbar class="toolbar-white">
		<div class="md-toolbar-tools">
			<md-button class="md-icon-button" aria-label="Member" ng-disabled="true">
			  <md-icon><i class="ion-ios-people text-muted"></i></md-icon>
			</md-button>
			<h2 flex md-truncate><?php echo lang('peopleonthisprojects') ?></h2>					
		</div>
	</md-toolbar>
	<div class="project-assignee">
		<div id="ciuis-customer-contact-detail">
			<div data-linkid="{{member.id}}" ng-repeat="member in project.members" class="ciuis-customer-contacts">
				<div data-toggle="modal" data-target="#contactmodal1">
					<img width="40" height="40" src="{{UPIMGURL}}{{member.staffavatar}}" alt="">
					<div style="padding: 16px;position: initial;">
						<strong ng-bind="member.staffname"></strong>
						<br>
						<span ng-bind="member.email"></span>
					</div>
					<div ng-show="project.authorization === 'true'" ng-click='UnlinkMember($index)' class="unlink">
					<i class="ion-ios-close-outline"></i>
					</div>
				</div>
			</div>
		</div>
	</div>
	<md-toolbar class="toolbar-white">
      <div class="md-toolbar-tools">
        <md-button class="md-icon-button" aria-label="Invoice" ng-disabled="true">
          <md-icon><i class="ion-document text-muted"></i></md-icon>
        </md-button>
        <h2 flex md-truncate><?php echo lang('files') ?></h2>
        <md-button ng-click="UploadFile()" class="md-icon-button md-primary" aria-label="Add File">
          <md-tooltip md-direction="bottom"><?php echo lang('upload').' '.lang('file') ?></md-tooltip>
          <md-icon class="ion-android-add-circle text-success"></md-icon>
        </md-button>
      </div>
    </md-toolbar>
    <div ng-show="projectFiles" layout-align="center center" class="text-center" id="circular_loader">
      <md-progress-circular md-mode="indeterminate" md-diameter="40"></md-progress-circular>
      <p style="font-size: 15px;margin-bottom: 5%;">
        <span><?php echo lang('please_wait') ?> <br>
        <small><strong><?php echo lang('loading'). ' '. lang('project_files').'...' ?></strong></small></span>
      </p>
    </div>
    <md-content class="bg-white" ng-show="!projectFiles">
      <md-list flex>
        <md-list-item class="md-2-line" ng-repeat="file in files | pagination : currentPage*itemsPerPage | limitTo: 6">
          <div class="md-list-item-text image-preview">
            <a ng-if="file.type == 'image'" class="cursor" ng-click="ViewFile($index, image)">
              <md-tooltip md-direction="left"><?php echo lang('preview') ?></md-tooltip>
              <img src="{{file.path}}">
            </a>
            <a ng-if="(file.type == 'archive')" class="cursor" ng-href="<?php echo base_url('area/projects/download_file/{{file.id}}');?>">
              <md-tooltip md-direction="left"><?php echo lang('download') ?></md-tooltip>
              <img src="<?php echo base_url('assets/img/zip_icon.png');?>">
            </a>
            <a ng-if="(file.type == 'file')" class="cursor" ng-href="<?php echo base_url('area/projects/download_file/{{file.id}}');?>">
              <md-tooltip md-direction="left"><?php echo lang('download') ?></md-tooltip>
              <img src="<?php echo base_url('assets/img/file_icon.png');?>">
            </a>
            <a ng-if="file.type == 'pdf'" class="cursor" ng-href="<?php echo base_url('area/projects/download_file/{{file.id}}');?>">
              <md-tooltip md-direction="left"><?php echo lang('download') ?></md-tooltip>
              <img src="<?php echo base_url('assets/img/pdf_icon.png');?>">
            </a>
          </div>
          <div class="md-list-item-text">
            <a class="cursor" ng-href="<?php echo base_url('area/projects/download_file/{{file.id}}');?>">
              <h3 class="link" ng-bind="file.file_name"></h3>
            </a>
          </div>
          <md-divider></md-divider>
        </md-list-item>
        <div ng-show="!files.length" class="text-center"><img width="70%" src="<?php echo base_url('assets/img/nofiles.jpg') ?>" alt=""></div>
      </md-list>
      <div ng-show="files.length>6 && !projectFiles" class="pagination-div">
        <ul class="pagination">
          <li ng-class="DisablePrevPage()"> <a href ng-click="prevPage()"><i class="ion-ios-arrow-back"></i></a> </li>
          <li ng-repeat="n in range()" ng-class="{active: n == currentPage}" ng-click="setPage(n)"> <a href="#" ng-bind="n+1"></a> </li>
          <li ng-class="DisableNextPage()"> <a href ng-click="nextPage()"><i class="ion-ios-arrow-right"></i></a> </li>
        </ul>
      </div>
    </md-content>
</div>
</div>
 <script type="text/ng-template" id="addfile-template.html">
  <md-dialog aria-label="options dialog">
  <?php echo form_open_multipart('area/projects/add_file/'.$projects['id'].'',array("class"=>"form-horizontal")); ?>
	<md-dialog-content layout-padding>
		<h2 class="md-title"><?php echo lang('choosefile'); ?></h2>
		<input type="file" required name="file_name">
	</md-dialog-content>
	<md-dialog-actions>
	  <span flex></span>
	  <md-button ng-click="close()"><?php echo lang('cancel') ?>!</md-button>
	  <md-button type="submit"><?php echo lang('add') ?>!</md-button>
	</md-dialog-actions>
  <?php echo form_close(); ?>
  </md-dialog>
</script>
<script type="text/ng-template" id="view_image.html">
  <md-dialog aria-label="options dialog">
  <md-dialog-content layout-padding>
    <?php $path = '{{file.path}}';
    if ($path) { ?>
      <img src="<?php echo $path ?>">
    <?php } ?>
  </md-dialog-content>
  <md-dialog-actions>
    <span flex></span>
    <md-button ng-href="<?php echo base_url('area/projects/download_file/') ?>{{file.id}}"><?php echo lang('download') ?>!</md-button>
    <md-button ng-click="close()"><?php echo lang('cancel') ?>!</md-button>
  </md-dialog-actions>
  <?php echo form_close(); ?>
  </md-dialog>
</script>
<script> var PROJECTID = "<?php echo $projects['id'];?>"; </script>
<?php include_once(APPPATH . 'views/area/inc/footer.php'); ?>