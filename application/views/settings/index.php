<?php $appconfig = get_appconfig(); ?>
<div class="ciuis-body-content" ng-controller="Settings_Controller">
  <div class="main-content container-fluid col-xs-12 col-md-12 col-lg-12">
    <md-toolbar class="toolbar-white">
      <div class="md-toolbar-tools">
        <md-button class="md-icon-button" aria-label="Settings" ng-disabled="true">
          <md-icon><i class="ion-ios-gear text-muted"></i></md-icon>
        </md-button>
        <h2 flex md-truncate><?php echo lang('crmsettings') ?></h2>
        <md-button ng-click="VersionCheck()" class="md-icon-button" aria-label="Update">
          <md-tooltip md-direction="bottom"><?php echo lang('version_check') ?></md-tooltip> 
          <md-icon><i class="ion-ios-cloud-download text-muted"></i></md-icon>
        </md-button>
        <md-button ng-click="UpdateSettings()" class="md-icon-button" aria-label="Save">
          <md-progress-circular ng-show="savingSettings == true" md-mode="indeterminate" md-diameter="20"></md-progress-circular>
          <md-tooltip ng-hide="savingSettings == true" md-direction="bottom"><?php echo lang('update') ?></md-tooltip>
          <md-icon ng-hide="savingSettings == true"><i class="ion-checkmark-circled text-muted"></i></md-icon>
        </md-button>
      </div>
    </md-toolbar>
    <md-content class="bg-white">
      <div ng-show="settings.loader" layout-align="center center" class="text-center" id="circular_loader">
        <md-progress-circular md-mode="indeterminate" md-diameter="40"></md-progress-circular>
        <p style="font-size: 15px;margin-bottom: 5%;">
          <span>
            <?php echo lang('please_wait') ?> <br>
            <small><strong><?php echo lang('loading'). ' '. lang('settings').'...' ?></strong></small>
          </span>
        </p>
      </div>
      <md-tabs ng-show="!settings.loader" md-dynamic-height md-border-bottom>
        <md-tab label="<?php echo lang('companysettings'); ?>">
          <md-content class="md-padding bg-white">
            <div class="col-md-6">
              <md-input-container class="md-block">
                <label><?php echo lang('company')?></label>
                <input required name="company" ng-model="settings_detail.company">
              </md-input-container>
              <md-input-container class="md-block">
                <label><?php echo lang('email')?></label>
                <input required name="company" ng-model="settings_detail.email">
              </md-input-container>
              <md-input-container class="md-block">
                <label><?php echo lang('country'); ?></label>
                <md-select placeholder="<?php echo lang('country'); ?>" ng-model="settings_detail.country_id" style="min-width: 200px;">
                  <md-option ng-value="country.id" ng-repeat="country in countries">{{country.shortname}}</md-option>
                </md-select>
                <br>
              </md-input-container>
              <md-input-container class="md-block">
                <label><?php echo lang('state')?></label>
                <input required ng-model="settings_detail.state">
              </md-input-container>
              <md-input-container class="md-block">
                <label><?php echo lang('city')?></label>
                <input required ng-model="settings_detail.city">
              </md-input-container>
              <md-input-container class="md-block">
                <label><?php echo lang('town')?></label>
                <input required ng-model="settings_detail.town">
              </md-input-container>
            </div>
            <div class="col-md-6">
              <md-input-container class="md-block">
                <label><?php echo lang('crmname')?></label>
                <input required ng-model="settings_detail.crm_name">
              </md-input-container>
              <md-input-container class="md-block">
                <label><?php echo lang('zipcode')?></label>
                <input required ng-model="settings_detail.zipcode">
              </md-input-container>
              <md-input-container class="md-block">
                <label><?php echo lang('phone')?></label>
                <input required ng-model="settings_detail.phone">
              </md-input-container>
              <md-input-container class="md-block">
                <label><?php echo lang('fax')?></label>
                <input required ng-model="settings_detail.fax">
              </md-input-container>
              <md-input-container class="md-block">
                <label><?php echo $appconfig['tax_label'].' '.lang('vatnumber')?></label>
                <input required ng-model="settings_detail.vatnumber">
              </md-input-container>
              <md-input-container class="md-block">
                <label><?php echo $appconfig['tax_label'].' '.lang('taxoffice')?></label>
                <input required ng-model="settings_detail.taxoffice">
              </md-input-container>
            </div>
            <div class="col-md-12">
              <md-input-container class="md-block">
                <label><?php echo lang('address')?></label>
                <textarea name="address" class="form-control" ng-model="settings_detail.address"></textarea>
              </md-input-container>
            </div>
          </md-content>
        </md-tab>
        <md-tab label="<?php echo lang('financialsettings'); ?>">
          <md-content class="md-padding bg-white">
            <div class="col-md-6">
              <md-input-container class="md-block">
                <label><?php echo lang('currency'); ?></label>
                <md-select required placeholder="<?php echo lang('currency'); ?>" ng-model="settings_detail.currencyid" style="min-width: 200px;">
                  <md-option ng-value="currency.id" ng-repeat="currency in currencies">{{currency.name}}</md-option>
                </md-select>
                <br>
              </md-input-container>
              <md-input-container class="md-block">
                <label><?php echo lang('tax').' '.lang('label')?></label>
                <textarea required name="address" class="form-control" ng-model="finance.tax_label"></textarea>
              </md-input-container>
              <!-- currency format -->
              <!-- <div layout="row" layout-wrap>
                <div flex-gt-xs="50" flex-xs="50">
                  <md-input-container class="md-block">
                    <label><?php echo lang('currency').' '.lang('format')?></label>
                    <md-select ng-change="showCurrencyOutput(currency_format)" placeholder="<?php echo lang('language'); ?>" ng-model="currency_format" style="min-width: 200px;">de_DE en_GB
                      <md-option ng-selected="true" ng-value="'en-US'">en_US</md-option>
                      <md-option ng-value="'de-DE'">de_DE</md-option>
                      <md-option ng-value="'en-GB'">en_GB</md-option>
                    </md-select>
                  </md-input-container>
                </div>
                <div flex-gt-xs="50" flex-xs="50">
                  <md-input-container class="md-block">
                    <span><?php echo lang('example').' '.lang('output')?></span><br>
                    <p ng-bind="currencyFormat_output"></p>
                  </md-input-container>
                </div>
              </div> -->
              <md-input-container class="md-block">
                <label><?php echo lang('termtitle')?></label>
                <input required ng-model="settings_detail.termtitle">
              </md-input-container>
              <md-input-container class="md-block">
                <label><?php echo lang('termdescription')?></label>
                <textarea required name="address" class="form-control" ng-model="settings_detail.termdescription"></textarea>
              </md-input-container>
            </div>
            <div class="col-md-6">
              <!-- Invoice -->
              <div layout="row" layout-wrap>
                <div flex-gt-xs="50" flex-xs="100">
                  <md-input-container class="md-block">
                    <label><?php echo lang('invoice').' '.lang('prefix')?></label>
                    <input required ng-model="finance.inv_prefix">
                  </md-input-container>
                </div>
                <div flex-gt-xs="50" flex-xs="100">
                  <md-input-container class="md-block">
                    <label><?php echo lang('invoice').' '.lang('suffix')?></label>
                    <input ng-model="finance.inv_suffix">
                  </md-input-container>
                </div>
              </div>
              <!-- Project -->
              <div layout="row" layout-wrap>
                <div flex-gt-xs="50" flex-xs="100">
                  <md-input-container class="md-block">
                    <label><?php echo lang('project').' '.lang('prefix')?></label>
                    <input required ng-model="finance.project_prefix">
                  </md-input-container>
                </div>
                <div flex-gt-xs="50" flex-xs="100">
                  <md-input-container class="md-block">
                    <label><?php echo lang('project').' '.lang('suffix')?></label>
                    <input ng-model="finance.project_suffix">
                  </md-input-container>
                </div>
              </div>
              <!-- Proposal -->
              <div layout="row" layout-wrap>
                <div flex-gt-xs="50" flex-xs="100">
                  <md-input-container class="md-block">
                    <label><?php echo lang('proposal').' '.lang('prefix')?></label>
                    <input required ng-model="finance.proposal_prefix">
                  </md-input-container>
                </div>
                <div flex-gt-xs="50" flex-xs="100">
                  <md-input-container class="md-block">
                    <label><?php echo lang('proposal').' '.lang('suffix')?></label>
                    <input ng-model="finance.proposal_suffix">
                  </md-input-container>
                </div>
              </div>
              <!-- Expense -->
              <div layout="row" layout-wrap>
                <div flex-gt-xs="50" flex-xs="100">
                  <md-input-container class="md-block">
                    <label><?php echo lang('expense').' '.lang('prefix')?></label>
                    <input required ng-model="finance.expense_prefix">
                  </md-input-container>
                </div>
                <div flex-gt-xs="50" flex-xs="100">
                  <md-input-container class="md-block">
                    <label><?php echo lang('expense').' '.lang('suffix')?></label>
                    <input ng-model="finance.expense_suffix">
                  </md-input-container>
                </div>
              </div>
              <!-- Order -->
              <div layout="row" layout-wrap>
                <div flex-gt-xs="50" flex-xs="100">
                  <md-input-container class="md-block">
                    <label><?php echo lang('order').' '.lang('prefix')?></label>
                    <input ng-model="finance.order_prefix">
                  </md-input-container>
                </div>
                <div flex-gt-xs="50" flex-xs="100">
                  <md-input-container class="md-block">
                    <label><?php echo lang('order').' '.lang('suffix')?></label>
                    <input ng-model="finance.order_suffix">
                  </md-input-container>
                </div>
              </div>
            </div>
          </md-content>
        </md-tab>
        <md-tab label="<?php echo lang('localization'); ?>">
          <md-content class="md-padding bg-white">
            <div class="col-md-6">
              <md-input-container class="md-block">
                <label><?php echo lang('language'); ?></label>
                <md-select placeholder="<?php echo lang('language'); ?>" ng-model="settings_detail.languageid" style="min-width: 200px;">
                  <md-option ng-value="language.foldername" ng-repeat="language in languages">{{language.name}}</md-option>
                </md-select>
                <br>
              </md-input-container>
              <md-input-container  class="md-block">
                <label><?php echo lang('defaulttimezone')?></label>
                <md-select ng-model="settings_detail.default_timezone">
                  <md-optgroup ng-repeat="timezone in timezones" label="{{timezone.group}}">
                    <md-option ng-value="zone.value" ng-repeat="zone in timezone.zones">{{zone.name}}</md-option>
                  </md-optgroup>
                </md-select>
              </md-input-container>
            </div>
            <div class="col-md-6">
              <md-input-container class="md-block" flex-gt-xs>
                <label><?php echo lang('dateformat'); ?></label>
                <md-select 
							ng-init="dateformats = [{value: 'yy.mm.dd',name: 'Y.M.D'}, {value: 'dd.mm.yy',name: 'D.M.Y'}, {value: 'yy-mm-dd',name: 'Y-M-D'}, {value: 'dd-mm-yy',name: 'D-M-Y'}, {value: 'yy/mm/dd',name: 'Y/M/D'}, {value: 'dd/mm/yy',name: 'D/M/Y'}];" 
							required 
							placeholder="<?php echo lang('dateformat'); ?>" 
							ng-model="settings_detail.dateformat" name="dateformat">
                  <md-option ng-value="dateformat.value" ng-repeat="dateformat in dateformats"><span class="text-uppercase">{{dateformat.name}}</span></md-option>
                </md-select>
                <br>
              </md-input-container>
            </div>
          </md-content>
        </md-tab>
        <md-tab label="<?php echo lang('emailsettings'); ?>">
          <md-content class="md-padding bg-white">
            <div class="col-md-6">
              <md-input-container class="md-block" flex-gt-xs>
                <label><span class="text-bold text-warning"><?php echo lang('email_encryption'); ?></span></label>
                <md-select  ng-init="email_encryptions = [{value: '0',name: 'NONE'},{value: '1',name: 'SSL'}, {value: '2',name: 'TLS'}];" placeholder="<?php echo lang('email_encryption'); ?>" ng-model="settings_detail.email_encryption">
                  <md-option ng-value="email_encryption.value" ng-repeat="email_encryption in email_encryptions"><span class="text-uppercase">{{email_encryption.name}}</span></md-option>
                </md-select>
                <br>
              </md-input-container>
              <md-input-container class="md-block">
                <label><?php echo lang('smtphost')?></label>
                <input required ng-model="settings_detail.smtphost">
              </md-input-container>
              <md-input-container class="md-block">
                <label><?php echo lang('smtpport')?></label>
                <input required ng-model="settings_detail.smtpport">
              </md-input-container>
              <md-input-container class="md-block">
                <label><?php echo lang('emailcharset')?></label>
                <input required ng-model="settings_detail.emailcharset">
              </md-input-container>
            </div>
            <div class="col-md-6">
              <md-input-container class="md-block">
                <label><?php echo lang('smtpusername')?></label>
                <input required ng-model="settings_detail.smtpusername">
              </md-input-container>
              <!-- <md-input-container class="md-block">
                <label><?php //echo lang('password')?></label>
                <input required ng-model="settings_detail.smtppassoword">
              </md-input-container> -->
              <md-input-container class="md-block password-input">
                <label><?php echo lang('password') ?></label>
                <input type="text" required ng-model="settings_detail.smtppassoword">
                <md-icon ng-click="seePasswordModal()" class="ion-eye" style="display:inline-block;">
                  <md-tooltip md-direction="top"><?php echo lang('view_password'); ?></md-tooltip>
                </md-icon>
              </md-input-container>
              <md-input-container class="md-block">
                <label><?php echo lang('sender_email')?></label>
                <input required ng-model="settings_detail.sendermail">
              </md-input-container>
              <md-input-container class="md-block">
                <label><?php echo lang('sender_name')?></label>
                <input required ng-model="settings_detail.sender_name">
              </md-input-container>
            </div>
          </md-content>
          <md-card flex-xs flex-gt-xs="30" layout="column" class="md-padding" style="margin-left: 1.8%;">
            <md-input-container class="md-block">
              <label><?php echo lang('email')?></label>
              <input type="email" required ng-model="settings_detail.testEmail" minlength="10" maxlength="100" ng-pattern="/^.+@.+\..+$/">
            </md-input-container>
            <section layout="row" layout-sm="column" layout-align="center center" layout-wrap>
              <md-button ng-click="sendTestEmail()" class="md-raised md-primary pull-right" ng-disabled="sendingTestEmail == true">
                <span ng-hide="sendingTestEmail == true"><?php echo lang('sendtestmail');?></span>
                <md-progress-circular class="white" ng-show="sendingTestEmail == true" md-mode="indeterminate" md-diameter="20"></md-progress-circular>
              </md-button>
            </section>
          </md-card>
        </md-tab>
        <md-tab label="<?php echo lang('customization'); ?>">
          <md-content class="md-padding bg-white">
            <?php echo form_open_multipart('settings/save_config/',array("class"=>"form-horizontal", "method" => "POST")); ?>
            <div class="col-md-6">
              <div class="col-md-12 md-p-0 upload-logo">
                <div layout="row" layout-wrap>
                  <div flex-gt-xs="50" flex-xs="100">
                    <div flex="100">
                      <h2 class="md-title"><?php echo lang('applogo'); ?></h2>
                    </div>
                    <div flex="100">
                      <div class="img">
                        <img ng-src="<?php echo base_url('uploads/ciuis_settings/{{settings_detail.app_logo}}')?>" on-error-src="<?php echo base_url('assets/img/placeholder.png')?>">
                      </div>
                      <span><?php echo lang('applogoinfo') ?></span>
                      <div flex-gt-xs="50" flex-xs="100" class="file-upload">
                        <input type="file" name="applogo" class="file-type" accept="image/*">
                      </div>
                    </div>
                  </div>
                  <div flex-gt-xs="50" flex-xs="100">
                    <div flex="100">
                      <h2 class="md-title"><?php echo lang('navlogo'); ?></h2>
                    </div>
                    <div flex="100">
                      <div class="img">
                        <img ng-src="<?php echo base_url('uploads/ciuis_settings/{{settings_detail.logo}}')?>" on-error-src="<?php echo base_url('assets/img/placeholder.png')?>">
                      </div>
                      <span><?php echo lang('reommended_size'); ?> 42 x 42 px.</span>
                      <div flex-gt-xs="50" flex-xs="100" class="file-upload">
                        <input type="file" name="navlogo" class="file-type" accept="image/*">
                      </div>
                    </div>
                  </div>
                </div>
                <div layout="row" layout-wrap>
                  <div flex-gt-xs="50" flex-xs="100">
                    <div flex="100">
                      <h2 class="md-title"><?php echo lang('admin_login_image'); ?></h2>
                    </div>
                    <div flex="100">
                      <div class="login_image">
                        <img ng-src="<?php echo base_url('assets/img/images/{{rebrand.admin_login_image}}')?>" on-error-src="<?php echo base_url('assets/img/placeholder.png')?>">
                      </div>
                      <span><?php echo lang('reommended_size'); ?> 1100 x 1600 px.</span>
                      <div flex-gt-xs="50" flex-xs="100" class="file-upload">
                        <input type="file" name="admin_login_image" class="file-type" accept="image/*">
                      </div>
                    </div>
                  </div>
                  <div flex-gt-xs="50" flex-xs="100">
                    <div flex="100">
                      <h2 class="md-title"><?php echo lang('client_login_image'); ?></h2>
                    </div>
                    <div flex="100">
                      <div class="login_image">
                        <img ng-src="<?php echo base_url('assets/img/images/{{rebrand.client_login_image}}')?>" on-error-src="<?php echo base_url('assets/img/placeholder.png')?>">
                      </div>
                      <span><?php echo lang('reommended_size'); ?> 1100 x 1600 px.</span>
                      <div flex-gt-xs="50" flex-xs="100" class="file-upload">
                        <input type="file" name="client_login_image" class="file-type" accept="image/*">
                      </div>
                    </div>
                  </div>
                </div>
                <div layout="row" layout-wrap>
                  <div flex-gt-xs="50" flex-xs="100">
                    <div flex="100">
                      <h2 class="md-title"><?php echo lang('favicon'); ?></h2>
                      <div class="img">
                      <img ng-src="<?php echo base_url('assets/img/images/{{rebrand.favicon_icon}}')?>" on-error-src="<?php echo base_url('assets/img/placeholder.png')?>" style=
                      "max-height: 20px !important;padding: 1px !important;">
                      </div>
                      <span><?php echo lang('reommended_size'); ?> 16 x 16 px.</span>
                      <div flex-gt-xs="50" flex-xs="100" class="file-upload">
                        <input type="file" name="favicon" class="file-type" accept="image/*">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
               <md-input-container class="md-block">
                <label><?php echo lang('title')?></label>
                <input type="text" ng-model="rebrand.title" name="title">
              </md-input-container>
              <md-input-container class="md-block">
                <label><?php echo lang('admin_login_text')?></label>
                <textarea ng-model="rebrand.admin_login_text" name="admin_login_text"></textarea>
              </md-input-container>
              <md-input-container class="md-block">
                <label><?php echo lang('client_login_text')?></label>
                <textarea ng-model="rebrand.client_login_text" name="client_login_text"></textarea>
              </md-input-container>
              <md-input-container class="md-block">
                <label><?php echo lang('meta_keywords')?></label>
                <textarea ng-model="rebrand.meta_keywords" name="meta_keywords"></textarea>
              </md-input-container>
              <md-input-container class="md-block">
                <label><?php echo lang('meta_description')?></label>
                <textarea ng-model="rebrand.meta_description" name="meta_description"></textarea>
              </md-input-container>
              <md-input-container ng-show="rebrand.enable_support_button_on_client" class="md-block">
                <label><?php echo lang('support_button_title')?></label>
                <input ng-model="rebrand.support_button_title" type="text" name="support_button_title">
              </md-input-container>
              <md-input-container ng-show="rebrand.enable_support_button_on_client" class="md-block">
                <label><?php echo lang('support_button_link')?></label>
                <input ng-model="rebrand.support_button_link" type="text" name="support_button_link">
              </md-input-container>
              <md-input-container class="md-block" style="margin-top: unset;">
                <md-switch class="pull-left" ng-model="rebrand.enable_support_button_on_client" aria-label="Status" style="margin: unset;"><strong class="text-muted"><?php echo lang('support_button') ?></strong></md-switch>
              </md-input-container>
              <input type="hidden" name="support_button" value="{{rebrand.enable_support_button_on_client}}" ng-value="rebrand.enable_support_button_on_client">
              <md-input-container class="md-block">
                <md-button class="template-button" type="submit">
                  <span ng-hide="saving == true"><?php echo lang('save');?></span>
                </md-button>
              </md-input-container>
            </div>
            <br><br>
            <?php echo form_close(); ?>
          </md-content>
        </md-tab>
        <md-tab label="<?php echo lang('custom_fields');?>">
          <md-content class="bg-white" style="padding:0px">
            <md-toolbar class="toolbar-white">
              <div class="md-toolbar-tools">
                <h2 flex md-truncate class="pull-left" ><strong><?php echo lang('custom_fields') ?></strong></h2>
                <md-button ng-click="CreateCustomField()" class="md-icon-button md-primary" aria-label="New">
                  <md-tooltip md-direction="bottom"><?php echo lang('new_custom_field') ?></md-tooltip>
                  <md-icon><i class="ion-plus-round text-muted"></i></md-icon>
                </md-button>
              </div>
            </md-toolbar>
            <md-list style="padding:0px;">
              <div class="text-uppercase" style="height: 50px; background: #404040; padding: 15px; color: white;">
                <div class="col-md-3"><strong><?php echo lang('field_name') ?></strong></div>
                <div class="col-md-3"><strong><?php echo lang('belongs_to') ?></strong></div>
                <div class="col-md-3"><strong><?php echo lang('type') ?></strong></div>
                <div class="col-md-3 text-right"><strong><?php echo lang('actions') ?></strong></div>
              </div>
              <md-list-item ng-click="Do" class="secondary-button-padding" ng-repeat="field in custom_fields">
                <div class="col-md-3" ><strong ng-bind="field.name"></strong></div>
                <div class="col-md-3" style="padding-left: 38px;"><strong class="text-uppercase" ng-bind="field.relation"></strong></div>
                <div class="col-md-3" style="padding-left: 60px;"><strong class="text-uppercase" ng-bind="field.type"></strong></div>
                <div class="col-md-3">
                  <md-switch style="float: right; margin: 0px;" ng-change="UpdateCustomFieldStatus(field.id,field.active)" ng-model="field.active" aria-label="Status"><?php echo lang('active') ?></md-switch>
                  <md-button ng-click="RemoveCustomField($index)" class="md-icon-button md-secondary" aria-label="New">
                    <md-tooltip md-direction="bottom"><?php echo lang('remove_field') ?></md-tooltip>
                    <md-icon><i class="ion-trash-b text-muted"></i></md-icon>
                  </md-button>
                  <md-button ng-click='GetFieldDetail(field.id); FieldDetail()' class="md-icon-button md-secondary" aria-label="New">
                    <md-tooltip md-direction="bottom"><?php echo lang('edit_field') ?></md-tooltip>
                    <md-icon><i class="ion-ios-gear text-muted"></i></md-icon>
                  </md-button>
                </div>
                <md-divider></md-divider>
              </md-list-item>
            </md-list>
          </md-content>
        </md-tab>
        <md-tab label="<?php echo lang('security'); ?>">
          <md-content class="md-padding bg-white">
            <div class="col-md-6">
              <div class="col-md-12 md-p-0">
                <h4><strong><?php echo lang('two_factor_authentication') ?></strong></h4>
                <span><?php echo lang('two_factor_authentication_description') ?></span>
                <!-- <hr> -->
                <ul>
                  <li><a href="https://www.google.com/landing/2step/" rel="nofollow"><?php echo lang('google_2_step_ver') ?></a></li>
                  <li><?php echo lang('google_authenticator') ?>:
                    <ul>
                      <li><a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" rel="nofollow"><?php echo lang('android_app') ?></a></li>
                      <li><a href="https://itunes.apple.com/us/app/google-authenticator/id388497605?mt=8" rel="nofollow"><?php echo lang('iphone_ipad') ?></a></li>
                      <li><a href="http://m.google.com/authenticator" rel="nofollow"><?php echo lang('blackberry_app') ?></a></li>
                    </ul>
                  </li>
                </ul>
              </div>
              <div class="col-md-12 md-p-0">
                <md-switch class="pull-left" ng-model="settings_detail.two_factor_authentication" aria-label="Status"><strong class="text-muted"><?php echo lang('two_factor_authentication') ?></strong></md-switch>
              </div>
            </div>
            <div class="col-md-6">
              <md-input-container class="md-block">
                <label><?php echo lang('acceptedfileformats')?></label>
                <input required ng-model="settings_detail.accepted_files_formats">
              </md-input-container>
              <md-input-container class="md-block">
                <label><?php echo lang('allowedipaddereses')?></label>
                <input required ng-model="settings_detail.allowed_ip_adresses">
              </md-input-container>
              <div class="col-md-12 md-p-0">
                <md-switch disabled class="pull-left" ng-model="settings_detail.pushState" aria-label="Status"><strong class="text-muted"><?php echo lang('pushstate') ?></strong></md-switch>
                <md-switch class="pull-left" ng-model="settings_detail.voicenotification" aria-label="Status"><strong class="text-muted"><?php echo lang('voicenotifications') ?></strong></md-switch>
                <md-switch class="pull-left" ng-model="settings_detail.is_mysql" aria-label="Status"><strong class="text-muted"><?php echo lang('mysql_queries') ?></strong></md-switch>
              </div>
            </div>
          </md-content>
        </md-tab>
        <md-tab label="<?php echo lang('paymentgateway'); ?>">
          <md-content class="md-padding bg-white">
            <div class="col-md-12">
            <div class="col-md-2">
            </div>
            <div class=" col-md-10">
            <ul class="custom-ciuis-list-body" style="padding: 0px;">
              <li class="ciuis-custom-list-item ciuis-special-list-item" style="margin-bottom: 1%" ng-click="paymentGateway('paypal')">
                <ul class="list-item-for-custom-list">
                  <li class="ciuis-custom-list-item-item col-md-12">
                    <div class="col-md-3">
                      <div class="assigned-staff-for-this-lead">
                        <img src="<?php echo base_url('assets/img/payment-modes/paypal.png')?>">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <small class="text-muted text-uppercase"><?php echo lang('gateway_name') ?></small><br>
                      <strong>PayPal</strong>
                    </div>
                    <div class="col-md-3">
                      <small class="text-muted text-uppercase"><?php echo lang('status') ?></small><br>
                      <strong ng-show="payment.paypal_active" class="badge green"><?php echo lang('active'); ?></strong>
                      <strong ng-show="!payment.paypal_active" class="badge red"><?php echo lang('inactive'); ?></strong>
                    </div>
                    <div class="col-md-3">
                      <small class="text-muted text-uppercase"><?php echo lang('action') ?></small><br>
                      <md-icon md-menu-align-target class="ion-ios-compose"></md-icon>
                    </div>
                  </li>
                </ul>
              </li>
              <li class="ciuis-custom-list-item ciuis-special-list-item" style="margin-bottom: 1%">
                <ul class="list-item-for-custom-list">
                  <li class="ciuis-custom-list-item-item col-md-12" ng-click="paymentGateway('ccavenue')">
                    <div class="col-md-3">
                      <div class="assigned-staff-for-this-lead">
                        <img src="<?php echo base_url('assets/img/payment-modes/ccavenue.png')?>">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <small class="text-muted text-uppercase"><?php echo lang('gateway_name') ?></small><br>
                      <strong>CCAvenue</strong>
                    </div>
                    <div class="col-md-3">
                      <small class="text-muted text-uppercase"><?php echo lang('status') ?></small><br>
                      <strong ng-show="payment.ccavenue_active" class="badge green"><?php echo lang('active'); ?></strong>
                      <strong ng-show="!payment.ccavenue_active" class="badge red"><?php echo lang('inactive'); ?></strong>
                    </div>
                    <div class="col-md-3">
                      <small class="text-muted text-uppercase"><?php echo lang('action') ?></small><br>
                      <md-icon md-menu-align-target class="ion-ios-compose"></md-icon>
                    </div>
                  </li>
                </ul>
              </li>
              <li class="ciuis-custom-list-item ciuis-special-list-item" style="margin-bottom: 1%">
                <ul class="list-item-for-custom-list">
                  <li class="ciuis-custom-list-item-item col-md-12" ng-click="paymentGateway('payumoney')">
                    <div class="col-md-3">
                      <div class="assigned-staff-for-this-lead">
                        <img src="<?php echo base_url('assets/img/payment-modes/payumoney.jpg')?>">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <small class="text-muted text-uppercase"><?php echo lang('gateway_name') ?></small><br>
                      <strong>PayUmoney</strong>
                    </div>
                    <div class="col-md-3">
                      <small class="text-muted text-uppercase"><?php echo lang('status') ?></small><br>
                      <strong ng-show="payment.payu_money_active" class="badge green"><?php echo lang('active'); ?></strong>
                      <strong ng-show="!payment.payu_money_active" class="badge red"><?php echo lang('inactive'); ?></strong>
                    </div>
                    <div class="col-md-3">
                      <small class="text-muted text-uppercase"><?php echo lang('action') ?></small><br>
                      <md-icon md-menu-align-target class="ion-ios-compose"></md-icon>
                    </div>
                  </li>
                </ul>
              </li>
              <li class="ciuis-custom-list-item ciuis-special-list-item" style="margin-bottom: 1%">
                <ul class="list-item-for-custom-list">
                  <li class="ciuis-custom-list-item-item col-md-12" ng-click="paymentGateway('stripe')">
                    <div class="col-md-3">
                      <div class="assigned-staff-for-this-lead">
                        <img src="<?php echo base_url('assets/img/payment-modes/stripe.png')?>">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <small class="text-muted text-uppercase"><?php echo lang('gateway_name') ?></small><br>
                      <strong>Stripe</strong>
                    </div>
                    <div class="col-md-3">
                      <small class="text-muted text-uppercase"><?php echo lang('status') ?></small><br>
                      <strong ng-show="payment.stripe_active" class="badge green"><?php echo lang('active'); ?></strong>
                      <strong ng-show="!payment.stripe_active" class="badge red"><?php echo lang('inactive'); ?></strong>
                    </div>
                    <div class="col-md-3">
                      <small class="text-muted text-uppercase"><?php echo lang('action') ?></small><br>
                      <md-icon md-menu-align-target class="ion-ios-compose"></md-icon>
                    </div>
                  </li>
                </ul>
              </li>
              <li class="ciuis-custom-list-item ciuis-special-list-item" style="margin-bottom: 1%">
                <ul class="list-item-for-custom-list">
                  <li class="ciuis-custom-list-item-item col-md-12" ng-click="paymentGateway('authorize')">
                    <div class="col-md-3">
                      <div class="assigned-staff-for-this-lead">
                        <img src="<?php echo base_url('assets/img/payment-modes/authorize.jpg')?>">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <small class="text-muted text-uppercase"><?php echo lang('gateway_name') ?></small><br>
                      <strong>Authorize.Net AIM</strong>
                    </div>
                    <div class="col-md-3">
                      <small class="text-muted text-uppercase"><?php echo lang('status') ?></small><br>
                      <strong ng-show="payment.authorize_aim_active" class="badge green"><?php echo lang('active'); ?></strong>
                      <strong ng-show="!payment.authorize_aim_active" class="badge red"><?php echo lang('inactive'); ?></strong>
                    </div>
                    <div class="col-md-3">
                      <small class="text-muted text-uppercase"><?php echo lang('action') ?></small><br>
                      <md-icon md-menu-align-target class="ion-ios-compose"></md-icon>
                    </div>
                  </li>
                </ul>
              </li>
              <li class="ciuis-custom-list-item ciuis-special-list-item" style="margin-bottom: 1%">
                <ul class="list-item-for-custom-list">
                  <li class="ciuis-custom-list-item-item col-md-12" ng-click="paymentGateway('razorpay')">
                    <div class="col-md-3">
                      <div class="assigned-staff-for-this-lead">
                        <img style="max-height: 38px;" src="<?php echo base_url('assets/img/payment-modes/razorpay.png')?>">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <small class="text-muted text-uppercase"><?php echo lang('gateway_name') ?></small><br>
                      <strong><?php echo lang('razorpay')?></strong>
                    </div>
                    <div class="col-md-3">
                      <small class="text-muted text-uppercase"><?php echo lang('status') ?></small><br>
                      <strong ng-show="payment.authorize_aim_active" class="badge green"><?php echo lang('active'); ?></strong>
                      <strong ng-show="!payment.authorize_aim_active" class="badge red"><?php echo lang('inactive'); ?></strong>
                    </div>
                    <div class="col-md-3">
                      <small class="text-muted text-uppercase"><?php echo lang('action') ?></small><br>
                      <md-icon md-menu-align-target class="ion-ios-compose"></md-icon>
                    </div>
                  </li>
                </ul>
              </li>
              <li class="ciuis-custom-list-item ciuis-special-list-item" style="margin-bottom: 1%">
                <ul class="list-item-for-custom-list">
                  <li class="ciuis-custom-list-item-item col-md-12" ng-click="paymentGateway('bank')">
                    <div class="col-md-3">
                      <div class="assigned-staff-for-this-lead">
                        <img style="max-height: 38px;" src="<?php echo base_url('assets/img/payment-modes/bank.png')?>">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <small class="text-muted text-uppercase"><strong><?php echo lang('default_bank') ?></strong></small><br>
                      <strong>
                        <span ng-repeat="account in accounts">
                          <span ng-if="account.id === payment.primary_bank_account">{{account.name}}</span>
                        </span>
                      </strong>
                    </div>
                    <div class="col-md-3">
                      <small class="text-muted text-uppercase"><?php echo lang('status') ?></small><br>
                      <strong\ class="badge green"><?php echo lang('active'); ?></strong>
                    </div>
                    <div class="col-md-3">
                      <small class="text-muted text-uppercase"><?php echo lang('action') ?></small><br>
                      <md-icon md-menu-align-target class="ion-ios-compose"></md-icon>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
            <!-- <div class="col-md-6">
              <md-input-container class="md-block">
                <label><?php echo lang('paypalemail')?></label>
                <input  ng-model="settings_detail.paypalemail">
              </md-input-container>
              <md-switch class="pull-left" ng-model="settings_detail.paypalenable" aria-label="Status"> <strong class="text-muted"><?php echo lang('enable') ?></strong> </md-switch>
              <md-switch class="pull-left" ng-model="settings_detail.paypalsandbox" aria-label="Status"> <strong class="text-muted"><?php echo lang('paypalsandbox') ?></strong> </md-switch>
            </div>
            <div class="col-md-6">
              <md-input-container class="md-block">
                <label><?php echo lang('paypalcurrency')?></label>
                <input  ng-model="settings_detail.paypalcurrency">
              </md-input-container>
              <md-input-container  class="md-block">
                <label><strong><?php echo lang('paypal_payment_account') ?></strong></label>
                <md-select ng-model="settings_detail.paypal_record_account">
                  <md-option ng-value="account.id" ng-repeat="account in accounts">{{account.name}}</md-option>
                </md-select>
              </md-input-container>
            </div> -->
          </div>
          </div>
          </md-content>
          <!-- <md-subheader class="md-primary"><?php echo lang('authorize_aim') ?></md-subheader>
          <md-content class="md-padding bg-white">
            <div class="col-md-6">
              <md-input-container class="md-block">
                <label><?php echo lang('api_login_id') ?></label>
                <input  ng-model="settings_detail.authorize_login_id">
              </md-input-container>
              <md-switch class="pull-left" ng-model="settings_detail.authorize_enable" aria-label="Status"> <strong class="text-muted"><?php echo lang('enable') ?></strong> </md-switch>
            </div>
            <div class="col-md-6">
              <md-input-container class="md-block">
                <label><?php echo lang('api_transaction_id') ?></label>
                <input  ng-model="settings_detail.authorize_transaction_key">
              </md-input-container>
              <md-input-container  class="md-block">
                <label><strong><?php echo lang('authorize_payment_account') ?></strong></label>
                <md-select ng-model="settings_detail.authorize_record_account">
                  <md-option ng-value="account.id" ng-repeat="account in accounts">{{account.name}}</md-option>
                </md-select>
              </md-input-container>
            </div>
          </md-content> -->
        </md-tab>
        <md-tab label="<?php echo lang('cron_job'); ?>">
          <md-content class="md-padding bg-white">
            <div class="form-group clearfix">
              <label class=" col-md-2"><?php echo lang('cron_job_link') ?></label>
              <div class=" col-md-10"><?php echo base_url('CronJob/run') ?></div>
            </div>
            <div class="form-group clearfix">
              <label class=" col-md-2"><?php echo lang('recommended_execution_interval') ?></label>
              <div class=" col-md-10"><?php echo  lang('every_one_hour'); ?></div>
            </div>
            <label class=" col-md-2"><?php echo lang('cpanel_cron_job_command') ?></label>
            <div class=" col-md-10">
              <div>
                <p>
                  <pre class="editorField">wget <?php echo base_url('CronJob/run') ?></pre>
                </p>
                <p>
                  <pre class="editorField">wget <?php echo base_url('CronJob/emails') ?></pre>
                </p>
              </div>
              <hr>
              <div>
                <p>
                  <pre class="editorField"><strong class="text-danger"><?php echo lang('or') ?></strong> wget -q -O- <?php echo base_url('CronJob/run') ?></pre>
                </p>
                <p>
                  <pre class="editorField"><strong class="text-danger"><?php echo lang('or') ?></strong> wget -q -O- <?php echo base_url('CronJob/emails') ?></pre>
                </p>
              </div>
            </div>
          </md-content>
        </md-tab>
        <md-tab label="<?php echo lang('backup'); ?>">
          <md-content class="md-padding bg-white" style="">
            <div class="col-md-12">
            <div class="form-group clearfix" style="border-bottom: 1px solid gray;">
              <h4 class="pull-left" ><strong><?php echo lang('backuprestore'); ?></strong></h4>
              <md-button ng-click="BackupDatabase()" class="md-raised md-primary pull-right successButton">
                <md-icon>
                  <i class="ion-archive"></i>
                </md-icon> 
                <?php echo lang('backupdatabase');?>
              </md-button>
              <md-button ng-click="RestoreDatabase()" class="md-raised md-primary pull-right successButton">
              <md-icon>
                <i class="ion-android-upload"></i>
              </md-icon> 
              <?php echo lang('restoredatabase');?>
            </md-button>
            <md-button ng-click="uploadAppFiles()" class="md-raised md-primary pull-right successButton">
              <md-icon>
                <i class="ion-android-upload"></i>
              </md-icon> 
              <?php echo lang('debug');?>
            </md-button>
            <md-button ng-click="RunMySQL()" class="md-raised md-primary pull-right successButton"  ng-show="settings_detail.is_mysql == '1'">
              <?php echo lang('mysql');?>
            </md-button>
            <md-button ng-click="systemInfo()" class="md-raised md-primary pull-right successButton">
              <md-icon>
                <i class="ion-information-circled"></i>
              </md-icon> 
              <?php echo lang('system');?>
            </md-button>
          </div>
          <md-list style="padding:0px;" ng-show="db_backup.length > 0">
            <div class="text-uppercase" style="height: 50px; background: #404040; padding: 15px; color: white;">
              <div class="col-md-3"><strong><?php echo lang('filename');?></strong></div>
                <div class="col-md-3"><strong><?php echo lang('version');?></strong></div>
                <div class="col-md-3"><strong><?php echo lang('date');?></strong></div>
                <div class="col-md-3 text-right"><strong><?php echo lang('actions');?></strong></div>
              </div>
              <md-list-item class="secondary-button-padding" ng-repeat="backup in db_backup">
                <div class="col-md-3" style="padding-left: 38px;"><strong class="text-uppercase" ng-bind="backup.filename"></strong></div>
                <div class="col-md-3" style="padding-left: 38px;"><strong class="text-uppercase" ng-bind="backup.version"></strong></div>
                <div class="col-md-3">
                  <span ng-bind="backup.created | date :  'dd.MM.y'" class="ng-binding"></span>
                </div>
                <div class="col-md-3">
                  <md-button ng-click="RestoreBackup(backup.id)" class="md-icon-button md-secondary" aria-label="New">
                    <md-tooltip md-direction="top"><?php echo lang('restorethisfile');?></md-tooltip>
                    <md-icon><i class="ion-android-upload text-success"></i></md-icon>
                  </md-button>
                  <md-button ng-click="RemoveBackup(backup.id)" class="md-icon-button md-secondary" aria-label="New">
                    <md-tooltip md-direction="bottom"><?php echo lang('deletebackup');?></md-tooltip>
                    <md-icon><i class="ion-trash-b text-muted"></i></md-icon>
                  </md-button>
                  <a href="<?php echo base_url('settings/download_backup/{{backup.filename}}.zip')?>" class="md-icon-button md-secondary">
                    <md-tooltip md-direction="bottom"><?php echo lang('download');?></md-tooltip>
                    <md-icon><i class="ion-archive text-muted"></i></md-icon>
                  </a>
                </div>
                <md-divider></md-divider>
              </md-list-item>
            </md-list>
          </div>
        </md-content>
        </md-tab>
        <md-tab label="<?php echo lang('embed'); ?>">
          <md-content class="md-padding bg-white">
            <div class="col-md-6">
              <md-input-container class="md-block">
                <label><?php echo lang('custom') . ' '.lang('css')?></label>
                <textarea id="custom_css" rows="6" placeholder="<?php echo lang('custom_css_note')?>">
                  <?php if(ini_get('allow_url_fopen')) { echo file_get_contents(base_url('assets/css/custom_css.css')); } ?>
                </textarea>
              </md-input-container>
            </div>
            <div class="col-md-6">
              <md-input-container class="md-block">
                <label><?php echo lang('custom'). ' '.lang('header') . ' '.lang('script')?></label>
                <textarea id="custom_header_js" rows="3" placeholder="<?php echo lang('header_js_note')?>">
                  <?php if(ini_get('allow_url_fopen')) { echo file_get_contents(base_url('assets/js/custom_header_js.txt')); } ?> 
                </textarea>
              </md-input-container>
              <md-input-container class="md-block">
                <label><?php echo lang('custom'). ' '.lang('footer') . ' '.lang('script')?></label>
                <textarea id="custom_footer_js" rows="3" placeholder="<?php echo lang('footer_js_note')?>">
                  <?php if(ini_get('allow_url_fopen')) { echo file_get_contents(base_url('assets/js/custom_footer_js.txt')); } ?>
                </textarea>
              </md-input-container>
            </div>
          </md-content>
        </md-tab>
      </md-tabs>
    </md-content>

    <md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="CreateCustomField">
      <md-toolbar class="toolbar-white">
        <div class="md-toolbar-tools">
          <md-button ng-click="close()" class="md-icon-button" aria-label="Close"> <i class="ion-android-arrow-forward"></i> </md-button>
          <md-truncate><?php echo lang('new_custom_field'); ?></md-truncate>
        </div>
      </md-toolbar>
      <md-content layout-padding>
        <md-input-container class="md-block">
          <label><?php echo lang('field_name'); ?></label>
          <input required ng-model="new_custom_field.name">
        </md-input-container>
        <md-input-container class="md-block" style=" margin-bottom: 40px; ">
          <label><?php echo lang('field_belogns_to'); ?></label>
          <md-select ng-model="new_custom_field.relation">
            <md-option ng-value="relation.relation" ng-repeat="relation in custom_fields_relation_types">{{relation.name}}</md-option>
          </md-select>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('field_order'); ?></label>
          <input type="number" required ng-model="new_custom_field.order">
        </md-input-container>
        <md-input-container class="md-block" style=" margin-bottom: 40px; ">
          <label><?php echo lang('field_type'); ?></label>
          <md-select ng-model="new_custom_field.type">
            <md-option ng-value="type.type" ng-repeat="type in custom_fields_types">{{type.name}}</md-option>
          </md-select>
        </md-input-container>
        <md-input-container class="md-icon-float md-icon-right md-block" ng-if="new_custom_field.type === 'select'">
          <input ng-model="new_custom_field.new_option_name" placeholder="Type option name">
          <md-icon ng-click="AddOption()" class="ion-ios-checkmark"></md-icon>
        </md-input-container>
        <md-list ng-if="new_custom_field.type === 'select'" class="bg-white">
          <md-list-item class="md-2-line" ng-repeat="option in select_options" style="max-height: 48px !important; height: 48px !important; min-height: 48px !important; padding: 0px;">
            <div class="md-list-item-text">
              <h3> {{ option.name }} </h3>
            </div>
            <md-button class="md-secondary md-icon-button" ng-click='RemoveOption($index)' aria-label="call">
              <md-icon class="ion-trash-a"></md-icon>
            </md-button>
            <md-divider></md-divider>
          </md-list-item>
        </md-list>
        {{custom_fields.select_options}}
        <md-switch class="pull-left" ng-model="new_custom_field.permission" aria-label="Status">Only Admin</md-switch>
        <section layout="row" layout-sm="column" layout-align="center center" layout-wrap>
          <md-button ng-click="AddCustomField()" class="md-raised md-primary pull-right"><?php echo lang('create');?></md-button>
        </section>
      </md-content>
    </md-sidenav>

    <md-sidenav class="md-sidenav-right md-whiteframe-4dp" [fixedInViewport]="true" md-component-id="RestoreDatabaseNav">
      <md-toolbar class="md-theme-light" style="background:#262626">
        <div class="md-toolbar-tools">
        <md-button ng-click="close()" class="md-icon-button" aria-label="Close"><i class="ion-android-arrow-forward"></i></md-button>
        <md-truncate><?php echo lang('restoredatabase') ?></md-truncate>
        </div>
      </md-toolbar>
      <md-content>
      <?php echo form_open_multipart('settings/restore_database'); ?>
        <div class="modal-body">
          <div class="form-group">
            <label for="name">
              <?php echo lang('backup_file_msg'); ?>
            </label>
            <div class="file-upload">
              <div class="file-select">
                <div class="file-select-button" id="fileName"><span class="mdi mdi-accounts-list-alt"></span>
                  <?php echo lang('attachment')?>
                </div>
                <div class="file-select-name" id="noFile">
                  <?php echo lang('notchoise')?>
                </div>
                <input type="file" name="upload_file" id="chooseFile" required="" accept="application/zip,application/x-zip,application/x-zip-compressed,application/octet-stream">
              </div>
            </div>
          </div>
          <br>
          <div class="well well-sm"><?php echo lang('backup_msg'); ?></div>
        </div>
        <div class="modal-footer">
          <button type="submit" ng-click="Restoring()" class="btn btn-default"><?php echo lang('save'); ?></button>
        </div>
      <?php echo form_close(); ?> 
      </md-content>
    </md-sidenav>

    <md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="FieldDetail">
      <md-toolbar class="toolbar-white">
        <div class="md-toolbar-tools">
          <md-button ng-click="close()" class="md-icon-button" aria-label="Close"> <i class="ion-android-arrow-forward"></i> </md-button>
          <md-truncate><?php echo lang('field_details'); ?></md-truncate>
        </div>
      </md-toolbar>
      <md-content layout-padding>
        <md-input-container class="md-block">
          <label><?php echo lang('field_name'); ?></label>
          <input required ng-model="selected_field.name">
        </md-input-container>
        <md-input-container class="md-block" style=" margin-bottom: 40px; ">
          <label><?php echo lang('field_belogns_to'); ?></label>
          <md-select ng-model="selected_field.relation">
            <md-option ng-value="relation.relation" ng-repeat="relation in custom_fields_relation_types">{{relation.name}}</md-option>
          </md-select>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('field_order'); ?></label>
          <input type="number" required ng-model="selected_field.order">
        </md-input-container>
        <md-input-container class="md-block" style=" margin-bottom: 40px; ">
          <label><?php echo lang('field_type'); ?></label>
          <md-select ng-model="selected_field.type">
            <md-option ng-value="type.type" ng-repeat="type in custom_fields_types">{{type.name}}</md-option>
          </md-select>
        </md-input-container>
        <md-input-container class="md-icon-float md-icon-right md-block" ng-if="selected_field.type === 'select'">
          <input ng-model="selected_field.new_option_name" placeholder="Type option name">
          <md-icon ng-click="AddOptionToField()" class="ion-ios-checkmark"></md-icon>
        </md-input-container>
        <md-list ng-if="selected_field.id === 1" class="bg-white">
          <md-list-item class="md-2-line" ng-repeat="option in selected_field.data" style="max-height: 48px !important; height: 48px !important; min-height: 48px !important; padding: 0px;">
            <div class="md-list-item-text">
              <h3> {{ option.name }} </h3>
            </div>
            <md-button class="md-secondary md-icon-button" ng-click='RemoveFieldOption($index)' aria-label="call">
              <md-icon class="ion-trash-a"></md-icon>
            </md-button>
            <md-divider></md-divider>
          </md-list-item>
        </md-list>
        {{custom_fields.select_options}}
        <md-switch class="pull-left" ng-model="selected_field.permission" aria-label="Status"><?php echo lang('only_admin');?></md-switch>
        <section layout="row" layout-sm="column" layout-align="center center" layout-wrap>
          <md-button ng-click="UpdateCustomField()" class="md-raised md-primary pull-right"><?php echo lang('update');?></md-button>
        </section>
      </md-content>
    </md-sidenav>

    <md-sidenav class="md-sidenav-right md-whiteframe-4dp" [fixedInViewport]="true" md-component-id="uploadAppFiles">
      <md-toolbar class="md-theme-light" style="background:#262626">
        <div class="md-toolbar-tools">
        <md-button ng-click="close()" class="md-icon-button" aria-label="Close"><i class="ion-android-arrow-forward"></i></md-button>
        <md-truncate><?php echo lang('uploadappfiles') ?></md-truncate>
        </div>
      </md-toolbar>
      <md-content>
      <?php echo form_open_multipart('settings/replace_files'); ?>
        <div class="modal-body">
          <div class="form-group">
            <label for="name">
              <?php echo lang('uploadappfiles'); ?>
            </label>
            <div class="file-upload">
              <div class="file-select">
                <div class="file-select-button" id="fileName"><span class="mdi mdi-upload"></span>
                  <?php echo lang('attachment')?>
                </div>
                <div class="file-select-name" id="noFile">
                  <?php echo lang('notchoise')?>
                </div>
                <input type="file" name="upload_file" id="chooseFile" required="" accept="application/zip,application/x-zip,application/x-zip-compressed,application/octet-stream">
              </div>
            </div>
          </div>
          <br>
          <div class="well well-sm"><?php echo lang('uploadappfiles_msg'); ?></div>
        </div>
        <div class="modal-footer">
          <button type="submit" ng-click="adding()" class="btn btn-default"><?php echo lang('upload'); ?></button>
        </div>
      <?php echo form_close(); ?> 
      </md-content>
    </md-sidenav>

    <md-sidenav class="md-sidenav-right md-whiteframe-4dp" [fixedInViewport]="true" md-component-id="RunMySQL" ng-show="settings_detail.is_mysql == '1'">
      <md-toolbar class="md-theme-light" style="background:#262626">
        <div class="md-toolbar-tools">
        <md-button ng-click="close()" class="md-icon-button" aria-label="Close"><i class="ion-android-arrow-forward"></i></md-button>
        <md-truncate><?php echo lang('mysql_queries') ?></md-truncate>
        </div>
      </md-toolbar>
      <md-content layout-padding>
        <md-input-container class="md-block">
          <textarea rows="2" ng-model="mysql_query" placeholder="<?php echo lang('write_sql'); ?>"></textarea>
        </md-input-container>
        <div class="well well-sm"><?php echo lang('mysql_queries_msg'); ?></div>
      </md-content>
      <md-content layout-padding>
        <section layout="row" layout-sm="column" layout-align="center center" layout-wrap>
          <md-button ng-click="RunMySQLQuery()" class="template-button" ng-disabled="executing == true">
            <span ng-hide="executing == true"><?php echo lang('execute');?></span>
            <md-progress-circular class="white" ng-show="executing == true" md-mode="indeterminate" md-diameter="20"></md-progress-circular>
          </md-button>
        </section>
      </md-content>
    </md-sidenav>

  </div> 
  <script type="text/ng-template" id="paypal.html">
    <md-dialog aria-label="Expense Detail">
      <md-toolbar class="toolbar-white">
        <div class="md-toolbar-tools">
          <h2><strong class="text-success"><?php echo lang('paypal') ?></strong></h2>
          <span flex></span>
          <md-switch ng-model="payment.paypal_active" aria-label="Type">
            <strong class="text-muted"><?php echo lang('active') ?></strong>
          </md-switch>
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
                <label><?php echo lang('paypalemail'); ?></label>
                <input ng-model="payment.paypal_username">
              </md-input-container>
            </md-list-item>
            <md-list-item>
              <md-input-container class="md-block full-width">
                <label><?php echo lang('paypalcurrency'); ?></label>
                <input ng-model="payment.paypal_currency">
              </md-input-container>
            </md-list-item>
            <md-list-item>
              <md-input-container  class="md-block full-width">
                <label><strong><?php echo lang('paypal_payment_account') ?></strong></label>
                <md-select ng-model="payment.paypal_payment_record_account">
                  <md-option ng-value="account.id" ng-repeat="account in accounts">{{account.name}}</md-option>
                </md-select>
              </md-input-container>
            </md-list-item>
            <md-list-item>
              <md-switch class="pull-left" ng-model="payment.paypal_test_mode_enabled" aria-label="Status"> <strong class="text-muted"><?php echo lang('paypalsandbox') ?></strong> </md-switch>
            </md-list-item>
            <md-divider>
            </md-divider>
            <br><br>
            <md-button ng-click="UpdatePaymentGateway('paypal')" class="template-button" ng-disabled="saving == true">
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

  <script type="text/ng-template" id="authorize.html">
    <md-dialog aria-label="Expense Detail">
      <md-toolbar class="toolbar-white">
        <div class="md-toolbar-tools">
          <h2><strong class="text-success"><?php echo lang('authorize_aim') ?></strong></h2>
          <span flex></span>
          <md-switch ng-model="payment.authorize_aim_active" aria-label="Type">
            <strong class="text-muted"><?php echo lang('active') ?></strong>
          </md-switch>
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
                <label><?php echo lang('authorize_aim_api_login_id'); ?></label>
                <input ng-model="payment.authorize_aim_api_login_id">
              </md-input-container>
            </md-list-item>
            <md-list-item>
              <md-input-container class="md-block full-width">
                <label><?php echo lang('authorize_aim_api_transaction_key'); ?></label>
                <input ng-model="payment.authorize_aim_api_transaction_key">
              </md-input-container>
            </md-list-item>
            <md-list-item>
              <md-input-container  class="md-block full-width">
                <label><strong><?php echo lang('authorize_aim'). ' '.lang('payment_account') ?></strong></label>
                <md-select ng-model="payment.authorize_aim_payment_record_account">
                  <md-option ng-value="account.id" ng-repeat="account in accounts">{{account.name}}</md-option>
                </md-select>
              </md-input-container>
            </md-list-item>
            <md-list-item>
              <md-switch class="pull-left" ng-model="payment.authorize_test_mode_enabled" aria-label="Status"> <strong class="text-muted"><?php echo lang('enable_test') ?></strong> </md-switch>
            </md-list-item>
            <md-divider>
            </md-divider>
            <br><br>
            <md-button ng-click="UpdatePaymentGateway('authorize_aim')" class="template-button" ng-disabled="saving == true">
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
  <script type="text/ng-template" id="ccavenue.html">
    <md-dialog>
      <md-toolbar class="toolbar-white">
        <div class="md-toolbar-tools">
          <h2><strong class="text-success"><?php echo lang('ccavenue') ?></strong></h2>
          <span flex></span>
          <md-switch ng-model="payment.ccavenue_active" aria-label="Type">
            <strong class="text-muted"><?php echo lang('active') ?></strong>
          </md-switch>
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
                <label><?php echo lang('ccavenue_marchent_key'); ?></label>
                <input ng-model="payment.ccavenue_marchent_key">
              </md-input-container>
            </md-list-item>
            <md-list-item>
              <md-input-container class="md-block full-width">
                <label><?php echo lang('ccavenue_working_key'); ?></label>
                <input ng-model="payment.ccavenue_working_key">
              </md-input-container>
            </md-list-item>
            <md-list-item>
              <md-input-container class="md-block full-width">
                <label><?php echo lang('ccavenue_access_code'); ?></label>
                <input ng-model="payment.ccavenue_access_code">
              </md-input-container>
            </md-list-item>
            <md-list-item>
              <md-input-container  class="md-block full-width">
                <label><strong><?php echo lang('ccavenue'). ' '.lang('payment_account') ?></strong></label>
                <md-select ng-model="payment.ccavenue_record_account">
                  <md-option ng-value="account.id" ng-repeat="account in accounts">{{account.name}}</md-option>
                </md-select>
              </md-input-container>
            </md-list-item>
            <md-list-item>
              <md-switch class="pull-left" ng-model="payment.ccavenue_test_mode" aria-label="Status"> <strong class="text-muted"><?php echo lang('enable_test') ?></strong> </md-switch>
            </md-list-item>
            <md-divider>
            </md-divider>
            <br><br>
            <md-button ng-click="UpdatePaymentGateway('ccavenue')" class="template-button" ng-disabled="saving == true">
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
  <script type="text/ng-template" id="payumoney.html">
    <md-dialog>
      <md-toolbar class="toolbar-white">
        <div class="md-toolbar-tools">
          <h2><strong class="text-success"><?php echo lang('payumoney') ?></strong></h2>
          <span flex></span>
          <md-switch ng-model="payment.payu_money_active" aria-label="Type">
            <strong class="text-muted"><?php echo lang('active') ?></strong>
          </md-switch>
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
                <label><?php echo lang('payu_money_key'); ?></label>
                <input ng-model="payment.payu_money_key">
              </md-input-container>
            </md-list-item>
            <md-list-item>
              <md-input-container class="md-block full-width">
                <label><?php echo lang('payu_money_salt'); ?></label>
                <input ng-model="payment.payu_money_salt">
              </md-input-container>
            </md-list-item>
            <md-list-item>
              <md-input-container  class="md-block full-width">
                <label><strong><?php echo lang('payumoney'). ' '.lang('payment_account') ?></strong></label>
                <md-select ng-model="payment.payu_money_record_account">
                  <md-option ng-value="account.id" ng-repeat="account in accounts">{{account.name}}</md-option>
                </md-select>
              </md-input-container>
            </md-list-item>
            <md-list-item>
              <md-switch class="pull-left" ng-model="payment.payu_money_test_mode_enabled" aria-label="Status"> <strong class="text-muted"><?php echo lang('enable_test') ?></strong> </md-switch>
            </md-list-item>
            <md-divider>
            </md-divider>
            <br><br>
            <md-button ng-click="UpdatePaymentGateway('payumoney')" class="template-button" ng-disabled="saving == true">
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
  <script type="text/ng-template" id="stripe.html">
    <md-dialog>
      <md-toolbar class="toolbar-white">
        <div class="md-toolbar-tools">
          <h2><strong class="text-success"><?php echo lang('stripe') ?></strong></h2>
          <span flex></span>
          <md-switch ng-model="payment.stripe_active" aria-label="Type">
            <strong class="text-muted"><?php echo lang('active') ?></strong>
          </md-switch>
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
                <label><?php echo lang('stripe_private_key'); ?></label>
                <input ng-model="payment.stripe_api_secret_key">
              </md-input-container>
            </md-list-item>
            <md-list-item>
              <md-input-container class="md-block full-width">
                <label><?php echo lang('stripe_public_key'); ?></label>
                <input ng-model="payment.stripe_api_publishable_key">
              </md-input-container>
            </md-list-item>
            <md-list-item>
              <md-input-container  class="md-block full-width">
                <label><strong><?php echo lang('stripe'). ' '.lang('payment_account') ?></strong></label>
                <md-select ng-model="payment.stripe_record_account">
                  <md-option ng-value="account.id" ng-repeat="account in accounts">{{account.name}}</md-option>
                </md-select>
              </md-input-container>
            </md-list-item>
            <md-list-item>
              <md-switch class="pull-left" ng-model="payment.stripe_test_mode_enabled" aria-label="Status"> <strong class="text-muted"><?php echo lang('enable_test') ?></strong> </md-switch>
            </md-list-item>
            <md-divider>
            </md-divider>
            <br><br>
            <md-button ng-click="UpdatePaymentGateway('stripe')" class="template-button" ng-disabled="saving == true">
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

  <script type="text/ng-template" id="razorpay.html">
    <md-dialog>
      <md-toolbar class="toolbar-white">
        <div class="md-toolbar-tools">
          <h2><strong class="text-success"><?php echo lang('razorpay') ?></strong></h2>
          <span flex></span>
          <md-switch ng-model="payment.razorpay_active" aria-label="Type">
            <strong class="text-muted"><?php echo lang('active') ?></strong>
          </md-switch>
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
                <label><?php echo lang('key_id'); ?></label>
                <input ng-model="payment.razorpay_key">
              </md-input-container>
            </md-list-item>
            <md-list-item>
              <md-input-container class="md-block full-width">
                <label><?php echo lang('razorpay_key_secret'); ?></label>
                <input ng-model="payment.razorpay_key_secret">
              </md-input-container>
            </md-list-item>
            <md-list-item>
              <md-input-container  class="md-block full-width">
                <label><strong><?php echo lang('razorpay'). ' '.lang('payment_account') ?></strong></label>
                <md-select ng-model="payment.razorpay_payment_record_account">
                  <md-option ng-value="account.id" ng-repeat="account in accounts">{{account.name}}</md-option>
                </md-select>
              </md-input-container>
            </md-list-item>
            <md-list-item>
              <md-switch class="pull-left" ng-model="payment.razorpay_test_mode_enabled" aria-label="Status"> <strong class="text-muted"><?php echo lang('enable_test') ?></strong> </md-switch>
            </md-list-item>
            <md-divider>
            </md-divider>
            <br><br>
            <md-button ng-click="UpdatePaymentGateway('razorpay')" class="template-button" ng-disabled="saving == true">
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

  <script type="text/ng-template" id="bank.html">
    <md-dialog>
      <md-toolbar class="toolbar-white">
        <div class="md-toolbar-tools">
          <h2><strong class="text-success"><?php echo lang('default_bank') ?></strong></h2>
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
              <md-input-container  class="md-block full-width">
                <label><strong><?php echo lang('select_account') ?></strong></label>
                <md-select ng-model="payment.primary_bank_account">
                  <md-option ng-value="account.id" ng-repeat="account in accounts">{{account.name}}</md-option>
                </md-select>
              </md-input-container>
            </md-list-item>
            <br><br>
            <md-button ng-click="UpdatePaymentGateway('stripe')" class="template-button" ng-disabled="saving == true">
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

  <script type="text/ng-template" id="see_smtp_password.html">
    <md-dialog>
      <md-toolbar class="toolbar-white">
        <div class="md-toolbar-tools">
          <h2><strong class="text-success"><?php echo lang('see_smtp_password') ?></strong></h2>
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
              <md-input-container  class="md-block full-width">
                <label><strong><?php echo lang('your_login_password') ?></strong></label>
                <input type="password" class="form-control" ng-model="your_login_password" placeholder="<?php echo lang('your_login_password'); ?>">
              </md-input-container>
            </md-list-item>
            <md-list-item ng-show="viewPassword == true">
              <md-input-container  class="md-block full-width">
                <p><?php echo lang('your_smtp_password') ?> <strong>{{final_smtp_password}}</strong></p>
              </md-input-container>
            </md-list-item>
            <br><br>
            <md-divider>
            </md-divider>
            <md-button ng-click="viewSMTPPassword()" class="template-button" ng-disabled="viewing == true">
              <span ng-hide="viewing == true"><?php echo lang('submit');?></span>
              <md-progress-circular class="white" ng-show="viewing == true" md-mode="indeterminate" md-diameter="20"></md-progress-circular>
            </md-button>
            <md-button ng-click="close()" class="">
              <span><?php echo lang('cancel');?></span>
            </md-button>
          </md-list>
        </md-content>     
      </md-dialog-content>
    </md-dialog>
  </script>

  <script type="text/ng-template" id="version-check-template.html">
  <md-dialog aria-label="options dialog">
    <?php echo form_open_multipart('settings/download_update/',array("class"=>"form-horizontal")); ?>
	<md-dialog-content layout-padding>
		<h2 class="md-title" style="border-bottom: 1px solid lightgray;">
      <?php echo lang('version_check'); ?>
    </h2>
		<span><?php echo lang('you_are_usign_version');?> 
    <strong><span>{{version_number}}</span></strong></span><a target="_blank" href="https://stellar.ladesk.com/325898-Change-Log" class="link">(<?php echo lang('view_changelog');?>)</a> <br>
    <span><?php echo lang('last_updated_on');?>:    
    <strong><span>{{versions.last_updated}}</span></strong></span>
    <p ng-show="msg=='Already updated'" class="text-success upto-date-message"><?php echo lang('up_to_date');?></p>
    <div ng-if="updated" style="border: 1px dotted #ececec;margin-top: 2%;padding-top: unset;">
      <h2 class="md-title"><?php echo lang('availableversion'); ?> {{ Version_latest }}</h2>
      <p ng-bind-html="version_log"></p>
      <p ng-bind-html="changeLog"></p> 
      <input type="hidden" name="version_number" value="{{Version_latest}}">
    </div>
	</md-dialog-content>
	<md-dialog-actions>
	  <span flex></span>
    <md-button ng-if="!updated" class="template-button" ng-click="checkForUpdates($event)" aria-label="Update">
      <span><?php echo lang('check_for_updates');?></span>
    </md-button>
    <md-button ng-if="updated" type="submit" ng-click="downloadUpdate($event)" class="template-button" aria-label="Update">
      <md-tooltip md-direction="top"><?php echo lang('download_update'); ?> </md-tooltip>
    <?php echo lang('update'); ?>
    </md-button>
	  <md-button ng-click="close()"><?php echo lang('close') ?>!</md-button>
	</md-dialog-actions>
  <?php echo form_close(); ?>
  </md-dialog>
</script> 

<script type="text/ng-template" id="checking.html">
  <md-dialog id="plz_wait" style="box-shadow:none;padding:unset;min-width: 25%;">
    <md-dialog-content layout="row" layout-margin layout-padding layout-align="center center" aria-label="wait">
      <md-progress-circular md-mode="indeterminate" md-diameter="40"></md-progress-circular>
      <span><?php echo lang('checking_for_updates') ?></span>
    </md-dialog-content> 
  </md-dialog>
</script>

<script type="text/ng-template" id="updating.html">
  <md-dialog id="updating" style="box-shadow:none;padding:unset;min-width: 25%;">
    <md-dialog-content layout-padding layout-align="center center" aria-label="wait" style="text-align: center;">
      <md-progress-circular md-mode="indeterminate" md-diameter="40" style="margin-left: auto;margin-right: auto;"></md-progress-circular>
      <span style="font-size: 15px;"><strong><?php echo lang('updating'); ?></strong></span>
      <div class="row">
        <div class="col-md-12">
          <p style="opacity: 0.7;"><br><?php echo lang('update_note'); ?></p>
        </div>
      </div>
    </md-dialog-content>
  </md-dialog>
</script> 

<script type="text/ng-template" id="backup.html">
  <md-dialog id="updating" style="box-shadow:none;padding:unset;min-width: 25%;">
    <md-dialog-content layout-padding layout-align="center center" aria-label="wait" style="text-align: center;">
      <md-progress-circular md-mode="indeterminate" md-diameter="40" style="margin-left: auto;margin-right: auto;"></md-progress-circular>
      <span style="font-size: 15px;"><strong><?php echo lang('backingup'); ?></strong></span>
      <div class="row">
        <div class="col-md-12">
          <p style="opacity: 0.7;"><br><?php echo lang('update_note'); ?></p>
        </div>
      </div>
    </md-dialog-content>
  </md-dialog>
</script> 

<script type="text/ng-template" id="restoring.html">
  <md-dialog id="updating" style="box-shadow:none;padding:unset;min-width: 25%;">
    <md-dialog-content layout-padding layout-align="center center" aria-label="wait" style="text-align: center;">
      <md-progress-circular md-mode="indeterminate" md-diameter="40" style="margin-left: auto;margin-right: auto;"></md-progress-circular>
      <span style="font-size: 15px;"><strong><?php echo lang('restoring'); ?></strong></span>
      <div class="row">
        <div class="col-md-12">
          <p style="opacity: 0.7;"><br><?php echo lang('update_note'); ?></p>
        </div>
      </div>
    </md-dialog-content>
  </md-dialog>
</script> 

  <script type="text/ng-template" id="system_info.html">
    <md-dialog aria-label="System Info">
      <md-toolbar class="toolbar-white">
        <div class="md-toolbar-tools">
          <h2><strong class="text-success"><?php echo lang('system').' '.lang('info') ?></strong></h2>
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
              <div class="col-md-12">
                <p><strong><?php echo lang('app').' '.lang('info');
                $CI = & get_instance();
                 ?>:</strong></p>
                <div layout="row" layout-wrap>
                  <div flex="50">
                    <p>
                      <?php echo lang('php_version')?>:
                    </p>
                  </div>
                  <div flex="50">
                    <p>
                      <strong><?php echo phpversion(); ?></strong>
                    </p>
                  </div>
                </div>
                <div layout="row" layout-wrap>
                  <div flex="50">
                    <p>
                      <?php echo lang('mysql_version')?>:
                    </p>
                  </div>
                  <div flex="50">
                    <p>
                      <strong>
                        <?php 
                        ob_start(); 
                        phpinfo(INFO_MODULES); 
                        $mysql = ob_get_contents(); 
                        ob_end_clean(); 
                        $mysql = stristr($mysql, 'Client API version'); 
                        preg_match('/[1-9].[0-9].[1-9][0-9]/', $mysql, $search);
                        echo $search[0];
                        ?>
                      </strong>
                    </p>
                  </div>
                </div>
                <div layout="row" layout-wrap>
                  <div flex="50">
                    <p>
                      <?php echo lang('database')?>:
                    </p>
                  </div>
                  <div flex="50">
                    <p>
                      <strong>
                        <?php echo $CI->db->database; ?>
                      </strong>
                    </p>
                  </div>
                </div>
                <div layout="row" layout-wrap>
                  <div flex="50">
                    <p>
                      <?php echo lang('tables_present')?>:
                    </p>
                  </div>
                  <div flex="50">
                    <p>
                      <strong>
                        <?php 
                        $tables = $CI->db->list_tables();
                        echo count($tables);
                         ?>
                      </strong>
                    </p>
                    <p style="display:none">
                      <?php 
                      echo json_encode($tables);
                      ?>
                    </p>  
                  </div>
                </div><br>
                <p><strong><?php echo lang('loaded_extensions'); ?>:</strong></p>
                <div layout="row" layout-wrap>
                  <div flex="50">
                    <p>
                      <?php echo 'MySQLi'?>
                    </p>
                  </div>
                  <div flex="50">
                    <p>
                      <strong>
                        <?php if(!extension_loaded('mysqli')) { ?>
                          <md-icon><i class="ion-ios-close text-danger"></i></md-icon>
                        <?php } else { ?>
                          <md-icon><i class="ion-ios-checkmark text-success"></i></md-icon>
                        <?php } ?>
                      </strong>
                    </p>
                  </div>
                </div>
                <div layout="row" layout-wrap>
                  <div flex="50">
                    <p>
                      <?php echo 'PDO'?>
                    </p>
                  </div>
                  <div flex="50">
                    <p>
                      <strong>
                        <?php if(!extension_loaded('pdo')) { ?>
                          <md-icon><i class="ion-ios-close text-danger"></i></md-icon>
                        <?php } else { ?>
                          <md-icon><i class="ion-ios-checkmark text-success"></i></md-icon>
                        <?php } ?>
                      </strong>
                    </p>
                  </div>
                </div>
                <div layout="row" layout-wrap>
                  <div flex="50">
                    <p>
                      <?php echo 'mcrypt'?>
                    </p>
                  </div>
                  <div flex="50">
                    <p>
                      <strong>
                        <?php if(!extension_loaded('mcrypt')) { ?>
                          <md-icon><i class="ion-ios-close text-danger"></i></md-icon>
                        <?php } else { ?>
                          <md-icon><i class="ion-ios-checkmark text-success"></i></md-icon>
                        <?php } ?>
                      </strong>
                    </p>
                  </div>
                </div>
                <div layout="row" layout-wrap>
                  <div flex="50">
                    <p>
                      <?php echo 'OpenSSL'?>
                    </p>
                  </div>
                  <div flex="50">
                    <p>
                      <strong>
                        <?php if(!extension_loaded('openssl')) { ?>
                          <md-icon><i class="ion-ios-close text-danger"></i></md-icon>
                        <?php } else { ?>
                          <md-icon><i class="ion-ios-checkmark text-success"></i></md-icon>
                        <?php } ?>
                      </strong>
                    </p>
                  </div>
                </div>
                <div layout="row" layout-wrap>
                  <div flex="50">
                    <p>
                      <?php echo 'iconv'?>
                    </p>
                  </div>
                  <div flex="50">
                    <p>
                      <strong>
                        <?php if(!extension_loaded('iconv')) { ?>
                          <md-icon><i class="ion-ios-close text-danger"></i></md-icon>
                        <?php } else { ?>
                          <md-icon><i class="ion-ios-checkmark text-success"></i></md-icon>
                        <?php } ?>
                      </strong>
                    </p>
                  </div>
                </div>
                <div layout="row" layout-wrap>
                  <div flex="50">
                    <p>
                      <?php echo 'cURL'?>
                    </p>
                  </div>
                  <div flex="50">
                    <p>
                      <strong>
                        <?php if(!extension_loaded('curl')) { ?>
                          <md-icon><i class="ion-ios-close text-danger"></i></md-icon>
                        <?php } else { ?>
                          <md-icon><i class="ion-ios-checkmark text-success"></i></md-icon>
                        <?php } ?>
                      </strong>
                    </p>
                  </div>
                </div>
                <div layout="row" layout-wrap>
                  <div flex="50">
                    <p>
                      <?php echo 'MBString'?>
                    </p>
                  </div>
                  <div flex="50">
                    <p>
                      <strong>
                        <?php if(!extension_loaded('mbstring')) { ?>
                          <md-icon><i class="ion-ios-close text-danger"></i></md-icon>
                        <?php } else { ?>
                          <md-icon><i class="ion-ios-checkmark text-success"></i></md-icon>
                        <?php } ?>
                      </strong>
                    </p>
                  </div>
                </div>
                <div layout="row" layout-wrap>
                  <div flex="50">
                    <p>
                      <?php echo 'GD'?>
                    </p>
                  </div>
                  <div flex="50">
                    <p>
                      <strong>
                        <?php if(!extension_loaded('gd')) { ?>
                          <md-icon><i class="ion-ios-close text-danger"></i></md-icon>
                        <?php } else { ?>
                          <md-icon><i class="ion-ios-checkmark text-success"></i></md-icon>
                        <?php } ?>
                      </strong>
                    </p>
                  </div>
                </div>
                <div layout="row" layout-wrap>
                  <div flex="50">
                    <p>
                      <?php echo 'ZIP'?>
                    </p>
                  </div>
                  <div flex="50">
                    <p>
                      <strong>
                        <?php if(!extension_loaded('zip')) { ?>
                          <md-icon><i class="ion-ios-close text-danger"></i></md-icon>
                        <?php } else { ?>
                          <md-icon><i class="ion-ios-checkmark text-success"></i></md-icon>
                        <?php } ?>
                      </strong>
                    </p>
                  </div>
                </div>
                <div layout="row" layout-wrap>
                  <div flex="50">
                    <p>
                      <?php echo 'allow_url_fopen'?>
                    </p>
                  </div>
                  <div flex="50">
                    <p>
                      <strong>
                        <?php if(!ini_get('allow_url_fopen')) { ?>
                          <md-icon><i class="ion-ios-close text-danger"></i></md-icon>
                        <?php } else { ?>
                          <md-icon><i class="ion-ios-checkmark text-success"></i></md-icon>
                        <?php } ?>
                      </strong>
                    </p>
                  </div>
                </div>
              </div>
            </md-list-item>
          </md-list>
        </md-content>     
      </md-dialog-content>
    </md-dialog>
  </script>
</div>