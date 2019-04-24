<link rel='stylesheet' href="<?php echo base_url('assets/lib/bootstrap/dist/css/bootstrap.min.css')?>">
<link rel='stylesheet' href="<?php echo base_url('assets/lib/form-builder/formio.full.min.css')?>">
<script src="<?php echo base_url('assets/lib/form-builder/formio.full.min.js')?>"></script> 
<div class="ciuis-body-content" ng-controller="WebLead_Controller">
  <div class="main-content container-fluid col-xs-12 col-md-12 col-lg-12">
    <style type="text/css">
      .btn-primary {
        color: #fff;
        background-color: #656565;
        border-color: #656565;
        font-size: 14px !important;
        padding: 8px !important;
      }
      .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
        background-color: rgb(255,188,0) !important;
        border-color: rgb(255,188,0) !important;
        box-shadow: inset 0 -1px 0 #ffbc00;
      }
      .formio-component-widget.type {
        display: none !important;
      }
        
      @media (max-width: 1500px) {
        .formcomponents {
          width: 25% !important;
        }
        .formarea.drag-container {
          width: 75% !important;
        }
      }
    </style>
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
        <md-button class="md-icon-button" aria-label="" ng-disabled="true">
          <md-icon><i class="ion-earth text-muted"></i></md-icon>
        </md-button>
        <h2 flex md-truncate><?php echo lang('webleads') ?></h2>
        <md-button ng-click="deleteForm()" class="md-icon-button" aria-label="Update">
          <md-tooltip md-direction="bottom"><?php echo lang('delete') ?></md-tooltip> 
          <md-icon><i class="ion-trash-b text-muted"></i></md-icon>
        </md-button>
        <md-switch ng-change="changeStatus(weblead.status)" ng-model="weblead.status" aria-label="Type">
          <strong class="text-muted"><?php echo lang('active') ?></strong>
        </md-switch>
      </div>
    </md-toolbar>
    <md-tabs ng-show="!webleadsLoader" md-dynamic-height md-border-bottom>
      <md-tab label="<?php echo lang('formbuilder'); ?>">
        <md-content class="md-padding bg-white">
          <div id='builder' style="width: 90%;margin-left: auto;margin-right: auto;border: 1px solid whitesmoke;padding: 10px;"></div>
          <md-content layout-padding class="bg-white" style="width: 80%;margin-left: auto;margin-right: auto;">
            <section layout="row" layout-sm="column" layout-align="pull-right" layout-wrap style="float: left;">
              <md-button id="saveFormContent" class="md-raised md-primary pull-right" ng-disabled="savingLead == true">
                <span ng-hide="savingLead == true"><?php echo lang('save');?></span>
                <md-progress-circular class="white" ng-show="savingLead == true" md-mode="indeterminate" md-diameter="20"></md-progress-circular>
              </md-button>
            </section>
          </md-content>
        </md-content>
      </md-tab>
      <md-tab label="<?php echo lang('formsetup'); ?>">
        <md-content class="md-padding bg-white">
          <div class="col-xs-12 col-md-12 col-lg-12">
            <div class="col-xs-12 col-md-12 col-lg-6">
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
                <label><?php echo lang('submit_text'); ?></label>
                <?php $submit = lang('submit'); ?>
                <input ng-model="weblead.submit_text" ng-init="weblead.submit_text = '<?php echo $submit ?>'">
              </md-input-container>
              <md-checkbox ng-model="weblead.duplicate"><?php echo lang('allow_duplicate') ?></md-checkbox><br>
              <md-checkbox ng-model="weblead.notification"><?php echo lang('email_notification') ?></md-checkbox>
            </div>
            <div class="col-xs-12 col-md-12 col-lg-6">
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
                <label><?php echo lang('message_after_success') ?></label>
                <?php $success = lang('leads_success_message'); ?>
                <textarea ng-model="weblead.success_message" ng-init="weblead.success_message = '<?php echo $success ?>'" md-maxlength="300" md-select-on-focus></textarea>
              </md-input-container>
            </div>
          </div>
          <br><br>
          <md-content layout-padding class="bg-white">
            <section layout="row" layout-sm="column" layout-align="pull-left" layout-wrap>
              <md-button ng-click="SaveWebLeadForm()" class="md-raised md-primary pull-right" ng-disabled="savingLead == true">
                <span ng-hide="savingLead == true"><?php echo lang('save');?></span>
                <md-progress-circular class="white" ng-show="savingLead == true" md-mode="indeterminate" md-diameter="20"></md-progress-circular>
              </md-button>
            </section>
          </md-content>
        </md-content>
      </md-tab>
      <md-tab label="<?php echo lang('integrationcode'); ?>">
        <md-content class="md-padding bg-white">
          <p>
            <?php echo lang('integrationcode_line1') ?>
          </p>
          <pre style="padding: 20px 10px;" class="editorField">
          </pre><br>
          <?php echo lang('test_it') ?>: <a class=""  target="_blank" href="<?php echo base_url('forms/wlf/'.$token) ?>"><?php echo base_url('forms/wlf/'.$token) ?></a> <br>
        </md-content>
      </md-tab>
    </md-tabs>
  </div>
</div>
  <script type="text/javascript">
    var lang = {};
    lang.title = '<?php echo lang('title') ?>';
    lang.formbuilder = '<?php echo lang('form_builder') ?>';
    lang.name = '<?php echo lang('name') ?>';
    lang.company = '<?php echo lang('company') ?>';
    lang.phone = '<?php echo lang('phone') ?>';
    lang.email = '<?php echo lang('email') ?>';
    lang.website = '<?php echo lang('website') ?>';
    lang.company = '<?php echo lang('company') ?>';
    lang.state = '<?php echo lang('state') ?>';
    lang.city = '<?php echo lang('city') ?>';
    lang.zip = '<?php echo lang('zip') ?>';
    lang.address = '<?php echo lang('address') ?>';
    lang.country = '<?php echo lang('country') ?>';
    lang.description = '<?php echo lang('description') ?>';
    lang.date = '<?php echo lang('date') ?>';
    lang.submit = '<?php echo $submit ?>';
    lang.emailError = '<?php echo lang('emailError') ?>';

    var FORMID = '<?php echo $formId; ?>';
</script>
