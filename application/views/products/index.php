<?php $appconfig = get_appconfig(); ?>
<div class="ciuis-body-content" ng-controller="Products_Controller">
  <style type="text/css">
    rect.highcharts-background {
      fill: #f3f3f3;
    }
  </style>
  <div class="main-content container-fluid col-xs-12 col-md-9 col-lg-9">
    <md-toolbar class="toolbar-white">
      <div class="md-toolbar-tools">
        <h2 flex md-truncate class="text-bold"><?php echo lang('products'); ?><br>
          <small flex md-truncate><?php echo lang('productsdescription'); ?></small>
        </h2>
        <div class="ciuis-external-search-in-table">
          <input ng-model="search.name" class="search-table-external" id="search" name="search" type="text" placeholder="<?php echo lang('searchword')?>">
          <md-button class="md-icon-button" aria-label="Search">
            <md-icon><i class="ion-search text-muted"></i></md-icon>
          </md-button>
        </div>
        <md-button ng-click="Create()" class="md-icon-button" aria-label="New">
          <md-icon><i class="ion-plus-round text-muted"></i></md-icon>
        </md-button>
        <md-menu md-position-mode="target-right target">
          <md-button aria-label="Open demo menu" class="md-icon-button" ng-click="$mdMenu.open($event)">
            <md-icon><i class="ion-android-more-vertical text-muted"></i></md-icon>
          </md-button>
          <md-menu-content width="4">
            <md-menu-item>
              <md-button ng-click="ImportProductsNav()">
                <div layout="row" flex>
                  <p flex ng-bind="lang.importproducts"></p>
                  <md-icon md-menu-align-target class="ion-upload text-muted" style="margin: auto 3px auto 0;"></md-icon>
                </div>
              </md-button>
            </md-menu-item>
            <?php echo form_open_multipart('products/exportdata',array("class"=>"form-horizontal")); ?>
            <md-menu-item>
              <md-button type="submit">
                <div layout="row" flex>
                  <p flex ng-bind="lang.exportproducts"></p>
                  <md-icon md-menu-align-target class="ion-android-download text-muted" style="margin: auto 3px auto 0;"></md-icon>
                </div>
              </md-button>
            </md-menu-item>
            <?php echo form_close(); ?>
          </md-menu-content>
        </md-menu>
      </div>
    </md-toolbar>
    <md-content  class="md-pt-0">
      <ul class="custom-ciuis-list-body" style="padding: 0px;">
        <li ng-repeat="product in products | pagination : currentPage*itemsPerPage | limitTo: 5"i class="ciuis-custom-list-item ciuis-special-list-item">
          <a href="<?php echo base_url('products/product/') ?>{{product.product_id}}">
          <ul class="list-item-for-custom-list">
            <li class="ciuis-custom-list-item-item col-md-12">
              <div class="assigned-staff-for-this-lead user-avatar"><i class="ico-ciuis-products" style="font-size: 32px"></i></div>
              <div class="pull-left col-md-3"> 
                  <strong ng-bind="product.name"></strong><br>
                  <small ng-bind="product.description| limitTo:35"></small> 
              </div>
              <div class="col-md-3">
                <span class="date-start-task"><small class="text-muted text-uppercase">
                  <?php echo lang('category') ?></small>
                  <br>
                  <strong>
                    <span class="badge" ng-bind="product.category_name"></span>
                  </strong>
                </span>
              </div>
              <div class="col-md-6">
                <div class="col-md-4"> <span class="date-start-task"><small class="text-muted text-uppercase"><?php echo lang('salesprice'); ?></small><br>
                  <strong ng-bind-html="product.price | currencyFormat:cur_code:null:true:cur_lct"></strong> </span> 
                </div>
                <div class="col-md-4"> <span class="date-start-task"><small class="text-muted text-uppercase"><?php echo $appconfig['tax_label']; ?></small><br>
                  <strong ng-bind="product.tax+'%'"></strong> 
                </div>
                <div class="col-md-4 text-right">
                  <span class="date-start-task">
                    <small class="text-muted text-uppercase">
                    <?php echo lang('instock') ?></small>
                    <br>
                    <strong>
                      <span class="" ng-bind="product.stock"></span>
                    </strong>
                  </span>
                  <!-- <md-button ng-href="<?php //echo base_url('products/product/') ?>{{product.product_id}}" class="md-icon-button" aria-label="View">
                    <md-tooltip md-direction="left"><?php //echo lang('view') ?></md-tooltip>
                    <md-icon><i class="ion-eye text-muted"></i></md-icon>
                  </md-button>
                  <md-button  ng-click="deleteProduct(product.product_id)" class="md-icon-button" aria-label="Delete">
                    <md-tooltip md-direction="left"><?php //echo lang('delete') ?></md-tooltip>
                    <md-icon><i class="ion-trash-b text-muted"></i></md-icon>
                  </md-button> -->
                </div>
              </div>
            </li>
          </ul>
        </a>
        </li>
      </ul>
      <md-content ng-show="!products.length" class="md-padding no-item-data"><?php echo lang('notdata') ?></md-content>
      <div class="pagination-div text-center">
        <ul class="pagination">
          <li ng-class="DisablePrevPage()"> <a href ng-click="prevPage()"><i class="ion-ios-arrow-back"></i></a> </li>
          <li ng-repeat="n in range()" ng-class="{active: n == currentPage}" ng-click="setPage(n)"> <a href="#" ng-bind="n+1"></a> </li>
          <li ng-class="DisableNextPage()"> <a href ng-click="nextPage()"><i class="ion-ios-arrow-right"></i></a> </li>
        </ul>
      </div>
    </md-content>
  </div>
  <div class="main-content container-fluid col-xs-12 col-md-3 col-lg-3 md-pl-0 lead-left-bar">
    <div class="panel-default panel-table borderten lead-manager-head">
      <md-toolbar class="toolbar-white">
        <div class="md-toolbar-tools">
          <h2 flex md-truncate class="text-bold"><?php echo lang('productcategory'); ?>
            <md-button ng-click="CreateCategory()" class="md-icon-button" aria-label="New">
              <md-icon><i class="ion-gear-a text-muted"></i></md-icon>
            </md-button>
            <br>
        </div>
      </md-toolbar>
      <div class="tasks-status-stat">
        <div class="widget-chart-container">
          <div class="widget-counter-group widget-counter-group-right">
            <div style="width: auto" class="pull-left"> <i style="font-size: 38px;color: #bfc2c6;margin-right: 10px" class="ion-stats-bars pull-left"></i>
              <div class="pull-right" style="text-align: left;margin-top: 10px;line-height: 10px;">
                <h4 style="padding: 0px;margin: 0px;"><b><?php echo lang('productbycategory') ?></b></h4>
                <small><?php echo lang('productcategorystats') ?></small> 
              </div>
            </div>
          </div>
          <div id="container" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
        </div>
      </div>
    </div>
  </div>
  <md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="Create">
    <md-toolbar class="toolbar-white">
      <div class="md-toolbar-tools">
        <md-button ng-click="close()" class="md-icon-button" aria-label="Close"> <i class="ion-android-arrow-forward"></i> </md-button>
        <md-truncate><?php echo lang('addproduct') ?></md-truncate>
      </div>
    </md-toolbar>
    <md-content>
      <md-content layout-padding>
        <md-input-container class="md-block">
          <label><?php echo lang('productname') ?></label>
          <input required type="text" ng-model="product.productname" class="form-control" id="name" placeholder="<?php echo lang('productname'); ?>"/>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('productcategory'); ?></label>
          <md-select placeholder="<?php echo lang('productcategory'); ?>" ng-model="product.categoryid" style="min-width: 200px;">
            <md-select-header>
              <md-toolbar class="toolbar-white">
                <div class="md-toolbar-tools">
                  <h4 flex md-truncate><?php echo lang('categories') ?></h4>
                  <md-button class="md-icon-button" ng-click="NewCategory()" aria-label="Create New">
                    <md-icon><i class="mdi mdi-plus text-muted"></i></md-icon>
                  </md-button>
                </div>
              </md-toolbar>
            </md-select-header>
            <md-option ng-value="name.id" ng-repeat="name in category">{{name.name}}</md-option>
          </md-select>
        </md-input-container>
        <br>
        <md-input-container class="md-block">
          <label><?php echo lang('purchaseprice') ?></label>
          <input required type="number" ng-model="product.purchase_price" class="form-control" id="amount" placeholder="0.00"/>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('salesprice') ?></label>
          <input required type="number" ng-model="product.sale_price" class="form-control" id="amount" placeholder="0.00"/>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('productcode') ?></label>
          <input  type="text" ng-model="product.code" class="form-control" id="productcode" placeholder="<?php echo lang('productcode'); ?>"/>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo $appconfig['tax_label'] ?></label>
          <input  type="number" ng-model="product.vat" ng-value="0" class="form-control" id="tax" placeholder="<?php echo $appconfig['tax_label']; ?>"/>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('instock') ?></label>
          <input  type="number" ng-model="product.stock" ng-value="0" class="form-control" id="stock" placeholder="<?php echo lang('instock'); ?>"/>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('description') ?></label>
          <textarea required name="description" ng-model="product.description" placeholder="<?php echo lang('typeSomething'); ?>" class="form-control"></textarea>
        </md-input-container>
      </md-content>
      <custom-fields-vertical></custom-fields-vertical>
      <md-content layout-padding>
        <section layout="row" layout-sm="column" layout-align="center center" layout-wrap>
          <md-button ng-click="AddProduct()" class="md-raised md-primary pull-right"><?php echo lang('add');?></md-button>
        </section>
      </md-content>
    </md-content>
  </md-sidenav>
  
  <md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="CreateCategory">
    <md-toolbar class="toolbar-white" style="background:#262626">
      <div class="md-toolbar-tools">
        <md-button ng-click="close()" class="md-icon-button" aria-label="Close"><i class="ion-android-arrow-forward"></i></md-button>
        <md-truncate><?php echo lang('categories') ?></md-truncate>
      </div>
    </md-toolbar>
    <md-content>
      <md-toolbar class="toolbar-white" style="background:#262626">
        <div class="md-toolbar-tools">
          <h4 class="text-bold text-muted" flex><?php echo lang('productCategories') ?></h4>
          <md-button aria-label="Add Status" class="md-icon-button" ng-click="NewCategory()">
            <md-tooltip md-direction="bottom"><?php echo lang('addProductCategory') ?></md-tooltip>
            <md-icon><i class="ion-plus-round text-success"></i></md-icon>
          </md-button>
        </div>
      </md-toolbar>
      <md-list-item ng-repeat="name in category" class="noright" ng-click="EditCategory(name.id,name.name, $event)" aria-label="Edit Status"> <strong ng-bind="name.name"></strong>
        <md-icon class="md-secondary md-hue-3 ion-compose " aria-hidden="Edit category"></md-icon>
        <md-icon ng-click='DeleteProductCategory($index)' aria-label="Remove Status" class="md-secondary md-hue-3 ion-trash-b" ></md-icon>
      </md-list-item>
    </md-content>
  </md-sidenav>

  <md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="ImportProductsNav">
      <md-toolbar class="md-theme-light" style="background:#262626">
        <div class="md-toolbar-tools">
        <md-button ng-click="close()" class="md-icon-button" aria-label="Close"><i class="ion-android-arrow-forward"></i></md-button>
        <md-truncate><?php echo lang('importcustomers') ?></md-truncate>
        </div>
      </md-toolbar>
    <md-content>
    <?php echo form_open_multipart('products/productsimport'); ?>
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
      </div>
      <div class="modal-footer">
        <a href="<?php echo base_url('uploads/samples/productimport.csv')?>" class="btn btn-success pull-left"><?php echo lang('downloadsample'); ?></a>
        <button type="submit" class="btn btn-default"><?php echo lang('save'); ?></button>
      </div>
    <?php echo form_close(); ?> 
    </md-content>
  </md-sidenav>
</div>