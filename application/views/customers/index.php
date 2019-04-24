<?php $appconfig = get_appconfig(); ?>
<div class="ciuis-body-content" ng-controller="Customers_Controller">
  <div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
    <div ng-show="customersLoader" layout-align="center center" class="text-center" id="circular_loader">
        <md-progress-circular md-mode="indeterminate" md-diameter="40"></md-progress-circular>
        <p style="font-size: 15px;margin-bottom: 5%;">
         <span>
            <?php echo lang('please_wait') ?> <br>
           <small><strong><?php echo lang('loading'). ' '. lang('customers').'...' ?></strong></small>
         </span>
       </p>
     </div>
    <md-toolbar ng-show="!customersLoader" class="toolbar-white">
      <div class="md-toolbar-tools">
        <md-button class="md-icon-button" aria-label="File">
          <md-icon><i class="ion-document text-muted"></i></md-icon>
        </md-button>
        <h2 flex md-truncate><?php echo lang('customers'); ?></h2>
        <div class="ciuis-external-search-in-table">
          <input ng-model="search.name" class="search-table-external" id="search" name="search" type="text" placeholder="<?php echo lang('searchword')?>">
          <md-button class="md-icon-button" aria-label="Search">
            <md-icon><i class="ion-search text-muted"></i></md-icon>
          </md-button>
        </div>
        <md-button ng-click="toggleFilter()" class="md-icon-button" aria-label="Filter">
          <md-tooltip md-direction="bottom"><?php echo lang('filter') ?></md-tooltip>
          <md-icon><i class="ion-android-funnel text-muted"></i></md-icon>
        </md-button> 
        <md-button ng-click="Create()" class="md-icon-button" aria-label="New">
          <md-tooltip md-direction="bottom"><?php echo lang('create') ?></md-tooltip>
          <md-icon><i class="ion-android-add-circle text-success"></i></md-icon>
        </md-button>
        <md-menu md-position-mode="target-right target">
          <md-button aria-label="Open demo menu" class="md-icon-button" ng-click="$mdMenu.open($event)">
            <md-icon><i class="ion-android-more-vertical text-muted"></i></md-icon>
          </md-button>
          <md-menu-content width="4">
            <md-menu-item>
              <md-button ng-click="ImportCustomersNav()">
                <div layout="row" flex>
                  <p flex ng-bind="lang.importcustomers"></p>
                  <md-icon md-menu-align-target class="ion-upload text-muted" style="margin: auto 3px auto 0;"></md-icon>
                </div>
              </md-button>
            </md-menu-item>
            <?php echo form_open_multipart('customers/exportdata',array("class"=>"form-horizontal")); ?>
            <md-menu-item>
              <md-button type="submit">
                <div layout="row" flex>
                  <p flex ng-bind="lang.exportcustomers"></p>
                  <md-icon md-menu-align-target class="ion-android-download text-muted" style="margin: auto 3px auto 0;"></md-icon>
                </div>
              </md-button>
            </md-menu-item>
            <?php echo form_close(); ?>
          </md-menu-content>
        </md-menu>
      </div>
    </md-toolbar>
    <ul ng-show="!customersLoader" class="custom-ciuis-list-body" style="padding: 0px;">
      <li ng-repeat="customer in customers | filter: FilteredData | filter:search | pagination : currentPage*itemsPerPage | limitTo: 5" class="ciuis-custom-list-item ciuis-special-list-item lead-name" ng-click="GoCustomer($index)">
        <a href="<?php echo base_url('customers/customer/')?>{{customer.id}}">
          <ul class="list-item-for-custom-list">
            <li class="ciuis-custom-list-item-item col-md-12">
              <div class="assigned-staff-for-this-lead user-avatar"><i class="ico-ciuis-staffdetail" style="font-size: 32px"></i></div>
              <div class="pull-left col-md-4"><strong ng-bind="customer.name"></strong><br>
                <small ng-bind="customer.email"></small> </div>
              <div class="col-md-8">
                <div class="col-md-9"> <span class="date-start-task"><small class="text-muted text-uppercase" ng-bind="customer.address"></small><br>
                  <strong ng-bind="customer.phone"></strong></span> </div>
                <div class="col-md-3 text-center hidden-xs">
                  <div class="hellociuislan">
                    <div ng-show="customer.balance !== 0"> <strong style="font-size: 20px;"><span ng-bind-html="customer.balance | currencyFormat:cur_code:null:true:cur_lct"></span></strong><br>
                      <span style="font-size:10px"><?php echo lang( 'currentdebt' ) ?></span> </div>
                    <div ng-show="customer.balance === 0"> <strong style="font-size: 22px;"><i class="text-success ion-android-checkmark-circle"></i></strong><br>
                      <span class="text-success" style="font-size:10px"><?php echo lang('nobalance') ?></span> </div>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </a>
      </li>
    </ul>
    <md-content ng-show="!customers.length && !customersLoader" class="md-padding no-item-data"><?php echo lang('notdata') ?></md-content>
    <div class="pagination-div" ng-show="customers.length">
      <ul class="pagination">
        <li ng-class="DisablePrevPage()"> <a href ng-click="prevPage()"><i class="ion-ios-arrow-back"></i></a> </li>
        <li ng-repeat="n in range()" ng-class="{active: n == currentPage}" ng-click="setPage(n)"> <a href="#" ng-bind="n+1"></a> </li>
        <li ng-class="DisableNextPage()"> <a href ng-click="nextPage()"><i class="ion-ios-arrow-right"></i></a> </li>
      </ul>
    </div>
  </div>
  <ciuis-sidebar ng-show="!customersLoader"></ciuis-sidebar>
  <md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="Create" style="width: 500px;"  ng-cloak>
    <md-toolbar class="toolbar-white">
      <div class="md-toolbar-tools">
        <md-button ng-click="close()" class="md-icon-button" aria-label="Close"> <i class="ion-android-arrow-forward"></i> </md-button>
        <h2 flex md-truncate><?php echo lang('create') ?></h2>
        <md-switch ng-model="isIndividual" aria-label="Type"><strong class="text-muted"><?php echo lang('individual') ?></strong></md-switch>
      </div>
    </md-toolbar>
    <md-content>
      <md-content layout-padding>
        <md-input-container ng-show="isIndividual != true" class="md-block">
          <label><?php echo lang('company'); ?></label>
          <md-icon md-svg-src="<?php echo base_url('assets/img/icons/company.svg') ?>"></md-icon>
          <input name="company" ng-model="customer.company">
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
          <label><?php echo $appconfig['tax_label'].' '.lang('taxnumberedit'); ?></label>
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
          <input name="email" ng-model="customer.email" required minlength="10" maxlength="100" ng-pattern="/^.+@.+\..+$/">
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
        <md-input-container class="md-block">
          <label><?php echo lang('default_payment_method'); ?></label>
          <md-select placeholder="<?php echo lang('default_payment_method'); ?>" ng-model="customer.default_payment_method" name="default_payment_method" style="min-width: 200px;">
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
        <br>
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
            <md-tooltip md-direction="top"><?php echo lang('same_as_customer') ?></md-tooltip>
          </md-icon>
        </md-button>
        <md-button class="pull-right hide-pinned-projects md-icon-button" aria-label="<?php echo lang('billing_address') ?>">
          <a data-toggle="collapse" data-parent="#billing_address" href="#billing_address">
            <md-icon class="ion-chevron-down">
            </md-icon>
          </a>
        </md-button>
      </md-subheader>
      <md-content layout-padding  id="billing_address" class="panel-collapse collapse out">
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
            <md-tooltip md-direction="top"><?php echo lang('same_as_billing') ?></md-tooltip>
          </md-icon>
        </md-button>
        <md-button class="pull-right hide-pinned-projects md-icon-button" aria-label="<?php echo lang('shipping_address') ?>">
          <a data-toggle="collapse" data-parent="#shipping_address" href="#shipping_address">
            <md-icon class="ion-chevron-down">
            </md-icon>
          </a>
        </md-button>
      </md-subheader>
      <md-content layout-padding id="shipping_address" class="panel-collapse collapse out">
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
      <custom-fields-vertical></custom-fields-vertical>
      <md-content layout-padding>
        <section layout="row" layout-sm="column" layout-align="center center" layout-wrap>
          <md-button ng-click="AddCustomer()" class="template-button" ng-disabled="saving == true">
            <span ng-hide="saving == true"><?php echo lang('create');?></span>
            <md-progress-circular class="white" ng-show="saving == true" md-mode="indeterminate" md-diameter="20"></md-progress-circular>
          </md-button>
        </section>
      </md-content>
    </md-content>
  </md-sidenav>

  <md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="ImportCustomersNav"  ng-cloak>
      <md-toolbar class="md-theme-light" style="background:#262626">
        <div class="md-toolbar-tools">
        <md-button ng-click="close()" class="md-icon-button" aria-label="Close"><i class="ion-android-arrow-forward"></i></md-button>
        <md-truncate><?php echo lang('importcustomers') ?></md-truncate>
        </div>
      </md-toolbar>
    <md-content>
    <?php echo form_open_multipart('customers/customersimport'); ?>
      <div class="modal-body">
        <div class="form-group">
          <label for="name">
            <?php echo lang('choosecsvfile'); ?>
          </label>
          <div class="file-upload">
            <div class="file-select">
              <div class="file-select-button" id="fileName"><span class="mdi mdi-accounts-list-alt"></span>
                <?php echo lang('attachment')?>
              </div>
              <div class="file-select-name" id="noFile">
                <?php echo lang('notchoise')?>
              </div>
              <input type="file" name="userfile" id="chooseFile" required="" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" >
            </div>
          </div>
        </div>
        <br>
        <md-input-container class="md-block">
          <label><?php echo lang('assigned'); ?></label>
          <md-select placeholder="<?php echo lang('choosestaff'); ?>" name="importassigned" ng-model="importassigned" style="min-width: 200px;" required>
            <md-option ng-value="staff.id" ng-repeat="staff in staff">{{staff.name}}</md-option>
          </md-select>
        </md-input-container>
        <br>
        <div class="well well-sm"><?php echo lang('importcustomersinfo'); ?></div>
      </div>
      <div class="modal-footer">
        <a href="<?php echo base_url('uploads/samples/customerimport.csv')?>" class="btn btn-success pull-left"><?php echo lang('downloadsample'); ?></a>
        <button type="submit" class="btn btn-default"><?php echo lang('save'); ?></button>
      </div>
    <?php echo form_close(); ?> 
    </md-content>
  </md-sidenav>
  <md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="ContentFilter"  ng-cloak>
    <md-toolbar class="md-theme-light" style="background:#262626">
      <div class="md-toolbar-tools">
        <md-button ng-click="close()" class="md-icon-button" aria-label="Close"> <i class="ion-android-arrow-forward"></i> </md-button>
        <md-truncate><?php echo lang('filter') ?></md-truncate>
      </div>
    </md-toolbar>
    <md-content layout-padding="">
      <div ng-repeat="(prop, ignoredValue) in customers[0]" ng-init="filter[prop]={}" ng-if="prop != 'id' && prop != 'name' && prop != 'address' && prop != 'email' && prop != 'phone' && prop != 'balance' && prop != 'customer_id' && prop != 'contacts' && prop != 'billing_street' && prop != 'billing_city' && prop != 'billing_state' && prop != 'billing_zip' && prop != 'billing_country' && prop != 'shipping_street' && prop != 'shipping_city' && prop != 'shipping_state' && prop != 'shipping_zip' && prop != 'shipping_country' && prop != 'customer_country'">
        <div class="filter col-md-12">
          <h4 class="text-muted text-uppercase"><strong>{{prop}}</strong></h4>
          <hr>
          <div class="labelContainer" ng-repeat="opt in getOptionsFor(prop)" ng-if="prop!='<?php echo lang('filterbycountry') ?>' && prop!='<?php echo lang('filterbyassigned') ?>'">
            <md-checkbox id="{{[opt]}}" ng-model="filter[prop][opt]" aria-label="{{opt}}"><span class="text-uppercase">{{opt}}</span></md-checkbox>
          </div>
          <div ng-if="prop=='<?php echo lang('filterbycountry') ?>'">
            <md-select aria-label="Filter" ng-model="filter_select" ng-init="filter_select='all'" ng-change="updateDropdown(prop)">
              <md-option value="all"><?php echo lang('all') ?></md-option>
              <md-option ng-repeat="opt in getOptionsFor(prop) | orderBy:'':true" value="{{opt}}">{{opt}}</md-option>
            </md-select>
          </div>
        </div>
      </div>
    </md-content>
  </md-sidenav>
</div>