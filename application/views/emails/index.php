<div class="ciuis-body-content bg-white" ng-controller="Emails_Controller">
  <div class="main-content container-fluid col-xs-12 col-md-4 col-lg-4 bg-white">
    <div class="panel-heading" style="border-bottom: 1px solid gainsboro;"> <strong><?php echo lang('emails'); ?></strong> 
      <span class="panel-subtitle"><?php echo lang('email_activities'); ?></span> 
    </div>
    <div ng-show="template.loadEmails" layout-align="center center" class="text-center" id="circular_loader">
      <md-progress-circular md-mode="indeterminate" md-diameter="40"></md-progress-circular>
      <p style="font-size: 15px;margin-bottom: 5%;">
        <span>
          <?php echo lang('please_wait') ?> <br>
          <small><strong><?php echo lang('loading'). ' '. lang('emails').'...' ?></strong></small>
        </span>
      </p>
    </div>
    <div ng-show="!template.loadEmails" class="row" style="padding: 0px 20px 0px 20px;">
     <ul class="custom-ciuis-list-body" style="padding: 0px;"> 
      <li ng-repeat="email in emails | filter: FilteredData | filter:search | pagination : currentPage*itemsPerPage | limitTo: 10" class="emails-list milestone-todos-list ciuis-custom-list-item ciuis-special-list-item paginationclass">
        <a ng-click="viewEmail($index)">
          <ul class="all-milestone-todos">
            <li>
              <div class="row">
                <div class="col-md-12">
                  <div class="col-md-9">
                    <h5><strong ng-bind="email.to"></strong> 
                      <small style="opacity: 0.6"> (<span>{{ email.email | limitTo: 45 }}{{email.email.length > 45 ? '...' : ''}}</span>) 
                        <md-icon ng-if="email.attachment" class="mdi-attachment"></md-icon>
                      </small>
                    </h5>
                  </div>
                  <div class="col-md-3 pull-right timing" >
                    <span ng-bind="email.time"></span>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="col-md-11 subject">
                    <span ng-bind="email.subject"></span>
                  </div>
                </div>
              </div>
              </li>
            </ul>
          </a>
        </li>
      </ul>
      <div class="pagination-div"  ng-hide="!emails.length">
        <ul class="pagination">
          <li ng-class="DisablePrevPage()"> <a href ng-click="prevPage()"><i class="ion-ios-arrow-back"></i></a> </li>
          <li ng-repeat="n in range()" ng-class="{active: n == currentPage}" ng-click="setPage(n)"> <a href="#" ng-bind="n+1"></a> </li>
          <li ng-class="DisableNextPage()"> <a href ng-click="nextPage()"><i class="ion-ios-arrow-right"></i></a> </li>
        </ul>
      </div>
      <md-content ng-show="!emails.length" class="md-padding bg-white no-item-data"><?php echo lang('noemails') ?></md-content>
    </div>
  </div>
  <div class="main-content container-fluid col-xs-12 col-md-8 col-lg-8 md-p-0 bg-white" style="border-left: 1px solid gainsboro;"> 
    <md-toolbar class="toolbar-trans" style="border-bottom: 1px solid gainsboro;">
      <div class="md-toolbar-tools">
        <h2 flex md-truncate class="text-bold"><?php echo lang('email_templates'); ?><br>
          <small flex md-truncate><?php echo lang('manage_email_templates'); ?></small>
        </h2>
      </div>
    </md-toolbar>
    <md-content>
      <div ng-show="template.loader" layout-align="center center" class="text-center" id="circular_loader">
        <md-progress-circular md-mode="indeterminate" md-diameter="40"></md-progress-circular>
        <p style="font-size: 15px;margin-bottom: 5%;">
          <span>
            <?php echo lang('please_wait') ?> <br>
            <small><strong><?php echo lang('loading'). ' '. lang('templates').'...' ?></strong></small>
          </span>
        </p>
      </div>
      <md-tabs ng-show="!template.loader" md-dynamic-height md-border-bottom class="bg-white">
        <md-tab label="<?php echo lang('invoices'); ?>" class="bg-white">
          <md-content class="md-padding bg-white">
            <ul class="custom-ciuis-list-body" style="padding: 0px;"> 
              <li ng-repeat="template in templates | filter: { relation: 'invoice' }  " class="milestone-todos-list ciuis-custom-list-item ciuis-special-list-item paginationclass" style="min-width: 100% !important;">
                <a href="<?php echo base_url('emails/template/')?>{{template.id}}">
                  <ul class="all-milestone-todos">
                    <li ng-show="template.status == '1'" class="milestone-todos-list-item col-md-12 done" style="color: unset;"> 
                      <span class="pull-left col-md-6">
                        <strong ng-bind="template.name"></strong><br>
                      </span>
                      <div class="col-md-6">
                        <div class="col-md-8">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('subject'); ?></small><br>
                            <strong ng-bind="template.subject"></strong>
                          </span>
                        </div>
                        <div class="col-md-4">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('status'); ?></small><br>
                            <strong class="badge green"><?php echo lang('active'); ?></strong>
                          </span>
                        </div>
                      </div>
                    </li>
                    <li ng-show="template.status != '1'" class="milestone-todos-list-item col-md-12"> 
                      <span class="pull-left col-md-6">
                        <strong ng-bind="template.name"></strong><br>
                      </span>
                      <div class="col-md-6">
                        <div class="col-md-8">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('subject'); ?></small><br>
                            <strong ng-bind="template.subject"></strong>
                          </span>
                        </div>
                        <div class="col-md-4">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('status'); ?></small><br>
                            <strong class="badge red"><?php echo lang('inactive'); ?></strong>
                          </span>
                        </div>
                      </div>
                    </li>
                  </ul>
                </a>
              </li>
            </ul>
          </md-content>
        </md-tab>
        <md-tab label="<?php echo lang('customers'); ?>">
          <md-content class="md-padding bg-white">
             <ul class="custom-ciuis-list-body" style="padding: 0px;"> 
              <li ng-repeat="template in templates | filter: { relation: 'customer' }  " class="milestone-todos-list ciuis-custom-list-item ciuis-special-list-item paginationclass" style="min-width: 100% !important;">
                <a href="<?php echo base_url('emails/template/')?>{{template.id}}">
                  <ul class="all-milestone-todos">
                    <li ng-show="template.status == '1'" class="milestone-todos-list-item col-md-12 done" style="color: unset;"> 
                      <span class="pull-left col-md-6">
                        <strong ng-bind="template.name"></strong><br>
                      </span>
                      <div class="col-md-6">
                        <div class="col-md-8">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('subject'); ?></small><br>
                            <strong ng-bind="template.subject"></strong>
                          </span>
                        </div>
                        <div class="col-md-4">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('status'); ?></small><br>
                            <strong class="badge green"><?php echo lang('active'); ?></strong>
                          </span>
                        </div>
                      </div>
                    </li>
                    <li ng-show="template.status != '1'" class="milestone-todos-list-item col-md-12"> 
                      <span class="pull-left col-md-6">
                        <strong ng-bind="template.name"></strong><br>
                      </span>
                      <div class="col-md-6">
                        <div class="col-md-8">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('subject'); ?></small><br>
                            <strong ng-bind="template.subject"></strong>
                          </span>
                        </div>
                        <div class="col-md-4">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('status'); ?></small><br>
                            <strong class="badge red"><?php echo lang('inactive'); ?></strong>
                          </span>
                        </div>
                      </div>
                    </li>
                  </ul>
                </a>
              </li>
            </ul>
          </md-content>
        </md-tab>
        <md-tab label="<?php echo lang('tickets'); ?>">
          <md-content class="md-padding bg-white">
            <ul class="custom-ciuis-list-body" style="padding: 0px;"> 
              <li ng-repeat="template in templates | filter: { relation: 'ticket' }  " class="milestone-todos-list ciuis-custom-list-item ciuis-special-list-item paginationclass" style="min-width: 100% !important;">
                <a href="<?php echo base_url('emails/template/')?>{{template.id}}">
                  <ul class="all-milestone-todos">
                    <li ng-show="template.status == '1'" class="milestone-todos-list-item col-md-12 done" style="color: unset;"> 
                      <span class="pull-left col-md-6">
                        <strong ng-bind="template.name"></strong><br>
                      </span>
                      <div class="col-md-6">
                        <div class="col-md-8">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('subject'); ?></small><br>
                            <strong ng-bind="template.subject"></strong>
                          </span>
                        </div>
                        <div class="col-md-4">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('status'); ?></small><br>
                            <strong class="badge green"><?php echo lang('active'); ?></strong>
                          </span>
                        </div>
                      </div>
                    </li>
                    <li ng-show="template.status != '1'" class="milestone-todos-list-item col-md-12"> 
                      <span class="pull-left col-md-6">
                        <strong ng-bind="template.name"></strong><br>
                      </span>
                      <div class="col-md-6">
                        <div class="col-md-8">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('subject'); ?></small><br>
                            <strong ng-bind="template.subject"></strong>
                          </span>
                        </div>
                        <div class="col-md-4">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('status'); ?></small><br>
                            <strong class="badge red"><?php echo lang('inactive'); ?></strong>
                          </span>
                        </div>
                      </div>
                    </li>
                  </ul>
                </a>
              </li>
            </ul>
          </md-content>
        </md-tab>
        <md-tab label="<?php echo lang('proposals'); ?>">
          <md-content class="md-padding bg-white">
            <ul class="custom-ciuis-list-body" style="padding: 0px;"> 
              <li ng-repeat="template in templates | filter: { relation: 'proposal' }  " class="milestone-todos-list ciuis-custom-list-item ciuis-special-list-item paginationclass" style="min-width: 100% !important;">
                <a href="<?php echo base_url('emails/template/')?>{{template.id}}">
                  <ul class="all-milestone-todos">
                    <li ng-show="template.status == '1'" class="milestone-todos-list-item col-md-12 done" style="color: unset;"> 
                      <span class="pull-left col-md-6">
                        <strong ng-bind="template.name"></strong><br>
                      </span>
                      <div class="col-md-6">
                        <div class="col-md-8">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('subject'); ?></small><br>
                            <strong ng-bind="template.subject"></strong>
                          </span>
                        </div>
                        <div class="col-md-4">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('status'); ?></small><br>
                            <strong class="badge green"><?php echo lang('active'); ?></strong>
                          </span>
                        </div>
                      </div>
                    </li>
                    <li ng-show="template.status != '1'" class="milestone-todos-list-item col-md-12"> 
                      <span class="pull-left col-md-6">
                        <strong ng-bind="template.name"></strong><br>
                      </span>
                      <div class="col-md-6">
                        <div class="col-md-8">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('subject'); ?></small><br>
                            <strong ng-bind="template.subject"></strong>
                          </span>
                        </div>
                        <div class="col-md-4">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('status'); ?></small><br>
                            <strong class="badge red"><?php echo lang('inactive'); ?></strong>
                          </span>
                        </div>
                      </div>
                    </li>
                  </ul>
                </a>
              </li>
            </ul>
          </md-content>
        </md-tab>
        <md-tab label="<?php echo lang('projects'); ?>">
          <md-content class="md-padding bg-white">
            <ul class="custom-ciuis-list-body" style="padding: 0px;"> 
              <li ng-repeat="template in templates | filter: { relation: 'project' }  " class="milestone-todos-list ciuis-custom-list-item ciuis-special-list-item paginationclass" style="min-width: 100% !important;">
                <a href="<?php echo base_url('emails/template/')?>{{template.id}}">
                  <ul class="all-milestone-todos">
                    <li ng-show="template.status == '1'" class="milestone-todos-list-item col-md-12 done" style="color: unset;"> 
                      <span class="pull-left col-md-6">
                        <strong ng-bind="template.name"></strong><br>
                      </span>
                      <div class="col-md-6">
                        <div class="col-md-8">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('subject'); ?></small><br>
                            <strong ng-bind="template.subject"></strong>
                          </span>
                        </div>
                        <div class="col-md-4">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('status'); ?></small><br>
                            <strong class="badge green"><?php echo lang('active'); ?></strong>
                          </span>
                        </div>
                      </div>
                    </li>
                    <li ng-show="template.status != '1'" class="milestone-todos-list-item col-md-12"> 
                      <span class="pull-left col-md-6">
                        <strong ng-bind="template.name"></strong><br>
                      </span>
                      <div class="col-md-6">
                        <div class="col-md-8">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('subject'); ?></small><br>
                            <strong ng-bind="template.subject"></strong>
                          </span>
                        </div>
                        <div class="col-md-4">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('status'); ?></small><br>
                            <strong class="badge red"><?php echo lang('inactive'); ?></strong>
                          </span>
                        </div>
                      </div>
                    </li>
                  </ul>
                </a>
              </li>
            </ul>
          </md-content>
        </md-tab>
        <md-tab label="<?php echo lang('tasks'); ?>">
          <md-content class="md-padding bg-white">
            <ul class="custom-ciuis-list-body" style="padding: 0px;"> 
              <li ng-repeat="template in templates | filter: { relation: 'task' }  " class="milestone-todos-list ciuis-custom-list-item ciuis-special-list-item paginationclass" style="min-width: 100% !important;">
                <a href="<?php echo base_url('emails/template/')?>{{template.id}}">
                  <ul class="all-milestone-todos">
                    <li ng-show="template.status == '1'" class="milestone-todos-list-item col-md-12 done" style="color: unset;"> 
                      <span class="pull-left col-md-6">
                        <strong ng-bind="template.name"></strong><br>
                      </span>
                      <div class="col-md-6">
                        <div class="col-md-8">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('subject'); ?></small><br>
                            <strong ng-bind="template.subject"></strong>
                          </span>
                        </div>
                        <div class="col-md-4">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('status'); ?></small><br>
                            <strong class="badge green"><?php echo lang('active'); ?></strong>
                          </span>
                        </div>
                      </div>
                    </li>
                    <li ng-show="template.status != '1'" class="milestone-todos-list-item col-md-12"> 
                      <span class="pull-left col-md-6">
                        <strong ng-bind="template.name"></strong><br>
                      </span>
                      <div class="col-md-6">
                        <div class="col-md-8">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('subject'); ?></small><br>
                            <strong ng-bind="template.subject"></strong>
                          </span>
                        </div>
                        <div class="col-md-4">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('status'); ?></small><br>
                            <strong class="badge red"><?php echo lang('inactive'); ?></strong>
                          </span>
                        </div>
                      </div>
                    </li>
                  </ul>
                </a>
              </li>
            </ul>
          </md-content>
        </md-tab>
        <md-tab label="<?php echo lang('staff'); ?>">
          <md-content class="md-padding bg-white">
             <ul class="custom-ciuis-list-body" style="padding: 0px;"> 
              <li ng-repeat="template in templates | filter: { relation: 'staff' }  " class="milestone-todos-list ciuis-custom-list-item ciuis-special-list-item paginationclass" style="min-width: 100% !important;">
                <a href="<?php echo base_url('emails/template/')?>{{template.id}}">
                  <ul class="all-milestone-todos">
                    <li ng-show="template.status == '1'" class="milestone-todos-list-item col-md-12 done" style="color: unset;"> 
                      <span class="pull-left col-md-6">
                        <strong ng-bind="template.name"></strong><br>
                      </span>
                      <div class="col-md-6">
                        <div class="col-md-8">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('subject'); ?></small><br>
                            <strong ng-bind="template.subject"></strong>
                          </span>
                        </div>
                        <div class="col-md-4">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('status'); ?></small><br>
                            <strong class="badge green"><?php echo lang('active'); ?></strong>
                          </span>
                        </div>
                      </div>
                    </li>
                    <li ng-show="template.status != '1'" class="milestone-todos-list-item col-md-12"> 
                      <span class="pull-left col-md-6">
                        <strong ng-bind="template.name"></strong><br>
                      </span>
                      <div class="col-md-6">
                        <div class="col-md-8">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('subject'); ?></small><br>
                            <strong ng-bind="template.subject"></strong>
                          </span>
                        </div>
                        <div class="col-md-4">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('status'); ?></small><br>
                            <strong class="badge red"><?php echo lang('inactive'); ?></strong>
                          </span>
                        </div>
                      </div>
                    </li>
                  </ul>
                </a>
              </li>
            </ul>
          </md-content>
        </md-tab>
        <md-tab label="<?php echo lang('leads'); ?>">
          <md-content class="md-padding bg-white">
            <ul class="custom-ciuis-list-body" style="padding: 0px;"> 
              <li ng-repeat="template in templates | filter: { relation: 'lead' }  " class="milestone-todos-list ciuis-custom-list-item ciuis-special-list-item paginationclass" style="min-width: 100% !important;">
                <a href="<?php echo base_url('emails/template/')?>{{template.id}}">
                  <ul class="all-milestone-todos">
                    <li ng-show="template.status == '1'" class="milestone-todos-list-item col-md-12 done" style="color: unset;"> 
                      <span class="pull-left col-md-6">
                        <strong ng-bind="template.name"></strong><br>
                      </span>
                      <div class="col-md-6">
                        <div class="col-md-8">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('subject'); ?></small><br>
                            <strong ng-bind="template.subject"></strong>
                          </span>
                        </div>
                        <div class="col-md-4">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('status'); ?></small><br>
                            <strong class="badge green"><?php echo lang('active'); ?></strong>
                          </span>
                        </div>
                      </div>
                    </li>
                    <li ng-show="template.status != '1'" class="milestone-todos-list-item col-md-12"> 
                      <span class="pull-left col-md-6">
                        <strong ng-bind="template.name"></strong><br>
                      </span>
                      <div class="col-md-6">
                        <div class="col-md-8">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('subject'); ?></small><br>
                            <strong ng-bind="template.subject"></strong>
                          </span>
                        </div>
                        <div class="col-md-4">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('status'); ?></small><br>
                            <strong class="badge red"><?php echo lang('inactive'); ?></strong>
                          </span>
                        </div>
                      </div>
                    </li>
                  </ul>
                </a>
              </li>
            </ul>
          </md-content>
        </md-tab>
        <md-tab label="<?php echo lang('expenses'); ?>">
          <md-content class="md-padding bg-white">
            <ul class="custom-ciuis-list-body" style="padding: 0px;"> 
              <li ng-repeat="template in templates | filter: { relation: 'expense' }  " class="milestone-todos-list ciuis-custom-list-item ciuis-special-list-item paginationclass" style="min-width: 100% !important;">
                <a href="<?php echo base_url('emails/template/')?>{{template.id}}">
                  <ul class="all-milestone-todos">
                    <li ng-show="template.status == '1'" class="milestone-todos-list-item col-md-12 done" style="color: unset;"> 
                      <span class="pull-left col-md-6">
                        <strong ng-bind="template.name"></strong><br>
                      </span>
                      <div class="col-md-6">
                        <div class="col-md-8">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('subject'); ?></small><br>
                            <strong ng-bind="template.subject"></strong>
                          </span>
                        </div>
                        <div class="col-md-4">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('status'); ?></small><br>
                            <strong class="badge green"><?php echo lang('active'); ?></strong>
                          </span>
                        </div>
                      </div>
                    </li>
                    <li ng-show="template.status != '1'" class="milestone-todos-list-item col-md-12"> 
                      <span class="pull-left col-md-6">
                        <strong ng-bind="template.name"></strong><br>
                      </span>
                      <div class="col-md-6">
                        <div class="col-md-8">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('subject'); ?></small><br>
                            <strong ng-bind="template.subject"></strong>
                          </span>
                        </div>
                        <div class="col-md-4">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('status'); ?></small><br>
                            <strong class="badge red"><?php echo lang('inactive'); ?></strong>
                          </span>
                        </div>
                      </div>
                    </li>
                  </ul>
                </a>
              </li>
            </ul>
          </md-content>
        </md-tab>
        <md-tab label="<?php echo lang('orders'); ?>">
          <md-content class="md-padding bg-white">
            <ul class="custom-ciuis-list-body" style="padding: 0px;"> 
              <li ng-repeat="template in templates | filter: { relation: 'order' }  " class="milestone-todos-list ciuis-custom-list-item ciuis-special-list-item paginationclass" style="min-width: 100% !important;">
                <a href="<?php echo base_url('emails/template/')?>{{template.id}}">
                  <ul class="all-milestone-todos">
                    <li ng-show="template.status == '1'" class="milestone-todos-list-item col-md-12 done" style="color: unset;"> 
                      <span class="pull-left col-md-6">
                        <strong ng-bind="template.name"></strong><br>
                      </span>
                      <div class="col-md-6">
                        <div class="col-md-8">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('subject'); ?></small><br>
                            <strong ng-bind="template.subject"></strong>
                          </span>
                        </div>
                        <div class="col-md-4">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('status'); ?></small><br>
                            <strong class="badge green"><?php echo lang('active'); ?></strong>
                          </span>
                        </div>
                      </div>
                    </li>
                    <li ng-show="template.status != '1'" class="milestone-todos-list-item col-md-12"> 
                      <span class="pull-left col-md-6">
                        <strong ng-bind="template.name"></strong><br>
                      </span>
                      <div class="col-md-6">
                        <div class="col-md-8">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('subject'); ?></small><br>
                            <strong ng-bind="template.subject"></strong>
                          </span>
                        </div>
                        <div class="col-md-4">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('status'); ?></small><br>
                            <strong class="badge red"><?php echo lang('inactive'); ?></strong>
                          </span>
                        </div>
                      </div>
                    </li>
                  </ul>
                </a>
              </li>
            </ul>
          </md-content>
        </md-tab>
        <!-- 
        <md-tab label="<?php echo lang('accounts'); ?>" style="display: none;">
          <md-content class="md-padding bg-white">
            <ul class="custom-ciuis-list-body" style="padding: 0px;"> 
              <li ng-repeat="template in templates | filter: { relation: 'account' }  " class="milestone-todos-list ciuis-custom-list-item ciuis-special-list-item paginationclass" style="min-width: 100% !important;">
                <a href="<?php echo base_url('emails/template/')?>{{template.id}}">
                  <ul class="all-milestone-todos">
                    <li ng-show="template.status == '1'" class="milestone-todos-list-item col-md-12 done" style="color: unset;"> 
                      <span class="pull-left col-md-6">
                        <strong ng-bind="template.name"></strong><br>
                      </span>
                      <div class="col-md-6">
                        <div class="col-md-8">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('subject'); ?></small><br>
                            <strong ng-bind="template.subject"></strong>
                          </span>
                        </div>
                        <div class="col-md-4">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('status'); ?></small><br>
                            <strong class="badge green"><?php echo lang('active'); ?></strong>
                          </span>
                        </div>
                      </div>
                    </li>
                    <li ng-show="template.status != '1'" class="milestone-todos-list-item col-md-12"> 
                      <span class="pull-left col-md-6">
                        <strong ng-bind="template.name"></strong><br>
                      </span>
                      <div class="col-md-6">
                        <div class="col-md-8">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('subject'); ?></small><br>
                            <strong ng-bind="template.subject"></strong>
                          </span>
                        </div>
                        <div class="col-md-4">
                          <span class="date-start-task">
                            <small class="text-muted text-uppercase"><?php echo lang('status'); ?></small><br>
                            <strong class="badge red"><?php echo lang('inactive'); ?></strong>
                          </span>
                        </div>
                      </div>
                    </li>
                  </ul>
                </a>
              </li>
            </ul>
          </md-content>
        </md-tab> -->
      </md-tabs>
    </md-content>
  </div>
</div>

<script type="text/ng-template" id="emailDialog.html">
    <md-dialog aria-label="Expense Detail">
      <md-toolbar class="toolbar-white">
        <div class="md-toolbar-tools">
          <h2><strong class="text-success"><?php echo lang('email') ?></strong></h2>
          <span flex></span>
          <!-- <md-button ng-click="deleteForm()" class="md-icon-button" aria-label="Update">
            <md-tooltip md-direction="bottom"><?php echo lang('delete') ?></md-tooltip> 
            <md-icon><i class="ion-trash-b text-muted"></i></md-icon>
          </md-button> -->
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
                <span ng-bind="email.subject">
                </span>
              </h3>
            </md-list-item>
            <md-list-item>
              <p><strong><?php echo lang('from'); ?></strong>: <span ng-bind="email.to"></span></p>
            </md-list-item>
            <md-list-item>
              <p><strong><?php echo lang('to'); ?></strong>: <a ng-href="mailto:email.email"><span ng-bind="email.email"></span></a></p><br>
            </md-list-item>
            <md-list-item ng-if="email.attachment">
              <p><strong><?php echo lang('attachment'); ?></strong>: 
                <a class="link" download ng-href="{{email.attachment}}">
                  <img src="<?php echo base_url('assets/img/file_export.png');?>">
                </a>
              </p>
            </md-list-item>
            <md-divider></md-divider>
            <md-list-item>
              <br><br>
              <div ng-bind-html="email.message"></div><br>
            </md-list-item>
            <md-divider></md-divider>
            <md-content layout-padding>
              <h3 class="md-mt-0">
                <small class="text-muted text-uppercase"><?php echo lang('date'); ?></small>: 
                <strong style="font-size: 17px;" ng-bind="email.time | date : 'MMM d, y h:mm:ss a'"></strong>
              </h3>
            </md-content>
          </md-list>
        </md-content>     
      </md-dialog-content>
    </md-dialog>
  </script>