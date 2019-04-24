<?php $appconfig = get_appconfig(); ?>
<div class="ciuis-body-content" ng-controller="Proposals_Controller">
  <div  class="main-content container-fluid col-xs-12 col-md-12 col-lg-9"> 
    <div ng-show="proposalsLoader" layout-align="center center" class="text-center" id="circular_loader">
        <md-progress-circular md-mode="indeterminate" md-diameter="40"></md-progress-circular>
        <p style="font-size: 15px;margin-bottom: 5%;">
         <span>
            <?php echo lang('please_wait') ?> <br>
           <small><strong><?php echo lang('loading').'...' ?></strong></small>
         </span>
       </p>
     </div>
    <md-toolbar ng-show="!proposalsLoader" class="toolbar-white">
      <div class="md-toolbar-tools">
        <md-button class="md-icon-button" aria-label="Proposal" ng-disabled="true">
          <md-icon><i class="ico-ciuis-proposals text-muted"></i></md-icon>
        </md-button>
        <h2 flex md-truncate><?php echo lang('createproposal') ?></h2>
        <md-switch ng-model="proposal_type" aria-label="Type"><strong class="text-muted"><?php echo lang('for_lead')?></strong></md-switch>
        <md-button ng-href="<?php echo base_url('proposals')?>" class="md-icon-button" aria-label="Save">
          <md-tooltip md-direction="bottom"><?php echo lang('cancel') ?></md-tooltip>
          <md-icon><i class="ion-close-circled text-danger"></i></md-icon>
        </md-button>
        <md-button type="button" ng-click="saveAll()" class="md-icon-button" aria-label="Save">
          <md-progress-circular ng-show="savingProposal == true" md-mode="indeterminate" md-diameter="20"></md-progress-circular>
          <md-tooltip ng-hide="savingProposal == true" md-direction="bottom"><?php echo lang('create') ?></md-tooltip>
          <md-icon ng-hide="savingProposal == true"><i class="ion-checkmark-circled text-success"></i></md-icon>
        </md-button>
      </div>
    </md-toolbar>
    <md-content ng-show="!proposalsLoader" class="bg-white" layout-padding>
      <div layout-gt-xs="row">
        <md-input-container class="md-block" flex-gt-sm>
          <label><?php echo lang('subject')?></label>
          <input ng-model="subject" name="subject">
        </md-input-container>
        <md-input-container ng-show="!proposal_type" class="md-block" flex-gt-xs>
          <label><?php echo lang('customer'); ?></label>
          <md-select required placeholder="<?php echo lang('choisecustomer'); ?>" ng-model="customer" name="customer" style="min-width: 200px;">
            <md-option ng-value="customer.id" ng-repeat="customer in all_customers">{{customer.name}}</md-option>
          </md-select>
          <div ng-messages="userForm.customer" role="alert" multiple>
            <div ng-message="required" class="my-message"><?php echo lang('you_must_supply_a_customer') ?></div>
          </div>
        </md-input-container>
        <md-input-container ng-show="proposal_type" class="md-block" flex-gt-xs>
          <label><?php echo lang('lead'); ?></label>
          <md-select required placeholder="<?php echo lang('choiselead'); ?>" ng-model="lead" name="lead" style="min-width: 200px;">
            <md-option ng-value="lead.id" ng-repeat="lead in leads">{{lead.name}}</md-option>
          </md-select>
          <div ng-messages="userForm.customer" role="alert" multiple>
            <div ng-message="required" class="my-message"><?php echo lang('you_must_supply_a_customer') ?></div>
          </div>
        </md-input-container>
        <md-input-container>
          <label><?php echo lang('dateofissuance') ?></label>
          <md-datepicker name="created" ng-model="created"></md-datepicker>
        </md-input-container>
      </div>
      <div layout-gt-xs="row">
        <md-input-container class="md-block" flex-gt-xs>
          <label><?php echo lang('assigned'); ?></label> 
          <md-select required placeholder="<?php echo lang('assigned'); ?>" ng-model="assigned" name="assigned" style="min-width: 200px;">
            <md-option ng-value="staff.id" ng-repeat="staff in staff">{{staff.name}}</md-option>
          </md-select>
          <div ng-messages="userForm.assigned" role="alert" multiple>
            <div ng-message="required" class="my-message"><?php echo lang('must_supply_assigner') ?></div>
          </div>
        </md-input-container>
        <md-input-container class="md-block" flex-gt-xs>
          <label><?php echo lang('status'); ?></label>
          <md-select ng-init="statuses = [{id: 1,name: '<?php echo lang('draft'); ?>'}, {id: 2,name: '<?php echo lang('sent'); ?>'}, {id: 3,name: '<?php echo lang('open'); ?>'}, {id: 4,name: '<?php echo lang('revised'); ?>'}, {id:5,name: '<?php echo lang('declined'); ?>'}, {id: 6,name: '<?php echo lang('accepted'); ?>'}];" required placeholder="<?php echo lang('status'); ?>" ng-model="status" name="status" style="min-width: 200px;">
            <md-option ng-value="status.id" ng-repeat="status in statuses"><span class="text-uppercase">{{status.name}}</span></md-option>
          </md-select>
          <div ng-messages="userForm.status" role="alert" multiple>
            <div ng-message="required" class="my-message"><?php echo lang('must_select_status') ?>.</div>
          </div>
        </md-input-container>
        <md-input-container>
          <label><?php echo lang('opentill') ?></label>
          <md-datepicker md-min-date="created" name="opentill" ng-model="opentill"></md-datepicker>
        </md-input-container>
      </div>
      <div layout-gt-xs="row">
        <md-input-container class="md-block" flex-gt-xs>
          <label><?php echo lang('detail') ?></label>
          <textarea ng-model="content" rows="3"></textarea>
        </md-input-container>
      </div>
      <md-checkbox class="pull-right" ng-model="comment" aria-label="Comment"> <strong class="text-muted text-uppercase"><?php echo lang('allowcomments');?></strong> </md-checkbox>
    </md-content>
    <md-content ng-show="!proposalsLoader" class="bg-white" layout-padding>
      <md-list-item ng-repeat="item in proposal.items">
        <div layout-gt-sm="row">
          <md-autocomplete
  	  	 	md-autofocus
  	  	 	md-items="product in GetProduct(item.name)"
		    md-search-text="item.name"
		    md-item-text="product.name"   
		    md-selected-item="selectedProduct"
		    md-no-cache="true"
		    md-min-length="0"
		    md-floating-label="<?php echo lang('productservice'); ?>">
            <md-item-template> <span md-highlight-text="item.name">{{product.name}}</span> <strong ng-bind-html="product.price | currencyFormat:cur_code:null:true:cur_lct"></strong> </md-item-template>
          </md-autocomplete>
          <md-input-container class="md-block">
            <label><?php echo lang('description'); ?></label>
            <input type="hidden" ng-model="item.name">
            <bind-expression ng-init="selectedProduct.name = item.name" expression="selectedProduct.name" ng-model="item.name" />
            <input ng-model="item.description" placeholder="<?php echo lang('description'); ?>">
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
            <bind-expression ng-init="selectedProduct.price = 0" expression="selectedProduct.price" ng-model="item.price" />
          </md-input-container>
          <md-input-container class="md-block" flex-gt-xs>
            <label><?php echo $appconfig['tax_label']; ?></label>
            <input ng-model="item.tax">
            <bind-expression ng-init="selectedProduct.tax = 0" expression="selectedProduct.tax" ng-model="item.tax" />
          </md-input-container>
          <md-input-container class="md-block" flex-gt-sm>
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
            <div class="text-right text-uppercase text-muted"><?php echo lang('sub_total') ?>:</div>
            <div ng-show="linediscount() > 0" class="text-right text-uppercase text-muted"><?php echo lang('total_discount') ?>:</div>
            <div ng-show="totaltax() > 0"class="text-right text-uppercase text-muted"><?php echo lang('total').' '.$appconfig['tax_label'] ?>:</div>
            <div class="text-right text-uppercase text-black"><?php echo lang('grand_total') ?>:</div>
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
    <custom-fields-vertical ng-show="!proposalsLoader && custom_fields.length > 0"></custom-fields-vertical> 
	</div>
  <div class="main-content container-fluid col-xs-12 col-md-12 col-lg-3">
    <ciuis-sidebar></ciuis-sidebar>
  </div>
</div>