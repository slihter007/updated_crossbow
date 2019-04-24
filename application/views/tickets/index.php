<?php include_once(APPPATH . 'views/inc/header.php'); ?>
<?php $appconfig = get_appconfig(); ?>
<style>
.topRow {
    margin-bottom : 30px;
}
.on-drag-enter {
}
.on-drag-hover:before {
    display: block;
    color: white;
    font-size: x-large;
    font-weight: 800;
}
</style>
<div class="ciuis-body-content" ng-controller="Tickets_Controller">
  <div class="main-content container-fluid col-xs-12 col-md-12 col-lg-12">
    <div ng-show="ticketsLoader" layout-align="center center" class="text-center" id="circular_loader">
        <md-progress-circular md-mode="indeterminate" md-diameter="40"></md-progress-circular>
        <p style="font-size: 15px;margin-bottom: 5%;">
         <span>
            <?php echo lang('please_wait') ?> <br>
           <small><strong><?php echo lang('loading'). ' '. lang('tickets').'...' ?></strong></small>
         </span>
       </p>
     </div>
    <div ng-show="!ticketsLoader" class="main-content container-fluid col-xs-12 col-md-3 col-lg-3 md-pl-0">
      <div class="panel-default panel-table borderten lead-manager-head">
        <md-content style="border-bottom: 2px dashed #e8e8e8; padding-bottom: 20px;" layout-padding>
          <div class="col-md-3 col-xs-6 border-right text-uppercase">
            <div class="tasks-status-stat">
              <h4 class="text-bold ciuis-task-stat-title">
              <span class="task-stat-number ng-binding" ng-bind="(tickets | filter:{status_id:'1'}).length"></span>
              <span class="task-stat-all ng-binding" ng-bind="'/'+' '+tickets.length+' '+'<?php echo lang('ticket') ?>'"></span>
              </h4>
              <span class="ciuis-task-percent-bg">
              <span class="ciuis-task-percent-fg" style="width: {{(tickets | filter:{status_id:'1'}).length * 100 / tickets.length }}%;"></span>
              </span>
            </div>
            <span style="color:#989898"><?php echo lang('open') ?></span>
          </div>
          <div class="col-md-3 col-xs-6 border-right text-uppercase">
            <div class="tasks-status-stat">
              <h4 class="text-bold ciuis-task-stat-title">
              <span class="task-stat-number ng-binding" ng-bind="(tickets | filter:{status_id:'2'}).length"></span>
              <span class="task-stat-all ng-binding" ng-bind="'/'+' '+tickets.length+' '+'<?php echo lang('ticket') ?>'"></span>
              </h4>
              <span class="ciuis-task-percent-bg">
              <span class="ciuis-task-percent-fg" style="width: {{(tickets | filter:{status_id:'2'}).length * 100 / tickets.length }}%;"></span>
              </span>
            </div>
            <span style="color:#989898"><?php echo lang('inprogress') ?></span>
          </div>
          <div class="col-md-3 col-xs-6 border-right text-uppercase">
            <div class="tasks-status-stat">
              <h4 class="text-bold ciuis-task-stat-title">
              <span class="task-stat-number ng-binding" ng-bind="(tickets | filter:{status_id:'3'}).length"></span>
              <span class="task-stat-all ng-binding" ng-bind="'/'+' '+tickets.length+' '+'<?php echo lang('ticket') ?>'"></span>
              </h4>
              <span class="ciuis-task-percent-bg">
              <span class="ciuis-task-percent-fg" style="width: {{(tickets | filter:{status_id:'3'}).length * 100 / tickets.length }}%;"></span>
              </span>
            </div>
            <span style="color:#989898"><?php echo lang('answered') ?></span>
          </div>
          <div class="col-md-3 col-xs-6 border-right text-uppercase">
            <div class="tasks-status-stat">
              <h4 class="text-bold ciuis-task-stat-title">
              <span class="task-stat-number ng-binding" ng-bind="(tickets | filter:{status_id:'4'}).length"></span>
              <span class="task-stat-all ng-binding" ng-bind="'/'+' '+tickets.length+' '+'<?php echo lang('ticket') ?>'"></span>
              </h4>
              <span class="ciuis-task-percent-bg">
              <span class="ciuis-task-percent-fg" style="width: {{(tickets | filter:{status_id:'4'}).length * 100 / tickets.length }}%;"></span>
              </span>
            </div>
            <span style="color:#989898"><?php echo lang('closed') ?></span>
          </div>
        </md-content>
          <div class="ticket-contoller-left">
            <div id="tickets-left-column text-left">
              <div class="col-md-12 ticket-row-left text-left">
              <div class="tickets-vertical-menu">
                <a ng-click="TicketsFilter = NULL" class="highlight text-uppercase"><i class="fa fa-inbox fa-lg" aria-hidden="true"></i> <?php echo lang('alltickets') ?> <span class="ticket-num" ng-bind="tickets.length"></span></a>
                <a ng-click="TicketsFilter = {status_id: 1}" class="side-tickets-menu-item"><?php echo lang('open') ?> <span class="ticket-num" ng-bind="(tickets | filter:{status_id:'1'}).length"></span></a>
                <a ng-click="TicketsFilter = {status_id: 2}" class="side-tickets-menu-item"><?php echo lang('inprogress') ?> <span class="ticket-num" ng-bind="(tickets | filter:{status_id:'2'}).length"></span></a>
                <a ng-click="TicketsFilter = {status_id: 3}" class="side-tickets-menu-item"><?php echo lang('answered') ?> <span class="ticket-num" ng-bind="(tickets | filter:{status_id:'3'}).length"></span></a>
                <a ng-click="TicketsFilter = {status_id: 4}" class="side-tickets-menu-item"><?php echo lang('closed') ?> <span class="ticket-num" ng-bind="(tickets | filter:{status_id:'4'}).length"></span></a>
                <h5 href="#" class="menu-icon active text-uppercase text-muted"><i class="fa fa-file-text fa-lg" aria-hidden="true"></i><?php echo lang('filterbypriority') ?></h5>
                <a ng-click="TicketsFilter = {priority_id: 1}" class="side-tickets-menu-item"><?php echo lang('low') ?> <span class="ticket-num" ng-bind="(tickets | filter:{priority_id:'1'}).length"></span></a>
                <a ng-click="TicketsFilter = {priority_id: 2}" class="side-tickets-menu-item"><?php echo lang('medium') ?> <span class="ticket-num" ng-bind="(tickets | filter:{priority_id:'2'}).length"></span></a>
                <a ng-click="TicketsFilter = {priority_id: 3}" class="side-tickets-menu-item"><?php echo lang('high') ?> <span class="ticket-num" ng-bind="(tickets | filter:{priority_id:'3'}).length"></span></a>
              </div>
              </div>
             </div>
          </div>
      </div>
    </div>
    <div ng-show="!ticketsLoader" class="main-content container-fluid col-xs-12 col-md-9 col-lg-9 md-p-0 lead-table">
      <md-toolbar class="toolbar-white">
        <div class="md-toolbar-tools">
          <h2 flex md-truncate class="text-bold"><?php echo lang('tickets'); ?><br>
            <small flex md-truncate><?php echo lang('tracktickets'); ?></small>
          </h2>
          <md-button ng-if="!KanbanBoard" ng-click="ShowKanban()"class="md-icon-button" aria-label="Show Kanban">
            <md-tooltip md-direction="bottom"><?php echo lang('showkanban'); ?></md-tooltip>
            <md-icon><i class="mdi mdi-view-week text-muted"></i></md-icon>
          </md-button>
          <md-button ng-if="KanbanBoard" ng-click="HideKanban()"class="md-icon-button" aria-label="Show List">
            <md-tooltip md-direction="bottom"><?php echo lang('showlist'); ?></md-tooltip>
            <md-icon><i class="mdi mdi-view-list text-muted"></i></md-icon>
          </md-button>
          <md-button ng-click="Create()" class="md-icon-button" aria-label="New">
            <md-tooltip md-direction="bottom"><?php echo lang('newticket') ?></md-tooltip>
            <md-icon><i class="ion-plus-round text-muted"></i></md-icon>
          </md-button>
        </div>
      </md-toolbar>
      <md-content ng-if="KanbanBoard" class="" style="padding: 0px">
        <md-content class="col-md-4" style="padding: 0px; border: 1px solid #e4e4e4; height: 500px;">
          <md-list flex style="padding-top: unset;">
          <md-subheader class="md-no-sticky bg-white"><md-icon class="ion-android-alert text-danger"></md-icon><strong><?php echo lang('high') ?></strong></md-subheader>
          <md-divider ></md-divider>
          <md-list-item class="md-2-line" ng-repeat="ticket in tickets | filter:TicketsFilter | filter:search | filter: { priority_id: '3' }" ng-click="GoTicket(ticket.id)">
            <div class="md-list-item-text">
            <h3> {{ ticket.subject }} </h3>
            <p> {{ ticket.contactname }} </p>
            <p ng-show="ticket.lastreply != NULL"><small><strong><?php echo lang('lastreply') ?></strong> <small ng-bind="ticket.lastreply | date : 'MMM d, y h:mm:ss a'"></small></small></p>
            <p ng-show="ticket.lastreply == NULL"><small><strong><?php echo lang('lastreply') ?></strong> <small><?php echo lang('n_a') ?></small></small></p>
            </div>
            <md-button ng-hide="ticket.status_id != 4" class="md-secondary md-icon-button" aria-label="Closed">
              <md-tooltip md-direction="bottom"><?php echo lang('closed') ?></md-tooltip>
            <md-icon class="ion-happy text-success"></md-icon>
            </md-button>
            <md-button ng-hide="ticket.status_id != 3" class="md-secondary md-icon-button" aria-label="Closed">
              <md-tooltip md-direction="bottom"><?php echo lang('answered') ?></md-tooltip>
            <md-icon class="mdi mdi-mail-reply-all text-muted"></md-icon>
            </md-button>
            <md-button ng-hide="ticket.status_id != 2" class="md-secondary md-icon-button" aria-label="Closed">
              <md-tooltip md-direction="bottom"><?php echo lang('inprogress') ?></md-tooltip>
            <md-icon class="mdi mdi-hourglass-alt text-muted"></md-icon>
            </md-button>
            <md-button ng-hide="ticket.status_id != 1" class="md-secondary md-icon-button" aria-label="Closed">
              <md-tooltip md-direction="bottom"><?php echo lang('open') ?></md-tooltip>
            <md-icon class="mdi mdi-flash text-danger"></md-icon>
            </md-button>
          <md-divider ></md-divider>
          </md-list-item>
          </md-list>
        </md-content>
        <md-content class="col-md-4" style="padding: 0px; border: 1px solid #e4e4e4; height: 500px;">
          <md-list flex style="padding-top: unset;">
          <md-subheader class="md-no-sticky bg-white"><md-icon class="ion-android-alert text-warning"></md-icon><strong><?php echo lang('medium') ?></strong></md-subheader>
          <md-divider ></md-divider>
          <md-list-item class="md-2-line" ng-repeat="ticket in tickets | filter:TicketsFilter | filter:search | filter: { priority_id: '2' }" ng-click="GoTicket(ticket.id)">
            <div class="md-list-item-text">
            <h3> {{ ticket.subject }} </h3>
            <p> {{ ticket.contactname }} </p>
            <p ng-show="ticket.lastreply != NULL"><small><strong><?php echo lang('lastreply') ?></strong> <small ng-bind="ticket.lastreply | date : 'MMM d, y h:mm:ss a'"></small></small></p>
            <p ng-show="ticket.lastreply == NULL"><small><strong><?php echo lang('lastreply') ?></strong> <small><?php echo lang('n_a') ?></small></small></p>
            </div>
            <md-button ng-hide="ticket.status_id != 4" class="md-secondary md-icon-button" aria-label="Closed">
              <md-tooltip md-direction="bottom"><?php echo lang('closed') ?></md-tooltip>
            <md-icon class="ion-happy text-success"></md-icon>
            </md-button>
            <md-button ng-hide="ticket.status_id != 3" class="md-secondary md-icon-button" aria-label="Closed">
              <md-tooltip md-direction="bottom"><?php echo lang('answered') ?></md-tooltip>
            <md-icon class="mdi mdi-mail-reply-all text-muted"></md-icon>
            </md-button>
            <md-button ng-hide="ticket.status_id != 2" class="md-secondary md-icon-button" aria-label="Closed">
              <md-tooltip md-direction="bottom"><?php echo lang('inprogress') ?></md-tooltip>
            <md-icon class="mdi mdi-hourglass-alt text-muted"></md-icon>
            </md-button>
            <md-button ng-hide="ticket.status_id != 1" class="md-secondary md-icon-button" aria-label="Closed">
              <md-tooltip md-direction="bottom"><?php echo lang('open') ?></md-tooltip>
            <md-icon class="mdi mdi-flash text-danger"></md-icon>
            </md-button>
          <md-divider ></md-divider>
          </md-list-item>
          </md-list>
        </md-content>
        <md-content class="col-md-4" style="padding: 0px; border: 1px solid #e4e4e4; height: 500px;">
          <md-list flex style="padding-top: unset;">
          <md-subheader class="md-no-sticky bg-white"><md-icon class="ion-android-alert text-success"></md-icon><strong><?php echo lang('low') ?></strong></md-subheader>
          <md-divider ></md-divider>
          <md-list-item class="md-2-line" ng-repeat="ticket in tickets | filter:TicketsFilter | filter:search | filter: { priority_id: '1' }" ng-click="GoTicket(ticket.id)">
            <div class="md-list-item-text">
            <h3> {{ ticket.subject }} </h3>
            <p> {{ ticket.contactname }} </p>
            <p ng-show="ticket.lastreply != NULL"><small><strong><?php echo lang('lastreply') ?></strong> <small ng-bind="ticket.lastreply | date : 'MMM d, y h:mm:ss a'"></small></small></p>
            <p ng-show="ticket.lastreply == NULL"><small><strong><?php echo lang('lastreply') ?></strong> <small><?php echo lang('n_a') ?></small></small></p>
            </div>
            <md-button ng-hide="ticket.status_id != 4" class="md-secondary md-icon-button" aria-label="Closed">
              <md-tooltip md-direction="bottom"><?php echo lang('closed') ?></md-tooltip>
            <md-icon class="ion-happy text-success"></md-icon>
            </md-button>
            <md-button ng-hide="ticket.status_id != 3" class="md-secondary md-icon-button" aria-label="Closed">
              <md-tooltip md-direction="bottom"><?php echo lang('answered') ?></md-tooltip>
            <md-icon class="mdi mdi-mail-reply-all text-muted"></md-icon>
            </md-button>
            <md-button ng-hide="ticket.status_id != 2" class="md-secondary md-icon-button" aria-label="Closed">
              <md-tooltip md-direction="bottom"><?php echo lang('inprogress') ?></md-tooltip>
            <md-icon class="mdi mdi-hourglass-alt text-muted"></md-icon>
            </md-button>
            <md-button ng-hide="ticket.status_id != 1" class="md-secondary md-icon-button" aria-label="Closed">
              <md-tooltip md-direction="bottom"><?php echo lang('open') ?></md-tooltip>
            <md-icon class="mdi mdi-flash text-danger"></md-icon>
            </md-button>
          <md-divider ></md-divider>
          </md-list-item>
          </md-list>
        </md-content>
      </md-content>
      <md-content ng-if="!KanbanBoard" class="md-pt-0">
        <ul class="custom-ciuis-list-body" style="padding: 0px;">
          <li ng-repeat="ticket in tickets | filter: TicketsFilter | filter:search | filter: ticket.priority | pagination : currentPage*itemsPerPage | limitTo: 5" class="ciuis-custom-list-item ciuis-special-list-item">
            <a ng-href="<?php echo base_url('tickets/ticket/')?>{{ticket.id}}">
              <ul class="list-item-for-custom-list">
                <li class="ciuis-custom-list-item-item col-md-12">
                  <div data-toggle="tooltip" data-placement="bottom" data-container="body" class="assigned-staff-for-this-lead user-avatar">
                    <i class="ion-help-buoy" style="font-size: 32px"></i> 
                  </div>
                  <div class="pull-left col-md-4"> 
                    <strong ng-bind="ticket.subject"></strong><br>
                    <small ng-bind="ticket.message"></small> 
                  </div>
                  <div class="col-md-8">
                    <div class="col-md-5"> 
                      <span class="date-start-task">
                        <small class="text-muted text-uppercase"><?php echo lang('contact'); ?></small><br>
                        <strong ng-bind="ticket.contactname">
                          <span> </span> 
                        </strong> 
                      </span> 
                    </div>
                    <div class="col-md-2"> 
                      <span class="date-start-task">
                        <small class="text-muted text-uppercase"><?php echo lang('priority'); ?></small><br>
                        <span ng-switch="ticket.priority_id">
                          <strong ng-switch-when="1"><?php echo lang( 'low' ); ?></strong>
                          <strong ng-switch-when="2"><?php echo lang( 'medium' ); ?></strong>
                          <strong ng-switch-when="3"><?php echo lang( 'high' ); ?></strong>
                        </span>
                      </span>
                    </div>
                    <div class="col-md-2"> 
                      <span class="date-start-task">
                        <small class="text-muted text-uppercase"><?php echo lang('status'); ?></small><br>
                        <span ng-switch="ticket.status_id">
                          <strong ng-switch-when="1"><?php echo lang( 'open' ); ?></strong>
                          <strong ng-switch-when="2"><?php echo lang( 'inprogress' ); ?></strong>
                          <strong ng-switch-when="3"><?php echo lang( 'answered' ); ?></strong>
                          <strong ng-switch-when="4"><?php echo lang( 'closed' ); ?></strong>
                        </span>
                      </div>
                      <div class="col-md-3"> 
                        <span class="date-start-task">
                          <small class="text-muted text-uppercase"><?php echo lang('lastreply'); ?></small><br>
                          <strong>
                            <span ng-show="ticket.lastreply == NULL" class="badge"><?php echo lang('n_a') ?></span><span ng-show="ticket.lastreply != NULL" class="badge" ng-bind="ticket.lastreply | date : 'MMM d, y h:mm:ss a'"></span> 
                          </strong> 
                        </span> 
                      </div>
                    </div>
                  </li>
                </ul>
            </a>
          </li>
        </ul>
        <div ng-hide="tickets.length < 5" class="pagination-div">
          <ul class="pagination">
            <li ng-class="DisablePrevPage()"> <a href ng-click="prevPage()"><i class="ion-ios-arrow-back"></i></a> </li>
            <li ng-repeat="n in range()" ng-class="{active: n == currentPage}" ng-click="setPage(n)"> <a href="#" ng-bind="n+1"></a> </li>
            <li ng-class="DisableNextPage()"> <a href ng-click="nextPage()"><i class="ion-ios-arrow-right"></i></a> </li>
          </ul>
        </div>
        <md-content ng-show="!tickets.length" class="md-padding no-item-data"><?php echo lang('notdata') ?></md-content>
      </md-content>
    </div>
  </div>
  <md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="Create">
    <md-toolbar class="toolbar-white">
    <div class="md-toolbar-tools">
    <md-button ng-click="close()" class="md-icon-button" aria-label="Close">
       <i class="ion-android-arrow-forward"></i>
    </md-button>
    <md-truncate><?php echo lang('create') ?></md-truncate>
    </div>
    </md-toolbar>
    <md-content layout-padding="">
    <md-content layout-padding>
    <?php echo form_open_multipart('tickets/create'); ?> 
      <md-input-container class="md-block">
        <label><?php echo lang('subject') ?></label>
        <input required type="text" ng-model="ticket.subject" name="subject" class="form-control">
      </md-input-container>
          <md-input-container class="md-block" flex-gt-xs>
              <label><?php echo lang('customer'); ?></label>
        <md-select required placeholder="<?php echo lang('choisecustomer'); ?>" ng-model="ticket.customer" name="customer">
          <md-option ng-value="customer" ng-repeat="customer in all_customers">{{customer.name}}</md-option>
        </md-select><br>
          </md-input-container>
          <md-input-container class="md-block" flex-gt-xs>
              <label><?php echo lang('contact'); ?></label>
        <md-select required ng-model="ticket.contact" name="contact">
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
      <div class="file-upload">
        <div class="file-select">
          <div class="file-select-button" id="fileName"><span class="mdi mdi-accounts-list-alt"></span> <?php echo lang('attachment')?></div>
          <div class="file-select-name" id="noFile"><?php echo lang('nofile')?></div>
          <input type="file" name="attachment" id="chooseFile">
        </div>
      </div>
      <section layout="row" layout-sm="column" layout-align="center center" layout-wrap>
        <md-button type="submit" class="template-button">
          <span><?php echo lang('create');?></span>
        </md-button>
        <!--<md-button type="submit" ng-click="creatingTicket()" class="md-raised md-primary pull-right"><?php echo lang('add');?></md-button>-->      
        </section>
    <?php echo form_close(); ?>
    </md-content>
   </md-content>
  </md-sidenav>
</div>
<?php include_once( APPPATH . 'views/inc/footer.php' );?>