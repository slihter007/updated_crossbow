<?php $appconfig = get_appconfig(); ?>
<md-content class="ciuis-body-content" ng-controller="Project_Controller">
  <div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
    <md-content class="bg-white">
      <md-toolbar class="toolbar-white">
        <div class="md-toolbar-tools">
          <md-progress-circular md-mode="determinate" value="{{project.progress}}" class="md-hue-2" md-diameter="20px"></md-progress-circular>
          <h2 class="md-pl-10" flex md-truncate>
            <strong><?php echo $appconfig['project_prefix'] ?>{{project.id}}<?php echo $appconfig['project_suffix'] ?></strong>
            {{ project.name}}
            <span ng-show="project.template == 1" class="badge"><strong><?php echo lang('template').' '.lang('project') ?></strong></span>
          </h2>
          <md-button ng-show="project.authorization === 'true' && project.template == 0" ng-click="ConvertDialog()" class="md-icon-button" aria-label="Convert">
            <md-tooltip md-direction="bottom"><?php echo lang('convertinvoice') ?></md-tooltip>
            <md-icon><i class="ion-loop text-success"></i></md-icon>
          </md-button>
          <md-menu ng-show="project.authorization === 'true'" md-position-mode="target-right target">
            <md-button aria-label="Open demo menu" class="md-icon-button" ng-mouseenter="$mdMenu.open($event)">
              <md-icon><i class="ion-android-add-circle text-muted"></i></md-icon>
            </md-button>
            <md-menu-content width="4" ng-mouseleave="$mdMenu.close()">
              <md-menu-item>
                <md-button ng-click="NewService()">
                  <div layout="row" flex>
                    <p flex ng-bind="lang.addservice"></p>
                    <md-icon md-menu-align-target class="ion-android-apps" style="margin: auto 3px auto 0;"></md-icon>
                  </div>
                </md-button>
              </md-menu-item>
              <md-menu-item>
                <md-button ng-click="NewMilestone()">
                  <div layout="row" flex>
                    <p flex ng-bind="lang.addmilestone"></p>
                    <md-icon md-menu-align-target class="ion-android-radio-button-on" style="margin: auto 3px auto 0;"></md-icon>
                  </div>
                </md-button>
              </md-menu-item>
              <md-menu-item>
                <md-button ng-click="NewTask()">
                  <div layout="row" flex>
                    <p flex ng-bind="lang.addtask"></p>
                    <md-icon md-menu-align-target class="icon ico-ciuis-tasks" style="margin: auto 3px auto 0;"></md-icon>
                  </div>
                </md-button>
              </md-menu-item>
              <md-menu-item>
                <md-button ng-click="NewExpense()">
                  <div layout="row" flex>
                    <p flex ng-bind="lang.newexpense"></p>
                    <md-icon md-menu-align-target class="icon ico-ciuis-expenses" style="margin: auto 3px auto 0;"></md-icon>
                  </div>
                </md-button>
              </md-menu-item>
              <md-menu-item>
                <md-button ng-click="NewTicket()">
                  <div layout="row" flex>
                    <p flex ng-bind="lang.newticket"></p>
                    <md-icon md-menu-align-target class="icon ico-ciuis-supports" style="margin: auto 3px auto 0;"></md-icon>
                  </div>
                </md-button>
              </md-menu-item>
            </md-menu-content>
          </md-menu>
          <md-menu ng-show="project.authorization === 'true'" md-position-mode="target-right target">
            <md-button aria-label="Open demo menu" class="md-icon-button" ng-click="$mdMenu.open($event)">
              <md-icon><i class="ion-android-more-vertical text-muted"></i></md-icon>
            </md-button>
            <md-menu-content width="4">
              <md-menu-item>
                <md-button ng-click="Update()">
                  <div layout="row" flex>
                    <p flex ng-bind="lang.updateproject"></p>
                    <md-icon md-menu-align-target class="ion-compose" style="margin: auto 3px auto 0;"></md-icon>
                  </div>
                </md-button>
              </md-menu-item>
              <md-menu-item ng-hide="project.status_id == '1'">
                <md-button ng-click="MarkAs(1,'<?php echo lang("notstarted") ?>')">
                  <div layout="row" flex>
                    <p flex ng-bind="lang.markasprojectnotstarted"></p>
                    <md-icon md-menu-align-target class="ion-ios-close-empty" style="margin: auto 3px auto 0;"></md-icon>
                  </div>
                </md-button>
              </md-menu-item>
              <md-menu-item ng-hide="project.status_id == '2'">
                <md-button ng-click="MarkAs(2,'<?php echo lang("started") ?>')">
                  <div layout="row" flex>
                    <p flex ng-bind="lang.markasprojectstarted"></p>
                    <md-icon md-menu-align-target class="ion-toggle-filled" style="margin: auto 3px auto 0;"></md-icon>
                  </div>
                </md-button>
              </md-menu-item>
              <md-menu-item ng-hide="project.status_id == '3'">
                <md-button ng-click="MarkAs(3,'<?php echo lang("percentage") ?>')">
                  <div layout="row" flex>
                    <p flex ng-bind="lang.markasprojectpercentage"></p>
                    <md-icon md-menu-align-target class="ion-toggle-filled" style="margin: auto 3px auto 0;"></md-icon>
                  </div>
                </md-button>
              </md-menu-item>
              <md-menu-item ng-hide="project.status_id == '4'">
                <md-button ng-click="MarkAs(4,'<?php echo lang("cancelled") ?>')">
                  <div layout="row" flex>
                    <p flex ng-bind="lang.markasprojectcancelled"></p>
                    <md-icon md-menu-align-target class="mdi mdi-close-circle-o" style="margin: auto 3px auto 0;"></md-icon>
                  </div>
                </md-button>
              </md-menu-item>
              <md-menu-item ng-hide="project.status_id == '4' || project.status_id == '5'">
                <md-button ng-click="MarkAs(5,'<?php echo lang("completed") ?>')">
                  <div layout="row" flex>
                    <p flex ng-bind="lang.markasprojectcomplete"></p>
                    <md-icon md-menu-align-target class="ion-checkmark-circled" style="margin: auto 3px auto 0;"></md-icon>
                  </div>
                </md-button>
              </md-menu-item>
              <md-menu-item>
                <md-button ng-click="Delete()">
                  <div layout="row" flex>
                    <p flex ng-bind="lang.delete"></p>
                    <md-icon md-menu-align-target class="ion-trash-b" style="margin: auto 3px auto 0;"></md-icon>
                  </div>
                </md-button>
              </md-menu-item>
            </md-menu-content>
          </md-menu>
        </div>
      </md-toolbar>
      <md-content class="bg-white">
        <div ng-show="projectLoader" layout-align="center center" class="text-center" id="circular_loader">
          <md-progress-circular md-mode="indeterminate" md-diameter="40"></md-progress-circular>
            <p style="font-size: 15px;margin-bottom: 5%;">
             <span>
                <?php echo lang('please_wait') ?> <br>
               <small><strong><?php echo lang('loading'). ' '. lang('project').'...' ?></strong></small>
             </span>
           </p>
         </div>
        <div ng-show="!projectLoader" id="project-details" class="on-schedule projects-top">
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
              <h5><?php echo lang('projectcost') ?></h5>
              <h3 ng-bind-html="project.value | currencyFormat:cur_code:null:true:cur_lct"></h3>
            </div>
          </div>
        </div>
      </md-content>
      <md-tabs ng-show="!projectLoader" md-dynamic-height md-border-bottom>
        <md-tab label="<?php echo lang('summary') ?>">
          <h4 layout-padding class="m-xs text-success text-bold" ng-show="project.template == 0">
            <md-button class="md-icon-button auto-cursor">
              <md-icon><i class="ico-ciuis-staffdetail text-success"></i>
              </md-icon> 
            </md-button>
            <span ng-bind="project.customer"></span>
          </h4>
          <md-divider ng-show="project.template == 0"></md-divider>
          <md-content class="bg-white">
            <div ng-show="!projectLoader" id="project-details" class="on-schedule projects-top">
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
                  <h4><span><span ng-bind="milestones.length"></span></span></h4>
                  <span class="stat-label"> <?php echo lang('total_s').' '.lang('milestones') ?> </span> 
                </div>
                <div flex-sm="33" flex-xs="50" flex-lg="16" flex-gt-sm="16" class="text-center">
                  <h4><span><span ng-bind="project.tasks.length"></span></span></h4>
                  <span class="stat-label"> <?php echo lang('total_s'). ' '.lang('tasks') ?> </span> 
                </div>
                <div flex-sm="33" flex-xs="50" flex-lg="16" flex-gt-sm="16" class="text-center">
                  <h4><span><span ng-bind="tickets.length"></span></span></h4>
                  <span class="stat-label"> <?php echo lang('total_s').' '.lang('tickets') ?> </span> 
                </div>
                <div flex-sm="33" flex-xs="50" flex-lg="16" flex-gt-sm="16" class="text-center">
                  <h4><span><span ng-bind="expenses.length"></span></span></h4>
                  <span class="stat-label"> <?php echo lang('total_s').' '.lang('expenses') ?> </span> 
                </div>
              </div>
            </div>
          </md-content>
          <md-content class="md-padding bg-white">
            <h3><?php echo lang('description') ?></h3>
            <p ng-bind="project.description"></p>
          </md-content>
          <md-content class="bg-white" ng-show="custom_fields.length > 0">
            <md-subheader ng-if="custom_fields"><?php echo lang('custom_fields') ?></md-subheader>
            <md-list-item ng-if="custom_fields" ng-repeat="field in custom_fields">
              <md-icon class="{{field.icon}} material-icons"></md-icon>
              <strong flex md-truncate>{{field.name}}</strong>
              <p ng-if="field.type === 'input'" class="text-right" flex md-truncate ng-bind="field.data"></p>
              <p ng-if="field.type === 'textarea'" class="text-right" flex md-truncate ng-bind="field.data"></p>
              <p ng-if="field.type === 'date'" class="text-right" flex md-truncate ng-bind="field.data | date:'dd, MMMM yyyy EEEE'"></p>
              <p ng-if="field.type === 'select'" class="text-right" flex md-truncate ng-bind="custom_fields[$index].selected_opt.name"></p>
              <md-divider ng-if="custom_fields"></md-divider>
            </md-list-item>
          </md-content>
        </md-tab>
        <md-tab label=" <?php echo lang('services') ?>">
          <md-content class="md-padding bg-white">
            <article class="expenses-project">
              <ul class="custom-ciuis-list-body" style="padding: 0px;">
                <li ng-repeat="service in projectservices" class="ciuis-custom-list-item ciuis-special-list-item lead-name">
                  <a class="ciuis_expense_receipt_number">
                    <ul class="list-item-for-custom-list">
                      <li class="ciuis-custom-list-item-item col-md-12">
                        <div data-toggle="tooltip" data-placement="bottom" data-container="body" title="" data-original-title="<?php echo lang('addedby'); ?>" class="assigned-staff-for-this-lead user-avatar"><i class="ion-document" style="font-size: 32px"></i> </div>
                        <div class="pull-left col-md-3"> 
                          <strong ng-bind="service.servicename"></strong>
                          <br>
                          <small ng-bind="service.servicedescription"></small>
                        </div>
                        <div class="col-md-9">
                          <div class="col-md-2"> <span class="date-start-task"><small class="text-muted text-uppercase"><?php echo lang('category'); ?></small><br>
                            <strong ng-bind="service.categoryname"></strong> 
                          </div>
                          <div class="col-md-2"> <span class="date-start-task"><small class="text-muted text-uppercase"><?php echo lang('quantity'); ?></small><br>
                            <strong ng-bind="service.quantity"></strong> 
                          </div>
                          <div class="col-md-2"> <span class="date-start-task"><small class="text-muted text-uppercase"><?php echo lang('unit'); ?></small><br>
                            <strong ng-bind="service.unit"></strong> 
                          </div>
                          <div class="col-md-2"> <span class="date-start-task"><small class="text-muted text-uppercase"><?php echo lang('amount'); ?></small><br>
                            <strong ng-bind-html="service.serviceprice | currencyFormat:cur_code:null:true:cur_lct"> </strong> </span> 
                          </div>
                          <div class="col-md-2"> <span class="date-start-task"><small class="text-muted text-uppercase"><?php echo $appconfig['tax_label']; ?></small><br>
                            <strong><span class="" ng-bind="service.servicetax"></span> </strong> </span> 
                          </div>
                          <div class="col-md-2">
                            <md-button ng-click="UpdateService($index)" class="md-icon-button" aria-label="Edit">
                              <md-tooltip md-direction="bottom"><?php echo lang('edit') ?></md-tooltip>
                              <md-icon><i class="ion-edit  text-muted"></i></md-icon>
                            </md-button>
                            <md-button ng-click="DeleteService($index)" class="md-icon-button" aria-label="Delete">
                              <md-tooltip md-direction="bottom"><?php echo lang('delete') ?></md-tooltip>
                              <md-icon><i class="ion-trash-b  text-muted"></i></md-icon>
                            </md-button>
                          </div>
                        </div>
                      </li>
                    </ul>
                  </a>
                </li>
              </ul>
            </article>
          </md-content>
          <md-content ng-show="!projectservices.length" class="md-padding bg-white no-item-data"><?php echo lang('notdata') ?></md-content>
        </md-tab>
        <md-tab label="<?php echo lang('milestones') ?>">
          <md-content class="md-padding bg-white">
            <article class="project_milestone_detail">
              <ul class="milestone_project">
                <li ng-repeat="milestone in milestones" class="milestone_project-milestone {{milestone.status}}">
                  <div class="milestone_project-action is-expandable expanded expensesSection">
                    <div class="pull-right">
                      <md-button aria-label="Remove Milestone" class="md-icon-button" ng-click="RemoveMilestone($index)">
                        <md-icon><i class="ion-trash-b text-muted"></i></md-icon>
                      </md-button>
                      <md-button aria-label="Show Milestone" class="md-icon-button" ng-click="ShowMilestone($index)">
                        <md-icon><i class="ion-ios-compose text-muted"></i></md-icon>
                      </md-button>
                    </div>
                    <h2 class="milestonetitle" ng-bind="milestone.name"></h2>
                    <span class="milestonedate exp" ng-bind="milestone.duedate"></span>
                    <div class="content">
                      <div ng-repeat="task in milestone.tasks" class="milestone-todos-list">
                        <ul class="all-milestone-todos">
                          <li ng-class="{'done' : task.status = 4}" class="milestone-todos-list-item col-md-12"> <span class="pull-left col-md-5"><strong ng-bind="task.name"></strong><br>
                            <small ng-bind="task.name"></small></span>
                            <div class="col-md-7">
                              <div class="col-md-3"><span class="date-start-task"><small class="text-muted"><?php echo lang('startdate') ?> <i class="ion-ios-stopwatch-outline"></i></small><br>
                                <strong ng-bind="task.startdate"></strong></span> </div>
                              <div class="col-md-3"><span class="date-start-task"><small class="text-muted"><?php echo lang('duedate') ?> <i class="ion-ios-timer-outline"></i></small><br>
                                <strong ng-bind="task.duedate"></strong></span> </div>
                              <div class="col-md-4"> <span class="date-start-task"> <small class="text-muted"><?php echo lang('status') ?> <i class="ion-ios-flag"></i></small><br>
                                <strong ng-if="task.status_id == '1' "><?php echo lang('open') ?></strong> <strong ng-if="task.status_id == '2' "><?php echo lang('inprogress') ?></strong> <strong ng-if="task.status_id == '3' "><?php echo lang('waiting') ?></strong> <strong ng-if="task.status_id == '4' "><?php echo lang('complete') ?></strong> </span> </div>
                              <div class="col-md-2">
                                <md-button aria-label="Go Task" class="md-icon-button" ng-href="<?php echo base_url('/tasks/task/')?>{{task.id}}">
                                  <md-icon><i class="ion-android-open text-muted"></i></md-icon>
                                </md-button>
                              </div>
                            </div>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </li>
              </ul>
            </article>
          </md-content>
        </md-tab>
        <md-tab label="<?php echo lang('tasks') ?>">
          <md-content class="md-padding bg-white">
            <div class="col-md-3 col-xs-6 border-right">
              <div class="tasks-status-stat">
                <h3 class="text-bold ciuis-task-stat-title"><span class="task-stat-number" ng-bind="(project.tasks | filter:{status_id:'1'}).length"></span> <span class="task-stat-all text-uppercase" ng-bind="'/'+' '+project.tasks.length+' '+'<?php echo lang('task') ?>'"></span></h3>
                <span class="ciuis-task-percent-bg"> <span class="ciuis-task-percent-fg" style="width: {{(project.tasks | filter:{status_id:'1'}).length * 100 / project.tasks.length }}%;"></span> </span> </div>
              <span class="text-uppercase" style="color:#989898"> <?php echo lang('open') ?> </span> 
            </div>
            <div class="col-md-3 col-xs-6 border-right">
              <div class="tasks-status-stat">
                <h3 class="text-bold ciuis-task-stat-title"><span class="task-stat-number" ng-bind="(project.tasks | filter:{status_id:'2'}).length"></span> <span class="task-stat-all text-uppercase" ng-bind="'/'+' '+project.tasks.length+' '+'<?php echo lang('task') ?>'"></span></h3>
                <span class="ciuis-task-percent-bg"> <span class="ciuis-task-percent-fg" style="width: {{(project.tasks | filter:{status_id:'2'}).length * 100 / project.tasks.length }}%;"></span> </span> </div>
              <span class="text-uppercase" style="color:#989898"> <?php echo lang('inprogress') ?> </span> 
            </div>
            <div class="col-md-3 col-xs-6 border-right">
              <div class="tasks-status-stat">
                <h3 class="text-bold ciuis-task-stat-title"><span class="task-stat-number" ng-bind="(project.tasks | filter:{status_id:'3'}).length"></span><span class="task-stat-all text-uppercase" ng-bind="'/'+' '+project.tasks.length+' '+'<?php echo lang('task') ?>'"></span></h3>
                <span class="ciuis-task-percent-bg"> <span class="ciuis-task-percent-fg" style="width: {{(project.tasks | filter:{status_id:'3'}).length * 100 / project.tasks.length }}%;"></span> </span> </div>
              <span class="text-uppercase" style="color:#989898"> <?php echo lang('waiting') ?> </span> 
            </div>
            <div class="col-md-3 col-xs-6 border-right">
              <div class="tasks-status-stat">
                <h3 class="text-bold ciuis-task-stat-title"><span class="task-stat-number" ng-bind="(project.tasks | filter:{status_id:'4'}).length"></span><span class="task-stat-all text-uppercase" ng-bind="'/'+' '+project.tasks.length+' '+'<?php echo lang('task') ?>'"></span></h3>
                <span class="ciuis-task-percent-bg"> <span class="ciuis-task-percent-fg" style="width: {{(project.tasks | filter:{status_id:'4'}).length * 100 / project.tasks.length }}%;"></span> </span> </div>
              <span class="text-uppercase" style="color:#989898"> <?php echo lang('complete') ?> </span> 
            </div>
            <hr ng-show="!project.tasks.length">
            <md-content ng-show="!project.tasks.length" class="md-padding bg-white no-item-data"><?php echo lang('notdata') ?></md-content>
            <div class="col-md-12">
              <div ng-repeat="projecttask in project.tasks" class="milestone-todos-list">
                <ul class="all-milestone-todos">
                  <li ng-class="{'done' : task.status = 4}" class="milestone-todos-list-item col-md-12"> <span class="pull-left col-md-5"><strong ng-bind="projecttask.name"></strong><br>
                    <small ng-bind="projecttask.description"></small></span>
                    <div class="col-md-7">
                      <div class="col-md-3"><span class="date-start-task"><small class="text-muted"><?php echo lang('startdate') ?> <i class="ion-ios-stopwatch-outline"></i></small><br>
                        <strong ng-bind="projecttask.startdate"></strong></span></div>
                      <div class="col-md-3"><span class="date-start-task"><small class="text-muted"><?php echo lang('duedate') ?> <i class="ion-ios-timer-outline"></i></small><br>
                        <strong ng-bind="projecttask.duedate"></strong></span></div>
                      <div class="col-md-4"><span class="date-start-task"> <small class="text-muted"><?php echo lang('status') ?> <i class="ion-ios-flag"></i></small><br>
                        <strong ng-bind="projecttask.status"></strong> </span> </div>
                      <div class="col-md-2">
                        <md-button aria-label="Go Task" class="md-icon-button" ng-href="<?php echo base_url('/tasks/task/')?>{{projecttask.id}}">
                          <md-icon><i class="ion-android-open text-muted"></i></md-icon>
                        </md-button>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </md-content>
        </md-tab>
        <md-tab label=" <?php echo lang('notes') ?>">
          <md-content class="md-padding bg-white">
            <section class="md-pb-30">
              <md-input-container class="md-block" ng-show="!editNote">
                <label><?php echo lang('description') ?></label>
                <textarea name="description" ng-model="note" placeholder="<?php echo lang('typeSomething'); ?>" class="form-control note-description"></textarea>
              </md-input-container>
              <md-input-container class="md-block" ng-show="editNote">
                <label><?php echo lang('description') ?></label>
                <textarea id="note_focus" name="description" ng-model="edit_note" placeholder="<?php echo lang('typeSomething'); ?>" class="form-control note-description"></textarea>
              </md-input-container>
              <input type="hidden" name="" ng-model="edit_note_id">
              <div class="form-group pull-right">
                <md-button ng-show="editNote" ng-click="SaveNote()" class="template-button pull-right" ng-disabled="saveNote == true">
                  <span ng-hide="saveNote == true"><?php echo lang('savenote');?></span>
                  <md-progress-circular class="white" ng-show="saveNote == true" md-mode="indeterminate" md-diameter="20"></md-progress-circular>
                </md-button>
                <md-button ng-show="!editNote" ng-click="AddNote()" class="template-button pull-right" ng-disabled="addNote == true">
                  <span ng-hide="addNote == true"><?php echo lang('addnote');?></span>
                  <md-progress-circular class="white" ng-show="addNote == true" md-mode="indeterminate" md-diameter="20"></md-progress-circular>
                </md-button>
              </div>
            </section>
            <section class="ciuis-notes show-notes">
              <article ng-repeat="note in notes" class="ciuis-note-detail">
                <div class="ciuis-note-detail-img"> <img src="<?php echo base_url('assets/img/note.png') ?>" alt="" width="50" height="50"/> </div>
                <div class="ciuis-note-detail-body"> 
                  <div class="text">
                    <p> 
                      <span ng-bind="note.description"></span> 
                      <a ng-click='DeleteNote($index)' class="ion-trash-a text-muted note-button pull-right" ng-disabled="modifyNote == true">
                        <md-tooltip md-direction="bottom"><?php echo lang('delete') ?></md-tooltip>
                      </a>
                      <a ng-click='EditNote($index)' class="ion-compose note-button text-muted pull-right" ng-disabled="modifyNote == true">
                        <md-tooltip md-direction="bottom"><?php echo lang('edit') ?></md-tooltip>
                      </a>
                    </p>
                  </div>
                  <p class="attribution"> <?php echo lang('addedby') ?> <strong><a href="<?php echo base_url('staff/staffmember/');?>/{{note.staffid}}" ng-bind="note.staff"></a></strong> <?php echo lang('at') ?> <span ng-bind="note.date"></span> </p>
                </div>
              </article>
            </section>
          </md-content>
        </md-tab>
        <md-tab label="<?php echo lang('timelogs') ?>">
          <md-content ng-show="!timelogs.length" class="md-padding bg-white no-item-data"><?php echo lang('notdata') ?></md-content>
          <md-content class="bg-white">
            <ul class="timelog-list">
              <li class="timelog-list-item" ng-repeat="timelog in timelogs" ng-class="{ 'timelog-list-item--active' : timelog.status == 0 }">
                <div class="timelog-list-item__clock">
                  <div class="timelog-list-item__bar"></div>
                  <i class="ion-android-time"></i> <span ng-show="timelog.status != '0'"><strong ng-bind="timelog.timed | time:'mm':'hhh mmm':false"></strong></span> <span ng-show="timelog.status != '1'"><strong><?php echo lang('n_a') ?></strong></span> </div>
                <div class="timelog-list-item__info">
                  <h3 class="timelog-list-item__description"><strong ng-bind="timelog.staff"></strong></h3>
                  <span class="timelog-list-item__details"><strong class="text-uppercase text-black"><?php echo lang('start') ?>: <span class="text-muted" ng-bind="timelog.start"></span></strong> | <strong class="text-uppercase text-black"><?php echo lang('end') ?>: <span class="text-muted" ng-bind="timelog.end"></strong></span></span> <span ng-show="timelog.status != '0'" class="timelog-list-item__chargeable-status"><strong ng-bind-html="timelog.amount | currencyFormat:cur_code:null:true:cur_lct"></strong></span> <span ng-show="timelog.status != '1'" class="timelog-list-item__chargeable-status"><strong ng-bind-html="0| currencyFormat:cur_code:null:true:cur_lct"></strong></span> </div>
              </li>
            </ul>
          </md-content>
        </md-tab>
        <md-tab label="<?php echo lang('expenses') ?>">
          <md-content class="md-padding bg-white">
            <article class="expenses-project">
              <ul class="custom-ciuis-list-body" style="padding: 0px;">
                <li ng-repeat="expense in expenses" i class="ciuis-custom-list-item ciuis-special-list-item lead-name">
                  <a class="ciuis_expense_receipt_number" ng-click="viewInvoice($index)">
                    <ul class="list-item-for-custom-list">
                      <li class="ciuis-custom-list-item-item col-md-12">
                        <div data-toggle="tooltip" data-placement="bottom" data-container="body" title="" data-original-title="<?php echo lang('addedby'); ?> {{expense.staff}}" class="assigned-staff-for-this-lead user-avatar"><i class="ion-document" style="font-size: 32px"></i> </div>
                        <div class="pull-left col-md-4"> 
                          <strong ng-bind="expense.prefix + '-' + expense.longid"></strong>
                          <br>
                          <small ng-bind="expense.title"></small> </div>
                        <div class="col-md-8">
                          <div class="col-md-5"> <span class="date-start-task"><small class="text-muted text-uppercase"><?php echo lang('amount'); ?></small><br>
                            <strong ng-bind-html="expense.amount | currencyFormat:cur_code:null:true:cur_lct"><span> <span ng-show="expense.billable != 'false'" class="label label-{{expense.color}}" ng-bind="expense.billstatus"></span> </span> </strong> </span> </div>
                          <div class="col-md-4"> <span class="date-start-task"><small class="text-muted text-uppercase"><?php echo lang('category'); ?></small><br>
                            <strong ng-bind="expense.category"></strong> </div>
                          <div class="col-md-3"> <span class="date-start-task"><small class="text-muted text-uppercase"><?php echo lang('date'); ?></small><br>
                            <strong><span class="badge" ng-bind="expense.date"></span> </strong> </span> </div>
                        </div>
                      </li>
                    </ul>
                  </a>
                </li>
              </ul>
            </article>
          </md-content>
          <md-content ng-show="!expenses.length" class="md-padding bg-white no-item-data"><?php echo lang('notdata') ?></md-content>
        </md-tab>
        <md-tab label="<?php echo lang('tickets') ?>">
          <md-content class="md-padding bg-white">
            <article class="expenses-project">
              <ul class="custom-ciuis-list-body" style="padding: 0px;">
                <li ng-repeat="ticket in tickets" i class="ciuis-custom-list-item ciuis-special-list-item lead-name">
                  <a class="ciuis_expense_receipt_number" ng-click="viewTicket($index)">
                    <ul class="list-item-for-custom-list">
                      <li class="ciuis-custom-list-item-item col-md-12">
                        <div data-toggle="tooltip" data-placement="bottom" data-container="body" title="" data-original-title="<?php echo lang('addedby'); ?> {{expense.staff}}" class="assigned-staff-for-this-lead user-avatar"><i class="ion-document" style="font-size: 32px"></i> </div>
                        <div class="pull-left col-md-4"> 
                          <strong ng-bind="ticket.subject"></strong>
                          <br>
                          <small ng-bind="ticket.message"></small> 
                        </div>
                        <div class="col-md-8">
                          <div class="col-md-5"> <span class="date-start-task"><small class="text-muted text-uppercase"><?php echo lang('contact'); ?></small><br>
                            <strong ng-bind="ticket.contactname">
                              <span> 
                              </span> </strong> </span> 
                          </div>
                          <div class="col-md-2"> <span class="date-start-task"><small class="text-muted text-uppercase"><?php echo lang('priority'); ?></small><br>
                            <span ng-switch="ticket.priority">
                              <strong ng-switch-when="1"><?php echo lang( 'low' ); ?></strong>
                              <strong ng-switch-when="2"><?php echo lang( 'medium' ); ?></strong>
                              <strong ng-switch-when="3"><?php echo lang( 'high' ); ?></strong>
                            </span>
                          </div>
                          <div class="col-md-2"> <span class="date-start-task"><small class="text-muted text-uppercase"><?php echo lang('status'); ?></small><br>
                            <span ng-switch="ticket.status_id">
                              <strong ng-switch-when="1"><?php echo lang( 'open' ); ?></strong>
                              <strong ng-switch-when="2"><?php echo lang( 'inprogress' ); ?></strong>
                              <strong ng-switch-when="3"><?php echo lang( 'answered' ); ?></strong>
                              <strong ng-switch-when="4"><?php echo lang( 'closed' ); ?></strong>
                            </span>
                          </div>
                          <div class="col-md-3"> <span class="date-start-task"><small class="text-muted text-uppercase"><?php echo lang('lastreply'); ?></small><br>
                            <strong><span ng-show="ticket.lastreply == NULL" class="badge"><?php echo lang('n_a') ?></span><span ng-show="ticket.lastreply != NULL" class="badge" ng-bind="ticket.lastreply | date : 'MMM d, y h:mm:ss a'"></span> </strong> </span> </div>
                        </div>
                      </li>
                    </ul>
                  </a>
                </li>
              </ul>
            </article>
          </md-content>
          <md-content ng-show="!expenses.length" class="md-padding bg-white no-item-data"><?php echo lang('notdata') ?></md-content>
        </md-tab>
        <md-tab label="<?php echo lang('projectactivities') ?>">
          <md-content class="md-padding bg-white">
            <ul class="user-timeline">
              <li ng-repeat="log_project in project.project_logs">
                <div class="user-timeline-title" ng-bind="log_project.date"></div>
                <div class="user-timeline-description" ng-bind-html="log_project.detail|trustAsHtml"></div>
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
        <md-button ng-click="InsertMember()" ng-show="project.authorization === 'true'" class="md-icon-button md-primary" aria-label="Add Member">
          <md-tooltip md-direction="bottom"><?php echo lang('add').' '.lang('staff') ?></md-tooltip>
          <md-icon class="ion-person-add"></md-icon>
        </md-button>
      </div>
    </md-toolbar>
    <div class="project-assignee">
      <div id="ciuis-customer-contact-detail">
        <div ng-if="project.authorization === 'false'" role="alert" class="alert alert-warning alert-icon alert-dismissible">
          <div class="icon"><span class="mdi mdi-block-alt"></span></div>
          <div class="message">
            <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true" class="mdi mdi-close"></span></button>
            <?php echo lang('notauthorized') ?> </div>
        </div>
        <div data-linkid="{{member.id}}" ng-repeat="member in project.members" class="ciuis-customer-contacts">
          <div data-toggle="modal" data-target="#contactmodal1"> <img width="40" height="40" src="{{UPIMGURL}}{{member.staffavatar}}" alt="">
            <div style="padding: 16px;position: initial;"> <strong ng-bind="member.staffname"></strong> <br>
              <span ng-bind="member.email"></span> </div>
            <div ng-show="project.authorization === 'true'" ng-click='UnlinkMember($index)' class="unlink"> <i class="ion-ios-close-outline"></i> </div>
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
        <md-button ng-click="UploadFile()" ng-show="project.authorization === 'true'" class="md-icon-button md-primary" aria-label="Add File">
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
            <a ng-if="(file.type == 'archive')" class="cursor" ng-href="<?php echo base_url('projects/download_file/{{file.id}}');?>">
              <md-tooltip md-direction="left"><?php echo lang('download') ?></md-tooltip>
              <img src="<?php echo base_url('assets/img/zip_icon.png');?>">
            </a>
            <a ng-if="(file.type == 'file')" class="cursor" ng-href="<?php echo base_url('projects/download_file/{{file.id}}');?>">
              <md-tooltip md-direction="left"><?php echo lang('download') ?></md-tooltip>
              <img src="<?php echo base_url('assets/img/file_icon.png');?>">
            </a>
            <a ng-if="file.type == 'pdf'" class="cursor" ng-href="<?php echo base_url('projects/download_file/{{file.id}}');?>">
              <md-tooltip md-direction="left"><?php echo lang('download') ?></md-tooltip>
              <img src="<?php echo base_url('assets/img/pdf_icon.png');?>">
            </a>
          </div>
          <div class="md-list-item-text">
            <a class="cursor" ng-href="<?php echo base_url('projects/download_file/{{file.id}}');?>">
              <h3 class="link" ng-bind="file.file_name"></h3>
            </a>
          </div>
          <md-button class="md-secondary md-primary md-fab md-mini md-icon-button" ng-click='DeleteFile(file.id)' aria-label="call">
            <md-icon class="ion-trash-b"></md-icon>
          </md-button>
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
  <md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="Update" style="width: 600px;">
    <md-toolbar class="md-theme-light" style="background:#262626">
      <div class="md-toolbar-tools">
        <md-button ng-click="close()" class="md-icon-button" aria-label="Close"> <i class="ion-android-arrow-forward"></i> </md-button>
        <md-truncate><?php echo lang('updateprojectinformations') ?></md-truncate>
      </div>
    </md-toolbar>
    <md-content>
      <md-content layout-padding>
        <md-input-container class="md-block">
          <label><?php echo lang('name') ?></label>
          <input required type="text" ng-model="project.name" class="form-control" id="title" placeholder="<?php echo lang('name'); ?>"/>
        </md-input-container>
        <md-input-container class="md-block" flex-gt-xs ng-show="project.template == 0">
          <label><?php echo lang('customer'); ?></label>
          <md-select required placeholder="<?php echo lang('choisecustomer'); ?>" ng-model="project.customer_id" name="customer" style="min-width: 200px;">
            <md-option ng-value="customer.id" ng-repeat="customer in all_customers">{{customer.name}}</md-option>
          </md-select>
        </md-input-container>
        <input type="hidden" ng-model="project.template" name="">
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
          <input type="text" required name="value" min="0" ng-model="project.value" placeholder="<?php echo lang('projectcost') ?>" class="form-control">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo $appconfig['tax_label'] ?></label>
          <input type="text" required name="tax" min="0" ng-model="project.tax" placeholder="<?php echo $appconfig['tax_label'] ?>" class="form-control">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('description') ?></label>
          <textarea required name="description" ng-model="project.description" placeholder="<?php echo lang('typeSomething'); ?>" class="form-control"></textarea>
        </md-input-container>
      </md-content>
      <custom-fields-vertical></custom-fields-vertical>
      <md-content>
        <section layout="row" layout-sm="column" layout-align="center center" layout-wrap>
          <md-button ng-click="UpdateProject()" class="md-raised md-primary pull-right" ng-disabled="saving == true">
            <span ng-hide="saving == true"><?php echo lang('update');?></span>
            <md-progress-circular class="white" ng-show="saving == true" md-mode="indeterminate" md-diameter="20"></md-progress-circular>
          </md-button>
        </section>
      </md-content>
    </md-content>
  </md-sidenav>
  <md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="NewMilestone">
    <md-toolbar class="md-theme-light" style="background:#262626">
      <div class="md-toolbar-tools">
        <md-button ng-click="close()" class="md-icon-button" aria-label="Close"> <i class="ion-android-arrow-forward"></i> </md-button>
        <md-truncate><?php echo lang('addmilestone') ?></md-truncate>
      </div>
    </md-toolbar>
    <md-content layout-padding="">
      <md-content layout-padding>
        <md-input-container class="md-block">
          <label><?php echo lang('name') ?></label>
          <input required type="text" ng-model="amilestone.name" class="form-control" id="title" placeholder="<?php echo lang('name'); ?>"/>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('duedate') ?></label>
          <input mdc-datetime-picker="" date="true" time="true" type="text" id="datetime" placeholder="<?php echo lang('chooseadate') ?>" show-todays-date="" minutes="true" show-icon="true" ng-model="amilestone.duedate" class=" dtp-no-msclear dtp-input md-input">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('description') ?></label>
          <textarea required name="description" ng-model="amilestone.description" placeholder="<?php echo lang('typeSomething'); ?>" class="form-control"></textarea>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('milestoneorder') ?></label>
          <input required type="number" ng-model="amilestone.order" class="form-control" id="title" placeholder="0"/>
        </md-input-container>
        <section layout="row" layout-sm="column" layout-align="center center" layout-wrap>
          <md-button ng-click="AddMilestone()" class="md-raised md-primary pull-right" ng-disabled="addingMilestone == true">
            <span ng-hide="addingMilestone == true"><?php echo lang('add');?></span>
            <md-progress-circular class="white" ng-show="addingMilestone == true" md-mode="indeterminate" md-diameter="20"></md-progress-circular>
          </md-button>
        </section>
      </md-content>
    </md-content>
  </md-sidenav>
  <md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="NewTask">
    <md-toolbar class="toolbar-white">
      <div class="md-toolbar-tools">
        <md-button ng-click="close()" class="md-icon-button" aria-label="Close"> <i class="ion-android-arrow-forward"></i> </md-button>
        <md-truncate><?php echo lang('addtask') ?></md-truncate>
      </div>
    </md-toolbar>
    <md-content layout-padding="">
      <md-content layout-padding>
        <md-input-container class="md-block">
          <label><?php echo lang('name') ?></label>
          <input required type="text" ng-model="newtask.name" class="form-control" id="title" placeholder="<?php echo lang('name'); ?>"/>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('hourlyrate') ?></label>
          <input type="text" ng-model="newtask.hourlyrate" class="form-control" id="title" placeholder="0.00"/>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('startdate') ?></label>
          <md-datepicker md-min-date="date" name="start" ng-model="newtask.startdate"></md-datepicker>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('duedate') ?></label>
          <md-datepicker md-min-date="date" name="start" ng-model="newtask.duedate"></md-datepicker>
        </md-input-container>
        <md-input-container class="md-block" flex-gt-xs>
          <label><?php echo lang('assigned'); ?></label>
          <md-select required ng-model="newtask.assigned" name="assigned" style="min-width: 200px;">
            <md-option ng-value="staff.id" ng-repeat="staff in staff">{{staff.name}}</md-option>
          </md-select>
        </md-input-container>
        <br>
        <md-input-container class="md-block" flex-gt-xs>
          <label><?php echo lang('priority'); ?></label>
          <md-select ng-init="priorities = [{id: 1,name: '<?php echo lang('low'); ?>'}, {id: 2,name: '<?php echo lang('medium'); ?>'}, {id: 3,name: '<?php echo lang('high'); ?>'}];" required placeholder="<?php echo lang('priority'); ?>" ng-model="newtask.priority" name="priority" style="min-width: 200px;">
            <md-option ng-value="priority.id" ng-repeat="priority in priorities"><span class="text-uppercase">{{priority.name}}</span></md-option>
          </md-select>
        </md-input-container>
        <br>
        <md-input-container class="md-block" flex-gt-xs>
          <label><?php echo lang('milestone'); ?></label>
          <md-select ng-model="newtask.milestone" name="assigned" style="min-width: 200px;">
            <md-option ng-value="milestone.id" ng-repeat="milestone in milestones">{{milestone.name}}</md-option>
          </md-select>
        </md-input-container>
        <br>
        <md-input-container class="md-block">
          <label><?php echo lang('description') ?></label>
          <textarea required name="description" ng-model="newtask.description" placeholder="<?php echo lang('typeSomething'); ?>" class="form-control"></textarea>
        </md-input-container>
        <md-switch ng-model="isPublic" aria-label="Type"><strong class="text-muted"><?php echo lang('public') ?></strong></md-switch>
        <md-switch ng-model="isBillable" aria-label="Type"><strong class="text-muted"><?php echo lang('billable') ?></strong></md-switch>
        <md-switch ng-model="isVisible" aria-label="Type"><strong class="text-muted"><?php echo lang('visiblecustomer') ?></strong></md-switch>
        <section layout="row" layout-sm="column" layout-align="center center" layout-wrap>
          <md-button ng-click="AddTask()" class="md-raised md-primary pull-right"><?php echo lang('add');?></md-button>
        </section>
      </md-content>
    </md-content>
  </md-sidenav>
  <md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="NewExpense">
    <md-toolbar class="toolbar-white">
      <div class="md-toolbar-tools">
        <md-button ng-click="close()" class="md-icon-button" aria-label="Close"> <i class="ion-android-arrow-forward"></i> </md-button>
        <md-truncate><?php echo lang('addexpense') ?></md-truncate>
      </div>
    </md-toolbar>
    <md-content layout-padding="">
      <md-content layout-padding>
        <md-input-container class="md-block">
          <label><?php echo lang('title') ?></label>
          <input required type="text" ng-model="newexpense.title" class="form-control" id="title" placeholder="<?php echo lang('title'); ?>"/>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('amount') ?></label>
          <input required type="number" ng-model="newexpense.amount" class="form-control" id="amount" placeholder="0.00"/>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('date') ?></label>
          <input mdc-datetime-picker="" date="true" time="true" type="text" id="datetime" placeholder="<?php echo lang('chooseadate') ?>" show-todays-date="" minutes="true" min-date="date" show-icon="true" ng-model="newexpense.date" class=" dtp-no-msclear dtp-input md-input">
        </md-input-container>
        <md-input-container class="md-block" flex-gt-xs>
          <label><?php echo lang('category'); ?></label>
          <md-select required ng-model="newexpense.category" name="category" style="min-width: 200px;">
            <md-option ng-value="category.id" ng-repeat="category in expensescategories">{{category.name}}</md-option>
          </md-select>
        </md-input-container>
        <br>
        <md-input-container class="md-block" flex-gt-xs>
          <label><?php echo lang('account'); ?></label>
          <md-select required ng-model="newexpense.account" name="account" style="min-width: 200px;">
            <md-option ng-value="account.id" ng-repeat="account in accounts">{{account.name}}</md-option>
          </md-select>
        </md-input-container>
        <br>
        <md-input-container class="md-block">
          <label><?php echo lang('description') ?></label>
          <textarea required name="description" ng-model="newexpense.description" placeholder="<?php echo lang('typeSomething'); ?>" class="form-control"></textarea>
        </md-input-container>
        <section layout="row" layout-sm="column" layout-align="center center" layout-wrap>
          <md-button ng-click="AddExpense()" class="md-raised md-primary pull-right" ng-disabled="adding == true">
            <span ng-hide="adding == true"><?php echo lang('add');?></span>
            <md-progress-circular class="white" ng-show="adding == true" md-mode="indeterminate" md-diameter="20"></md-progress-circular>
          </md-button>
        </section>
      </md-content>
    </md-content>
  </md-sidenav>

  <md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="NewTicket">
    <md-toolbar class="toolbar-white">
    <div class="md-toolbar-tools">
    <md-button ng-click="close()" class="md-icon-button" aria-label="Close">
       <i class="ion-android-arrow-forward"></i>
    </md-button>
    <md-truncate><?php echo lang('create') ?></md-truncate>
    </div>
    </md-toolbar>
    <md-content layout-padding>
    <?php //echo form_open_multipart('tickets/create'); ?>
      <md-input-container class="md-block">
        <label><?php echo lang('subject') ?></label>
        <input required type="text" ng-model="ticket.subject" name="subject" class="form-control">
      </md-input-container>
          <md-input-container ng-show="project.template == 1" class="md-block" flex-gt-xs>
            <label><?php echo lang('customer'); ?></label>
            <md-select placeholder="<?php echo lang('choisecustomer'); ?>" ng-model="ticket.customer" name="customer"  ng-init="project.customer_id">
              <md-option ng-value="customer" ng-repeat="customer in all_customers">{{customer.name}}</md-option>
            </md-select>
            <br>
          </md-input-container>
          <md-input-container ng-show="project.template == 0" class="md-block" flex-gt-xs>
            <label><?php echo lang('customer'); ?></label>
            <md-select disabled placeholder="<?php echo lang('choisecustomer'); ?>" ng-model="ticket.customer" name="customer" ng-init="project.customer_id">
              <md-option ng-value="customer" ng-repeat="customer in all_customers" ng-selected="customer.id == project.customer_id">{{customer.name}}</md-option>
            </md-select>
            <br>
          </md-input-container>
          <md-input-container class="md-block" flex-gt-xs>
            <label><?php echo lang('contact'); ?></label>
            <md-select required ng-model="ticket.contact" name="contact">
              <md-select-header>
                <md-toolbar class="toolbar-white">
                  <div class="md-toolbar-tools">
                    <h4 flex md-truncate><?php echo lang('contacts') ?></h4>
                    <md-button class="md-icon-button" ng-href="<?php echo base_url('customers/customer/{{ticket.customer.id}}')?>" target="_blank" aria-label="Create New">
                      <md-icon><i class="mdi mdi-plus text-muted"></i></md-icon>
                    </md-button>
                  </div>
                </md-toolbar>
              </md-select-header>
              <md-option ng-value="contact.id" ng-repeat="contact in ticket.customer.contacts">{{contact.name + ' ' + contact.surname}}</md-option>
            </md-select><br>
          </md-input-container>
          <md-input-container class="md-block" flex-gt-xs>
              <label><?php echo lang('department'); ?></label>
        <md-select required ng-model="ticket.department" name="department">
          <md-option ng-value="department.id" ng-repeat="department in departments">{{department.name}}</md-option>
        </md-select><br>
          </md-input-container>
          <md-input-container class="md-block" flex-gt-xs>
              <label><?php echo lang('priority'); ?></label>
        <md-select ng-init="priorities = [{id: 1,name: '<?php echo lang('low'); ?>'}, {id: 2,name: '<?php echo lang('medium'); ?>'}, {id: 3,name: '<?php echo lang('high'); ?>'}];" required placeholder="<?php echo lang('priority'); ?>" ng-model="ticket.priority" name="priority">
          <md-option ng-value="priority.id" ng-repeat="priority in priorities"><span class="text-uppercase">{{priority.name}}</span></md-option>
        </md-select><br>
          </md-input-container>
          <md-input-container class="md-block">
        <label><?php echo lang('message') ?></label>
        <textarea required name="message" ng-model="ticket.message" placeholder="<?php echo lang('typeSomething'); ?>" class="form-control"></textarea>
      </md-input-container>
      <section layout="row" layout-sm="column" layout-align="center center" layout-wrap>
          <md-button type="button" ng-click="createTicket()" class="md-raised md-primary pull-right"><?php echo lang('add');?></md-button>
      </section>
    <?php //echo form_close(); ?>
    </md-content>
  </md-sidenav>

  <md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="NewService">
    <md-toolbar class="toolbar-white">
      <div class="md-toolbar-tools">
        <md-button ng-click="close()" class="md-icon-button" aria-label="Close"> <i class="ion-android-arrow-forward"></i> </md-button>
        <md-truncate><?php echo lang('addservice') ?></md-truncate>
      </div>
    </md-toolbar>
    <md-content layout-padding="">
      <md-content layout-padding>
        <md-input-container class="md-block" flex-gt-xs>
          <label><?php echo lang('category'); ?></label>
          <md-select required ng-model="newservice.category" ng-change="getProducts(newservice.category)" name="category" style="min-width: 200px;">
            <md-select-header>
              <md-toolbar class="toolbar-white">
                <div class="md-toolbar-tools">
                  <h4 flex md-truncate><?php echo lang('categories') ?></h4>
                  <a href="<?php echo base_url('products') ?>">
                    <md-button class="md-icon-button" aria-label="Create New">
                      <md-icon><i class="mdi mdi-plus text-muted"></i></md-icon>
                    </md-button>
                  </a>
                </div>
              </md-toolbar>
            </md-select-header>
            <md-option ng-value="category.id" ng-repeat="category in productcategories">{{category.name}}</md-option>
          </md-select>
          <p class="text-danger" ng-show="productFound"><?php echo lang('productnotfound') ?></p>
        </md-input-container>
        <md-input-container class="md-block" flex-gt-xs>
          <label><?php echo lang('product'); ?></label>
          <md-select required ng-model="newservice.product" name="product" ng-change="getProductData(newservice.product)" style="min-width: 200px;">
            <md-toolbar class="toolbar-white">
                <div class="md-toolbar-tools">
                  <h4 flex md-truncate><?php echo lang('products') ?></h4>
                  <a href="<?php echo base_url('products') ?>">
                    <md-button class="md-icon-button" aria-label="Create New">
                      <md-icon><i class="mdi mdi-plus text-muted"></i></md-icon>
                    </md-button>
                  </a>
                </div>
              </md-toolbar>
            <md-option ng-value="product.id" ng-repeat="product in categoriesproduct">{{product.productname}}</md-option>
          </md-select>
        </md-input-container>
        <br>
        <md-input-container class="md-block">
          <label><?php echo lang('productname') ?></label>
          <input required type="text" ng-model="newservice.productname" class="form-control" id="title" placeholder="<?php echo lang('productname'); ?>"/>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('price') ?></label>
          <input required type="text" ng-model="newservice.price" class="form-control" id="price" placeholder="0.00"/>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo $appconfig['tax_label'] ?></label>
          <input required type="text" ng-model="newservice.tax" class="form-control" id="tax" placeholder="0.00"/>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('quantity') ?></label>
          <input type="number" required name="quantity" min="1" ng-model="newservice.quantity" ng-init="newservice.quantity=1" placeholder="<?php echo lang('quantity') ?>" class="form-control">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('unit') ?></label>
          <input type="text" required name="unit" ng-model="newservice.unit" ng-init="newservice.unit='Unit'" placeholder="<?php echo lang('unit') ?>" class="form-control">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('description') ?></label>
          <textarea required name="description" ng-model="newservice.description" placeholder="<?php echo lang('typeSomething'); ?>" class="form-control"></textarea>
        </md-input-container>
        <section layout="row" layout-sm="column" layout-align="center center" layout-wrap>
          <md-button ng-click="AddService()" class="md-raised md-primary pull-right"><?php echo lang('add');?></md-button>
        </section>
      </md-content>
    </md-content>
  </md-sidenav>

  <md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="UpdateService">
    <md-toolbar class="toolbar-white">
      <div class="md-toolbar-tools">
        <md-button ng-click="close()" class="md-icon-button" aria-label="Close"> <i class="ion-android-arrow-forward"></i> </md-button>
        <md-truncate><?php echo lang('updateservice') ?></md-truncate>
      </div>
    </md-toolbar>
    <md-content layout-padding="">
      <md-content layout-padding>
        <md-input-container class="md-block" flex-gt-xs>
          <label><?php echo lang('category'); ?></label>
          <md-select required ng-model="updateservice.category" ng-change="getProducts(updateservice.category)" name="category" style="min-width: 200px;">
            <md-select-header>
              <md-toolbar class="toolbar-white">
                <div class="md-toolbar-tools">
                  <h4 flex md-truncate><?php echo lang('categories') ?></h4>
                  <a href="<?php echo base_url('products') ?>">
                    <md-button class="md-icon-button" aria-label="Create New">
                      <md-icon><i class="mdi mdi-plus text-muted"></i></md-icon>
                    </md-button>
                  </a>
                </div>
              </md-toolbar>
            </md-select-header>
            <md-option ng-value="category.id" ng-repeat="category in productcategories">{{category.name}}</md-option>
          </md-select>
          <p class="text-danger" ng-show="productFound"><?php echo lang('productnotfound') ?></p>
        </md-input-container>
        <md-input-container class="md-block" flex-gt-xs>
          <label><?php echo lang('product'); ?></label>
          <md-select required ng-model="updateservice.product" name="product" ng-change="getProductData(updateservice.product)" style="min-width: 200px;">
            <md-toolbar class="toolbar-white">
                <div class="md-toolbar-tools">
                  <h4 flex md-truncate><?php echo lang('products') ?></h4>
                  <a href="<?php echo base_url('products') ?>">
                    <md-button class="md-icon-button" aria-label="Create New">
                      <md-icon><i class="mdi mdi-plus text-muted"></i></md-icon>
                    </md-button>
                  </a>
                </div>
              </md-toolbar>
            <md-option ng-value="product.id" ng-repeat="product in categoriesproduct">{{product.productname}}</md-option>
          </md-select>
        </md-input-container>
        <br>
        <md-input-container class="md-block">
          <label><?php echo lang('productname') ?></label>
          <input required type="text" ng-model="updateservice.productname" class="form-control" id="title" placeholder="<?php echo lang('productname'); ?>"/>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('price') ?></label>
          <input required type="text" ng-model="updateservice.price" class="form-control" id="price" placeholder="0.00"/>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo $appconfig['tax_label'] ?></label>
          <input required type="text" ng-model="updateservice.tax" class="form-control" id="tax" placeholder="0.00"/>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('quantity') ?></label>
          <input type="text" required name="quantity" min="1" ng-model="updateservice.quantity" ng-init="updateservice.quantity=1" placeholder="<?php echo lang('quantity') ?>" class="form-control">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('unit') ?></label>
          <input type="text" required name="unit" ng-model="updateservice.unit" ng-init="updateservice.unit='Unit'" placeholder="<?php echo lang('unit') ?>" class="form-control">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('description') ?></label>
          <textarea required name="description" ng-model="updateservice.description" placeholder="<?php echo lang('typeSomething'); ?>" class="form-control"></textarea>
        </md-input-container>
        <section layout="row" layout-sm="column" layout-align="center center" layout-wrap>
          <md-button ng-click="SaveService()" class="md-raised md-primary pull-right"><?php echo lang('update');?></md-button>
        </section>
      </md-content>
    </md-content>
  </md-sidenav>

  <script type="text/ng-template" id="convertDialog.html">
    <md-dialog aria-label="Expense Detail">
      <md-toolbar class="toolbar-white">
        <div class="md-toolbar-tools">
          <h2><strong class=""><?php echo lang('convertinvoice') ?></strong></h2>
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
                <div class="col-md-6" style="padding-bottom: 3%;margin-bottom: 5%;border: 1px solid #efefef;border-radius: 3px;text-align: center;margin-left: -3px;margin-right: 3px;">
                  <p class="text-success"><?php echo lang('convertinvoicewithservicevalue'); ?></p>
                  <md-button ng-click="Convert()" ng-disabled="invoiceButton" class="md-raised md-primary"><?php echo lang('services'). ' ' .lang('invoice');?></md-button><br>
                </div>
                <div class="col-md-6" style="padding-bottom: 3%;margin-bottom: 5%;border: 1px solid #efefef;border-radius: 3px;text-align: center;margin-left: 3px;margin-right: -3px;">
                  <p class="text-success"><?php echo lang('convertinvoicewithprojectvalue'); ?></p>
                  <md-button ng-click="ConvertWithProjectValue()" ng-disabled="invoiceButton" class="md-raised md-primary"><?php echo  lang('project'). ' ' .lang('invoice');?></md-button><br>
                </div>
              </div>
            </md-list-item>
          </md-list>
        </md-content>     
      </md-dialog-content>
    </md-dialog>
  </script>

  <script type="text/ng-template" id="ticketDialog.html">
    <md-dialog aria-label="Ticket Detail">
      <md-toolbar class="toolbar-white">
        <div class="md-toolbar-tools">
          <h2><strong class="text-success"><?php echo lang('ticket') ?></strong></h2>
          <span flex></span>
          <md-button class="md-icon-button" ng-click="close()">
            <md-icon class="ion-close-round" aria-label="Close dialog" style="color:black"></md-icon>
            <md-tooltip md-direction="left"><?php echo lang('close') ?></md-tooltip>
          </md-button>
        </div>
      </md-toolbar>
      <md-dialog-content style="max-width:800px;max-height:810px; ">
        <md-content class="bg-white" layout-padding>
          <div class="ciuis-ticket-row">
            <h4 style="width:100%"><strong ng-bind="ticket.subject"></strong> 
              <md-menu md-position-mode="target-right target" class=" pull-right">
                <a class="cursor"  ng-click="$mdMenu.open($event)" style="font-size: 25px;padding: 25px;"><i class="ion-android-more-vertical"></i>
                  <md-tooltip md-direction="top"><?php echo lang('actions') ?></md-tooltip>
                </a>
                  <md-menu-content width="4">
                    <md-menu-item>
                      <md-button ng-click="TicketMarkAs(1,lang.open, ticket.id)" ng-bind="lang.markasopen" aria-label="Open"></md-button>
                    </md-menu-item>
                    <md-menu-item>
                      <md-button ng-click="TicketMarkAs(2,lang.inprogress, ticket.id)" ng-bind="lang.markasinprogress" aria-label="In Progress"></md-button>
                    </md-menu-item>
                    <md-menu-item>
                      <md-button ng-click="TicketMarkAs(3,lang.answered, ticket.id)" ng-bind="lang.markasanswered" aria-label="Answered"></md-button>
                    </md-menu-item>
                    <md-menu-item>
                      <md-button ng-click="TicketMarkAs(4,lang.closed, ticket.id)" ng-bind="lang.markasclosed" aria-label="Closed"></md-button>
                    </md-menu-item>
                    <md-menu-item>
                      <md-button ng-click="DeleteTicket(ticket.id)" ng-bind="lang.delete" aria-label="Closed"></md-button>
                    </md-menu-item>
                    </md-menu-content>
                </md-menu>
              <a href="<?php echo base_url('tickets/ticket/')?>{{ticket.id}}" class="pull-right" style="font-size: 25px;"><i class="ion-android-open"></i>
                <md-tooltip md-direction="left"><?php echo lang('go_to_ticket') ?></md-tooltip>
              </a> 
            </h4>
          </div>
          <div class="ciuis-ticket-row">
            <div class="ciuis-ticket-fieldgroup">
              <div class="ticket-label">
                <?php echo lang('assignedstaff')?>
              </div>
              <div class="ticket-data" ng-bind="ticket.staffname"></div>
            </div>
            <div class="ciuis-ticket-fieldgroup">
              <div class="ticket-label">
                <?php echo lang('customer')?>
              </div>
              <div class="ticket-data">
                <a href="<?php echo base_url('customers/customer/{{ticket.customer_id}}')?>" ng-bind="ticket.contactsurname"></a>
              </div>
            </div>
          </div>
          <div class="ciuis-ticket-row">
            <div class="ciuis-ticket-fieldgroup">
              <div class="ticket-label">
                <?php echo lang('contactname')?>
              </div>
              <div class="ticket-data" ng-bind="ticket.contactname">
              </div>
            </div>
            <div class="ciuis-ticket-fieldgroup">
              <div class="ticket-label">
                <?php echo lang('department')?>
              </div>
              <div class="ticket-data" ng-bind="ticket.department"></div>
            </div>
          </div>
          <div class="ciuis-ticket-row">
            <div class="ciuis-ticket-fieldgroup">
              <div class="ticket-label">
                <?php echo lang('status')?>
              </div>
              <div class="ticket-data label-status">
                <span ng-switch="ticket.status_id">
                  <span class="badge" ng-switch-when="1"><?php echo lang( 'open' ); ?></span>
                  <span class="badge" ng-switch-when="2"><?php echo lang( 'inprogress' ); ?></span>
                  <span class="badge" ng-switch-when="3"><?php echo lang( 'answered' ); ?></span>
                  <span class="badge" ng-switch-when="4"><?php echo lang( 'closed' ); ?></span>
                </span>
              </div>
            </div>
            <div class="ciuis-ticket-fieldgroup">
              <div class="ticket-label">
                <?php echo lang('priority')?>
              </div>
              <div class="ticket-data">
                <span ng-switch="ticket.priority">
                  <span ng-switch-when="1"><?php echo lang( 'low' ); ?></span>
                  <span ng-switch-when="2"><?php echo lang( 'medium' ); ?></span>
                  <span ng-switch-when="3"><?php echo lang( 'high' ); ?></span>
                </span>
              </div>
            </div>
          </div>
          <div class="ciuis-ticket-row">
            <div class="ciuis-ticket-fieldgroup">
              <div class="ticket-label">
                <?php echo lang('datetimeopened')?>
              </div>
              <div class="ticket-data">
                <span class="badge" ng-bind="ticket.date | date : 'MMM d, y h:mm:ss a'"></span>
              </div>
            </div>
            <div class="ciuis-ticket-fieldgroup">
              <div class="ticket-label">
                <?php echo lang('datetimelastreplies')?>
              </div>
              <div class="ticket-data">
                <span ng-show="ticket.lastreply == NULL" class="badge"><?php echo lang('n_a') ?></span><span ng-show="ticket.lastreply != NULL" class="badge" ng-bind="ticket.lastreply | date : 'MMM d, y h:mm:ss a'"></span>
              </div>
            </div>
          </div>
          <div class="ciuis-ticket-row">
            <div class="ciuis-ticket-fieldgroup full">
              <div class="ticket-label">
                <strong><?php echo lang('message') ?></strong>
              </div>
              <div style="padding: 10px; border-radius: 3px; margin-bottom: 10px; font-weight: 600; background: #f3f3f3;" class="ticket-data">
                <span ng-bind="ticket.message"></span>
              </div>
            </div>
          </div>
        </md-content>     
      </md-dialog-content>
    </md-dialog>
  </script>

  <script type="text/ng-template" id="expenseDialog.html">
    <md-dialog aria-label="Expense Detail">
      <md-toolbar class="toolbar-white">
        <div class="md-toolbar-tools">
          <h2><strong class="text-success"><?php echo lang('expense') ?></strong></h2>
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
              <h3>
                <a class="ciuis_expense_receipt_number" href="<?php echo base_url('expenses/receipt/') ?>{{expense.id}}">
                  <strong ng-bind="expense.prefix + '-' + expense.longid"></strong>
                </a>
                <a href="<?php echo base_url('expenses/receipt/') ?>{{expense.id}}"><i class="ion-android-open"></i><md-tooltip md-direction="top"><?php echo lang('go_to').' '.lang('expense') ?></md-tooltip></a>
              </h3>
            </md-list-item>
            <md-list-item>
              <p>
                <small ng-bind="expense.title">
                </small> 
                <span ng-show="expense.billable != 'false'" class="label label-{{expense.color}}" ng-bind="expense.billstatus"></span>
                <span flex></span>
              </p>
            </md-list-item>
            <md-list-item>
              <h4 class="text-bold money-area">
                <small class="text-muted text-uppercase"><?php echo lang('amount'); ?></small>: 
                <strong ng-bind-html="expense.amount | currencyFormat:cur_code:null:true:cur_lct"></strong>
              </h4>
            </md-list-item>
            <md-list-item>
              <div> <span class="date-start-task"><small class="text-muted text-uppercase"><?php echo lang('category'); ?></small>: 
                <strong ng-bind="expense.category"></strong> 
              </div>
            </md-list-item>
            <md-divider>
            </md-divider>
            <md-content layout-padding>
              <h3 class="md-mt-0">
                <small class="text-muted text-uppercase"><?php echo lang('date'); ?></small>: 
                <strong ng-bind="expense.date | date : 'MMM d, y h:mm:ss a'"></strong>
              </h3>
            </md-content>
          </md-list>
        </md-content>     
      </md-dialog-content>
    </md-dialog>
  </script>

  <script type="text/ng-template" id="insert-member-template.html">
  <md-dialog aria-label="options dialog">
	<md-dialog-content layout-padding>
	  <h2 class="md-title"><?php echo lang('assigned'); ?></h2>
		<md-select required ng-model="insertedStaff" style="min-width: 200px;" aria-label="AddMember">
			<md-option ng-value="staff.id" ng-repeat="staff in staff">{{staff.name}}</md-option>
		</md-select>
	</md-dialog-content>
	<md-dialog-actions>
	  <span flex></span>
	  <md-button ng-click="close()"><?php echo lang('cancel') ?>!</md-button>
	  <md-button ng-click="AddProjectMember()"><?php echo lang('add') ?>!</md-button>
	</md-dialog-actions>
  </md-dialog>
</script> 
  <script type="text/ng-template" id="addfile-template.html">
  <md-dialog aria-label="options dialog">
  <?php echo form_open_multipart('projects/add_file/'.$projects['id'].'',array("class"=>"form-horizontal")); ?>
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
    <md-button ng-click='DeleteFile(file.id)'><?php echo lang('delete') ?>!</md-button>
    <md-button ng-href="<?php echo base_url('projects/download_file/') ?>{{file.id}}"><?php echo lang('download') ?>!</md-button>
    <md-button ng-click="close()"><?php echo lang('cancel') ?>!</md-button>
  </md-dialog-actions>
  <?php echo form_close(); ?>
  </md-dialog>
</script>
<script type="text/ng-template" id="delete_project.html">
  <md-dialog aria-label="options dialog">
    <md-toolbar class="toolbar-white">
        <div class="md-toolbar-tools">
          <h2><strong class="text-danger"><?php echo lang('delete_project_note') ?></strong></h2>
          <span flex></span>
          <md-button class="md-icon-button" ng-click="close()">
            <md-icon class="ion-close-round" aria-label="Close dialog" style="color:black"></md-icon>
          </md-button>
        </div>
      </md-toolbar>
  <md-dialog-content layout-padding>
    <p class="text-danger" style="margin:unset;">
      <strong><?php echo lang('delete_project_warning') ?> </strong>
      <li><?php echo lang('all').' '.lang('tickets').' '.lang('of_this').' '.lang('project') ?></li>
      <li><?php echo lang('all').' '.lang('services').' '.lang('of_this').' '.lang('project') ?></li>
      <li><?php echo lang('all').' '.lang('milestones').' '.lang('of_this').' '.lang('project') ?></li>
      <li><?php echo lang('all').' '.lang('tasks').' '.lang('of_this').' '.lang('project') ?></li>
      <li><?php echo lang('all').' '.lang('expenses').' '.lang('of_this').' '.lang('project') ?></li>
      <li><?php echo lang('all').' '.lang('files').' '.lang('of_this').' '.lang('project') ?></li>
    </p>
  </md-dialog-content>
  <md-dialog-actions>
    <span flex></span>
    <md-button ng-click="DeleteProject()" class="delete-button" ng-disabled="deletingProject == true">
      <span ng-hide="deletingProject == true"><?php echo lang('delete');?></span>
      <md-progress-circular ng-show="deletingProject == true" md-mode="indeterminate" md-diameter="20"></md-progress-circular>
    </md-button>
    <md-button ng-click="close()"><?php echo lang('cancel') ?>!</md-button>
  </md-dialog-actions>
  <?php echo form_close(); ?>
  </md-dialog>
</script>
  <div style="visibility: hidden">
    <div ng-repeat="milestone in milestones" class="md-dialog-container" id="ShowMilestone-{{milestone.id}}">
      <md-dialog aria-label="Milestone Detail">
        <form>
          <md-toolbar class="toolbar-white">
            <div class="md-toolbar-tools">
              <h2><?php echo lang('update') ?> {{milestone.name}}</h2>
              <span flex></span>
              <md-button class="md-icon-button" ng-click="close()">
                <md-icon class="ion-close-round" aria-label="Close dialog" style="color:black"></md-icon>
              </md-button>
            </div>
          </md-toolbar>
          <md-dialog-content style="max-width:800px;max-height:810px; ">
            <md-content class="bg-white" layout-padding>
              <md-input-container class="md-block">
                <label><?php echo lang('name') ?></label>
                <input required type="text" ng-model="milestone.name" class="form-control" id="title" placeholder="<?php echo lang('name'); ?>"/>
              </md-input-container>
              <md-input-container class="md-block">
                <label><?php echo lang('duedate') ?></label>
                <input mdc-datetime-picker="" date="true" time="true" type="text" id="datetime" placeholder="<?php echo lang('chooseadate') ?>" show-todays-date="" minutes="true" show-icon="true" ng-model="milestone.duedate" class=" dtp-no-msclear dtp-input md-input">
              </md-input-container>
              <md-input-container class="md-block">
                <label><?php echo lang('description') ?></label>
                <textarea required ng-model="milestone.description" placeholder="<?php echo lang('typeSomething'); ?>" class="form-control note-description"></textarea>
              </md-input-container>
              <md-input-container class="md-block">
                <label><?php echo lang('milestone_order') ?></label>
                <input required type="text" ng-model="milestone.order" class="form-control" id="title" placeholder="<?php echo lang('order'); ?>"/>
              </md-input-container>
            </md-content>
          </md-dialog-content>
          <md-dialog-actions layout="row">
            <md-button ng-click="UpdateMilestone($index)" class="md-raised md-primary pull-right" ng-disabled="savingMilestone == true">
              <span ng-hide="savingMilestone == true"><?php echo lang('update');?></span>
              <md-progress-circular class="white" ng-show="savingMilestone == true" md-mode="indeterminate" md-diameter="20"></md-progress-circular>
            </md-button>
          </md-dialog-actions>
        </form>
      </md-dialog>
    </div>
  </div>
</md-content>
<script> 
  var PROJECTID = "<?php echo $projects['id'];?>"; 
  var langs = {};
  langs.marked = '<?php echo lang("marked") ?>';
  langs.remove_staff = '<?php echo lang("remove_staff") ?>';
  langs.doIt = '<?php echo lang("doIt") ?>';
  langs.attention = '<?php echo lang("attention") ?>';
  langs.cancel = '<?php echo lang("cancel") ?>';
  langs.delete_milestone = '<?php echo lang("delete_milestone") ?>';
</script>
<?php include_once( APPPATH . 'views/inc/footer.php' ); ?>