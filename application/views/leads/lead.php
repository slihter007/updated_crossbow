<?php include_once(APPPATH . 'views/inc/header.php'); ?>
<?php $appconfig = get_appconfig(); ?>
<div class="ciuis-body-content" ng-controller="Lead_Controller">
  <div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
    <md-toolbar class="toolbar-white">
      <div class="md-toolbar-tools">
        <md-button class="md-icon-button" aria-label="Settings" ng-disabled="true">
          <md-icon><i class="ico-ciuis-leads text-warning"></i></md-icon>
        </md-button>
        <h2 flex md-truncate ng-bind="lead.name"></h2>
        <md-button ng-show="ONLYADMIN == 'true' || lead.assigned_id == user.id" ng-if="!lead.dateconverted" ng-click="Convert()" class="md-icon-button" aria-label="Convert">
          <md-tooltip md-direction="bottom"><?php echo lang('convert') ?></md-tooltip>
          <md-icon><i class="ion-loop text-success"></i></md-icon>
        </md-button>
        <md-button ng-show="lead.lost == '1'" class="md-icon-button mark-lost" aria-label="Lost">
          <md-tooltip md-direction="bottom"><?php echo lang('lost') ?></md-tooltip>
          <md-icon><i class="ion-sad text-black"></i></md-icon>
        </md-button>
        <md-button ng-show="lead.junk == '1'" class="md-icon-button mark-junk" aria-label="Junk">
          <md-tooltip md-direction="bottom"><?php echo lang('junk') ?></md-tooltip>
          <md-icon><i class="ion-sad-outline text-warning"></i></md-icon>
        </md-button>
        <md-button ng-if="lead.dateconverted" class="md-icon-button" aria-label="Converted">
          <md-tooltip md-direction="bottom"><?php echo lang('converted') ?></md-tooltip>
          <md-icon><i class="ion-trophy text-success"></i></md-icon>
        </md-button>
        <md-menu ng-show="ONLYADMIN == 'true' || lead.assigned_id == user.id" md-position-mode="target-right target">
          <md-button aria-label="Open demo menu" class="md-icon-button" ng-click="$mdMenu.open($event)">
            <md-icon><i class="ion-android-more-vertical text-muted"></i></md-icon>
          </md-button>
          <md-menu-content width="4">
            <md-menu-item ng-show="ONLYADMIN == 'true' || lead.assigned_id == user.id">
              <md-button ng-click="Update()" aria-label="update">
                <div layout="row" flex>
                  <p flex ng-bind="lang.update"></p>
                  <md-icon md-menu-align-target class="mdi mdi-edit" style="margin: auto 3px auto 0;"></md-icon>
                </div>
              </md-button>
            </md-menu-item>
            <md-menu-item ng-show="ONLYADMIN == 'true' || lead.assigned_id == user.id">
              <md-button ng-click="Delete()" aria-label="Delete">
                <div layout="row" flex>
                  <p flex ng-bind="lang.delete"></p>
                  <md-icon md-menu-align-target class="ion-trash-b" style="margin: auto 3px auto 0;"></md-icon>
                </div>
              </md-button>
            </md-menu-item>
            <md-menu-item ng-show="lead.lost == '0'">
              <md-button ng-click="MarkLeadAs(1)" aria-label="Not Started">
                <div layout="row" flex>
                  <p flex ng-bind="lang.markleadaslost"></p>
                  <md-icon md-menu-align-target class="ion-sad text-black" style="margin: auto 3px auto 0;"></md-icon>
                </div>
              </md-button>
            </md-menu-item>
            <md-menu-item ng-show="lead.lost != '0'">
              <md-button ng-click="MarkLeadAs(2)" aria-label="Started">
                <div layout="row" flex>
                  <p flex ng-bind="lang.unmarkleadaslost"></p>
                  <md-icon md-menu-align-target class="ion-sad text-muted" style="margin: auto 3px auto 0;"></md-icon>
                </div>
              </md-button>
            </md-menu-item>
            <md-menu-item ng-show="lead.junk == '0'">
              <md-button ng-click="MarkLeadAs(3)" aria-label="Percentage">
                <div layout="row" flex>
                  <p flex ng-bind="lang.markleadasjunk"></p>
                  <md-icon md-menu-align-target class="ion-sad-outline text-warning" style="margin: auto 3px auto 0;"></md-icon>
                </div>
              </md-button>
            </md-menu-item>
            <md-menu-item ng-show="lead.junk != '0'">
              <md-button ng-click="MarkLeadAs(4)" aria-label="Cancelled">
                <div layout="row" flex>
                  <p flex ng-bind="lang.unmarkleadasjunk"></p>
                  <md-icon md-menu-align-target class="ion-sad-outline text-muted" style="margin: auto 3px auto 0;"></md-icon>
                </div>
              </md-button>
            </md-menu-item>
          </md-menu-content>
        </md-menu>
      </div>
    </md-toolbar>
    <div ng-show="leadsLoader" layout-align="center center" class="text-center" id="circular_loader">
        <md-progress-circular md-mode="indeterminate" md-diameter="40"></md-progress-circular>
        <p style="font-size: 15px;margin-bottom: 5%;">
         <span>
            <?php echo lang('please_wait') ?> <br>
           <small><strong><?php echo lang('loading'). ' '. lang('lead').'...' ?></strong></small>
         </span>
       </p>
     </div>
    <md-content ng-show="!leadsLoader" class="bg-white">
      <md-tabs md-dynamic-height md-border-bottom>
        <md-tab label="<?php echo lang('lead') ?>" >
          <div layout="row">
            <md-content class="bg-white" flex="50" style="border-right:1px solid #e0e0e0;">
              <md-list flex class="md-p-0 sm-p-0 lg-p-0">
                <md-list-item>
                  <md-icon class="mdi mdi-account-o"></md-icon>
                  <strong flex md-truncate><?php echo lang('title') ?></strong>
                  <p class="text-right" flex md-truncate ng-bind="lead.title"></p>
                </md-list-item>
                <md-divider></md-divider>
                <md-list-item>
                  <md-icon class="mdi mdi-nature-people"></md-icon>
                  <strong flex md-truncate><?php echo lang('status') ?></strong>
                  <p class="text-right" flex md-truncate ng-bind="lead.status"></p>
                </md-list-item>
                <md-divider></md-divider>
                <md-list-item>
                  <md-icon class="mdi mdi-local-phone"></md-icon>
                  <strong flex md-truncate><?php echo lang('phone') ?></strong>
                  <p class="text-right" flex md-truncate ng-bind="lead.phone"></p>
                </md-list-item>
                <md-divider></md-divider>
                <md-list-item>
                  <md-icon class="mdi mdi-markunread-mailbox"></md-icon>
                  <strong flex md-truncate><?php echo lang('zip') ?></strong>
                  <p class="text-right" flex md-truncate ng-bind="lead.zip"></p>
                </md-list-item>
                <md-divider></md-divider>
                <md-list-item>
                  <md-icon class="mdi mdi-map"></md-icon>
                  <strong flex md-truncate><?php echo lang('state') ?></strong>
                  <p class="text-right" flex md-truncate ng-bind="lead.state"></p>
                </md-list-item>
                <md-divider></md-divider>
                <md-list-item>
                  <md-icon class="mdi mdi-http"></md-icon>
                  <strong flex md-truncate><?php echo lang('web') ?></strong>
                  <p class="text-right" flex md-truncate ng-bind="lead.website"></p>
                </md-list-item>
                <md-divider></md-divider>
                <md-list-item>
                  <md-icon class="ion-android-calendar"></md-icon>
                  <strong flex md-truncate><?php echo lang('date_contacted') ?></strong>
                  <p class="text-right" flex md-truncate ng-bind="lead.date_contacted"></p>
                </md-list-item>
                <md-divider></md-divider>
              </md-list>
            </md-content>
            <md-content class="bg-white" flex="50">
              <md-list flex class="md-p-0 sm-p-0 lg-p-0">
                <md-list-item>
                  <md-icon class="mdi mdi-local-store"></md-icon>
                  <strong flex md-truncate><?php echo lang('company') ?></strong>
                  <p class="text-right" flex md-truncate ng-bind="lead.company"></p>
                </md-list-item>
                <md-divider></md-divider>
                <md-list-item>
                  <md-icon class="ion-android-mail"></md-icon>
                  <strong flex md-truncate><?php echo lang('email') ?></strong>
                  <p class="text-right" flex md-truncate ng-bind="lead.email"></p>
                </md-list-item>
                <md-divider></md-divider>
                <md-list-item>
                  <md-icon class="mdi mdi-assignment-account"></md-icon>
                  <strong flex md-truncate><?php echo lang('assigned') ?></strong>
                  <p class="text-right" flex md-truncate ng-bind="lead.assigned"></p>
                </md-list-item>
                <md-divider></md-divider>
                <md-list-item>
                  <md-icon class="mdi mdi-city"></md-icon>
                  <strong flex md-truncate><?php echo lang('city') ?></strong>
                  <p class="text-right" flex md-truncate ng-bind="lead.city"></p>
                </md-list-item>
                <md-divider></md-divider>
                <md-list-item>
                  <md-icon class="ion-earth"></md-icon>
                  <strong flex md-truncate><?php echo lang('country') ?></strong>
                  <p class="text-right" flex md-truncate ng-bind="lead.country"></p>
                </md-list-item>
                <md-divider></md-divider>
                <md-list-item>
                  <md-icon class="mdi mdi-book-image"></md-icon>
                  <strong flex md-truncate><?php echo lang('source') ?></strong>
                  <p class="text-right" flex md-truncate ng-bind="lead.source"></p>
                </md-list-item>
                <md-divider></md-divider>
                <md-list-item>
                  <strong flex md-truncate></strong>
                  <p class="text-right" flex md-truncate></p>
                </md-list-item>
                <md-divider></md-divider>
              </md-list>
            </md-content>
          </div>
          <md-content class="bg-white">
            <md-list-item>
              <md-icon class="mdi mdi-pin-drop"></md-icon>
              <strong flex md-truncate><?php echo lang('address') ?></strong>
              <p class="text-right" flex ng-bind="lead.address"></p>
            </md-list-item>
          </md-content>
          <md-divider></md-divider>
          <md-content class="bg-white" layout-padding>
            <md-list-item>
              <md-icon class="mdi mdi-sort-desc"></md-icon>
              <p class="text-left" flex ng-bind="lead.description"></p>
            </md-list-item>
          </md-content>
        </md-tab>
        <md-tab label="<?php echo lang('proposals') ?>">
          <md-content class="bg-white">
            <md-list flex class="md-p-0 sm-p-0 lg-p-0">
              <md-list-item ng-repeat="proposal in proposals" ng-click="GoProposal($index)" aria-label="Proposal">
                <md-icon class="ico-ciuis-proposals"></md-icon>
                <p><strong ng-bind="proposal.prefix+'-'+proposal.longid"></strong></p>
                <h4><strong ng-bind-html="proposal.total | currencyFormat:cur_code:null:true:cur_lct"></strong></h4>
                <md-divider></md-divider>
              </md-list-item>
            </md-list>
            <md-content ng-show="!proposals.length" class="md-padding bg-white no-item-data"><?php echo lang('notdata') ?></md-content>
          </md-content>
        </md-tab>
        <md-tab label="<?php echo lang('notes') ?>">
          <md-content class="md-padding bg-white">
            <section class="ciuis-notes show-notes">
              <article ng-repeat="note in notes" class="ciuis-note-detail">
                <div class="ciuis-note-detail-img"> <img src="<?php echo base_url('assets/img/note.png') ?>" alt="" width="50" height="50" /> </div>
                <div class="ciuis-note-detail-body">
                  <div class="text">
                    <p> <span ng-bind="note.description"></span> <a ng-click='DeleteNote($index)' style="cursor: pointer;" class="mdi ion-trash-b pull-right delete-note-button"></a> </p>
                  </div>
                  <p class="attribution"> by <strong><a href="<?php echo base_url('staff/staffmember/');?>/{{note.staffid}}" ng-bind="note.staff"></a></strong> at <span ng-bind="note.date"></span> </p>
                </div>
              </article>
            </section>
            <section class="md-pb-30">
              <md-input-container class="md-block">
                <label><?php echo lang('description') ?></label>
                <textarea required name="description" ng-model="note" placeholder="<?php echo lang('typeSomething'); ?>" class="form-control note-description"></textarea>
              </md-input-container>
              <section layout="row" layout-sm="column" layout-wrap class="pull-right">
                <md-button ng-click="AddNote()" class="md-raised md-primary"><?php echo lang('addnote');?></md-button>
              </section>
            </section>
          </md-content>
        </md-tab>
        <md-tab label="<?php echo lang('reminders') ?>">
          <md-list ng-cloak>
            <md-toolbar class="toolbar-white">
              <div class="md-toolbar-tools">
                <h2><?php echo lang('reminders') ?></h2>
                <span flex></span>
                <md-button ng-click="ReminderForm()" class="md-icon-button test-tooltip" aria-label="Add Reminder">
                  <md-tooltip md-direction="left"><?php echo lang('addreminder') ?></md-tooltip>
                  <md-icon><i class="ion-plus-round text-success"></i></md-icon>
                </md-button>
              </div>
            </md-toolbar>
            <md-list-item ng-repeat="reminder in in_reminders" ng-click="goToPerson(person.name, $event)" class="noright"> <img alt="{{ reminder.staff }}" ng-src="{{ reminder.avatar }}" class="md-avatar" />
              <p>{{ reminder.description }}</p>
              <md-icon ng-click="" aria-label="Send Email" class="md-secondary md-hue-3" >
                <md-tooltip md-direction="left">{{reminder.date}}</md-tooltip>
                <i class="ion-ios-calendar-outline"></i> </md-icon>
              <md-icon ng-click="DeleteReminder($index)" aria-label="Send Email" class="md-secondary md-hue-3" >
                <md-tooltip md-direction="left"><?php echo lang('delete') ?></md-tooltip>
                <i class="ion-ios-trash-outline"></i> </md-icon>
            </md-list-item>
          </md-list>
        </md-tab>
      </md-tabs>
      <md-subheader ng-if="custom_fields.length > 0"><?php echo lang('custom_fields'); ?></md-subheader>
      <md-list-item ng-if="custom_fields.length > 0" ng-repeat="field in custom_fields">
        <md-icon class="{{field.icon}} material-icons"></md-icon>
        <strong flex md-truncate>{{field.name}}</strong>
        <p ng-if="field.type === 'input'" class="text-right" flex md-truncate ng-bind="field.data"></p>
        <p ng-if="field.type === 'textarea'" class="text-right" flex md-truncate ng-bind="field.data"></p>
        <p ng-if="field.type === 'date'" class="text-right" flex md-truncate ng-bind="field.data | date:'dd, MMMM yyyy EEEE'"></p>
        <p ng-if="field.type === 'select'" class="text-right" flex md-truncate ng-bind="custom_fields[$index].selected_opt.name"></p>
        <md-divider ng-if="custom_fields"></md-divider>
      </md-list-item>
    </md-content>
  </div>
  <ciuis-sidebar></ciuis-sidebar>
  <md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="Update">
    <md-toolbar class="toolbar-white">
      <div class="md-toolbar-tools">
        <md-button ng-click="close()" class="md-icon-button" aria-label="Close"> <i class="ion-android-arrow-forward"></i> </md-button>
        <md-truncate><?php echo lang('update') ?></md-truncate>
      </div>
    </md-toolbar>
    <md-content>
      <md-content layout-padding>
        <md-input-container class="md-block">
          <label><?php echo lang('title'); ?></label>
          <input ng-model="lead.title">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('name'); ?></label>
          <md-icon md-svg-src="<?php echo base_url('assets/img/icons/individual.svg') ?>"></md-icon>
          <input required name="name" ng-model="lead.name">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('company'); ?></label>
          <md-icon md-svg-src="<?php echo base_url('assets/img/icons/company.svg') ?>"></md-icon>
          <input ng-model="lead.company">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('assigned'); ?></label>
          <md-select required placeholder="<?php echo lang('choosestaff'); ?>" ng-model="lead.assigned_id" style="min-width: 200px;">
            <md-option ng-value="staff.id" ng-repeat="staff in staff">{{staff.name}}</md-option>
          </md-select>
        </md-input-container>
        <br>
        <md-input-container class="md-block">
          <label><?php echo lang('status'); ?></label>
          <md-select required placeholder="<?php echo lang('status'); ?>" ng-model="lead.status_id" style="min-width: 200px;">
            <md-option ng-value="status.id" ng-repeat="status in statuses">{{status.name}}</md-option>
          </md-select>
        </md-input-container>
        <br>
        <md-input-container class="md-block">
          <label><?php echo lang('source'); ?></label>
          <md-select required placeholder="<?php echo lang('source'); ?>" ng-model="lead.source_id" style="min-width: 200px;">
            <md-option ng-value="source.id" ng-repeat="source in sources">{{source.name}}</md-option>
          </md-select>
        </md-input-container>
        <br>
        <md-input-container class="md-block">
          <label><?php echo lang('phone'); ?></label>
          <input ng-model="lead.phone">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('email'); ?></label>
          <input ng-model="lead.email" type="email" required minlength="10" maxlength="100" ng-pattern="/^.+@.+\..+$/">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('web'); ?></label>
          <input ng-model="lead.website">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('country'); ?></label>
          <md-select required placeholder="<?php echo lang('country'); ?>" ng-model="lead.country_id" style="min-width: 200px;">
            <md-option ng-value="country.id" ng-repeat="country in countries">{{country.shortname}}</md-option>
          </md-select>
        </md-input-container>
        <br>
        <md-input-container class="md-block">
          <label><?php echo lang('state'); ?></label>
          <input ng-model="lead.state">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('city'); ?></label>
          <input ng-model="lead.city">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('zip'); ?></label>
          <input ng-model="lead.zip">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('address') ?></label>
          <textarea ng-model="lead.address" md-maxlength="500" rows="3" md-select-on-focus></textarea>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('description') ?></label>
          <textarea required ng-model="lead.description" md-maxlength="500" rows="3" md-select-on-focus></textarea>
        </md-input-container>
        <md-input-container class="md-block pull-left">
          <md-checkbox ng-model="lead.public"><?php echo lang('public') ?></md-checkbox>
        </md-input-container>
        <md-input-container class="md-block pull-left">
          <md-checkbox ng-model="lead.type"><?php echo lang('individual') ?></md-checkbox>
        </md-input-container>
      </md-content>
      <custom-fields-vertical></custom-fields-vertical>
      <md-content>
        <section layout="row" layout-sm="column" layout-align="center center" layout-wrap>
          <md-button ng-click="UpdateLead()" class="template-button" ng-disabled="saving == true">
            <span ng-hide="saving == true"><?php echo lang('update');?></span>
            <md-progress-circular class="white" ng-show="saving == true" md-mode="indeterminate" md-diameter="20"></md-progress-circular>
          </md-button>
        </section>
      </md-content>
    </md-content>
  </md-sidenav>
  <md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="ReminderForm">
    <md-toolbar class="md-theme-light" style="background:#262626">
      <div class="md-toolbar-tools">
        <md-button ng-click="close()" class="md-icon-button" aria-label="Close"> <i class="ion-android-arrow-forward"></i> </md-button>
        <md-truncate><?php echo lang('addreminder') ?></md-truncate>
      </div>
    </md-toolbar>
    <md-content layout-padding="">
      <md-content layout-padding>
        <md-input-container class="md-block">
          <label><?php echo lang('datetobenotified') ?></label>
          <input mdc-datetime-picker="" date="true" time="true" type="text" id="datetime" placeholder="<?php echo lang('chooseadate') ?>" show-todays-date="" minutes="true" min-date="date" show-icon="true" ng-model="reminder_date" class=" dtp-no-msclear dtp-input md-input">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('setreminderto'); ?></label>
          <md-select placeholder="<?php echo lang('setreminderto'); ?>" ng-model="reminder_staff" name="country_id" style="min-width: 200px;">
            <md-option ng-value="staff.id" ng-repeat="staff in staff">{{staff.name}}</md-option>
          </md-select>
        </md-input-container>
        <br>
        <md-input-container class="md-block">
          <label><?php echo lang('description') ?></label>
          <textarea required name="description" ng-model="reminder_description" placeholder="<?php echo lang('typeSomething'); ?>" class="form-control note-description"></textarea>
        </md-input-container>
        <section layout="row" layout-sm="column" layout-wrap class="pull-right">
          <md-button ng-click="AddReminder()" class="md-raised md-primary"><?php echo lang('add');?></md-button>
        </section>
      </md-content>
    </md-content>
  </md-sidenav>
</div>
<script>
	var LEADID = "<?php echo $lead['id'];?>";
</script>
<?php include_once( APPPATH . 'views/inc/footer.php' );?>