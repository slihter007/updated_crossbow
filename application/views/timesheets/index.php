<?php $appconfig = get_appconfig(); ?>
<div class="ciuis-body-content" ng-controller="Timesheets_Controller">
  <style type="text/css">
    rect.highcharts-background {
      fill: #f3f3f3;
    }
  </style>
  <div class="main-content container-fluid col-xs-12 col-md-9 col-lg-9"> 
    <md-toolbar class="toolbar-white">
      <div class="md-toolbar-tools">
        <h2 flex md-truncate class="text-bold"><?php echo lang('timesheets'); ?><br>
          <small flex md-truncate><?php echo lang('timesheets_description'); ?></small>
        </h2>
        <div class="ciuis-external-search-in-table">
          <input ng-model="search.name" class="search-table-external" id="search" name="search" type="text" placeholder="<?php echo lang('search_by').' '.lang('task').' '.lang('name')?>">
          <md-button class="md-icon-button" aria-label="Search">
            <md-icon><i class="ion-search text-muted"></i></md-icon>
          </md-button>
        </div>
        <md-button ng-click="refreshTimeLogs()" class="md-icon-button" aria-label="Filter">
          <md-progress-circular ng-show="refreshing == true" md-mode="indeterminate" md-diameter="20"></md-progress-circular>
          <md-tooltip ng-hide="refreshing == true" md-direction="top"><?php echo lang('refresh').' '.lang('timesheets') ?></md-tooltip>
          <md-icon ng-hide="refreshing == true"><i class="ion-ios-refresh text-muted"></i></md-icon>
        </md-button>
        <md-button ng-click="LogTime()" class="md-icon-button" aria-label="New">
          <md-tooltip md-direction="bottom"><?php echo lang('log').' '.lang('time') ?></md-tooltip>
          <md-icon><i class="ion-android-add-circle text-success"></i></md-icon>
        </md-button>
      </div>
    </md-toolbar>
    <div ng-show="loadingTimesheets" layout-align="center center" class="text-center" id="circular_loader">
        <md-progress-circular md-mode="indeterminate" md-diameter="40"></md-progress-circular>
        <p style="font-size: 15px;margin-bottom: 5%;">
         <span>
            <?php echo lang('please_wait') ?> <br>
           <small><strong><?php echo lang('loading'). ' '. lang('timesheets').'...' ?></strong></small>
         </span>
       </p>
     </div>
    <md-content ng-show="!loadingTimesheets" class="md-pt-0">
      <ul class="custom-ciuis-list-body" style="padding: 0px;cursor: auto;">
        <li ng-repeat="timesheet in timesheets | filter: FilteredData | filter:search | pagination : currentPage*itemsPerPage | limitTo: 8" class="ciuis-custom-list-item ciuis-special-list-item">
          <ul class="list-item-for-custom-list">
            <li class="ciuis-custom-list-item-item col-md-12">
              <div data-toggle="tooltip" data-placement="bottom" data-container="body" title="" data-original-title="Assigned: {{lead.assigned}}" class="assigned-staff-for-this-lead user-avatar">
                <a ng-href="<?php echo base_url('staff/staffmember/')?>{{timesheet.staff_id}}"> 
                  <img src="<?php echo base_url('uploads/images/{{timesheet.avatar}}')?>" alt="{{timesheet.staff}}">
                </a> 
              </div>
              <div class="pull-left col-md-3" ng-click="viewTimesheet(timesheet.id)">
                <strong>{{ timesheet.staff | limitTo: 30 }}{{timesheet.staff > 30 ? '...' : ''}}</strong>
                <br>
                <small>
                  {{ timesheet.note | limitTo: 35 }}{{timesheet.note > 35 ? '...' : ''}}
                </small> 
              </div>
              <div class="col-md-9">
                <div class="col-md-3" ng-click="viewTimesheet(timesheet.id)">
                  <span class="date-start-task">
                    <small class="text-muted text-uppercase"><?php echo lang('task') ?> <i class="ion-ios-stopwatch-outline"></i></small><br>
                    <a ng-href="<?php echo base_url('tasks/task/')?>{{timesheet.task_id}}">
                      <strong>{{ timesheet.name | limitTo: 30 }}{{timesheet.name > 30 ? '...' : ''}}</strong>
                    </a>
                  </span>
                </div> 
                <div class="col-md-2" ng-click="viewTimesheet(timesheet.id)">
                  <span class="date-start-task">
                    <small class="text-muted text-uppercase"><?php echo lang('start_time') ?> <i class="ion-ios-stopwatch-outline"></i></small><br>
                    <strong ng-bind="timesheet.start_time"></strong>
                  </span>
                </div>
                <div class="col-md-3" ng-click="viewTimesheet(timesheet.id)">
                  <span class="date-start-task">
                    <small class="text-muted text-uppercase"><?php echo lang('end_time') ?> <i class="ion-ios-stopwatch-outline"></i></small>
                    <br>
                    <strong ng-bind="timesheet.end_time"></strong>
                    <span class="badge ng-binding" style="border-color: #fff;background-color: #26c281;" ng-if="!timesheet.end_time"><?php echo lang('in_progress') ?></span>
                  </span>
                </div>
                <div class="col-md-2" ng-click="viewTimesheet(timesheet.id)">
                  <span class="date-start-task">
                    <small class="text-muted text-uppercase"><?php echo lang('timeCaptured') ?> <i class="ion-ios-stopwatch-outline"></i></small><br>
                    <strong ng-bind="timesheet.total_time"></strong>
                  </span>
                </div>
                <div class="col-md-2">
                  <md-icon ng-click="editTimeLog(timesheet.id)">
                    <i class="ion-compose"></i>
                    <md-tooltip md-direction="bottom"><?php echo lang('edit') ?></md-tooltip>
                  </md-icon>
                  <md-icon ng-click="deleteTimesheet(timesheet.id)" style="margin-left: 10px;">
                    <i class="ion-trash-b"></i>
                    <md-tooltip md-direction="bottom"><?php echo lang('delete') ?></md-tooltip>
                  </md-icon>
                </div>
              </div>
            </li>
          </ul>
        </li>
      </ul>
      <div class="ciuis-custom-list-item-item col-md-12">
        <div class="pull-right col-md-4">
          <span class="text-muted"><?php echo lang('time_captured') ?>: </span>
          <md-tooltip md-direction="bottom" ng-bind='lang.time_format'></md-tooltip>
          <strong ng-bind="total_time"></strong>
        </div>
      </div>
      <div ng-hide="timesheets.length < 8" class="pagination-div">
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
      <md-content ng-show="!timesheets.length" class="md-padding no-item-data"><?php echo lang('notdata') ?></md-content>
    </md-content>
  </div>
  <div class="main-content container-fluid col-xs-12 col-md-3 col-lg-3 md-pl-0 lead-left-bar">
    <ciuis-sidebar></ciuis-sidebar>
  </div>
</div>
<script type="text/ng-template" id="add-timer.html">
    <md-dialog>
      <md-toolbar class="toolbar-white">
        <div class="md-toolbar-tools">
          <h2><strong class="text-success"><?php echo lang('log').' '.lang('time') ?></strong></h2>
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
              <md-input-container class="md-block full-width">
                <label><?php echo lang('select').' '.lang('task'); ?></label>
                <md-select required placeholder="<?php echo lang('select').' '.lang('task'); ?>" ng-model="logtime.task" style="min-width: 200px;">
                  <md-option ng-value="task.id" ng-repeat="task in timerTasks">{{task.name}}</md-option>
                </md-select><br>
              </md-input-container>
              <br>
            </md-list-item>
            <md-list-item>
              <md-input-container class="md-block full-width">
                <label><?php echo lang('start_time'); ?></label>
                <input mdc-datetime-picker="" date="true" time="true" type="text" id="datetime" placeholder="<?php echo lang('chooseadate') ?>" show-todays-date="" minutes="true" show-icon="true" ng-model="logtime.start_time" class=" dtp-no-msclear dtp-input md-input">
              </md-input-container>
            </md-list-item>
            <md-list-item>
              <md-input-container  class="md-block full-width">
                <label><strong><?php echo lang('end_time') ?></strong></label>
                <input mdc-datetime-picker="" date="true" time="true" type="text" id="datetime" placeholder="<?php echo lang('chooseadate') ?>" show-todays-date="" minutes="true" min-date="logtime.start_time" show-icon="true" ng-model="logtime.end_time" class=" dtp-no-msclear dtp-input md-input">
              </md-input-container>
            </md-list-item>
            <md-list-item>
              <md-input-container  class="md-block full-width">
                <label><strong><?php echo lang('description') ?></strong></label>
                <textarea required  ng-model="logtime.description" class="form-control" id="title" placeholder="<?php echo lang('description'); ?>"></textarea>
              </md-input-container>
            </md-list-item>
            <br><br>
            <md-divider>
            </md-divider>
            <md-button ng-click="CreateLogTime()" class="template-button" ng-disabled="adding == true">
              <span ng-hide="adding == true"><?php echo lang('add');?></span>
              <md-progress-circular class="white" ng-show="adding == true" md-mode="indeterminate" md-diameter="20"></md-progress-circular>
            </md-button>
            <md-button ng-click="close()" class="">
              <span><?php echo lang('cancel');?></span>
            </md-button>
          </md-list>
        </md-content>     
      </md-dialog-content>
    </md-dialog>
  </script>

  <script type="text/ng-template" id="update_timer.html">
    <md-dialog>
      <md-toolbar class="toolbar-white">
        <div class="md-toolbar-tools">
          <h2><strong class="text-success"><?php echo lang('update').' '.lang('log').' '.lang('time') ?></strong></h2>
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
              <md-input-container class="md-block full-width">
                <label><?php echo lang('select').' '.lang('task'); ?></label>
                <md-select required placeholder="<?php echo lang('select').' '.lang('task'); ?>" ng-model="updatetimer.task_id" style="min-width: 200px;">
                  <md-option ng-value="task.id" ng-repeat="task in timerTasks">{{task.name}}</md-option>
                </md-select><br>
              </md-input-container>
              <br>
            </md-list-item>
            <md-list-item>
              <md-input-container class="md-block full-width">
                <label><?php echo lang('start_time'); ?></label>
                <input mdc-datetime-picker="" date="true" time="true" type="text" id="datetime" placeholder="<?php echo lang('chooseadate') ?>" show-todays-date="" minutes="true" show-icon="true" ng-model="updatetimer.start_time" class=" dtp-no-msclear dtp-input md-input">
              </md-input-container>
            </md-list-item>
            <md-list-item>
              <md-input-container  class="md-block full-width">
                <label><strong><?php echo lang('end_time') ?></strong></label>
                <input mdc-datetime-picker="" date="true" time="true" type="text" id="datetime" placeholder="<?php echo lang('chooseadate') ?>" show-todays-date="" minutes="true" min-date="updatetimer.start_time" show-icon="true" ng-model="updatetimer.end_time" class=" dtp-no-msclear dtp-input md-input">
              </md-input-container>
            </md-list-item>
            <md-list-item>
              <md-input-container  class="md-block full-width">
                <label><strong><?php echo lang('description') ?></strong></label>
                <textarea required  ng-model="updatetimer.note" class="form-control" id="title" placeholder="<?php echo lang('description'); ?>"></textarea>
              </md-input-container>
            </md-list-item>
            <br><br>
            <md-divider>
            </md-divider>
            <md-button ng-click="UpdateLogTime(updatetimer.id)" class="template-button" ng-disabled="saving == true">
              <span ng-hide="saving == true"><?php echo lang('save');?></span>
              <md-progress-circular class="white" ng-show="saving == true" md-mode="indeterminate" md-diameter="20"></md-progress-circular>
            </md-button>
            <md-button ng-click="close()" class="">
              <span><?php echo lang('cancel');?></span>
            </md-button>
          </md-list>
        </md-content>     
      </md-dialog-content>
    </md-dialog>
  </script>

  <script type="text/ng-template" id="view_timesheet.html">
    <md-dialog aria-label="Expense Detail">
      <md-toolbar class="toolbar-white">
        <div class="md-toolbar-tools">
          <h2><strong class="text-success"><?php echo lang('timesheet') ?></strong></h2>
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
              <h4 class="text-bold money-area">
                <small class="text-muted"><?php echo lang('logged_by'); ?></small>: 
                <small><strong ng-bind="loggedtime.staff | date : 'MMM d, y h:mm:ss a'"></strong></small>
              </h4>
            </md-list-item>
            <md-list-item>
              <h4 class="text-bold money-area">
                <small class="text-muted"><?php echo lang('task'); ?></small>: 
                <a class="ciuis_expense_receipt_number" href="<?php echo base_url('tasks/task/') ?>{{loggedtime.task_id}}">
                    <strong ng-bind="loggedtime.name"></strong>
                  </a>
                <a href="<?php echo base_url('tasks/task/') ?>{{loggedtime.task_id}}"><i class="ion-android-open"></i><md-tooltip md-direction="top"><?php echo lang('go_to').' '.lang('task') ?></md-tooltip></a>
              </h4>
            </md-list-item>
            <md-list-item>
              <h4 class="text-bold money-area">
                <small class="text-muted"><?php echo lang('start_time'); ?></small>: 
                <small><strong ng-bind="loggedtime.start_time | date : 'MMM d, y h:mm:ss a'"></strong></small>
              </h4>
            </md-list-item>
            <md-list-item>
              <h4 class="text-bold money-area">
                <small class="text-muted"><?php echo lang('end_time'); ?></small>: 
                <small><strong ng-bind="loggedtime.end_time | date : 'MMM d, y h:mm:ss a'"></strong></small>
                <span ng-show="!loggedtime.end_time" class="badge ng-binding" style="border-color: #fff;background-color: #26c281;" ng-if="!timesheet.end_time"><?php echo lang('in_progress') ?></span>
              </h4>
            </md-list-item>
            <md-list-item>
              <h4 class="text-bold money-area">
                <small class="text-muted"><?php echo lang('total').' ' .lang('time'); ?></small>: 
                <small><strong ng-bind="loggedtime.total_time | date : 'MMM d, y h:mm:ss a'"></strong></small>
              </h4>
            </md-list-item>
            <md-list-item>
              <h4 class="text-bold money-area">
                <small class="text-muted"><?php echo lang('description'); ?></small>: <br>
                <small><strong ng-bind="loggedtime.note"></strong></small>
              </h4>
            </md-list-item>
          </md-list>
        </md-content>     
      </md-dialog-content>
    </md-dialog>
  </script>

<script type="text/javascript">
  var langs = {
    delete_timelog: "<?php echo lang('delete_timelog') ?>",
    delete_timelog_message: "<?php echo lang('delete_timelog_message') ?>",
    delete: "<?php echo lang('delete') ?>",
    cancel: "<?php echo lang('cancel') ?>",
  };
</script>