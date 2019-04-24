<div class="ciuis-body-content" ng-controller="WebLeads_Controller">
  <div class="main-content container-fluid col-xs-12 col-md-12 col-lg-12">
    <div class="main-content container-fluid col-xs-12 col-md-3 col-lg-3 md-pl-0">
    </div>
    <div class="main-content container-fluid col-xs-12 col-md-9 col-lg-9 md-p-0 lead-table">
      <div ng-show="webleadsLoader" layout-align="center center" class="text-center" id="circular_loader">
        <md-progress-circular md-mode="indeterminate" md-diameter="40"></md-progress-circular>
          <p style="font-size: 15px;margin-bottom: 5%;">
           <span>
              <?php echo lang('please_wait') ?> <br>
             <small><strong><?php echo lang('loading'). ' '. lang('webleads').'...' ?></strong></small>
           </span>
         </p>
       </div>
      <md-toolbar ng-show="!webleadsLoader" class="toolbar-white">
        <div class="md-toolbar-tools">
          <md-button class="md-icon-button" aria-label="Settings" ng-disabled="true">
            <md-icon><i class="ion-earth text-muted"></i></md-icon>
          </md-button>
          <h2 flex md-truncate><?php echo lang('webleads') ?><br>
            <small flex md-truncate><?php echo lang('manage_webleads'); ?></small>
          </h2>
          <div class="ciuis-external-search-in-table">
            <input ng-model="search.name" class="search-table-external" id="search" name="search" type="text" placeholder="<?php echo lang('searchword')?>">
            <md-button class="md-icon-button" aria-label="Search">
              <md-icon><i class="ion-search text-muted"></i></md-icon>
            </md-button>
          </div>
          <md-button ng-click="createForm()" class="md-icon-button" aria-label="Update">
            <md-tooltip md-direction="bottom"><?php echo lang('newform') ?></md-tooltip> 
            <md-icon><i class="ion-plus-round text-muted"></i></md-icon>
          </md-button>
        </div>
      </md-toolbar>
      <md-content ng-show="!webleadsLoader"> 
        <ul class="custom-ciuis-list-body" style="padding: 0px;">
          <li ng-repeat="form in webleads | filter: FilteredData | filter:search | pagination : currentPage*itemsPerPage | limitTo: 5" class="ciuis-custom-list-item ciuis-special-list-item">
            <a ng-href="<?php echo base_url('leads/form/')?>{{form.id}}">
              <ul class="list-item-for-custom-list">
                <li class="ciuis-custom-list-item-item col-md-12">
                  <div data-toggle="tooltip" data-placement="bottom" data-container="body" title="" data-original-title="Assigned: {{form.assigned}}" class="assigned-staff-for-this-lead user-avatar">
                    <img src="<?php echo base_url('uploads/images/{{form.avatar}}')?>" alt="{{form.assigned}}"> 
                  </div>
                  <div class="pull-left col-md-4">
                      <small class="text-muted text-uppercase"><?php echo lang('form_name') ?></small><br>
                      <strong ng-bind="form.name"></strong>
                  </div>
                  <div class="col-md-8">
                    <div class="col-md-3"> 
                      <span class="date-start-task">
                        <small class="text-muted text-uppercase"><?php echo lang('total_submissions') ?> <i class="ion-android-list"></i></small><br>
                        <strong ng-bind="form.total_submissions"></strong>
                      </span> 
                    </div>
                    <div class="col-md-3">
                      <span class="date-start-task">
                        <small class="text-muted text-uppercase"><?php echo lang('form_status') ?></small><br>
                        <strong ng-show="form.status == '1'" class="badge green"><?php echo lang('active'); ?></strong>
                        <strong ng-show="form.status != '1'" class="badge red"><?php echo lang('inactive'); ?></strong>
                      </span> 
                    </div>
                    <div class="col-md-3">
                      <span class="date-start-task">
                        <small class="text-muted text-uppercase"><?php echo lang('source') ?> <i class="ion-ios-circle-filled"></i></small><br>
                        <strong>
                          <span class="badge" ng-bind="form.sourcename"></span>
                        </strong>
                      </span> 
                    </div>
                    <div class="col-md-3">
                      <span class="date-start-task">
                        <small class="text-muted text-uppercase"><?php echo lang('created') ?> <i class="ion-ios-stopwatch-outline"></i></small><br>
                        <strong ng-bind="form.createddate"></strong>
                      </span> 
                    </div>
                  </div>
                </li>
              </ul>
            </a>
          </li>
        </ul>
        <div ng-show="!webleadsLoader" ng-hide="webleads.length < 5" class="pagination-div">
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
        <md-content ng-show="!webleadsLoader" ng-hide="webleads.length > 0" class="md-padding no-item-data"><?php echo lang('no_webleads_found') ?></md-content>
      </md-content>
    </div>
  </div>

  <md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="Create" style="width: 650px;"  ng-cloak>
    <md-toolbar class="toolbar-white">
      <div class="md-toolbar-tools">
        <md-button ng-click="close()" class="md-icon-button" aria-label="Close"> <i class="ion-android-arrow-forward"></i> </md-button>
        <md-truncate><?php echo lang('create_weblead_form') ?></md-truncate> &nbsp;&nbsp;&nbsp;
        <md-switch ng-checked="true" ng-model="weblead.status" aria-label="Type">
          <strong class="text-muted"><?php echo lang('active') ?></strong>
        </md-switch>
      </div>
    </md-toolbar>
    <md-content>
      <md-content layout-padding>
        <md-input-container class="md-block">
          <label><?php echo lang('form_name'); ?></label>
          <input ng-model="weblead.name">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('assigned'); ?></label>
          <md-select placeholder="<?php echo lang('choosestaff'); ?>" ng-model="weblead.assigned_id" style="min-width: 200px;">
            <md-option ng-value="staff.id" ng-repeat="staff in staff">{{staff.name}}</md-option>
          </md-select>
        </md-input-container>
        <br>
        <md-input-container class="md-block">
          <label><?php echo lang('status'); ?></label>
          <md-select placeholder="<?php echo lang('status'); ?>" ng-model="weblead.status_id" style="min-width: 200px;">
            <md-select-header>
              <md-toolbar class="toolbar-white">
                <div class="md-toolbar-tools">
                  <h4 flex md-truncate><?php echo lang('status') ?></h4>
                  <md-button class="md-icon-button" ng-click="NewStatus()" aria-label="Create New">
                    <md-icon><i class="mdi mdi-plus text-muted"></i></md-icon>
                  </md-button>
                </div>
              </md-toolbar>
            </md-select-header>
            <md-option ng-value="status.id" ng-repeat="status in leadstatuses">{{status.name}}</md-option>
          </md-select>
        </md-input-container>
        <br>
        <md-input-container class="md-block">
          <label><?php echo lang('source'); ?></label>
          <md-select placeholder="<?php echo lang('source'); ?>" ng-model="weblead.source_id" style="min-width: 200px;">
            <md-select-header>
              <md-toolbar class="toolbar-white">
                <div class="md-toolbar-tools">
                  <h4 flex md-truncate><?php echo lang('source') ?></h4>
                  <md-button class="md-icon-button" ng-click="NewSource()" aria-label="Create New">
                    <md-icon><i class="mdi mdi-plus text-muted"></i></md-icon>
                  </md-button>
                </div>
              </md-toolbar>
            </md-select-header>
            <md-option ng-value="source.id" ng-repeat="source in leadssources">{{source.name}}</md-option>
          </md-select>
        </md-input-container><br>
        <md-input-container class="md-block">
          <label><?php echo lang('submit_text'); ?></label>
          <?php $submit = lang('submit'); ?>
          <input ng-model="weblead.submit_text" ng-init="weblead.submit_text = '<?php echo $submit ?>'">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('message_after_success') ?></label>
          <?php $success = lang('leads_success_message'); ?>
          <textarea ng-model="weblead.success_message" ng-init="weblead.success_message = '<?php echo $success ?>'" md-maxlength="300" md-select-on-focus></textarea>
        </md-input-container>
        <md-checkbox ng-checked="true" ng-model="weblead.notification"><?php echo lang('email_notification') ?></md-checkbox>
        <md-checkbox ng-checked="true" ng-model="weblead.duplicate"><?php echo lang('allow_duplicate') ?></md-checkbox>
      </md-content>
      <md-content layout-padding>
        <section layout="row" layout-sm="column" layout-align="center center" layout-wrap>
          <md-button ng-click="AddWebLeadForm()" class="md-raised md-primary pull-right" ng-disabled="saving == true">
            <span ng-hide="addingLead == true"><?php echo lang('create');?></span>
            <md-progress-circular class="white" ng-show="addingLead == true" md-mode="indeterminate" md-diameter="20"></md-progress-circular>
          </md-button>
        </section>
      </md-content>
    </md-content>
  </md-sidenav>
</div>