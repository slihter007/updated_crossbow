<?php include_once(APPPATH . 'views/inc/header.php'); ?>
<?php $appconfig = get_appconfig(); ?>
<div class="ciuis-body-content" ng-controller="Invoice_Controller">
  <div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
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
        <md-button class="md-icon-button" aria-label="Invoice" ng-disabled="true">
          <md-icon><i class="ico-ciuis-invoices text-muted"></i></md-icon>
        </md-button>
        <h2 flex md-truncate><?php echo lang('updateinvoicetitle') ?></h2>
        <md-switch ng-model="invoice.recurring_status" aria-label="recurring_status"> <strong class="text-muted"><?php echo lang('recurring') ?></strong> </md-switch>
        <md-button ng-href="<?php echo base_url('invoices/invoice/{{invoice.id}}')?>" class="md-icon-button" aria-label="View">
          <md-tooltip md-direction="bottom"><?php echo lang('view') ?></md-tooltip>
          <md-icon><i class="ion-eye text-warning"></i></md-icon>
        </md-button>
        <md-button ng-href="<?php echo base_url('invoices/invoice/{{invoice.id}}')?>" class="md-icon-button" aria-label="Cancel">
          <md-tooltip md-direction="bottom"><?php echo lang('cancel') ?></md-tooltip>
          <md-icon><i class="ion-close-circled text-danger"></i></md-icon>
        </md-button>
        <md-button ng-click="saveAll()" class="md-icon-button" aria-label="Save">
          <md-progress-circular ng-show="savingInvoice == true" md-mode="indeterminate" md-diameter="20"></md-progress-circular>
          <md-tooltip ng-hide="savingInvoice == true" md-direction="bottom"><?php echo lang('save') ?></md-tooltip>
          <md-icon ng-hide="savingInvoice == true"><i class="ion-checkmark-circled text-success"></i></md-icon>
        </md-button>
      </div>
    </md-toolbar>
    <md-content ng-show="!invoiceLoader" class="bg-white" layout-padding>
      <div layout-gt-xs="row">
        <md-input-container class="md-block" flex-gt-sm>
          <label><?php echo lang('serie')?></label>
          <input ng-model="invoice.serie" name="serie">
        </md-input-container>
        <md-input-container class="md-block" flex-gt-sm>
          <label><?php echo lang('invoicenumber')?></label>
          <input ng-model="invoice.no" name="no">
        </md-input-container>
        <md-input-container class="md-block" flex-gt-xs>
          <label><?php echo lang('customer'); ?></label>
          <md-select required placeholder="<?php echo lang('choisecustomer'); ?>" ng-model="invoice.customer" ng-change="changeBank(invoice.customer)" name="customer" style="min-width: 200px;">
            <md-option ng-value="customer.id" ng-repeat="customer in all_customers">{{customer.name}}</md-option>
          </md-select>
          <div ng-messages="userForm.customer" role="alert" multiple>
            <div ng-message="required" class="my-message"><?php echo lang('you_must_supply_a_customer') ?></div>
          </div>
        </md-input-container>
        <md-input-container>
          <label><?php echo lang('dateofissuance') ?></label>
          <md-datepicker name="created" ng-model="invoice.created"></md-datepicker>
        </md-input-container>
      </div>
      <div ng-show="invoice.recurring_status" layout-gt-xs="row">
        <input name="recurring_id" ng-model="invoice.recurring_id" type="hidden">
        <md-input-container class="md-block" flex-gt-xs>
          <label><?php echo lang('recurring_period') ?></label>
          <input type="number" ng-model="invoice.recurring_period" name="recurring_period">
        </md-input-container>
        <md-input-container class="md-block" flex-gt-xs>
          <label><?php echo lang('recurring_type') ?></label>
          <md-select ng-model="invoice.recurring_type" name="recurring_type">
            <md-option value="0"><?php echo lang('days') ?></md-option>
            <md-option value="1" selected><?php echo lang('weeks') ?></md-option>
            <md-option value="2"><?php echo lang('months') ?></md-option>
            <md-option value="3"><?php echo lang('years') ?></md-option>
          </md-select>
        </md-input-container>
        <md-input-container>
          <label><?php echo lang('ends_on') ?></label>
          <md-datepicker md-min-date="date" name="recurring_endDate" ng-model="invoice.recurring_endDate"></md-datepicker>
          <div >
            <div ng-message="required" class="my-message"><?php echo lang('leave_blank_for_lifetime') ?></div>
          </div>
        </md-input-container>
      </div>
      <div ng-show="invoice.status_id != '2'" layout-gt-xs="row">
        <md-input-container class="md-block" flex-gt-sm>
          <label><?php echo lang('duenote') ?></label>
          <input ng-model="invoice.duenote" name="duenote">
        </md-input-container>
        <md-input-container>
          <label><?php echo lang('duedate') ?></label>
          <md-datepicker md-min-date="invoice.created" name="duedate" ng-model="invoice.duedate"></md-datepicker>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('payment_method'); ?></label>
          <md-select placeholder="<?php echo lang('default_payment_method'); ?>" ng-model="invoice.default_payment_method" name="default_payment_method" style="min-width: 200px;">
            <?php if ($payment['paypal_active'] === true) { ?>
              <md-option ng-value="'paypal'"><?php echo lang('paypal') ?></md-option>
            <?php } 
            if ($payment['authorize_aim_active'] === true) { ?>
              <md-option ng-value="'authorize_aim'"><?php echo lang('authorize_aim') ?></md-option>
              <?php } 
            if ($payment['payu_money_active'] === true) { ?>
              <md-option ng-value="'payumoney'"><?php echo lang('payumoney') ?></md-option>
              <?php } 
            if ($payment['ccavenue_active'] === true) { ?>
              <md-option ng-value="'ccavenue'"><?php echo lang('ccavenue') ?></md-option>
              <?php } 
            if ($payment['stripe_active'] === true) { ?>
              <md-option ng-value="'stripe'"><?php echo lang('stripe') ?></md-option>
              <?php } 
            if ($payment['razorpay_active'] === true) { ?>
              <md-option ng-value="'razorpay'"><?php echo lang('razorpay') ?></md-option>
              <?php } 
              if ($payment['primary_bank_account']) { ?>
              <md-option ng-value="'bank'"><?php echo $payment['bank'].' '. lang('account') ?></md-option>
              <?php } ?>
          </md-select>
        </md-input-container>
      </div>
    </md-content>
    <md-content ng-show="!invoiceLoader" class="bg-white" layout-padding>
      <md-list-item ng-repeat="item in invoice.items">
        <div layout-gt-sm="row">
          <md-autocomplete
  	  	 	md-autofocus
  	  	 	md-items="product in GetProduct(item.name)"
		    md-search-text="item.name"
		    md-item-text="product.name"   
		    md-selected-item="selectedProduct"
		    md-no-cache="true"
		    md-min-length="0"
		    md-floating-label="<?php echo lang('productservice'); ?>"
		    placeholder="What is your favorite US state?">
            <md-item-template> <span md-highlight-text="item.name">{{product.name}}</span> <strong ng-bind-html="product.price | currencyFormat:cur_code:null:true:cur_lct"></strong> </md-item-template>
          </md-autocomplete>
          <md-input-container class="md-block">
            <label><?php echo lang('description'); ?></label>
            <input type="hidden" ng-model="item.name">
            <bind-expression ng-init="selectedProduct.name = item.name" expression="selectedProduct.name" ng-model="item.name" />
            <input ng-model="item.description">
            <bind-expression ng-init="selectedProduct.description = item.description" expression="selectedProduct.description" ng-model="item.description" />
            <input type="hidden" ng-model="item.product_id">
            <bind-expression ng-init="selectedProduct.product_id = item.product_id" expression="selectedProduct.product_id" ng-model="item.product_id" />
            <input type="hidden" ng-model="item.code" ng-value="selectedProduct.code">
            <bind-expression ng-init="selectedProduct.code = item.code" expression="selectedProduct.code" ng-model="item.code" />
          </md-input-container>
          <md-input-container class="md-block" flex-gt-sm>
            <label><?php echo lang('quantity'); ?></label>
            <input ng-model="item.quantity" >
          </md-input-container>
          <md-input-container class="md-block" flex-gt-xs>
            <label><?php echo lang('unit'); ?></label>
            <input ng-model="item.unit" >
          </md-input-container>
          <md-input-container class="md-block">
            <label><?php echo lang('price'); ?></label>
            <input ng-model="item.price">
            <bind-expression ng-init="selectedProduct.price = item.price" expression="selectedProduct.price" ng-model="item.price" />
          </md-input-container>
          <md-input-container class="md-block" flex-gt-sm>
            <label><?php echo $appconfig['tax_label']; ?></label>
            <input ng-model="item.tax">
            <bind-expression ng-init="selectedProduct.tax = item.tax" expression="selectedProduct.tax" ng-model="item.tax" />
          </md-input-container>
          <md-input-container class="md-block" flex-gt-xs>
            <label><?php echo lang('discount'); ?></label>
            <input ng-model="item.discount">
          </md-input-container>
          <md-input-container class="md-block">
            <label><?php echo lang('total'); ?></label>
            <input ng-value="item.quantity * item.price + ((item.tax)/100*item.quantity * item.price) - ((item.discount)/100*item.quantity * item.price)">
          </md-input-container>
        </div>
        <md-icon aria-label="Remove Line" ng-click="remove($index)" class="md-secondary ion-trash-b text-muted"></md-icon>
      </md-list-item>
      <md-content class="bg-white" layout-padding>
        <div class="col-md-6">
          <md-button ng-click="add()" class="md-fab pull-left" ng-disabled="false" aria-label="Add Line">
            <md-icon class="ion-plus-round text-muted"></md-icon>
          </md-button>
        </div>
        <div class="col-md-6 md-pr-0" style="font-weight: 900; font-size: 16px; color: #c7c7c7;">
          <div class="col-md-7">
            <div class="text-right text-uppercase text-muted"><?php echo lang('sub_total'); ?>:</div>
            <div ng-show="linediscount() > 0" class="text-right text-uppercase text-muted"><?php echo lang('total_discount'); ?>:</div>
            <div ng-show="totaltax() > 0"class="text-right text-uppercase text-muted"><?php echo lang('total').' '.$appconfig['tax_label']; ?>:</div>
            <div class="text-right text-uppercase text-black"><?php echo lang('grand_total'); ?>:</div>
          </div>
          <div class="col-md-5">
            <div class="text-right" ng-bind-html="subtotal() | currencyFormat:cur_code:null:true:cur_lct"></div>
            <div ng-show="linediscount() > 0" class="text-right" ng-bind-html="linediscount() | currencyFormat:cur_code:null:true:cur_lct"></div>
            <div ng-show="totaltax() > 0"class="text-right" ng-bind-html="totaltax() | currencyFormat:cur_code:null:true:cur_lct"></div>
            <div class="text-right" ng-bind-html="grandtotal() | currencyFormat:cur_code:null:true:cur_lct"></div>
          </div>
        </div>
      </md-content>
    </md-content>
  </div>
  <div ng-show="!invoiceLoader" class="main-content container-fluid lg-pl-0 col-xs-12 col-md-12 col-lg-3">
    <md-toolbar class="toolbar-white">
      <div class="md-toolbar-tools">
        <md-button class="md-icon-button" aria-label="Invoice" ng-disabled="true">
          <md-icon><i class="ico-ciuis-invoices text-muted"></i></md-icon>
        </md-button>
        <h2 flex md-truncate><?php echo lang('billing_and_shipping_details'); ?></h2>
      </div>
    </md-toolbar>
    <md-subheader class="md-primary bg-white text-uppercase text-bold"><?php echo lang('billing_address'); ?></md-subheader>
    <md-divider></md-divider>
    <md-content layout-padding class="bg-white">
      <address class="m-t-5 m-b-5">
      <strong ng-bind="invoice.billing_street"></strong><br>
      <span ng-bind="invoice.billing_city"></span> / <span ng-bind="invoice.billing_state"></span> <span ng-bind="invoice.billing_zip"></span><br>
      <strong ng-bind="invoice.billing_country.shortname"></strong>
      <srong ng-bind="invoice.billing_country.id"></srong>
      </address>
      <md-content ng-if='EditBilling == true' layout-padding class="bg-white">
        <md-input-container class="md-block">
          <label><?php echo lang('address') ?></label>
          <textarea ng-model="invoice.billing_street" md-maxlength="500" rows="2" md-select-on-focus></textarea>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('city'); ?></label>
          <input name="city" ng-model="invoice.billing_city">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('state'); ?></label>
          <input name="state" ng-model="invoice.billing_state">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('zipcode'); ?></label>
          <input name="zipcode" ng-model="invoice.billing_zip">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('country'); ?></label>
          <md-select placeholder="<?php echo lang('country'); ?>" ng-model="invoice.billing_country"  style="min-width: 200px;">
            <md-option ng-value="country" ng-repeat="country in countries">{{country.shortname}}</md-option>
          </md-select>
        </md-input-container>
      </md-content>
      <md-switch ng-model="NeedShippingAddress" aria-label="Status"><strong class="text-muted"><?php echo lang('need_shipping_address'); ?></strong></md-switch>
      <md-button ng-show='EditBilling == false' ng-click="EditBilling = true" ng-init="EditBilling=false" class="md-icon-button pull-right" aria-label="Edit">
        <md-icon><i class="mdi mdi-edit text-muted"></i></md-icon>
        <md-tooltip md-direction="left"><?php echo lang('edit'); ?></md-tooltip>
      </md-button>
      <md-button ng-show='EditBilling == true' ng-click="EditBilling = false" class="md-icon-button pull-right" aria-label="Hide Billing Form">
        <md-icon><i class="mdi mdi-minus-circle-outline text-muted"></i></md-icon>
        <md-tooltip md-direction="left"><?php echo lang('hide'); ?></md-tooltip>
      </md-button>
      <md-button ng-click='CopyBillingFromCustomer()' class="md-icon-button pull-right" aria-label="Billing Copy">
        <md-icon><i class="mdi mdi-copy text-muted"></i></md-icon>
        <md-tooltip md-direction="left"><?php echo lang('copy_from_customer'); ?></md-tooltip>
      </md-button>
    </md-content>
    <md-divider></md-divider>
    <md-subheader ng-show='NeedShippingAddress == true' class="md-primary bg-white text-uppercase text-bold"><?php echo lang('shipping_address'); ?></md-subheader>
    <md-divider ng-show='NeedShippingAddress == true'></md-divider>
    <md-content  ng-show='NeedShippingAddress == true' layout-padding class="bg-white">
      <address ng-hide='EditShipping == true' class="m-t-5 m-b-5">
      <strong ng-bind="invoice.shipping_street"></strong><br>
      <span ng-bind="invoice.shipping_city"></span> / <span ng-bind="invoice.billing_state"></span> <span ng-bind="invoice.shipping_zip"></span><br>
      <strong ng-bind="invoice.shipping_country.shortname"></strong>
      <srong ng-bind="invoice.shipping_country.id"></srong>
      </address>
      <md-content ng-show='EditShipping == true' layout-padding class="bg-white">
        <md-input-container class="md-block">
          <label><?php echo lang('address') ?></label>
          <textarea ng-model="invoice.shipping_street" md-maxlength="500" rows="2" md-select-on-focus></textarea>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('city'); ?></label>
          <input name="city" ng-model="invoice.shipping_city">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('state'); ?></label>
          <input name="state" ng-model="invoice.shipping_state">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('zipcode'); ?></label>
          <input name="zipcode" ng-model="invoice.shipping_zip">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('country'); ?></label>
          <md-select placeholder="<?php echo lang('country'); ?>" ng-model="invoice.shipping_country"  style="min-width: 200px;">
            <md-option ng-value="country" ng-repeat="country in countries">{{country.shortname}}</md-option>
          </md-select>
        </md-input-container>
        <bind-expression ng-init="invoice.shipping_country = '----'" expression="customer.shipping_country" ng-model="invoice.shipping_country" />
      </md-content>
      <md-button ng-show='EditShipping == false' ng-click="EditShipping = true" ng-init="EditShipping=false" class="md-icon-button pull-right" aria-label="Edit">
        <md-icon><i class="mdi mdi-edit text-muted"></i></md-icon>
        <md-tooltip md-direction="left"><?php echo lang('edit'); ?></md-tooltip>
      </md-button>
      <md-button ng-show='EditShipping == true' ng-click="EditShipping = false" class="md-icon-button pull-right" aria-label="Hide Form">
        <md-icon><i class="mdi mdi-minus-circle-outline text-muted"></i></md-icon>
        <md-tooltip md-direction="left"><?php echo lang('hide'); ?></md-tooltip>
      </md-button>
      <md-button ng-click='CopyShippingFromCustomer()'  class="md-icon-button pull-right" aria-label="Cop Shipping">
        <md-icon><i class="mdi mdi-copy text-muted"></i></md-icon>
        <md-tooltip md-direction="left"><?php echo lang('copy_from_customer'); ?></md-tooltip>
      </md-button>
    </md-content>
    <md-content class="bg-white">
    <custom-fields-vertical></custom-fields-vertical>
    </md-toolbar>
    <md-divider></md-divider>
  </div>
  <div id="remove{{invoice.id}}" tabindex="-1" role="dialog" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-hidden="true" class="close"><span class="mdi mdi-close"></span></button>
        </div>
        <div class="modal-body">
          <div class="text-center">
            <div class="text-danger"><span class="modal-main-icon mdi mdi-close-circle-o"></span> </div>
            <h3><?php echo lang('attention'); ?></h3>
            <p><?php echo lang('inv_remove_msg'); ?></p>
            <div class="xs-mt-50"> <a type="button" data-dismiss="modal" class="btn btn-space btn-default"><?php echo lang('cancel'); ?></a> <a href="<?php echo site_url('invoices/remove/'.$invoices['id']); ?>" type="button" class="btn btn-space btn-danger"><?php echo lang('delete'); ?></a> </div>
          </div>
        </div>
        <div class="modal-footer"></div>
      </div>
    </div>
  </div>
  <script>
	var INVOICEID = <?php echo $invoices['id']; ?>;
	var INVOICECUSTOMER = <?php echo $invoices['customer_id']; ?>;
	</script>
</div>
<?php include_once( APPPATH . 'views/inc/footer.php' );?>