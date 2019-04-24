<?php $appconfig = get_appconfig(); ?>
<div class="ciuis-body-content" ng-controller="Expense_Controller">
  <div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
    <div ng-show="expensesLoader" layout-align="center center" class="text-center" id="circular_loader">
      <md-progress-circular md-mode="indeterminate" md-diameter="40"></md-progress-circular>
      <p style="font-size: 15px;margin-bottom: 5%;">
        <span><?php echo lang('please_wait'). '....' ?> <br>
          <small><strong><?php echo lang('loading'). ' '. lang('expense').'...' ?></strong></small>
        </span>
      </p>
    </div>
    <md-toolbar ng-show="!expensesLoader" class="toolbar-white">
      <div class="md-toolbar-tools">
        <md-button class="md-icon-button" aria-label="Invoice" ng-disabled="true">
          <md-icon><i class="ico-ciuis-expenses text-muted"></i></md-icon>
        </md-button>
        <h2 flex md-truncate><span ng-bind="expense.prefix+'-'+expense.longid"></span></h2>
        <md-switch ng-model="expense.internal" aria-label="Internal">
          <md-tooltip md-direction="bottom"><?php echo lang('mark_as_internal_expense') ?></md-tooltip>
          <strong class="text-muted"><?php echo lang('internal') ?></strong>
          <md-tooltip md-direction="bottom"><?php echo lang('mark_as_internal_expense') ?></md-tooltip>
        </md-switch>
        <md-switch ng-model="expense.recurring_status" aria-label="Recurring"> <strong class="text-muted"><?php echo lang('recurring') ?></strong> </md-switch>
        <md-button ng-href="<?php echo base_url('expenses')?>" class="md-icon-button" aria-label="Save">
          <md-tooltip md-direction="bottom"><?php echo lang('cancel') ?></md-tooltip>
          <md-icon><i class="ion-close-circled text-danger"></i></md-icon>
        </md-button>
        <md-button type="submit" ng-click="UpdateExpense()" class="md-icon-button" aria-label="Save">
          <md-progress-circular ng-show="savingExpense == true" md-mode="indeterminate" md-diameter="20"></md-progress-circular>
          <md-tooltip ng-hide="savingExpense == true" md-direction="bottom"><?php echo lang('save') ?></md-tooltip>
          <md-icon ng-hide="savingExpense == true"><i class="ion-checkmark-circled text-success"></i></md-icon>
        </md-button>
      </div>
    </md-toolbar>
    <md-content ng-show="!expensesLoader" class="bg-white" layout-padding>
      <div layout-gt-xs="row">
        <input name="recurring_id" ng-model="expense.recurring_id" type="hidden">
        <md-input-container required class="md-block" flex-gt-sm>
          <label><?php echo lang('title')?></label>
          <input required ng-model="expense.title" name="title">
        </md-input-container>
        <md-input-container class="md-block" flex-gt-xs>
          <label><?php echo lang('category'); ?></label>
          <md-select required ng-model="expense.category" name="category" style="min-width: 200px;">
            <md-option ng-value="category.id" ng-repeat="category in categories">{{category.name}}</md-option>
          </md-select>
        </md-input-container>
        <br>
        <md-input-container class="md-block" flex-gt-xs ng-show="!expense.internal">
          <label><?php echo lang('customer'); ?></label>
          <md-select required placeholder="<?php echo lang('choisecustomer'); ?>" ng-model="expense.customer" name="customer" style="min-width: 200px;">
            <md-option ng-value="customer.id" ng-repeat="customer in all_customers">{{customer.name}}</md-option>
          </md-select>
        </md-input-container>
        <md-input-container class="md-block" flex-gt-xs ng-show="expense.internal">
          <label><?php echo lang('staff'); ?></label>
          <md-select required placeholder="<?php echo lang('choisestaff'); ?>" ng-model="expense.staff_id" name="customer" style="min-width: 200px;">
            <md-option ng-value="staf.id" ng-repeat="staf in staff">{{staf.name}}</md-option>
          </md-select>
        </md-input-container>
        <br>
      </div>
      <div layout-gt-xs="row">
        <md-input-container class="md-block" flex-gt-xs>
          <label><?php echo lang('expense_number')?></label>
          <input ng-model="expense.number" name="expense.number">
        </md-input-container>
        <br>        
        <md-input-container class="md-block" flex-gt-xs>
          <label><?php echo lang('account'); ?></label>
          <md-select required ng-model="expense.account" name="account" style="min-width: 200px;">
            <md-option ng-value="account.id" ng-repeat="account in accounts">{{account.name}}</md-option>
          </md-select>
        </md-input-container>
        <br>
        <md-input-container class="md-block">
          <label><?php echo lang('date') ?></label>
          <md-datepicker required name="created" ng-model="expense.date"></md-datepicker>
          <md-tooltip md-direction="top"><?php echo lang('expense').' '.lang('date') ?></md-tooltip>
        </md-input-container>
        <br>
      </div>
      <div ng-show="expense.recurring_status" layout-gt-xs="row">
        <md-input-container class="md-block" flex-gt-xs>
          <label><?php echo lang('recurring_period') ?></label>
          <input type="number" ng-value="1" value="1" ng-init="recurring_period = 1" min="1" ng-model="expense.recurring_period" name="recurring_period">
        </md-input-container>
        <md-input-container class="md-block" flex-gt-xs>
          <label><?php echo lang('recurring_type') ?></label>
          <md-select ng-model="expense.recurring_type" name="recurring_type">
            <md-option value="0"><?php echo lang('days') ?></md-option>
            <md-option value="1"><?php echo lang('weeks') ?></md-option>
            <md-option value="2" selected><?php echo lang('months') ?></md-option>
            <md-option value="3"><?php echo lang('years') ?></md-option>
          </md-select>
        </md-input-container>
        <br>
        <md-input-container>
          <label><?php echo lang('ends_on') ?></label>
          <md-datepicker md-min-date="date" name="EndRecurring" ng-model="expense.EndRecurring" style="min-width: 100%;"></md-datepicker>
          <div >
            <div ng-message="required" class="my-message"><?php echo lang('leave_blank_for_lifetime') ?></div>
          </div>
        </md-input-container>
      </div>
    </md-content>
    <md-content ng-show="!expensesLoader" class="bg-white" layout-padding>
      <md-list-item ng-repeat="item in expense.items">
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
            <label><?php echo $appconfig['tax_label']; ?> (%)</label>
            <input ng-model="item.tax">
            <bind-expression ng-init="selectedProduct.tax = item.tax" expression="selectedProduct.tax" ng-model="item.tax" />
          </md-input-container>
          <!-- <md-input-container class="md-block" flex-gt-xs>
            <label><?php echo lang('discount'); ?></label>
            <input ng-model="item.discount">
          </md-input-container> -->
          <md-input-container class="md-block">
            <label><?php echo lang('total'); ?></label>
            <input disabled="" ng-value="item.quantity * item.price + ((item.tax)/100*item.quantity * item.price) - ((item.discount)/100*item.quantity * item.price)">
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
            <div class="text-right text-uppercase text-muted"><?php echo lang('subtotal') ?>:</div>
            <div ng-show="linediscount() > 0" class="text-right text-uppercase text-muted"><?php echo lang('total_discount') ?>:</div>
            <div ng-show="totaltax() > 0"class="text-right text-uppercase text-muted"><?php echo lang('total').' '.$appconfig['tax_label'] ?>:</div>
            <div class="text-right text-uppercase text-black"><?php echo lang('grandtotal') ?>:</div>
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
  <div class="main-content container-fluid lg-pl-0 col-xs-12 col-md-12 col-lg-3">
    <md-toolbar class="toolbar-white">
      <div class="md-toolbar-tools">
        <md-button class="md-icon-button" aria-label="" ng-disabled="true">
          <md-icon><i class="ion-document text-muted"></i></md-icon>
        </md-button>
        <h2 flex md-truncate><?php echo lang('files') ?></h2>
        <md-button ng-click="UploadFile()" class="md-icon-button md-primary" aria-label="Add File">
          <md-tooltip md-direction="bottom"><?php echo lang('upload_new_file') ?></md-tooltip>
          <md-icon class="ion-plus-round add-file"></md-icon>
        </md-button>
      </div>
    </md-toolbar>
    <div ng-show="expensesFiles" layout-align="center center" class="text-center" id="circular_loader">
      <md-progress-circular md-mode="indeterminate" md-diameter="40"></md-progress-circular>
      <p style="font-size: 15px;margin-bottom: 5%;">
        <span><?php echo lang('please_wait') ?> <br>
        <small><strong><?php echo lang('loading'). ' '. lang('expense_files').'...' ?></strong></small></span>
      </p>
    </div>
    <md-content ng-show="!expensesFiles" class="bg-white">
      <md-list flex>
        <md-list-item class="md-2-line" ng-repeat="file in files | pagination : currentPage*itemsPerPage | limitTo: 6">
          <div class="md-list-item-text image-preview">
            <a ng-if="file.type == 'image'" class="cursor" ng-click="ViewFile($index, image)">
              <md-tooltip md-direction="left"><?php echo lang('preview') ?></md-tooltip>
              <img src="{{file.path}}">
            </a>
            <a ng-if="(file.type == 'archive')" class="cursor" ng-href="<?php echo base_url('expenses/download_file/{{file.id}}');?>">
              <md-tooltip md-direction="left"><?php echo lang('download') ?></md-tooltip>
              <img src="<?php echo base_url('assets/img/zip_icon.png');?>">
            </a>
            <a ng-if="(file.type == 'file')" class="cursor" ng-href="<?php echo base_url('expenses/download_file/{{file.id}}');?>">
              <md-tooltip md-direction="left"><?php echo lang('download') ?></md-tooltip>
              <img src="<?php echo base_url('assets/img/file_icon.png');?>">
            </a>
            <a ng-if="file.type == 'pdf'" class="cursor" ng-href="<?php echo base_url('expenses/download_file/{{file.id}}');?>">
              <md-tooltip md-direction="left"><?php echo lang('download') ?></md-tooltip>
              <img src="<?php echo base_url('assets/img/pdf_icon.png');?>">
            </a>
          </div>
          <div class="md-list-item-text">
            <a class="cursor" ng-href="<?php echo base_url('expenses/download_file/{{file.id}}');?>">
              <h3 class="link" ng-bind="file.file_name"></h3>
            </a>
          </div>
          <?php if (!$this->session->userdata('other')) { ?>
          <md-button class="md-secondary md-primary md-fab md-mini md-icon-button" ng-click='DeleteFile($index)' aria-label="call">
            <md-icon class="ion-trash-b"></md-icon>
          </md-button>
        <?php } ?>
          <md-divider></md-divider>
        </md-list-item>
        <div ng-show="!files.length" class="text-center"><img width="70%" src="<?php echo base_url('assets/img/nofiles.jpg') ?>" alt=""></div>
      </md-list>
      <div ng-show="files.length>6 && !expensesFiles" class="pagination-div">
        <ul class="pagination">
          <li ng-class="DisablePrevPage()"> <a href ng-click="prevPage()"><i class="ion-ios-arrow-back"></i></a> </li>
          <li ng-repeat="n in range()" ng-class="{active: n == currentPage}" ng-click="setPage(n)"> <a href="#" ng-bind="n+1"></a> </li>
          <li ng-class="DisableNextPage()"> <a href ng-click="nextPage()"><i class="ion-ios-arrow-right"></i></a> </li>
        </ul>
      </div>
    </md-content>
  </div>
  <md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="CreateCustomer"  ng-cloak>
    <md-toolbar class="toolbar-white">
      <div class="md-toolbar-tools">
        <md-button ng-click="close()" class="md-icon-button" aria-label="Close"> <i class="ion-android-arrow-forward"></i> </md-button>
        <h2 flex md-truncate><?php echo lang('create') ?></h2>
        <md-switch ng-model="isIndividual" aria-label="Type"><strong class="text-muted"><?php echo lang('individual') ?></strong></md-switch>
      </div>
    </md-toolbar>
    <md-content flex>
      <md-content layout-padding>
        <md-input-container ng-show="isIndividual != true" class="md-block">
          <label><?php echo lang('company'); ?></label>
          <md-icon md-svg-src="<?php echo base_url('assets/img/icons/company.svg') ?>"></md-icon>
          <input  md-autofocus name="company" ng-model="customer.company">
        </md-input-container>
        <md-input-container ng-show="isIndividual == true" class="md-block">
          <label><?php echo lang('namesurname'); ?></label>
          <md-icon md-svg-src="<?php echo base_url('assets/img/icons/individual.svg') ?>"></md-icon>
          <input name="namesurname" ng-model="customer.namesurname">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo $appconfig['tax_label'].' '.lang('taxofficeedit'); ?></label>
          <input name="taxoffice" ng-model="customer.taxoffice">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo $appcongi['tax_label'].' '.lang('taxnumberedit'); ?></label>
          <input name="taxnumber" ng-model="customer.taxnumber">
        </md-input-container>
        <md-input-container ng-show="isIndividual == true" class="md-block">
          <label><?php echo lang('ssn'); ?></label>
          <input name="ssn" ng-model="customer.ssn" ng-pattern="/^[0-9]{3}-[0-9]{2}-[0-9]{4}$/" />
          <div class="hint" ng-if="showHints">###-##-####</div>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('executiveupdate'); ?></label>
          <input name="executive" ng-model="customer.executive">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('phone'); ?></label>
          <input name="phone" ng-model="customer.phone">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('fax'); ?></label>
          <input name="fax" ng-model="customer.fax">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('email'); ?></label>
          <input name="email" ng-model="customer.email" required minlength="10" maxlength="100" ng-pattern="/^.+@.+\..+$/" />
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('customerweb'); ?></label>
          <input name="web" ng-model="customer.web">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('country'); ?></label>
          <md-select placeholder="<?php echo lang('country'); ?>" ng-model="customer.country_id" name="country_id" style="min-width: 200px;">
            <md-option ng-value="country.id" ng-repeat="country in countries">{{country.shortname}}</md-option>
          </md-select>
        </md-input-container>
        <br>
        <md-input-container class="md-block">
          <label><?php echo lang('state'); ?></label>
          <input name="state" ng-model="customer.state">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('city'); ?></label>
          <input name="city" ng-model="customer.city">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('town'); ?></label>
          <input name="town" ng-model="customer.town">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('zipcode'); ?></label>
          <input name="zipcode" ng-model="customer.zipcode">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('address') ?></label>
          <textarea ng-model="customer.address" name="address" md-maxlength="500" rows="3" md-select-on-focus></textarea>
        </md-input-container>
        <md-slider-container> <span><?php echo lang('riskstatus');?></span>
          <md-slider flex min="0" max="100" ng-model="customer.risk" aria-label="red" id="red-slider"> </md-slider>
          <md-input-container>
            <input name="risk" flex type="number" ng-model="customer.risk" aria-label="red" aria-controls="red-slider">
          </md-input-container>
        </md-slider-container>
      </md-content>
      <md-subheader class="md-primary">
        <md-truncate><?php echo lang('billing_address') ?></md-truncate>
        <md-button ng-click='SameAsCustomerAddress()' class="md-icon-button" aria-label="Copy Customer Address">
          <md-icon class="ion-ios-copy">
            <md-tooltip md-direction="right"><?php echo lang('same_as_customer') ?></md-tooltip>
          </md-icon>
        </md-button>
      </md-subheader>
      <md-content layout-padding>
        <md-input-container class="md-block">
          <label><?php echo lang('address') ?></label>
          <textarea ng-model="customer.billing_street" name="address" md-maxlength="500" rows="3" md-select-on-focus></textarea>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('city'); ?></label>
          <input name="city" ng-model="customer.billing_city">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('state'); ?></label>
          <input name="state" ng-model="customer.billing_state">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('zipcode'); ?></label>
          <input name="zipcode" ng-model="customer.billing_zip">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('country'); ?></label>
          <md-select placeholder="<?php echo lang('country'); ?>" ng-model="customer.billing_country" name="billing_country" style="min-width: 200px;">
            <md-option ng-value="country.id" ng-repeat="country in countries">{{country.shortname}}</md-option>
          </md-select>
        </md-input-container>
        <br>
      </md-content>
      <md-subheader class="md-primary">
        <md-truncate><?php echo lang('shipping_address') ?></md-truncate>
        <md-button ng-click='SameAsBillingAddress()' class="md-icon-button" aria-label="Favorite">
          <md-icon class="ion-ios-copy">
            <md-tooltip md-direction="right"><?php echo lang('same_as_billing') ?></md-tooltip>
          </md-icon>
        </md-button>
      </md-subheader>
      <md-content layout-padding>
        <md-input-container class="md-block">
          <label><?php echo lang('address') ?></label>
          <textarea ng-model="customer.shipping_street" name="address" md-maxlength="500" rows="3" md-select-on-focus></textarea>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('city'); ?></label>
          <input name="city" ng-model="customer.shipping_city">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('state'); ?></label>
          <input name="state" ng-model="customer.shipping_state">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('zipcode'); ?></label>
          <input name="zipcode" ng-model="customer.shipping_zip">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('country'); ?></label>
          <md-select placeholder="<?php echo lang('country'); ?>" ng-model="customer.shipping_country" name="shipping_country" style="min-width: 200px;">
            <md-option ng-value="country.id" ng-repeat="country in countries">{{country.shortname}}</md-option>
          </md-select>
        </md-input-container>
        <br>
      </md-content>
      <md-content layout-padding>
        <section layout="row" layout-sm="column" layout-align="center center" layout-wrap>
          <md-button ng-click="AddCustomer()" class="template-button" ng-disabled="saving == true">
            <span ng-hide="saving == true"><?php echo lang('create');?></span>
            <md-progress-circular class="white" ng-show="saving == true" md-mode="indeterminate" md-diameter="20"></md-progress-circular>
          </md-button>
          <!-- <md-button ng-click="AddCustomer()" class="md-raised md-primary pull-right"><?php echo lang('create');?></md-button> -->
        </section>
      </md-content>
    </md-content>
  </md-sidenav>
</div>
<script type="text/ng-template" id="addfile-template.html">
  <md-dialog aria-label="options dialog">
  <?php echo form_open_multipart('expenses/add_file/'.$expenses['id'].'',array("class"=>"form-horizontal")); ?>
  <md-dialog-content layout-padding>
    <h2 class="md-title"><?php echo lang('choosefile'); ?></h2>
    <input type="file" required name="file_name">
  </md-dialog-content>
  <md-dialog-actions>
    <span flex></span>
    <md-button ng-click="close()"><?php echo lang('cancel') ?>!</md-button>
    <md-button type="submit"><?php echo lang('add') ?>!</md-button>
  </md-dialog-actions>
  <?php echo form_close(); ?>
  </md-dialog>
</script>
<script type="text/ng-template" id="view_image.html">
  <md-dialog aria-label="options dialog">
  <?php echo form_open_multipart('expenses/add_file/'.$expenses['id'].'',array("class"=>"form-horizontal")); ?>
  <md-dialog-content layout-padding>
    <img src='{{file.path}}'>
  </md-dialog-content>
  <md-dialog-actions>
    <span flex></span>
    <md-button ng-click='DeleteFiles(file.id)'><?php echo lang('delete') ?>!</md-button>
    <md-button ng-href="<?php echo base_url('expenses/download_file/') ?>{{file.id}}"><?php echo lang('download') ?>!</md-button>
    <md-button ng-click="close()"><?php echo lang('cancel') ?>!</md-button>
  </md-dialog-actions>
  <?php echo form_close(); ?>
  </md-dialog>
</script>
<script> var EXPENSEID = "<?php echo $expenses['id'] ?>"</script>