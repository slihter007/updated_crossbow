<?php $appconfig = get_appconfig(); ?>
<div class="ciuis-body-content" ng-controller="Order_Controller">
  <div class="main-content container-fluid col-md-9 borderten">
    <md-toolbar class="toolbar-white">
      <div class="md-toolbar-tools">
        <md-button class="md-icon-button" aria-label="Settings" ng-disabled="true">
          <md-icon><i class="ico-ciuis-proposals text-warning"></i></md-icon>
        </md-button>
        <h2 flex md-truncate><?php echo $orders['subject'];?></h2>
        <md-button ng-click="sendEmail()" class="md-icon-button" aria-label="Email">
          <md-progress-circular ng-show="sendingEmail == true" md-mode="indeterminate" md-diameter="20"></md-progress-circular>
          <md-tooltip ng-hide="sendingEmail == true" md-direction="bottom" ng-bind="lang.send"></md-tooltip>
          <md-icon ng-hide="sendingEmail == true"><i class="mdi mdi-email text-muted"></i></md-icon>
        </md-button>
        <md-button ng-click="GeneratePDF()" class="md-icon-button" aria-label="Pdf">
          <md-tooltip md-direction="bottom"><?php echo lang('pdf') ?></md-tooltip>
          <md-icon><i class="mdi mdi-collection-pdf text-muted"></i> </md-icon>
        </md-button>
        <md-button ng-href="<?php echo base_url('orders/print_/{{order.id}}') ?>" class="md-icon-button" aria-label="Print">
          <md-tooltip md-direction="bottom"><?php echo lang('print') ?></md-tooltip>
          <md-icon><i class="mdi mdi-print text-muted"></i></md-icon>
        </md-button>
        <md-menu ng-if="!order.invoice_id" md-position-mode="target-right target">
          <md-button aria-label="Convert" class="md-icon-button" ng-click="$mdMenu.open($event)">
            <md-icon><i class="ion-loop text-success"></i></md-icon>
          </md-button>
          <md-menu-content width="4">
            <md-contet class="text-center" layout-padding> <img height="80%" src="https://cdn4.iconfinder.com/data/icons/business-399/512/invoice-128.png" alt="">
              <p style="max-width: 250px"> <strong ng-show="order.relation_type == true" ><?php echo lang('leadproposalconvertalert') ?></strong> <strong ng-show="order.relation_type != true" ><?php echo lang('convert_order_to_invoice'); ?></strong> </p>
              <section layout="row" layout-sm="column" layout-align="center center" layout-wrap>
                <md-button ng-click="Convert()" class="ion-filemd-primary pull-right" aria-label="Convert"><span ng-bind="lang.convert"></span></md-button>
              </section>
            </md-contet>
          </md-menu-content>
        </md-menu>
        <md-button ng-if="order.invoice_id" ng-href="<?php echo base_url('invoices/invoice/{{order.invoice_id}}')?>" class="md-icon-button">
          <md-tooltip md-direction="bottom"><?php echo lang('invoice') ?></md-tooltip>
          <md-icon><i class="ion-document-text text-success"></i></md-icon>
        </md-button>
        <md-menu md-position-mode="target-right target">
          <md-button aria-label="Open demo menu" class="md-icon-button" ng-click="$mdMenu.open($event)">
            <md-icon><i class="ion-android-more-vertical text-muted"></i></md-icon>
          </md-button>
          <md-menu-content width="4">
            <md-menu-item>
              <md-button ng-click="ViewProposal()" ng-bind="lang.view_order" aria-label="View Order"></md-button>
            </md-menu-item>
            <md-menu-item>
              <md-button ng-click="NewMilestone()" ng-bind="lang.sentexpirationreminder" aria-label="sentexpirationreminder"></md-button>
            </md-menu-item>
            <md-menu-item>
              <md-button ng-click="MarkAs(1,'Draft')" ng-bind="lang.markasdraft" aria-label="Draft"></md-button>
            </md-menu-item>
            <md-menu-item>
              <md-button ng-click="MarkAs(2,'Sent')" ng-bind="lang.markassent" aria-label="Sent"></md-button>
            </md-menu-item>
            <md-menu-item>
              <md-button ng-click="MarkAs(3,'Open')" ng-bind="lang.markasopen" aria-label="Open"></md-button>
            </md-menu-item>
            <md-menu-item>
              <md-button ng-click="MarkAs(4,'Revised')" ng-bind="lang.markasrevised" aria-label="Revised"></md-button>
            </md-menu-item>
            <md-menu-item>
              <md-button ng-click="MarkAs(5,'Declined')" ng-bind="lang.markasdeclined" aria-label="Complete"></md-button>
            </md-menu-item>
            <md-menu-item>
              <md-button ng-click="MarkAs(6,'Accepted')" ng-bind="lang.markasaccepted" aria-label="Accepted"></md-button>
            </md-menu-item>
            <md-divider></md-divider>
            <md-menu-item>
              <md-button ng-click="Update()" aria-label="Update">
                <div layout="row" flex>
                  <p flex ng-bind="lang.edit"></p>
                  <md-icon md-menu-align-target class="ion-edit" style="margin: auto 3px auto 0;"></md-icon>
                </div>
              </md-button>
            </md-menu-item>
            <md-divider></md-divider>
            <md-menu-item>
              <md-button ng-click="Delete()" aria-label="Delete">
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
      <md-tabs md-dynamic-height md-border-bottom>
        <md-tab label="Proposal">
          <md-content class="md-padding bg-white">
            <div class="proposal">
              <main>
                <div id="details" class="clearfix">
                  <div id="company">
                    <h2 class="name"><?php echo $settings['company'] ?></h2>
                    <div><?php echo $settings['address'] ?></div>
                    <div><?php echo lang('phone')?>:</b><?php echo $settings['phone'] ?></div>
                    <div><a href="mailto:<?php echo $settings['email'] ?>"><?php echo $settings['email'] ?></a></div>
                  </div>
                  <div id="client">
                    <div class="to"><span><?php echo lang('orderto'); ?>:</span></div>
                    <h2 class="name">
                      <?php if($orders['relation_type'] == 'customer'){if($orders['customercompany']===NULL){echo $orders['namesurname'];} else echo $orders['customercompany'];} ?>
                      <?php if($orders['relation_type'] == 'lead'){echo $orders['leadname'];} ?>
                    </h2>
                    <div class="address"><?php echo $orders['toaddress']; ?></div>
                    <div class="email"><a href="mailto:<?php echo $orders['toemail']; ?>"><?php echo $orders['toemail']; ?></a></div>
                  </div>
                  <div id="invoice">
                    <h1 ng-bind="order.long_id"></h1>
                    <div class="date"><?php echo lang('dateofissuance')?>: <span ng-bind="order.date"></span></div>
                    <div class="date text-bold"><?php echo lang('opentill')?>: <span ng-bind="order.opentill"></span></div>
                    <span class="text-uppercase" ng-bind="order.status_name"></span> </div>
                </div>
                <table border="0" cellspacing="0" cellpadding="0">
                  <thead>
                    <tr>
                      <th class="desc"><?php echo lang('description') ?></th>
                      <th class="qty"><?php echo lang('quantity') ?></th>
                      <th class="unit"><?php echo lang('price') ?></th>
                      <th class="discount"><?php echo lang('discount') ?></th>
                      <th class="tax"><?php echo $appconfig['tax_label'] ?></th>
                      <th class="total"><?php echo lang('total') ?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr ng-repeat="item in order.items">
                      <td class="desc"><h3 ng-bind="item.name"><br>
                        </h3>
                        <span ng-bind="item.description"></span></td>
                      <td class="qty" ng-bind="item.quantity"></td>
                      <td class="unit"><span ng-bind-html="item.price | currencyFormat:cur_code:null:true:cur_lct"></span></td>
                      <td class="discount" ng-bind="item.discount+'%'"></td>
                      <td class="tax" ng-bind="item.tax+'%'"></td>
                      <td class="total"><span ng-bind-html="item.total | currencyFormat:cur_code:null:true:cur_lct"></span></td>
                    </tr>
                  </tbody>
                </table>
                <div class="col-md-12 md-pr-0" style="font-weight: 900; font-size: 16px; color: #c7c7c7;">
                  <div class="col-md-10">
                    <div class="text-right text-uppercase text-muted"><?php echo lang('sub_total'); ?>:</div>
                    <div ng-show="linediscount() > 0" class="text-right text-uppercase text-muted"><?php echo lang('total_discount'); ?>:</div>
                    <div ng-show="totaltax() > 0"class="text-right text-uppercase text-muted"><?php echo lang('total'). ' '.$appconfig['tax_label']; ?>:</div>
                    <div class="text-right text-uppercase text-black"><?php echo lang('grand_total'); ?>:</div>
                  </div>
                  <div class="col-md-2">
                    <div class="text-right" ng-bind-html="subtotal() | currencyFormat:cur_code:null:true:cur_lct"></div>
                    <div ng-show="linediscount() > 0" class="text-right" ng-bind-html="linediscount() | currencyFormat:cur_code:null:true:cur_lct"></div>
                    <div ng-show="totaltax() > 0"class="text-right" ng-bind-html="totaltax() | currencyFormat:cur_code:null:true:cur_lct"></div>
                    <div class="text-right" ng-bind-html="grandtotal() | currencyFormat:cur_code:null:true:cur_lct"></div>
                  </div>
                </div>
              </main>
            </div>
          </md-content>
        </md-tab>
        <md-tab label="<?php echo lang('notes'); ?>">
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
              <div class="form-group pull-right">
                <button ng-click="AddNote()" type="button" class="btn btn-warning btn-xl ion-ios-paperplane" type="submit">
                <?php echo lang('addnote')?>
                </button>
              </div>
            </section>
          </md-content>
        </md-tab>
        <md-tab label="<?php echo lang('comments'); ?>">
          <md-content class="md-padding bg-white">
            <section class="ciuis-notes show-notes">
              <article ng-repeat="comment in order.comments" class="ciuis-note-detail">
                <div class="ciuis-note-detail-img"> <img src="<?php echo base_url('assets/img/comment.png') ?>" alt="" width="50" height="50" /> </div>
                <div class="ciuis-note-detail-body">
                  <div class="text">
                    <p ng-bind="comment.content"></p>
                  </div>
                  <p class="attribution"><strong><?php echo lang('customer_comment')?></strong> <?php echo lang('at')?> <span ng-bind="comment.created"></span></p>
                </div>
              </article>
            </section>
          </md-content>
        </md-tab>
        <md-tab label="<?php echo lang('reminders'); ?>">
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
    </md-content>
  </div>
  <ciuis-sidebar></ciuis-sidebar>
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
        <div class="form-group pull-right">
          <button ng-click="AddReminder()" type="button" class="btn btn-warning btn-xl ion-ios-paperplane" type="submit">
          <?php echo lang('addreminder')?>
          </button>
        </div>
      </md-content>
    </md-content>
  </md-sidenav>
</div>
<script type="text/ng-template" id="generate-order.html">
  <md-dialog aria-label="options dialog">
	<md-dialog-content layout-padding class="text-center">
		<md-content class="bg-white" layout-padding>
			<h2 class="md-title" ng-hide="PDFCreating == true"><?php echo lang('generate_proposal_pdf')?></h2>
			<h2 class="md-title" ng-if="PDFCreating == true"><?php echo lang('report_generating')?></h2>
			<span ng-hide="PDFCreating == false"><?php echo lang('generate_pdf_msg')?></span><br><br>
			<span ng-if="PDFCreating == false"><?php echo lang('generate_pdf_last_msg')?></span><br><br>
			<img ng-if="PDFCreating == true" ng-src="<?php echo base_url('assets/img/loading_time.gif') ?>" alt="">
			<a ng-if="PDFCreating == false" href="<?php echo base_url('assets/files/generated_pdf_files/orders/{{CreatedPDFName}}') ?>" download><img  width="30%"ng-src="<?php echo base_url('assets/img/download_pdf.png') ?>" alt=""></a>
		</md-content>
	</md-dialog-content>
	<md-dialog-actions>
	  <span flex></span>
	  <md-button ng-click="CloseModal()"><?php echo lang('cancel') ?>!</md-button>
	  <md-button ng-click="CreatePDF()"><?php echo lang('create') ?>!</md-button>
	</md-dialog-actions>
  </md-dialog>
</script> 
<script>
	var ORDERID = "<?php echo $orders['id'];?>";
</script>