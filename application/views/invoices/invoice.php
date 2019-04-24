<?php $appconfig = get_appconfig(); ?>
<div class="ciuis-body-content" ng-controller="Invoice_Controller">
  <div class="main-content container-fluid col-md-9"> 
    <div ng-show="invoiceLoader" layout-align="center center" class="text-center" id="circular_loader">
          <md-progress-circular md-mode="indeterminate" md-diameter="40"></md-progress-circular>
            <p style="font-size: 15px;margin-bottom: 5%;">
             <span>
                <?php echo lang('please_wait') ?> <br>
               <small><strong><?php echo lang('loading'). ' '. lang('invoice').'...' ?></strong></small>
             </span>
           </p>
         </div>
    <md-toolbar ng-show="!invoiceLoader" class="toolbar-white">
      <div class="md-toolbar-tools">
        <md-button class="md-icon-button" aria-label="Settings" ng-disabled="true">
          <md-icon><i class="ico-ciuis-invoices text-muted"></i></md-icon>
        </md-button>
        <h2 flex md-truncate ng-bind="invoice.properties.invoice_id"></h2>
        <?php if (!$this->session->userdata('other')) { ?>
        <md-button ng-click="Discussions()" class="md-icon-button" aria-label="Discussions">
          <md-tooltip md-direction="bottom"><?php echo lang('discussions') ?></md-tooltip>
          <md-icon><i class="mdi ion-chatboxes text-muted"></i></md-icon>
        </md-button> <!-- ng-href="<?php //echo base_url('invoices/send_email/{{invoice.id}}')?>" -->
        <md-button ng-click="sendEmail()" class="md-icon-button" aria-label="Email">
          <md-progress-circular ng-show="sendingEmail == true" md-mode="indeterminate" md-diameter="20"></md-progress-circular>
          <md-tooltip ng-hide="sendingEmail == true" md-direction="bottom" ng-bind="lang.send"></md-tooltip>
          <md-icon ng-hide="sendingEmail == true"><i class="mdi mdi-email text-muted"></i></md-icon>
        </md-button>
        <?php } ?>
        <md-button ng-click="GeneratePDF()" class="md-icon-button" aria-label="Pdf">
          <md-tooltip md-direction="bottom"><?php echo lang('pdf') ?></md-tooltip>
          <md-icon><i class="mdi mdi-collection-pdf text-muted"></i> </md-icon>
        </md-button>
        <md-button ng-href="<?php echo base_url('invoices/print_/{{invoice.id}}') ?>" class="md-icon-button" aria-label="Print">
          <md-tooltip md-direction="bottom" ng-bind="lang.print"></md-tooltip>
          <md-icon><i class="mdi mdi-print text-muted"></i></md-icon>
        </md-button>
        <?php if (!$this->session->userdata('other')) { ?>
        <md-menu md-position-mode="target-right target">
          <md-button aria-label="Open demo menu" class="md-icon-button" ng-click="$mdMenu.open($event)">
            <md-icon><i class="ion-android-more-vertical text-muted"></i></md-icon>
          </md-button>
          <md-menu-content width="4">
            <md-menu-item>
              <md-button ng-click="MarkAsDraft()">
                <div layout="row" flex>
                  <p flex ng-bind="lang.markasdraft"></p>
                  <md-icon md-menu-align-target class="ion-document" style="margin: auto 3px auto 0;"></md-icon>
                </div>
              </md-button>
            </md-menu-item>
            <md-menu-item>
              <md-button ng-click="MarkAsCancelled()">
                <div layout="row" flex>
                  <p flex ng-bind="lang.markascancelled"></p>
                  <md-icon md-menu-align-target class="mdi mdi-close-circle-o" style="margin: auto 3px auto 0;"></md-icon>
                </div>
              </md-button>
            </md-menu-item>
            <md-menu-item>
              <md-button ng-click="UodateInvoice(invoice.id)">
                <div layout="row" flex>
                  <p flex ng-bind="lang.update"></p>
                  <md-icon md-menu-align-target class="mdi mdi-edit" style="margin: auto 3px auto 0;"></md-icon>
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
        <?php } ?>
      </div>
    </md-toolbar>
    <md-content ng-show="!invoiceLoader" class="bg-white invoice">
      <div class="invoice-header col-md-12">
        <div class="invoice-from col-md-4 col-xs-12"> <small class="text-uppercase" ng-bind="lang.from"></small>
          <address class="m-t-5 m-b-5">
          <strong ng-bind="settings.company"></strong><br>
          <span ng-bind="settings.address"></span><br>
          <span ng-bind="settings.phone"></span><br>
          </address>
        </div>
        <div class="invoice-to col-md-4 col-xs-12"> <small class="text-uppercase" ng-bind="lang.to"></small>
          <address class="m-t-5 m-b-5">
          <strong ng-bind="invoice.properties.customer"></strong><br>
          <span ng-bind="invoice.properties.customer_address"></span><br>
          <span ng-bind="invoice.properties.customer_phone"></span>
          </address>
        </div>
        <div class="invoice-date col-md-4 col-xs-12">
          <div class="date m-t-5" ng-bind="invoice.created | date : 'MMM d, y'"></div>
          <div class="invoice-detail"> <span ng-bind="invoice.serie + invoice.no"></span><br>
          </div>
        </div>
      </div>
      <div class="invoice-content col-md-12 md-p-0 xs-p-0 sm-p-0 lg-p-0">
        <div class="table-responsive">
          <table class="table table-invoice">
            <thead>
              <tr>
                <th ng-bind="lang.product"></th>
                <th ng-bind="lang.quantity"></th>
                <th ng-bind="lang.price"></th>
                <th><?php echo $appconfig['tax_label'] ?></th>
                <th ng-bind="lang.discount"></th>
                <th ng-bind="lang.total"></th>
              </tr>
            </thead>
            <tbody>
              <tr ng-repeat="item in invoice.items">
                <td><span ng-bind="item.name"></span><br>
                  <small ng-bind="item.description"></small></td>
                <td ng-bind="item.quantity"></td>
                <td ng-bind-html="item.price | currencyFormat:cur_code:null:true:cur_lct"></td>
                <td ng-bind="item.tax + '%'"></td>
                <td ng-bind="item.discount + '%'"></td>
                <td ng-bind-html="item.total | currencyFormat:cur_code:null:true:cur_lct"></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="invoice-price">
          <div class="invoice-price-left">
            <div class="invoice-price-row">
              <div class="sub-price"> <small ng-bind="lang.subtotal"></small> <span ng-bind-html="invoice.sub_total | currencyFormat:cur_code:null:true:cur_lct"></span> </div>
              <div class="sub-price"> <i class="ion-plus-round"></i> </div>
              <div class="sub-price"> <small><?php echo $appconfig['tax_label'] ?></small> <span ng-bind-html="invoice.total_tax | currencyFormat:cur_code:null:true:cur_lct"></span> </div>
              <div class="sub-price"> <i class="ion-minus-round"></i> </div>
              <div class="sub-price"> <small ng-bind="lang.discount"></small> <span ng-bind-html="invoice.total_discount | currencyFormat:cur_code:null:true:cur_lct"></span> </div>
            </div>
          </div>
          <div class="invoice-price-right"> <small ng-bind="lang.total"></small> <span ng-bind-html="invoice.total | currencyFormat:cur_code:null:true:cur_lct"></span> </div>
        </div>
      </div>
    </md-content>
  </div>
  <div ng-show="!invoiceLoader" class="main-content container-fluid col-md-3 md-pl-0">
    <md-toolbar class="toolbar-white">
      <div class="md-toolbar-tools">
        <h2 flex md-truncate class="pull-left" ng-show="invoice.balance != 0 && invoice.status_id != 4"><strong><span ng-bind="lang.balance"></span> : <span ng-bind-html="invoice.balance | currencyFormat:cur_code:null:true:cur_lct"></span></strong></h2>
        <h2 flex md-truncate class="pull-left text-success" ng-hide="invoice.balance != 0"><strong ng-bind="lang.paidinv"></strong></h2>
        <h2 flex md-truncate class="pull-left text-danger text-uppercase" ng-show="invoice.status_id == 4"><strong ng-bind="lang.cancelled"></strong></h2>
        <md-button ng-hide="invoice.partial_is != true" class="md-icon-button" aria-label="Partial">
          <md-tooltip md-direction="bottom" ng-bind="lang.partial"></md-tooltip>
          <md-icon><i class="ion-pie-graph text-muted"></i></md-icon>
        </md-button>
        <md-button ng-hide="invoice.balance != 0" class="md-icon-button" aria-label="Paid" >
          <md-tooltip md-direction="bottom" ng-bind="lang.paid"></md-tooltip>
          <md-icon><i class="ion-checkmark-circled text-success"></i></md-icon>
        </md-button>
      </div>
    </md-toolbar>
    <md-content class="bg-white" style="border-bottom:1px solid #e0e0e0;">
      <md-list flex>
        <md-list-item>
          <md-icon class="ion-ios-bell"></md-icon>
          <p ng-bind="invoice.duedate_text"></p>
        </md-list-item>
        <md-divider></md-divider>
        <md-list-item>
          <md-icon class="ion-android-mail"></md-icon>
          <p ng-bind="invoice.mail_status"></p>
        </md-list-item>
        <md-divider></md-divider>
        <md-list-item>
          <md-icon class="ion-person"></md-icon>
          <p><strong ng-bind="invoice.properties.invoice_staff"></strong></p>
        </md-list-item>
      </md-list>
      <md-subheader ng-if="custom_fields.length > 0"><?php echo lang('custom_fields'); ?></md-subheader>
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
    <md-toolbar class="toolbar-white">
      <div class="md-toolbar-tools">
        <h2 flex md-truncate class="text-bold"><?php echo lang('payments'); ?><br>
          <small flex md-truncate><?php echo lang('paymentsside'); ?></small>
        </h2>
          <?php if (!$this->session->userdata('other')) { ?>
        <md-button ng-show="invoice.balance != 0 && invoice.status_id != 4" ng-click="RecordPayment()" class="md-icon-button" aria-label="Record Payment">
          <md-tooltip md-direction="left"><?php echo lang('recordpayment') ?></md-tooltip>
          <md-icon><i class="ion-android-add-circle text-success"></i></md-icon> 
        </md-button>
        <?php } ?>
      </div>
    </md-toolbar>
    <md-content class="bg-white">
      <md-content ng-show="!invoice.payments.length" class="md-padding no-item-payment bg-white"></md-content>
      <md-list flex>
        <md-list-item class="md-2-line" ng-repeat="payment in invoice.payments">
          <md-icon class="ion-arrow-down-a text-muted"></md-icon>
          <div class="md-list-item-text">
            <h3 ng-bind="payment.name"></h3>
            <p ng-bind-html="payment.amount | currencyFormat:cur_code:null:true:cur_lct"></p>
          </div>
          <md-button class="md-secondary md-primary md-fab md-mini md-icon-button" ng-click="doSecondaryAction($event)" aria-label="call">
            <md-icon class="ion-ios-search-strong"></md-icon>
          </md-button>
          <md-divider></md-divider>
        </md-list-item>
      </md-list>
    </md-content>
  </div>
  <md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="RecordPayment">
    <md-toolbar class="toolbar-white" style="background:#262626">
      <div class="md-toolbar-tools">
        <md-button ng-click="close()" class="md-icon-button" aria-label="Close"> <i class="ion-android-arrow-forward"></i> </md-button>
        <md-truncate><?php echo lang('recordpayment') ?></md-truncate>
      </div>
    </md-toolbar>
    <md-content layout-padding="">
      <form name="InvoiceRecordPayment">
        <md-content layout-padding>
          <md-input-container class="md-block">
            <label><?php echo lang('datepayment') ?></label>
            <input mdc-datetime-picker="" date="true" time="true" type="text" id="datetime" placeholder="<?php echo lang('chooseadate') ?>" show-todays-date="" minutes="true" show-icon="true" ng-model="date" class=" dtp-no-msclear dtp-input md-input">
          </md-input-container>
          <md-input-container class="md-block">
            <label><?php echo lang('amount') ?></label>
            <input required type="number" name="amount" ng-model="amount" max="{{invoice.balance}}"/>
          </md-input-container>
          <md-input-container class="md-block">
            <label><?php echo lang('description') ?></label>
            <textarea required name="not" ng-model="not" placeholder="<?php echo lang('typeSomething'); ?>" class="form-control"></textarea>
          </md-input-container>
          <md-input-container class="md-block">
            <label><?php echo lang('account'); ?></label>
            <md-select placeholder="<?php echo lang('account'); ?>" ng-model="account" name="account" style="min-width: 200px;">
              <md-option ng-value="account.id" ng-repeat="account in accounts">{{account.name}}</md-option>
            </md-select>
          </md-input-container>
          <section layout="row" layout-sm="column" layout-align="center right" layout-wrap>
            <md-button ng-click="AddPayment()" class="md-raised md-primary pull-right template-button" ng-disabled="doing == true">
              <span ng-hide="doing == true"><?php echo lang('save');?></span>
              <md-progress-circular class="white" ng-show="doing == true" md-mode="indeterminate" md-diameter="20"></md-progress-circular>
            </md-button>
            <!-- <md-button ng-click="AddPayment()" class="md-raised md-primary pull-right" ng-bind="lang.save"></md-button> -->
          </section>
        </md-content>
      </form>
    </md-content>
  </md-sidenav>
  <md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="Discussions">
    <md-toolbar class="toolbar-white">
      <div class="md-toolbar-tools">
        <md-button ng-click="close()" class="md-icon-button" aria-label="Close"> <i class="ion-android-arrow-forward"></i> </md-button>
        <h2 flex md-truncate><?php echo lang('discussions'); ?></h2>
        <md-button ng-click="NewDiscussion()" class="md-icon-button" aria-label="Record Payment">
          <md-tooltip md-direction="left"><?php echo lang('new_disscussion'); ?></md-tooltip>
          <md-icon><i class="ion-plus-round text-muted"></i></md-icon>
        </md-button>
      </div>
    </md-toolbar>
    <md-content class="bg-white">
      <md-list flex>
        <md-list-item class="md-2-line" ng-repeat="discussion in discussions" ng-click="Discussion_Detail($index)" aria-label="Discussion Detail">
          <div  data-letter-avatar="--" class="ticket-area-av-im2 md-avatar"></div>
          <div class="md-list-item-text" ng-class="{'md-offset': phone.options.offset }">
            <h3 ng-bind="discussion.subject"></h3>
            <p ng-bind="discussion.contact"></p>
          </div>
          <md-divider></md-divider>
        </md-list-item>
      </md-list>
    </md-content>
  </md-sidenav>
  <md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="NewDiscussion">
    <md-toolbar class="toolbar-white">
      <div class="md-toolbar-tools">
        <md-button ng-click="close()" class="md-icon-button" aria-label="Close"> <i class="ion-android-arrow-forward"></i> </md-button>
        <h2 flex md-truncate><?php echo lang('new_disscussion'); ?></h2>
        <md-switch ng-model="ShowCustomer" aria-label="Type"><strong class="text-muted"><?php echo lang('show_customer'); ?></strong></md-switch>
      </div>
    </md-toolbar>
    <md-content layout-padding="">
      <md-content layout-padding>
        <md-input-container class="md-block">
          <label><?php echo lang('subject') ?></label>
          <input required type="text" ng-model="new_discussion.subject"/>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('description') ?></label>
          <textarea required ng-model="new_discussion.description" placeholder="<?php echo lang('typeSomething'); ?>" class="form-control"></textarea>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('contact'); ?></label>
          <md-select placeholder="<?php echo lang('contact'); ?>" ng-model="new_discussion.contact_id" name="contact" style="min-width: 200px;">
            <md-option ng-value="contact.id" ng-repeat="contact in contacts">{{contact.name}}</md-option>
          </md-select>
        </md-input-container>
        <div class="form-group pull-right">
          <button ng-click="CreateDiscussion()" class="btn btn-warning btn-xl ion-ios-paperplane"> <?php echo lang('create')?></button>
        </div>
      </md-content>
    </md-content>
  </md-sidenav>
  <div style="visibility: hidden">
    <div ng-repeat="discussion in discussions" class="md-dialog-container" id="Discussion_Detail-{{discussion.id}}">
      <md-dialog aria-label="Discussion_Detail">
        <md-toolbar class="toolbar-white">
          <div class="md-toolbar-tools">
            <h2>{{discussion.subject}} by {{discussion.contact}}</h2>
            <span flex></span>
            <md-button class="md-icon-button" ng-click="CloseModal()">
              <md-icon class="ion-close-round" aria-label="Close dialog" style="color:black"></md-icon>
            </md-button>
          </div>
        </md-toolbar>
        <md-dialog-content style="max-width:800px;max-height:810px; ">
          <md-content class="md-padding bg-white">
            <md-list flex>
              <md-list-item>
                <md-icon class="mdi mdi-calendar"></md-icon>
                <p><?php echo lang('date')?></p>
                <p class="md-secondary" ng-bind="discussion.datecreated | date : 'MMM d, y'"></p>
              </md-list-item>
              <md-divider></md-divider>
              <md-content class="bg-white" layout-padding>
                <p class="md-secondary" ng-bind="discussion.description"></p>
              </md-content>
              <md-divider></md-divider>
            </md-list>
            <md-content class="bg-white" layout-padding>
              <section class="ciuis-notes show-notes">
                <article ng-repeat="comment in discussion.comments" class="ciuis-note-detail">
                  <div class="ciuis-note-detail-img"> <img src="<?php echo base_url('assets/img/comment.png') ?>" alt="" width="50" height="50" /> </div>
                  <div class="ciuis-note-detail-body">
                    <div class="text">
                      <p ng-bind="comment.content"></p>
                    </div>
                    <p class="attribution"><?php echo lang('repliedby'); ?> <strong><span ng-bind="comment.full_name"></span></strong> at <span ng-bind="comment.created"></span></p>
                  </div>
                </article>
              </section>
              <md-input-container class="md-block">
                <label><?php echo lang('message') ?></label>
                <textarea required ng-model="discussion.newcontent" placeholder="<?php echo lang('typeSomething'); ?>" class="form-control comment-description"></textarea>
              </md-input-container>
            </md-content>
          </md-content>
        </md-dialog-content>
        <md-dialog-actions layout="row">
          <md-button ng-click="AddComment($index)" style="margin-right:20px;" > <?php echo lang('reply')?> </md-button>
        </md-dialog-actions>
      </md-dialog>
    </div>
  </div>
  <script>
var INVOICEID = <?php echo $invoices['id']; ?>;
var INVOICECUSTOMER = <?php echo $invoices['customer_id']; ?>;
</script> 
  <script type="text/ng-template" id="generate-invoice.html">
  <md-dialog aria-label="options dialog">
	<md-dialog-content layout-padding class="text-center">
		<md-content class="bg-white" layout-padding>
			<h2 class="md-title" ng-hide="PDFCreating == true"><?php echo lang('generate_pdf') ?></h2>
			<h2 class="md-title" ng-if="PDFCreating == true"><?php echo lang('report_generating') ?></h2>
			<span ng-hide="PDFCreating == false"><?php echo lang('generate_pdf_msg') ?></span><br><br>
			<span ng-if="PDFCreating == false"><?php echo lang('generate_pdf_last_msg') ?></span><br><br>
			<img ng-if="PDFCreating == true" ng-src="<?php echo base_url('assets/img/loading_time.gif') ?>" alt="">
			<a ng-if="PDFCreating == false" href="<?php echo base_url('assets/files/generated_pdf_files/invoices/{{CreatedPDFName}}') ?>" download><img  width="30%"ng-src="<?php echo base_url('assets/img/download_pdf.png') ?>" alt=""></a>
		</md-content>
	</md-dialog-content>
	<md-dialog-actions>
	  <span flex></span>
	  <md-button ng-click="CloseModal()"><?php echo lang('cancel') ?>!</md-button>
	  <md-button ng-click="CreatePDF()"><?php echo lang('create') ?>!</md-button>
	</md-dialog-actions>
  </md-dialog>
</script> 
</div>
