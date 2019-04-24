<?php $appconfig = get_appconfig(); ?>
<style type="text/css">
.progress-bar {
  background-color: rgb(34, 194, 129) !important;
}
</style>
<div class="ciuis-body-content" ng-controller="Expenses_Controller">
  <div class="main-content container-fluid col-xs-12 col-md-12 col-lg-12">
    <div class="col-md-9" style="padding: 0px">
      <div class="panel-default">
        <div class="ciuis-invoice-summary"></div>
      </div>
      <md-toolbar class="toolbar-white">
        <div class="md-toolbar-tools">
          <h2 flex md-truncate class="text-bold"><?php echo lang('expenses'); ?><br>
            <small flex md-truncate><?php echo lang('organizeyourexpenses'); ?></small></h2>
          <div class="ciuis-external-search-in-table">
            <input ng-model="search.title" class="search-table-external" id="search" name="search" type="text" placeholder="<?php echo lang('searchword')?>">
            <md-button class="md-icon-button" aria-label="Search">
              <md-icon><i class="ion-search text-muted"></i></md-icon>
            </md-button>
          </div>
          <md-button ng-click="toggleFilter()" class="md-icon-button" aria-label="Filter">
            <md-tooltip md-direction="bottom"><?php echo lang('filter') ?></md-tooltip>
            <md-icon><i class="ion-android-funnel text-muted"></i></md-icon>
          </md-button>
          <?php if (!$this->session->userdata('other')) { ?>
            <md-button  ng-href="<?php echo base_url('expenses/create')?>" class="md-icon-button" aria-label="New">
              <md-tooltip md-direction="bottom"><?php echo lang('newexpense') ?></md-tooltip>
              <md-icon><i class="ion-plus-round text-muted"></i></md-icon>
            </md-button>
          <?php } ?>
        </div>
      </md-toolbar>
      <md-content style="padding-top: 0px;">
        <div ng-show="expensesLoader" layout-align="center center" class="text-center" id="circular_loader">
          <md-progress-circular md-mode="indeterminate" md-diameter="40"></md-progress-circular>
          <p style="font-size: 15px;margin-bottom: 5%;">
            <span><?php echo lang('please_wait') ?> <br>
            <small><strong><?php echo lang('loading'). ' '. lang('expenses').'...' ?></strong></small></span>
          </p>
        </div>
        <ul ng-show="!expensesLoader" class="custom-ciuis-list-body" style="padding: 0px;">
          <li ng-repeat="expense in expenses | filter: FilteredData | filter:search | pagination : currentPage*itemsPerPage | limitTo: 5"i class="ciuis-custom-list-item ciuis-special-list-item lead-name">
            <a class="ciuis_expense_receipt_number" href="<?php echo base_url('expenses/receipt/') ?>{{expense.id}}">
              <ul class="list-item-for-custom-list">
                <li class="ciuis-custom-list-item-item col-md-12">
                  <div data-toggle="tooltip" data-placement="bottom" data-container="body" title="" data-original-title="<?php echo lang('addedby'); ?> {{expense.staff}}" class="assigned-staff-for-this-lead user-avatar"><i class="ion-document" style="font-size: 32px"></i></div>
                  <div class="pull-left col-md-4"> <strong ng-bind="expense.prefix + '' + expense.longid"></strong> 
                    <span class="label label-{{expense.color}}" ng-bind="expense.billstatus"></span> <br>
                    <small ng-bind="expense.title"></small>
                  </div>
                  <div class="col-md-8">
                    <div class="col-md-5"> <span class="date-start-task"><small class="text-muted text-uppercase"><?php echo lang('amount'); ?></small><br>
                      <strong ng-bind-html="expense.amount | currencyFormat:cur_code:null:true:cur_lct"><span> </span></strong></span> 
                    </div>
                    <div class="col-md-4"> <span class="date-start-task"><small class="text-muted text-uppercase"><?php echo lang('category'); ?></small><br>
                      <strong ng-bind="expense.category"></strong> 
                    </div>
                    <div class="col-md-3"> <span class="date-start-task"><small class="text-muted text-uppercase"><?php echo lang('date'); ?></small><br>
                      <strong><span class="badge" ng-bind="expense.date"></span></strong></span> 
                    </div>
                  </div>
                </li>
              </ul>
            </a>
          </li>
        </ul>
        <div class="pagination-div" ng-show="!expensesLoader">
          <ul class="pagination">
            <li ng-class="DisablePrevPage()"> <a href ng-click="prevPage()"><i class="ion-ios-arrow-back"></i></a> </li>
            <li ng-repeat="n in range()" ng-class="{active: n == currentPage}" ng-click="setPage(n)"> <a href="#" ng-bind="n+1"></a> </li>
            <li ng-class="DisableNextPage()"> <a href ng-click="nextPage()"><i class="ion-ios-arrow-right"></i></a> </li>
          </ul>
        </div>
        <md-content ng-show="!expenses.length && !expensesLoader" class="md-padding no-item-data"><?php echo lang('notdata') ?></md-content>
      </md-content>
    </div>
    <div class="col-md-3" style="padding: 0px;padding-left: 10px;">
      <md-toolbar class="toolbar-white">
        <div class="md-toolbar-tools">
          <h2 flex md-truncate class="text-bold"><?php echo lang('expensescategories'); ?><br>
            <small flex md-truncate><?php echo lang('expensescategoriessub'); ?></small></h2>
            <?php if (!$this->session->userdata('other')) { ?>
            <md-button ng-click="NewCategory()" class="md-icon-button" aria-label="New Category">
              <md-icon><i class="ion-plus-round text-muted"></i></md-icon>
            </md-button>
            <?php } ?>
        </div>
      </md-toolbar>
      <div ng-show="expensesCatLoader" layout-align="center center" class="text-center" id="circular_loader">
        <md-progress-circular md-mode="indeterminate" md-diameter="25"></md-progress-circular>
        <p style="font-size: 15px;margin-bottom: 5%;">
          <span><?php echo lang('please_wait') ?> <br>
          <small><strong><?php echo lang('loading'). ' '. lang('expensescategories').'...' ?></strong></small></span>
        </p>
      </div>
      <md-content ng-show="!expensesCatLoader" ng-repeat="category in categories">
        <div class="widget widget-stats widget-expenses  red-bg margin-top-0">
          <?php if (!$this->session->userdata('other')) { ?>
          <md-button ng-click="Remove($index)" class="md-icon-button pull-right" aria-label="Remove">
            <md-tooltip md-direction="bottom"><?php echo lang('remove') ?></md-tooltip>
            <md-icon><i class="ion-trash-b text-muted"></i></md-icon>
          </md-button>
          <md-button ng-click="UpdateCategory($index)" class="md-icon-button pull-right" aria-label="Update">
            <md-tooltip md-direction="bottom"><?php echo lang('update') ?></md-tooltip>
            <md-icon><i class="ion-gear-a text-muted"></i></md-icon>
          </md-button>
          <?php } ?>
          <div class="stats-title text-uppercase" ng-bind="category.name"></div>
          <div class="stats-number"><span ng-bind-html="category.amountby | currencyFormat:cur_code:null:true:cur_lct"></span></div>
          <div class="stats-progress progress">
            <div style="width: {{category.percent}}%;" class="progress-bar"></div>
          </div>
          <div class="stats-desc"><?php echo lang('categorypercent') ?> (<span ng-bind="category.percent+'%'"></span>)</div>
        </div>
      </md-content> 
    </div>
  </div>
  <!-- <ciuis-sidebar ng-show="expensesLoader"></ciuis-sidebar> -->
  <md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="ContentFilter"  ng-cloak>
    <md-toolbar class="md-theme-light" style="background:#262626">
      <div class="md-toolbar-tools">
        <md-button ng-click="close()" class="md-icon-button" aria-label="Close"> <i class="ion-android-arrow-forward"></i> </md-button>
        <md-truncate><?php echo lang('filter') ?></md-truncate>
      </div>
    </md-toolbar>
    <md-content layout-padding="">
      <div ng-repeat="(prop, ignoredValue) in expenses[0]" ng-init="filter[prop]={}" ng-if="prop != 'id' && prop != 'title' && prop != 'prefix' && prop != 'longid' && prop != 'amount' && prop != 'staff' && prop != 'color' && prop != 'displayinvoice' && prop != 'date' && prop != 'category' && prop != 'billstatus' && prop != 'billable'">
        <div class="filter col-md-12">
          <h4 class="text-muted text-uppercase"><strong>{{prop}}</strong></h4>
          <hr>
          <div class="labelContainer" ng-repeat="opt in getOptionsFor(prop)">
            <md-checkbox id="{{[opt]}}" ng-model="filter[prop][opt]" aria-label="{{opt}}"><span class="text-uppercase">{{opt}}</span></md-checkbox>
          </div>
        </div>
      </div>
    </md-content>
  </md-sidenav>

  <md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="NewExpense"  ng-cloak>
    <md-toolbar class="toolbar-white">
      <div class="md-toolbar-tools">
        <md-button ng-click="close()" class="md-icon-button" aria-label="Close"> <i class="ion-android-arrow-forward"></i> </md-button>
        <md-truncate><?php echo lang('addexpense') ?></md-truncate> &nbsp;&nbsp;&nbsp;&nbsp;
        <md-switch ng-model="newexpense.internal" aria-label="Type">
          <md-tooltip md-direction="bottom"><?php echo lang('mark_as_internal_expense') ?></md-tooltip>
          <strong class="text-muted"><?php echo lang('internal') ?></strong>
          <md-tooltip md-direction="bottom"><?php echo lang('mark_as_internal_expense') ?></md-tooltip>
        </md-switch>
      </div>
    </md-toolbar>
    <md-content>
      <md-content layout-padding>
        <md-input-container class="md-block">
          <label><?php echo lang('title') ?></label>
          <input required type="text" ng-model="newexpense.title" class="form-control" id="title" placeholder="<?php echo lang('title'); ?>"/>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('amount') ?></label>
          <input required type="number" ng-model="newexpense.amount" class="form-control" id="amount" placeholder="0.00"/>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('date') ?></label>
          <input required mdc-datetime-picker="" date="true" time="true" type="text" id="datetime" placeholder="<?php echo lang('chooseadate') ?>" show-todays-date="" minutes="true"  show-icon="true" ng-model="newexpense.date" class=" dtp-no-msclear dtp-input md-input">
        </md-input-container>
        <md-input-container class="md-block" flex-gt-xs>
          <label><?php echo lang('category'); ?></label>
          <md-select required ng-model="newexpense.category" name="category" style="min-width: 200px;">
            <md-select-header>
              <md-toolbar class="toolbar-white">
                <div class="md-toolbar-tools">
                  <h4 flex md-truncate><?php echo lang('category') ?></h4>
                  <md-button class="md-icon-button" ng-click="NewCategory()" aria-label="Create New">
                    <md-icon><i class="mdi mdi-plus text-muted"></i></md-icon>
                  </md-button>
                </div>
              </md-toolbar>
            </md-select-header>
            <md-option ng-value="category.id" ng-repeat="category in categories">{{category.name}}</md-option>
          </md-select>
        </md-input-container>
        <br>
        <md-input-container id="newcustomer" class="md-block" flex-gt-xs ng-hide="newexpense.internal">
          <label><?php echo lang('customer'); ?></label>
          <md-select required placeholder="<?php echo lang('choisecustomer'); ?>" ng-model="newexpense.customer" name="customer" style="min-width: 200px;">
            <md-select-header>
              <md-toolbar class="toolbar-white">
                <div class="md-toolbar-tools">
                  <h4 flex md-truncate><?php echo lang('customers') ?></h4>
                  <md-button class="md-icon-button" ng-click="CreateCustomer()" aria-label="Create New">
                    <md-icon><i class="mdi mdi-plus text-muted"></i></md-icon>
                  </md-button>
                </div>
              </md-toolbar>
            </md-select-header>
            <md-option ng-value="customer.id" ng-repeat="customer in all_customers">{{customer.name}}</md-option>
          </md-select>
        </md-input-container>
        <br ng-hide="newexpense.internal">
        <md-input-container class="md-block" flex-gt-xs>
          <label><?php echo lang('account'); ?></label>
          <md-select required ng-model="newexpense.account" name="account" style="min-width: 200px;">
            <md-option ng-value="account.id" ng-repeat="account in accounts">{{account.name}}</md-option>
          </md-select>
        </md-input-container>
        <br>
        <md-input-container class="md-block">
          <label><?php echo lang('description') ?></label>
          <textarea required name="description" ng-model="newexpense.description" placeholder="<?php echo lang('typeSomething'); ?>" class="form-control"></textarea>
        </md-input-container>
      </md-content>
      <custom-fields-vertical></custom-fields-vertical>
      <md-content layout-padding>
        <section layout="row" layout-sm="column" layout-align="center center" layout-wrap>
          <md-button ng-click="AddExpense()" class="md-raised md-primary pull-right"  ng-disabled="savingExpense == true">
            <span ng-hide="savingExpense == true"><?php echo lang('add');?></span>
            <md-progress-circular class="white" ng-show="savingExpense == true" md-mode="indeterminate" md-diameter="20"></md-progress-circular>
          </md-button>
        </section>
      </md-content>
    </md-content>
  </md-sidenav>

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
<?php include_once(APPPATH . 'views/inc/footer.php');?>
<script type="text/javascript">
(function umd(root, name, factory)
  {
    'use strict';
    if ('function' === typeof define && define.amd) {
    define(name, ['jquery'], factory);
    } else {
    root[name] = factory();
    }
  }
  (this, 'CiuisInvoiceStats', function UMDFactory()
    {
      'use strict';
      var ReportOverview = ReportOverviewConstructor;
      reportCircleGraph();
      return ReportOverview;
      function ReportOverviewConstructor(options) {
      var factory = {
        init: init
        },
        _elements = {
        $element: options.element
        };
      init();
      return factory;
      function init() {
        _elements.$element.append($(getTemplateString()));

        $('.invoice-percent').percentCircle({
        width: 130,
        trackColor: '#ececec',
        barColor: '#22c39e',
        barWeight: 3,
        endPercent: 0.<?php echo $billed ?>,
        fps: 60
        });
        $('.invoice-percent-2').percentCircle({
        width: 130,
        trackColor: '#ececec',
        barColor: '#ee7a6b',
        barWeight: 3,
        endPercent: 0.<?php echo $not_billed ?>,
        fps: 60
        });

        $('.invoice-percent-3').percentCircle({
        width: 130,
        trackColor: '#ececec',
        barColor: '#22c39e',
        barWeight: 3,
        endPercent: 0.<?php echo $internal ?>,
        fps: 60
        });
      }
      function getTemplateString()
      {
        return [
        '<div>',
        '<div class="row">',
        '<div class="col-md-12">',
        '<div style="border-top-left-radius: 10px;" class="ciuis-right-border-b1 ciuis-invoice-summaries-b1">',
        '<div class="box-header text-uppercase text-bold"><?php echo lang('total').' '.lang('expenses'); ?></div>',
        '<div class="box-content">',
        '<div class="sentTotal">{{totalinvoicesayisi}}</div>'.replace(/{{totalinvoicesayisi}}/, options.data.totalinvoicesayisi),
        '</div>',
        '<div class="box-foot">',
        '<div class="sendTime box-foot-left"><?php echo lang('amount'); ?><br><span class="box-foot-stats"><strong><?php echo currency;?> <?php switch($settings['unitseparator']){case ',': echo number_format($expensesAmount, 2, ',', '.');break;case '.': echo number_format($expensesAmount, 2, '.', ',');break;}?></strong></span></div>'.replace(/{{date}}/, options.data.date),
        '</div>',
        '</div>',
        '<div class="ciuis-right-border-b1 ciuis-invoice-summaries-b1">',
        '<div class="box-header text-uppercase text-bold"><?php echo lang('billed'); ?></div>',
        '<div class="box-content invoice-percent">',
        '<div class="percentage">%<?php echo $billed ?></div>',
        '</div>',
        '<div class="box-foot">',
        '<span class="arrow arrow-up"></span>',
        '<div class="box-foot-left"><?php echo lang('amount'); ?><br><span class="box-foot-stats"><strong><?php echo currency;?> <?php switch($settings['unitseparator']){case ',': echo number_format($billed_expenses, 2, ',', '.');break;case '.': echo number_format($billed_expenses, 2, '.', ',');break;}?></strong></span></div>',
        '<span class="arrow arrow-down"></span>',
        '<div class="box-foot-right"><br><span class="box-foot-stats""><strong><?php echo $billed_expenses_num ?></strong> (%<?php echo $billed ?>)</span></div>',
        '</div>',
        '</div>',
        '<div class="ciuis-right-border-b1 ciuis-invoice-summaries-b1">',
        '<div class="box-header text-uppercase text-bold"><?php echo lang('notbilled'); ?></div>',
        '<div class="box-content invoice-percent-2">',
        '<div class="percentage">%<?php echo $not_billed ?></div>',
        '</div>',
        '<div class="box-foot">',
        '<span class="arrow arrow-up"></span>',
        '<div class="box-foot-left"><?php echo lang('amount'); ?><br><span class="box-foot-stats"><strong><?php echo currency;?> <?php switch($settings['unitseparator']){case ',': echo number_format($not_billed_expenses, 2, ',', '.');break;case '.': echo number_format($not_billed_expenses, 2, '.', ',');break;}?></strong></span></div>'.replace(/{{OdenmeyenInvoicesAmount}}/, options.data.OdenmeyenInvoicesAmount),
        '<span class="arrow arrow-down"></span>',
        '<div class="box-foot-right"><br><span class="box-foot-stats"><strong><?php echo $not_billed_expenses_num ?></strong> (%<?php echo $not_billed ?>)</span></div>',
        '</div>',
        '</div>',
        '<div style="border-top-right-radius: 10px;" class="ciuis-invoice-summaries-b1">',
        '<div class="box-header text-uppercase text-bold"><?php echo lang('internal'); ?></div>',
        '<div class="box-content invoice-percent-3">',
        '<div class="percentage">%<?php echo $internal ?></div>',
        '</div>',
        '<div class="box-foot">',
        '<span class="arrow arrow-up"></span>',
        '<div class="box-foot-left"><?php echo lang('amount'); ?><br><span class="box-foot-stats"><strong><?php echo currency;?> <?php switch($settings['unitseparator']){case ',': echo number_format($internal_expenses, 2, ',', '.');break;case '.': echo number_format($internal_expenses, 2, '.', ',');break;}?></strong></span></div>'.replace(/{{VadesiDolanInvoices}}/, options.data.VadesiDolanInvoices),
        '<div class="box-foot-right"><br><span class="box-foot-stats"><strong><?php echo $internal_expenses_num ?></strong> (%<?php echo $internal ?>)</span></div>',
        '</div>',
        '</div>'
        ].join('');
      }
      }
      function reportCircleGraph() {
      $.fn.percentCircle = function pie(options) {
        var settings = $.extend({
          width: 130,
          trackColor: '#fff',
          barColor: '#fff',
          barWeight: 3,
          startPercent: 0,
          endPercent: 1,
          fps: 60
        }, options);
        this.css({
          width: settings.width,
          height: settings.width
        });
        var _this = this,
          canvasWidth = settings.width,
          canvasHeight = canvasWidth,
          id = $('canvas').length,
          canvasElement = $('<canvas id="' + id + '" width="' + canvasWidth + '" height="' + canvasHeight + '"></canvas>'),
          canvas = canvasElement.get(0).getContext('2d'),
          centerX = canvasWidth / 2,
          centerY = canvasHeight / 2,
          radius = settings.width / 2 - settings.barWeight / 2,
          counterClockwise = false,
          fps = 500 / settings.fps,
          update = 0.01;
        this.angle = settings.startPercent;
        this.drawInnerArc = function (startAngle, percentFilled, color) {
          var drawingArc = true;
          canvas.beginPath();
          canvas.arc(centerX, centerY, radius, (Math.PI / 180) * (startAngle * 360 - 90), (Math.PI / 180) * (percentFilled * 360 - 90), counterClockwise);
          canvas.strokeStyle = color;
          canvas.lineWidth = settings.barWeight - 2;
          canvas.stroke();
          drawingArc = false;
        };
        this.drawOuterArc = function (startAngle, percentFilled, color) {
          var drawingArc = true;
          canvas.beginPath();
          canvas.arc(centerX, centerY, radius, (Math.PI / 180) * (startAngle * 360 - 90), (Math.PI / 180) * (percentFilled * 360 - 90), counterClockwise);
          canvas.strokeStyle = color;
          canvas.lineWidth = settings.barWeight;
          canvas.lineCap = 'round';
          canvas.stroke();
          drawingArc = false;
        };
        this.fillChart = function (stop) {
          var loop = setInterval(function () {
            canvas.clearRect(0, 0, canvasWidth, canvasHeight);
            _this.drawInnerArc(0, 360, settings.trackColor);
            _this.drawOuterArc(settings.startPercent, _this.angle, settings.barColor);
            _this.angle += update;
            if (_this.angle > stop) {
              clearInterval(loop);
            }
          }, fps);
        };
        this.fillChart(settings.endPercent);
        this.append(canvasElement);
        return this;
      };
    }
    function getMockData() {
      return {
        totalinvoicesayisi: <?php echo $expenses_num ?>,
      };
    }
  }));
(function activateCiuisInvoiceStats($) {
  'use strict';
  var $el = $('.ciuis-invoice-summary');
  return new CiuisInvoiceStats({
    element: $el,
    data: {
      totalinvoicesayisi: <?php echo $expenses_num ?>,
    }
  });
}(jQuery));
</script>