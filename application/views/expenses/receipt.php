<?php $appconfig = get_appconfig(); ?>
<div class="ciuis-body-content" ng-controller="Expense_Controller">
  <div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
    <div ng-show="expensesLoader" layout-align="center center" class="text-center" id="circular_loader">
      <md-progress-circular md-mode="indeterminate" md-diameter="40"></md-progress-circular>
      <p style="font-size: 15px;margin-bottom: 5%;">
        <span><?php echo lang('please_wait') ?> <br>
        <small><strong><?php echo lang('loading'). ' '. lang('expense').'...' ?></strong></small></span>
      </p>
    </div>
    <md-toolbar ng-show="!expensesLoader" class="toolbar-white">
      <div class="md-toolbar-tools">
        <h2 class="md-pl-10" flex md-truncate>
          <span ng-bind="expense.prefix+'-'+expense.longid"></span>  
          <span class="badge"><strong ng-bind="expense.category_name"></strong></span>
        </h2>
        <?php if (!$this->session->userdata('other')) { ?>
        <md-button ng-if="expense.internal === false" ng-hide="expense.invoice_id" ng-click="Convert()" class="md-icon-button" aria-label="Convert">
          <md-tooltip md-direction="bottom"><?php echo lang('convert') ?></md-tooltip>
          <md-icon><i class="ion-loop text-success"></i></md-icon>
        </md-button>
        <?php } ?>
        <md-button ng-if="expense.invoice_id" ng-href="<?php echo base_url('invoices/invoice/{{expense.invoice_id}}')?>" class="md-icon-button">
          <md-tooltip md-direction="bottom"><?php echo lang('invoice') ?></md-tooltip>
          <md-icon><i class="ion-document-text text-success"></i></md-icon>
        </md-button>
        <md-button ng-click="sendEmail()" class="md-icon-button" aria-label="Email">
          <md-progress-circular ng-show="sendingEmail == true" md-mode="indeterminate" md-diameter="20"></md-progress-circular>
          <md-tooltip ng-hide="sendingEmail == true" md-direction="bottom" ng-bind="lang.send"></md-tooltip>
          <md-icon ng-hide="sendingEmail == true"><i class="mdi mdi-email text-muted"></i></md-icon>
        </md-button>
        <md-button ng-show="expense.pdf_status == '0'" ng-click="GeneratePDF()" class="md-icon-button" aria-label="Pdf">
          <md-tooltip md-direction="bottom"><?php echo lang('expense'). ' '.lang('summary') ?></md-tooltip>
          <md-icon><i class="mdi mdi-collection-pdf text-muted"></i> </md-icon>
        </md-button>
        <md-button ng-show="expense.pdf_status == '1'" ng-href="<?php echo base_url('expenses/download_pdf/').$expenses['id'] ?>" class="md-icon-button" aria-label="Pdf">
          <md-tooltip md-direction="bottom"><?php echo lang('expense'). ' '.lang('summary') ?></md-tooltip>
          <md-icon><i class="mdi mdi-collection-pdf text-muted"></i> </md-icon>
        </md-button>
        <?php if (!$this->session->userdata('other')) { ?>
        <md-menu md-position-mode="target-right target">
            <md-button aria-label="Open demo menu" class="md-icon-button" ng-click="$mdMenu.open($event)">
              <md-icon><i class="ion-android-more-vertical text-muted"></i></md-icon>
            </md-button>
            <md-menu-content width="4">
              <md-menu-item>
                <md-button ng-href="<?php echo base_url('expenses/update/')?>{{expense.id}}">
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
    <md-content ng-show="!expensesLoader" class="bg-white invoice" layout-padding>
      <div class="invoice-header col-md-12">
        <div class="col-md-6 col-xs-6"> 
          <div class="ciuis-expenses-receipt-xs-colum" style="border: unset;"> <i class="mdi mdi-balance-wallet" aria-hidden="true"></i>
            <p> 
              <span><?php echo lang('amount')?>:</span><br>
              <span style="font-size: 26px;font-weight: 900;" ng-bind-html="expense.amount | currencyFormat:cur_code:null:true:cur_lct"></span><br>
              <small><?php echo lang('paidvia')?> <strong ng-bind="expense.account_name"></strong></small> 
            </p>
          </div>
        </div>
        <div class="col-md-6 col-xs-6">  
          <div class="ciuis-expenses-receipt-xs-colum">
            <p><?php echo lang('title') ?>:<br>
              <span><strong ng-bind="expense.title"></strong></span>
            </p>
          </div>
        </div>
      </div>
      <div class="invoice-header col-md-12">
        <div class="col-md-6 col-xs-6">  
          <div class="ciuis-expenses-receipt-xs-colum">
            <p><?php echo lang('expense'). ' '.lang('date')?>:<br>
              <span><strong ng-bind="expense.date | date:'dd, MMMM yyyy EEEE'"></strong></span>
            </p>
          </div>
        </div>
        <div class="col-md-6 col-xs-6">  
          <div class="ciuis-expenses-receipt-xs-colum">
            <p><?php echo lang('created')?>:<br>
              <span><strong ng-bind="expense.created"></strong></span>
            </p>
          </div>
        </div>
      </div>
      <div class="invoice-header col-md-12">
        <div class="col-md-6 col-xs-6"> 
          <p ng-show="expense.internal">
            <span class="label label-success"><strong><?php echo lang('internal'). ' '.lang('expense')?></strong></span>
          </p>
          <div ng-show="!expense.internal" class="invoice-from">
            <small class="text-uppercase" ng-bind="lang.customer"></small>
            <address class="m-t-5 m-b-5">
              <strong ng-bind="expense.customername"></strong><br>
              <span ng-bind="expense.customeremail"></span><br>
              <span ng-bind="expense.customer_address"></span><br>
              <span ng-bind="expense.customer_phone"></span>
            </address>
          </div>
        </div>
        <div class="col-md-6 col-xs-6">
          <div class="">
            <p><?php echo lang('staff')?>:<br>
              <a ng-href="<?php echo base_url('staff/staffmember/')?>{{expense.staff_id}}">
                <span><strong ng-bind="expense.staff_name"></strong></span>
              </a>
            </p>
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
                <th ng-bind="lang.total"></th>
              </tr>
            </thead>
            <tbody>
              <tr ng-repeat="item in expense.items">
                <td><span ng-bind="item.name"></span><br>
                  <small ng-bind="item.description"></small></td>
                <td ng-bind="item.quantity"></td>
                <td ng-bind-html="item.price | currencyFormat:cur_code:null:true:cur_lct"></td>
                <td ng-bind="item.tax + '%'"></td>
                <td ng-bind-html="item.total | currencyFormat:cur_code:null:true:cur_lct"></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="invoice-price">
          <div class="invoice-price-left">
            <div class="invoice-price-row">
              <div class="sub-price"> <small ng-bind="lang.subtotal"></small> <span ng-bind-html="expense.sub_total | currencyFormat:cur_code:null:true:cur_lct"></span> </div>
              <div class="sub-price"> <i class="ion-plus-round"></i> </div>
              <div class="sub-price"> <small><?php echo $appconfig['tax_label'] ?></small> <span ng-bind-html="expense.total_tax | currencyFormat:cur_code:null:true:cur_lct"></span> </div>
            </div>
          </div>
          <div class="invoice-price-right"> <small ng-bind="lang.total"></small> <span ng-bind-html="expense.total | currencyFormat:cur_code:null:true:cur_lct"></span> </div>
        </div>
      </div>
    </md-content>
  </md-content>
  <md-content ng-show="!expensesLoader" class="bg-white">
    <md-subheader ng-if="custom_fields.length > 0"><?php echo lang('custom_fields') ?></md-subheader>
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
</div>
<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-3 project-sidebar">
    <md-toolbar class="toolbar-white">
      <div class="md-toolbar-tools">
        <md-button class="md-icon-button" aria-label="" ng-disabled="true">
          <md-icon><i class="ion-document text-muted"></i></md-icon>
        </md-button>
        <h2 flex md-truncate><?php echo lang('files') ?></h2>
        <?php if (!$this->session->userdata('other')) { ?>
        <md-button ng-click="UploadFile()" class="md-icon-button md-primary" aria-label="Add File">
          <md-tooltip md-direction="bottom"><?php echo lang('upload_new_file') ?></md-tooltip>
          <md-icon class="ion-plus-round add-file"></md-icon>
        </md-button>
        <?php } ?>
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
          <md-button class="md-secondary md-primary md-fab md-mini md-icon-button" ng-click='DeleteFile(file.id)' aria-label="call">
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
<md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="Update"  ng-cloak>
  <md-toolbar class="toolbar-white">
    <div class="md-toolbar-tools">
      <md-button ng-click="close()" class="md-icon-button" aria-label="Close"> <i class="ion-android-arrow-forward"></i> </md-button>
      <md-truncate><?php echo lang('addexpense') ?></md-truncate>
    </div>
  </md-toolbar>
  <md-content>
    <md-content layout-padding="">
      <md-input-container class="md-block">
        <label><?php echo lang('title') ?></label>
        <input required type="text" ng-model="expense.title" class="form-control" id="title" placeholder="<?php echo lang('title'); ?>"/>
      </md-input-container>
      <md-input-container class="md-block">
        <label><?php echo lang('amount') ?></label>
        <input required type="text" ng-model="expense.amount" class="form-control" id="amount" placeholder="0.00"/>
      </md-input-container>
      <md-input-container class="md-block">
        <label><?php echo lang('date') ?></label>
        <input mdc-datetime-picker="" date="true" time="true" type="text" id="datetime" placeholder="<?php echo lang('chooseadate') ?>" show-todays-date="" minutes="true" min-date="date" show-icon="true" ng-model="expense.date" class=" dtp-no-msclear dtp-input md-input">
      </md-input-container>
      <md-input-container class="md-block" flex-gt-xs>
        <label><?php echo lang('category'); ?></label>
        <md-select required ng-model="expense.category" name="category" style="min-width: 200px;">
          <md-option ng-value="category.id" ng-repeat="category in expensescategories">{{category.name}}</md-option>
        </md-select>
      </md-input-container>
      <br>
      <md-input-container ng-show="expense.customer != 0" class="md-block" flex-gt-xs>
        <label><?php echo lang('customer'); ?></label>
        <md-select placeholder="<?php echo lang('choisecustomer'); ?>" ng-model="expense.customer" name="customer" style="min-width: 200px;">
          <md-option ng-value="customer.id" ng-repeat="customer in all_customers">{{customer.name}}</md-option>
        </md-select>
      </md-input-container>
      <br ng-show="expense.customer != 0">
      <md-input-container class="md-block" flex-gt-xs>
        <label><?php echo lang('account'); ?></label>
        <md-select required ng-model="expense.account" name="account" style="min-width: 200px;">
          <md-option ng-value="account.id" ng-repeat="account in accounts">{{account.name}}</md-option>
        </md-select>
      </md-input-container>
      <br>
      <md-input-container class="md-block">
        <label><?php echo lang('description') ?></label>
        <textarea required name="description" ng-model="expense.description" placeholder="<?php echo lang('typeSomething'); ?>" class="form-control"></textarea>
      </md-input-container>
    </md-content>
    <custom-fields-vertical></custom-fields-vertical>
    <md-content layout-padding>
      <section layout="row" layout-sm="column" layout-align="center center" layout-wrap>
        <md-button ng-click="UpdateExpense()" class="md-raised md-primary pull-right"><?php echo lang('update');?></md-button>
      </section>
    </md-content>
  </md-content>
  </md-content>
</md-sidenav>
</div>
<?php $fileUrl = base_url('uploads/files/expenses/$expenses["id"]/') ?>
<script> 
  var EXPENSEID = "<?php echo $expenses['id'] ?>";
  var lang = {
    email_sent_success: "<?php echo lang('email_sent_success') ?>"
  };
</script>
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
<script type="text/ng-template" id="generate-expense-summary.html">
  <md-dialog aria-label="options dialog">
  <md-dialog-content layout-padding class="text-center">
    <md-content class="bg-white" layout-padding>
      <h2 class="md-title" ng-hide="PDFCreating == true"><?php echo lang('generate').' '.lang('expense').' '.lang('pdf') ?></h2>
      <h2 class="md-title" ng-if="PDFCreating == true"><?php echo lang('report_generating') ?></h2>
      <span ng-hide="PDFCreating == false"><?php echo lang('generate_expense_pdf_msg') ?></span><br><br>
      <span ng-if="PDFCreating == false"><?php echo lang('generate_pdf_last_msg') ?></span><br><br>
      <img ng-if="PDFCreating == true" ng-src="<?php echo base_url('assets/img/loading_time.gif') ?>" alt="">
      <a ng-if="PDFCreating == false" href="<?php echo base_url('expenses/download_pdf/'.$expenses['id'].'') ?>"><img  width="30%"ng-src="<?php echo base_url('assets/img/download_pdf.png') ?>" alt=""></a>
    </md-content>
  </md-dialog-content>
  <md-dialog-actions>
    <span flex></span>
    <md-button class="text-success" ng-if="PDFCreating == false" href="<?php echo base_url('expenses/download_pdf/'.$expenses['id'].'') ?>">
      <?php echo lang('download') ?>
    </md-button>
    <md-button class="text-success" ng-hide="PDFCreating == false" ng-click="CreatePDF()"><?php echo lang('create') ?></md-button>
    <md-button class="text-danger" ng-click="close()"><?php echo lang('cancel') ?></md-button>
  </md-dialog-actions>
  </md-dialog>
</script> 
<script type="text/ng-template" id="view_image.html">
  <md-dialog aria-label="options dialog">
  <?php echo form_open_multipart('expenses/add_file/'.$expenses['id'].'',array("class"=>"form-horizontal")); ?>
  <md-dialog-content layout-padding>
    <?php $path = '{{file.path}}';
    if ($path) { ?>
      <img src="<?php echo $path ?>">
    <?php } ?>
  </md-dialog-content>
  <md-dialog-actions>
    <span flex></span>
    <?php if (!$this->session->userdata('other')) { ?>
    <md-button ng-click='DeleteFiles(file.id)'><?php echo lang('delete') ?>!</md-button>
  <?php } ?>
    <md-button ng-href="<?php echo base_url('expenses/download_file/') ?>{{file.id}}"><?php echo lang('download') ?>!</md-button>
    <md-button ng-click="close()"><?php echo lang('cancel') ?>!</md-button>
  </md-dialog-actions>
  <?php echo form_close(); ?>
  </md-dialog>
</script>