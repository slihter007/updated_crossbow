<?php include_once(APPPATH . 'views/area/inc/header.php'); ?>
<?php $appconfig = get_appconfig(); ?>
<div class="ciuis-body-content" ng-controller="Invoice_Controller">
	<div class="main-content container-fluid col-md-9">
	<md-toolbar class="toolbar-white">
	  <div class="md-toolbar-tools">
		<md-button class="md-icon-button" aria-label="Settings" ng-disabled="true">
		  <md-icon><i class="ico-ciuis-invoices text-muted"></i></md-icon>
		</md-button>
		<h2 flex md-truncate ng-bind="invoice.properties.invoice_id"></h2>
		<md-button ng-if="is_admin == 'true' " ng-click="Discussions()" class="md-icon-button" aria-label="Discussions">
			<md-tooltip md-direction="bottom"><?php echo lang('discussions') ?></md-tooltip>
			<md-icon><i class="mdi ion-chatboxes text-muted"></i></md-icon>
		</md-button>
		<md-button ng-href="<?php echo base_url('share/pdf/{{invoice.token}}') ?>" class="md-icon-button" aria-label="PDF">
			<md-tooltip md-direction="bottom"><?php echo lang('download') ?></md-tooltip>
			<md-icon><i class="mdi mdi-collection-pdf text-muted"></i></md-icon>
		</md-button>
		<md-button  class="md-icon-button" aria-label="Print">
			<md-tooltip md-direction="bottom"><?php echo lang('print') ?></md-tooltip>
			<md-icon><i class="mdi mdi-print text-muted"></i></md-icon>
		</md-button>
	  </div>
	</md-toolbar>
	<md-content class="bg-white invoice">
		<div class="invoice-header col-md-12">
			<div class="invoice-from col-md-4 col-xs-12">
				<small><?php echo  lang('from'); ?></small>
				<address class="m-t-5 m-b-5">
					<strong ng-bind="settings.company"></strong><br>
					<span ng-bind="settings.address"></span><br>
					<span ng-bind="settings.phone"></span><br>
				</address>
			</div>
			<div class="invoice-to col-md-4 col-xs-12">
				<small><?php echo  lang('to'); ?></small>
				<address class="m-t-5 m-b-5">
					<strong ng-bind="invoice.properties.customer"></strong><br>
					<span ng-bind="invoice.properties.customer_address"></span><br>
					<span ng-bind="invoice.properties.customer_phone"></span>
				</address>
			</div>
			<div class="invoice-date col-md-4 col-xs-12">
				<div class="date m-t-5" ng-bind="invoice.created | date : 'MMM d, y'"></div>
				<div class="invoice-detail">
					<span ng-bind="invoice.serie + invoice.no"></span><br>
				</div>
			</div>
		</div>
		<div class="invoice-content col-md-12 md-p-0 xs-p-0 sm-p-0 lg-p-0">
			<div class="table-responsive">
				<table class="table table-invoice">
					<thead>
						<tr>
							<th><?php echo lang('product') ?></th>
							<th><?php echo lang('quantity') ?></th>
							<th><?php echo lang('price') ?></th>
							<th><?php echo $appconfig['tax_label'] ?></th>
							<th><?php echo lang('discount') ?></th>
							<th><?php echo lang('total') ?></th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="item in invoice.items">
							<td><span ng-bind="item.name"></span><br><small ng-bind="item.description"></small></td>
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
						<div class="sub-price">
							<small><?php echo lang('subtotal') ?></small>
							<span ng-bind-html="invoice.sub_total | currencyFormat:cur_code:null:true:cur_lct"></span>
						</div>
						<div class="sub-price">
							<i class="ion-plus-round"></i>
						</div>
						<div class="sub-price">
							<small><?php echo $appconfig['tax_label'] ?></small>
							<span ng-bind-html="invoice.total_tax | currencyFormat:cur_code:null:true:cur_lct"></span>
						</div>
						<div class="sub-price">
							<i class="ion-minus-round"></i>
						</div>
						<div class="sub-price">
							<small><?php echo lang('discount') ?></small>
							<span ng-bind-html="invoice.total_discount | currencyFormat:cur_code:null:true:cur_lct"></span>
						</div>
					</div>
				</div>
				<div class="invoice-price-right">
					<small><?php echo lang('total') ?></small>
					<span ng-bind-html="invoice.total | currencyFormat:cur_code:null:true:cur_lct"></span>
				</div>
			</div>
		</div>
	</md-content>
	</div>
	<div class="main-content container-fluid col-md-3 md-pl-0">
    <md-toolbar class="toolbar-white">
      <div class="md-toolbar-tools">
        <h2 flex md-truncate class="pull-left" ng-show="invoice.balance != 0 && invoice.status_id != 4"><strong><span><?php echo lang('balance') ?></span> : <span ng-bind-html="invoice.balance | currencyFormat:cur_code:null:true:cur_lct"></span></strong></h2>
        <md-menu md-position-mode="target-right target" style="margin-top: 15px;" ng-show="invoice.balance > 0">
	        		<md-button ng-disabled="paymentError" class="md-raised payNow-button" ng-click="$mdMenu.open($event)">
	        			<?php echo lang('pay_now') ?>
	        		</md-button>
	        		<md-menu-content width="4" ng-show="paymentOption"> 
	        			<?php if (($payment['paypal_active'] === true) && ($invoice['default_payment_method'] == 'paypal')) { ?>
	        				<md-menu-item>
								 <md-button ng-click="PayViaPaypal(invoice.token)"> 
								  <div layout="row" flex>
									<p flex><?php echo lang('pay_via_paypal') ?></p>
									<md-icon md-menu-align-target class="mdi mdi-paypal-alt" style="margin: auto 3px auto 0;"></md-icon>
								  </div>
								 </md-button>
							</md-menu-item>
						<?php } 
						if ($payment['authorize_aim_active'] === true && ($invoice['default_payment_method'] == 'authorize_aim')) { ?>
							<md-menu-item>
								 <md-button ng-click="PayViaAuthorize(invoice.token)">
								  <div layout="row" flex>
									<p flex><?php echo lang('pay_via_authorize_net') ?></p>
									<md-icon md-menu-align-target class="ion-card" style="margin: auto 3px auto 0;"></md-icon>
								  </div>
								 </md-button>
							</md-menu-item>
							<?php } 
						if ($payment['payu_money_active'] === true && ($invoice['default_payment_method'] == 'payumoney')) { ?>
							<md-menu-item>
								 <md-button ng-click="PayViaPayUMoney(invoice.token)">
								  <div layout="row" flex>
									<p flex><?php echo lang('pay_via_payumoney') ?></p>
									<md-icon md-menu-align-target class="ion-card" style="margin: auto 3px auto 0;"></md-icon>
								  </div>
								 </md-button>
							</md-menu-item>
							<?php } 
						if ($payment['ccavenue_active'] === true && ($invoice['default_payment_method'] == 'ccavenue')) { ?>
							<md-menu-item>
								 <md-button ng-click="PayViaCCAvenue(invoice.token)">
								  <div layout="row" flex>
									<p flex><?php echo lang('pay_via_ccavenue') ?></p>
									<md-icon md-menu-align-target class="ion-card" style="margin: auto 3px auto 0;"></md-icon>
								  </div>
								 </md-button>
							</md-menu-item>
							<?php } 
						if ($payment['stripe_active'] === true && ($invoice['default_payment_method'] == 'stripe')) { ?>
							<md-menu-item>
								 <md-button ng-click="PayViaStripe(invoice.token)">
								  <div layout="row" flex>
									<p flex><?php echo lang('pay_via_stripe') ?></p>
									<md-icon md-menu-align-target class="ion-card" style="margin: auto 3px auto 0;"></md-icon>
								  </div>
								 </md-button>
							</md-menu-item>
							<?php } 
						if ($payment['razorpay_active'] === true && ($invoice['default_payment_method'] == 'razorpay')) { ?>
							<md-menu-item>
								 <md-button ng-click="PayViaRazorpay(invoice.token)">
								  <div layout="row" flex>
									<p flex><?php echo lang('pay_via_razorpay') ?></p>
									<md-icon md-menu-align-target class="ion-card" style="margin: auto 3px auto 0;"></md-icon>
								  </div>
								 </md-button>
							</md-menu-item>
							<?php } ?>
						</md-menu-content>
					</md-menu>
        <h2 flex md-truncate class="pull-left text-success" ng-hide="invoice.balance != 0"><strong><?php echo lang('paidinv') ?></strong></h2>
        <h2 flex md-truncate class="pull-left text-danger text-uppercase" ng-show="invoice.status_id == 4"><strong><?php echo lang('cancelled') ?></strong></h2>
        <md-button ng-hide="invoice.partial_is != true" class="md-icon-button" aria-label="Partial">
          <md-tooltip md-direction="bottom"><?php echo lang('partial') ?></md-tooltip>
          <md-icon><i class="ion-pie-graph text-muted"></i></md-icon>
        </md-button>
        <md-button ng-hide="invoice.balance != 0" class="md-icon-button" aria-label="Paid" >
          <md-tooltip md-direction="bottom"><?php echo lang('paid') ?></md-tooltip>
          <md-icon><i class="ion-checkmark-circled text-success"></i></md-icon>
        </md-button>
      </div>
    </md-toolbar>
    <md-content class="bg-white" style="border-bottom:1px solid #e0e0e0;">
      <md-list flex>
        	<!-- <div layout="row" flex ng-show="invoice.balance > 0">
        		<md-input-container flex class="md-icon-float md-block md-title" style="margin-top: 25px;">
	        		<label>Amount</label>
	        		<md-icon style="font-size: 15px;">USD</md-icon>
	        		<input type="number" ng-model="payBalance" ng-change="checkBalance(payBalance)" ng-value="invoice.balance" min="0">
	        		<p ng-show="paymentError" class="my-message md-input-message-animation"><?php echo lang('pay_error')?> {{payBalance}}.</p>
	        	</md-input-container>
        		<md-menu md-position-mode="target-right target" style="margin-top: 15px;">
	        		<md-button ng-disabled="paymentError" class="md-raised payNow-button" ng-click="$mdMenu.open($event)">
	        			<?php echo lang('pay_now') ?>
	        		</md-button>
	        		<md-menu-content width="4" ng-show="paymentOption"> 
	        			<?php if ($payment['paypal_active'] === true) { ?>
	        				<md-menu-item>
								 <md-button ng-click="PayViaPaypal(invoice.token)"> 
								  <div layout="row" flex>
									<p flex><?php echo lang('pay_via_paypal') ?></p>
									<md-icon md-menu-align-target class="mdi mdi-paypal-alt" style="margin: auto 3px auto 0;"></md-icon>
								  </div>
								 </md-button>
							</md-menu-item>
						<?php } 
						if ($payment['authorize_aim_active'] === true) { ?>
							<md-menu-item>
								 <md-button ng-click="PayViaAuthorize(invoice.token)">
								  <div layout="row" flex>
									<p flex><?php echo lang('pay_via_authorize_net') ?></p>
									<md-icon md-menu-align-target class="ion-card" style="margin: auto 3px auto 0;"></md-icon>
								  </div>
								 </md-button>
							</md-menu-item>
							<?php } 
						if ($payment['payu_money_active'] === true) { ?>
							<md-menu-item>
								 <md-button ng-click="PayViaPayUMoney(invoice.token)">
								  <div layout="row" flex>
									<p flex><?php echo lang('pay_via_payumoney') ?></p>
									<md-icon md-menu-align-target class="ion-card" style="margin: auto 3px auto 0;"></md-icon>
								  </div>
								 </md-button>
							</md-menu-item>
							<?php } 
						if ($payment['ccavenue_active'] === true) { ?>
							<md-menu-item>
								 <md-button ng-click="PayViaCCAvenue(invoice.token)">
								  <div layout="row" flex>
									<p flex><?php echo lang('pay_via_ccavenue') ?></p>
									<md-icon md-menu-align-target class="ion-card" style="margin: auto 3px auto 0;"></md-icon>
								  </div>
								 </md-button>
							</md-menu-item>
							<?php } 
						if ($payment['stripe_active'] === true) { ?>
							<md-menu-item>
								 <md-button ng-click="PayViaStripe(invoice.token)">
								  <div layout="row" flex>
									<p flex><?php echo lang('pay_via_stripe') ?></p>
									<md-icon md-menu-align-target class="ion-card" style="margin: auto 3px auto 0;"></md-icon>
								  </div>
								 </md-button>
							</md-menu-item>
							<?php } 
						if ($payment['razorpay_active'] === true) { ?>
							<md-menu-item>
								 <md-button ng-click="PayViaRazorpay(invoice.token)">
								  <div layout="row" flex>
									<p flex><?php echo lang('pay_via_razorpay') ?></p>
									<md-icon md-menu-align-target class="ion-card" style="margin: auto 3px auto 0;"></md-icon>
								  </div>
								 </md-button>
							</md-menu-item>
							<?php } ?>
						</md-menu-content>
					</md-menu>
        	</div>
        	<md-divider></md-divider> -->
        	<md-list-item>
          <md-icon class="ion-ios-bell" style="margin-right: unset !important;"></md-icon>
          <p><strong><?php echo lang('duedate') ?>: </strong> <span ng-bind="invoice.duedate_text"></span></p>
        </md-list-item> 
      </md-list>
    </md-content>
    <md-toolbar class="toolbar-white">
      <div class="md-toolbar-tools">
        <h2 flex md-truncate class="text-bold"><?php echo lang('payments'); ?><br>
          <small flex md-truncate><?php echo lang('paymentsside'); ?></small>
        </h2>
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
          <md-divider></md-divider>
        </md-list-item>
      </md-list>
    </md-content>
  </div>
	<md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="Discussions"  ng-cloak>
	  <md-toolbar class="toolbar-white">
	  <div class="md-toolbar-tools">
		<md-button ng-click="close()" class="md-icon-button" aria-label="Close">
			 <i class="ion-android-arrow-forward"></i>
		</md-button>
		<md-truncate><?php echo lang('discussions') ?></md-truncate>
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
				<div class="ciuis-note-detail-img">
					<img src="<?php echo base_url('assets/img/comment.png') ?>" alt="" width="50" height="50" />
				</div>
				<div class="ciuis-note-detail-body">
					<div class="text"><p ng-bind="comment.content"></p></div>
					<p class="attribution"><?php echo lang('repliedby') ?>  <strong><span ng-bind="comment.full_name"></span></strong> at <span ng-bind="comment.created"></span></p>
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
      <md-button ng-click="AddComment($index)" style="margin-right:20px;" >
        <?php echo lang('reply')?>
      </md-button>
    </md-dialog-actions>
</md-dialog>
</div>
</div>
<script>
var INVOICEID = <?php echo $invoice['id']; ?>;
var INVOICECUSTOMER = <?php echo $invoice['customer_id']; ?>;
</script>
</div>
<?php include_once( APPPATH . 'views/area/inc/footer.php' );?>