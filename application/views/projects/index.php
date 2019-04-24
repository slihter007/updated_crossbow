<?php $appconfig = get_appconfig(); ?>
<div class="ciuis-body-content" ng-controller="Projects_Controller">
  <div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
    <md-toolbar class="toolbar-white" style="margin-bottom: 1%;">
      <div class="md-toolbar-tools">
        <md-button class="md-icon-button" aria-label="File">
          <md-icon><i class="icon ico-ciuis-projects text-muted"></i></md-icon>
        </md-button>
        <h2 flex md-truncate><?php echo lang('projects'); ?></h2>
        <div class="ciuis-external-search-in-table">
          <input ng-model="search.name" class="search-table-external" id="search" name="search" type="text" placeholder="<?php echo lang('search_by').' '.lang('project').' '.lang('name')?>">
          <md-button class="md-icon-button" aria-label="Search">
            <md-icon><i class="ion-search text-muted"></i></md-icon>
          </md-button>
        </div>
        <md-button ng-click="toggleFilter()" class="md-icon-button" aria-label="Filter">
          <md-tooltip md-direction="bottom"><?php echo lang('filter') ?></md-tooltip>
          <md-icon><i class="ion-android-funnel text-muted"></i></md-icon>
        </md-button>
        <md-button ng-click="Create()" class="md-icon-button" aria-label="New">
          <md-tooltip md-direction="bottom"><?php echo lang('create') ?></md-tooltip>
          <md-icon><i class="ion-android-add-circle text-success"></i></md-icon>
        </md-button>
      </div>
    </md-toolbar>
    <div class="row projectRow">
      <div ng-show="projectLoader" layout-align="center center" class="text-center" id="circular_loader">
          <md-progress-circular md-mode="indeterminate" md-diameter="40"></md-progress-circular>
            <p style="font-size: 15px;margin-bottom: 5%;">
             <span>
                <?php echo lang('please_wait') ?> <br>
               <small><strong><?php echo lang('loading'). ' '. lang('projects').'...' ?></strong></small>
             </span>
           </p>
         </div>
      <div ng-show="!projectLoader" id="ciuisprojectcard" style="padding-left: 15px;padding-right: 5px;">
        <div ng-repeat="project in projects | pagination : currentPage*itemsPerPage | filter:search | limitTo: 6" class="col-md-4 {{project.status_class}}" style="padding-left: 0px;padding-right: 10px;">
          <div id="project-card" class="ciuis-project-card">
            <div class="ciuis-project-content">
              <div class="ciuis-content-header">
                <a href="<?php echo base_url('/projects/project/') ?>{{project.id}}">
                <div class="pull-left">
                  <p ng-attr-title="{{project.name}}" class="md-m-0" style="font-size: 14px;font-weight: 900;margin: unset;">
                    <strong><?php echo $appconfig['project_prefix'] ?>{{project.id}}<?php echo $appconfig['project_suffix'] ?></strong>
                    {{ project.name | limitTo: 28 }}{{project.name.length > 30 ? '...' : ''}}
                  </p>
                  <small ng-show="project.template == 1" ng-attr-title="<?php echo lang('template').' '.lang('project') ?>"><?php echo lang('template').' '.lang('project') ?></small> 
                  <small ng-show="project.template == 0" ng-attr-title="{{project.customer}}">{{ project.customer | limitTo: 28 }}{{project.customer.length > 30 ? '...' : ''}}</small>
                </div>
              </a>
              <div class="pull-right md-pr-10"  ng-hide="project.status_id == '4' || project.status_id == '5'">
                  <i class="ciuis-project-badge pull-right ion-checkmark-circled text-success" ng-click="markasComplete(project.id)"></i>
                  <md-tooltip md-direction="top"><?php echo lang('markasprojectcomplete') ?></md-tooltip>
                </div>
                <div class="pull-right md-pr-10" ng-show="project.template == 1 || project.template == 'true'">
                  <i class="ciuis-project-badge pull-right ion-ios-copy" ng-click="copyProjectDialog(project.id)"></i>
                  <md-tooltip md-direction="top"><?php echo lang('create_new_template_project') ?></md-tooltip>
                </div>
                <div class="pull-right md-pr-10"> 
                  <span>
                    <i ng-click='CheckPinned($index)' class="ciuis-project-badge pull-right ion-pin"></i>
                    <md-tooltip md-direction="top"><?php echo lang('mark_as_pinned') ?></md-tooltip>
                  </span>
                 <img data-toggle="tooltip" data-placement="left" data-container="body" title="" data-original-title="{{project.status}}" class="pull-right md-mr-5" height="32" ng-src="{{IMAGESURL}}{{project.status_icon}}"> 
               </div>
              </div>
              <div class="ciuis-project-dates">
                <div class="ciuis-project-start text-uppercase"><strong><?php echo lang('start'); ?></strong><b ng-bind="project.startdate"></b></div>
                <div class="ciuis-project-end text-uppercase"><strong><?php echo lang('deadline'); ?></strong><b ng-bind="project.leftdays"></b></div>
              </div>
              <div class="ciuis-project-stat col-md-12">
                <div class="col-md-6 bottom-left">
                  <div class="progress-widget">
                    <div class="progress-data text-left"><span ng-hide="project.status_class == 'cancelled'" class="progress-value" ng-bind="project.progress+'%'"></span> <span class="name" ng-bind="project.status"></span> </div>
                    <div ng-hide="project.status_class == 'cancelled'" class="progress" style="height: 7px">
                      <div ng-hide="project.progress == 100" style="width: {{project.progress}}%;" class="progress-bar progress-bar-primary"></div>
                      <div ng-show="project.progress == 100" style="width: {{project.progress}}%;" class="progress-bar progress-bar-success"></div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 md-p-0 bottom-right text-right">
                  <ul class="more-avatar">
                    <li ng-repeat="member in project.members" data-toggle="tooltip" data-placement="left" data-container="body" title="" data-original-title="{{member.staffname}}">
                      <md-tooltip md-direction="top">{{member.staffname}}</md-tooltip>
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
      <md-content ng-show="!projects.length && !projectLoader" class="md-padding no-item-data"><?php echo lang('notdata') ?></md-content>
    </div>
    <div ng-show="projects.length > 6 && !projectLoader">
      <div class="pagination-div">
        <ul class="pagination">
          <li ng-class="DisablePrevPage()"> <a href ng-click="prevPage()"><i class="ion-ios-arrow-back"></i></a> </li>
          <li ng-repeat="n in range()" ng-class="{active: n == currentPage}" ng-click="setPage(n)"> <a href="#" ng-bind="n+1"></a> </li>
          <li ng-class="DisableNextPage()"> <a href ng-click="nextPage()"><i class="ion-ios-arrow-right"></i></a> </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="main-content container-fluid col-xs-12 col-md-12 col-lg-3 md-pl-0 bg-white">
    <div class="projects-graph">
      <div class="col-md-12" style="padding: 0px;">
        <div class="panel-default">
          <div class="panel-heading panel-heading-divider xs-pb-15 text-bold" style="margin: 0px;"><?php echo lang('project').' '.lang('statuses'); ?></div>
          <div class="panel-body" style="padding: 0px;">
            <div class="project-stats-body pull-left">
              <div class="project-progress-data"> <span class="project-progress-value pull-right" ng-bind="stats.not_started_percent+'%'"></span> <span class="project-name"><?php echo lang('notstarted'); ?></span> </div>
              <div class="progress" style="height: 5px">
                <div style="width: {{stats.not_started_percent}}%;" class="progress-bar progress-bar-success"></div>
              </div>
            </div>
            <div class="project-stats-body pull-left">
              <div class="project-progress-data"> <span class="project-progress-value pull-right" ng-bind="stats.started_percent+'%'"></span> <span class="project-name"><?php echo lang('started'); ?></span> </div>
              <div class="progress" style="height: 5px">
                <div style="width: {{stats.started_percent}}%;" class="progress-bar progress-bar-success"></div>
              </div>
            </div>
            <div class="project-stats-body pull-left">
              <div class="project-progress-data"> <span class="project-progress-value pull-right" ng-bind="stats.percentage_percent+'%'"></span> <span class="project-name"><?php echo lang('percentage'); ?></span> </div>
              <div class="progress" style="height: 5px">
                <div style="width: {{stats.percentage_percent}}%;" class="progress-bar progress-bar-success"></div>
              </div>
            </div>
            <div class="project-stats-body pull-left">
              <div class="project-progress-data"> <span class="project-progress-value pull-right" ng-bind="stats.cancelled_percent+'%'"></span> <span class="project-name"><?php echo lang('cancelled'); ?></span> </div>
              <div class="progress" style="height: 5px">
                <div style="width: {{stats.cancelled_percent}}%;" class="progress-bar progress-bar-success"></div>
              </div>
            </div>
            <div class="project-stats-body pull-left">
              <div class="project-progress-data"> <span class="project-progress-value pull-right" ng-bind="stats.complete_percent+'%'"></span> <span class="project-name"><?php echo lang('complete'); ?></span> </div>
              <div class="progress" style="height: 5px">
                <div style="width: {{stats.complete_percent}}%;" class="progress-bar progress-bar-success"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-12 pinnedprojects bg-white">
      <div class="panel-default bg-white">
        <div class="pinned-projects-header bg-white"> <span><i class="ion-pin"></i> <?php echo lang('pinnedprojects'); ?></span> <span class="pull-right hide-pinned-projects"><a data-toggle="collapse" data-parent="#pinned-projects" href="#pinned-projects"><i class="icon mdi ion-minus-circled"></i></a></span> </div>
        <div id="pinned-projects" class="panel-collapse collapse in">
          <div class="pinned-projects">
            <div ng-repeat="project_pinned in pinnedprojects | filter: { pinned: '1' }" class="pinned-project-widget">
              <div class="pinned-project-body pull-left">
                <div class="project-progress-data"> <span class="project-progress-value pull-right" ng-bind="project_pinned.progress+'%'"></span> <span class="project-name" ng-bind="project_pinned.name"></span> </div>
                <div class="progress" style="height: 5px">
                  <div style="width:{{project_pinned.progress}}%;" class="progress-bar progress-bar-info"></div>
                </div>
              </div>
              <a ng-click='UnPinned(project_pinned.id)' class="pinned-project-action pull-right"><i class="ion-close-round"></i><md-tooltip md-direction="top"><?php echo lang('remove') ?></md-tooltip></a> <a href="<?php echo base_url('projects/project/')?>{{project_pinned.id}}" class="pinned-project-action pull-right"><i class="ion-android-open"></i><md-tooltip md-direction="top"><?php echo lang('go_to_project') ?></md-tooltip></a> </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="Create" style="width: 600px;">
    <md-toolbar class="toolbar-white">
      <div class="md-toolbar-tools">
        <md-button ng-click="close()" class="md-icon-button" aria-label="Close"> <i class="ion-android-arrow-forward"></i> </md-button>
        <h2 flex md-truncate><?php echo lang('create') ?></h2>
        <md-switch ng-model="project.template" aria-label="Type">
          <md-tooltip md-direction="bottom"><?php echo lang('save_as_template'); ?></md-tooltip>
          <strong class="text-muted"><?php echo lang('template'); ?> <i class="ion-information-circled"></i></strong>
        </md-switch>
      </div>
    </md-toolbar>
    <md-content>
      <md-content layout-padding>
        <md-input-container class="md-block">
          <label><?php echo lang('name') ?></label>
          <input required type="text" ng-model="project.name" class="form-control" id="title" placeholder="<?php echo lang('name'); ?>"/>
        </md-input-container>
        <md-input-container class="md-block" flex-gt-xs ng-hide="project.template">
          <label><?php echo lang('customer'); ?></label>
          <md-select required placeholder="<?php echo lang('choisecustomer'); ?>" ng-model="project.customer" name="customer" style="min-width: 200px;">
            <md-option ng-value="customer.id" ng-repeat="customer in all_customers">{{customer.name}}</md-option>
          </md-select>
        </md-input-container>
        <md-input-container>
          <label><?php echo lang('startdate') ?></label>
          <md-datepicker name="start" ng-model="project.start"></md-datepicker>
        </md-input-container>
        <md-input-container>
          <label><?php echo lang('deadline') ?></label>
          <md-datepicker md-min-date="project.start" name="deadline" ng-model="project.deadline"></md-datepicker>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('projectcost') ?></label>
          <input type="number" required name="value" min="0" ng-model="project.value" ng-init="project.value=0" placeholder="<?php echo lang('projectcost') ?>" class="form-control">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo $appconfig['tax_label'] ?></label>
          <input type="number" required name="tax" min="0" ng-model="project.tax" ng-init="project.tax=0" placeholder="<?php echo lang('tax') ?>" class="form-control">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('description') ?></label><br>
          <textarea required name="description" ng-model="project.description" placeholder="<?php echo lang('typeSomething'); ?>" class="form-control"></textarea>
        </md-input-container>
      </md-content>
      <custom-fields-vertical></custom-fields-vertical>
      <md-content layout-padding>
        <section layout="row" layout-sm="column" layout-align="center center" layout-wrap>
          <md-button ng-click="CreateNew()" class="md-raised md-primary pull-right" ng-disabled="saving == true">
            <span ng-hide="saving == true"><?php echo lang('create');?></span>
            <md-progress-circular class="white" ng-show="saving == true" md-mode="indeterminate" md-diameter="20"></md-progress-circular>
          </md-button>
        </section>
      </md-content> 
    </md-content>
  </md-sidenav>
  <md-sidenav class="md-sidenav-right md-whiteframe-4dp projects-filter" md-component-id="ContentFilter">
    <md-toolbar class="md-theme-light" style="background:#262626">
      <div class="md-toolbar-tools">
        <md-button ng-click="close()" class="md-icon-button" aria-label="Close"> <i class="ion-android-arrow-forward"></i> </md-button>
        <md-truncate><?php echo lang('filter') ?></md-truncate>
      </div>
    </md-toolbar>
    <md-content layout-padding="">
      <h4 class="text-bold"><?php echo lang('filter_by_status'); ?></h4>
      <button type="button" class="active pbtn btn btn-xl btn-default" id="all"><?php echo lang('showall'); ?></button>
        <button type="button" class="btn btn-xl pbtn btn-default" id="notstarted"> <?php echo lang('notstarted'); ?></button>
        <button type="button" class="btn btn-xl pbtn btn-default" id="started"> <?php echo lang('started'); ?></button>
        <button type="button" class="btn btn-xl pbtn btn-default" id="percentage"> <?php echo lang('percentage'); ?></button>
        <button type="button" class="btn btn-xl pbtn btn-default" id="cancelled"> <?php echo lang('cancelled'); ?></button>
        <button type="button" class="btn btn-xl pbtn btn-default text-success" id="complete"> <?php echo lang('completed'); ?></button>
        <button type="button" class="btn btn-xl pbtn btn-default text-success" id="template"> <?php echo lang('template'); ?></button>
    </md-content>
  </md-sidenav>
</div>
  <script type="text/ng-template" id="copyProjectDialog.html">
    <md-dialog aria-label="Expense Detail">
      <md-toolbar class="toolbar-white">
        <div class="md-toolbar-tools">
          <h2><strong class=""><?php echo lang('create_new_template_project') ?></strong></h2>
          <span flex></span>
          <md-button class="md-icon-button" ng-click="close()">
            <md-icon class="ion-close-round" aria-label="Close dialog" style="color:black"></md-icon>
          </md-button>
        </div>
      </md-toolbar>
      <md-dialog-content style="max-width:800px;max-height:810px;">
        <md-content class="bg-white">
          <md-list flex>
            <md-list-item>
              <div class="ciuis-custom-list-item-item col-md-12">
                <p><?php echo lang('projectCopy') ?></p>
                <md-input-container class="md-block" flex-gt-xs>
                  <md-checkbox ng-model="copy.service" ng-value="true" ng-checked="true">
                    <?php echo lang('copy_services') ?>
                  </md-checkbox><br>
                  <md-checkbox ng-model="copy.expenses" ng-value="true" ng-checked="false">
                    <?php echo lang('copy_expenses') ?>
                  </md-checkbox><br>
                  <md-checkbox ng-model="copy.milestones" ng-value="true" ng-checked="copy.tasks">
                    <?php echo lang('copy_milesstones') ?>
                  </md-checkbox><br>
                  <md-checkbox ng-model="copy.tasks" ng-value="true" ng-checked="copy.milestones">
                    <?php echo lang('copy_tasks') ?>
                  </md-checkbox><br>
                  <md-checkbox ng-model="copy.peoples" ng-value="true" ng-checked="true">
                    <?php echo lang('copy_project_peoples') ?>
                  </md-checkbox><br>
                  <md-checkbox ng-model="copy.files" ng-value="true" ng-checked="true">
                    <?php echo lang('copy_uploaded_files') ?>
                  </md-checkbox><br>
                  <md-checkbox ng-model="copy.notes" ng-value="true" ng-checked="false">
                    <?php echo lang('copy_project_notes') ?>
                  </md-checkbox>
                </md-input-container>
                <div class="row">
                  <div class="col-md-6 md-block">
                    <md-input-container class="md-block" flex-gt-xs>
                      <label><?php echo lang('customer'); ?></label>
                      <md-select required placeholder="<?php echo lang('choisecustomer'); ?>" ng-model="copy.customer" name="customer" style="min-width: 200px;">
                        <md-option ng-value="customer.id" ng-repeat="customer in all_customers">{{customer.name}}</md-option>
                      </md-select>
                    </md-input-container>
                  </div>
                  <div class="col-md-6 md-block">
                  </div>
                </div>
                <md-input-container>
                  <label><?php echo lang('startdate') ?></label>
                  <md-datepicker name="start" ng-model="copy.start"></md-datepicker>
                </md-input-container>
                <md-input-container>
                  <label><?php echo lang('deadline') ?></label>
                  <md-datepicker name="deadline" ng-model="copy.end"></md-datepicker>
                </md-input-container>
              </div>
            </md-list-item>
          </md-list>
        </md-content>     
      </md-dialog-content>
      <md-dialog-actions>
        <span flex></span>
        <md-button ng-click="close()"><?php echo lang('cancel') ?>!</md-button>
        <md-button ng-click="copyProjectConfirm()"><?php echo lang('doIt') ?></md-button>
      </md-dialog-actions>
    </md-dialog>
  </script>
  <script type="text/ng-template" id="processing.html">
    <md-dialog id="updating" style="box-shadow:none;padding:unset;min-width: 25%;">
      <md-dialog-content layout-padding layout-align="center center" aria-label="wait" style="text-align: center;">
        <md-progress-circular md-mode="indeterminate" md-diameter="40" style="margin-left: auto;margin-right: auto;"></md-progress-circular>
        <span style="font-size: 15px;"><strong><?php echo lang('processing'); ?></strong></span>
        <div class="row">
          <div class="col-md-12">
            <p style="opacity: 0.7;"><br><?php echo lang('update_note'); ?></p>
          </div>
        </div>
      </md-dialog-content>
    </md-dialog>
  </script>
  <script type="text/javascript">
    var lang = {};
    lang.doIt = '<?php echo lang("doIt") ?>';
    lang.project_complete_note = '<?php echo lang("project_complete_note") ?>';
    lang.attention = '<?php echo lang("attention") ?>';
    lang.cancel = '<?php echo lang("cancel") ?>';
  </script>
  <?php include_once( APPPATH . 'views/inc/footer.php' ); ?>
