<?php $appconfig = get_appconfig(); ?>
<div class="ciuis-body-content" ng-controller="Account_Controller">
	<md-content class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
	<md-toolbar class="toolbar-white">
		<div class="md-toolbar-tools">
			<h2 class="md-pl-10" flex md-truncate ng-bind="account.name"></h2>
			<md-button ng-hide="QuickTransfer_ == true" ng-click="QuickTransfer()" class="md-icon-button md-primary" aria-label="Actions">
					<md-tooltip md-direction="left"><?php echo lang('quick_transfer') ?></md-tooltip>
					<md-icon class="mdi mdi-swap"></md-icon>
			</md-button>
			<md-button ng-hide="QuickTransfer_ == false" ng-click="CancelTransfer()" class="md-icon-button md-primary" aria-label="Actions">
					<md-tooltip md-direction="left"><?php echo lang('cancel_transfer') ?></md-tooltip>
					<md-icon class="mdi mdi-close"></md-icon>
			</md-button>
			<md-button ng-click="Update()" class="md-icon-button md-primary" aria-label="Actions">
					<md-tooltip md-direction="left">{{lang.update}}</md-tooltip>
					<md-icon class="mdi mdi-edit"></md-icon>
			</md-button>
			<md-button ng-click="Delete()" class="md-icon-button md-primary" aria-label="Actions">
				<md-tooltip md-direction="left">{{lang.delete}}</md-tooltip>
				<md-icon class="ion-trash-b"></md-icon>
			</md-button>
		</div>
	</md-toolbar>
	<md-subheader style="background: #424242;color: white;" ng-hide="QuickTransfer_ == false" class="md-primary"><?php echo lang('quick_transfer') ?></md-subheader>
	<md-content ng-hide="QuickTransfer_ == false" class="ciuis9876578d bg-white">
		<div class="col-md-4">
		<h3 ng-bind="account.name"></h3>
		<h5 class="text-bold money-area"><strong ng-bind-html="current_balance - TransferAmount | currencyFormat:cur_code:null:true:cur_lct"></strong></h5>	
		</div>
		<div class="col-md-4">
			<md-input-container class="md-block" flex-gt-xs>
            <label><?php echo lang('to_account') ?></label>
			<md-select placeholder="<?php echo lang('choiseaccount'); ?>" ng-model="To_Account_ID" style="min-width: 200px;">
				<md-option ng-value="account.id" ng-repeat="account in accounts">{{account.name}}</md-option>
			</md-select>
          	</md-input-container>
		</div>
		<div class="col-md-3">
			<md-input-container class="md-block" flex-gt-sm>
				<label><?php echo lang('amount'); ?></label>
				<input ng-model="TransferAmount">
			</md-input-container>
		</div>
		<div class="col-md-1">
			<md-button ng-init="transfer_message = 'Transaction successfully completed.'" style="margin-top: 10px;" ng-click="MakeTransfer()" class="md-icon-button md-primary" aria-label="Make Transfer">
				<md-tooltip md-direction="bottom"><?php echo lang('make_transfer') ?></md-tooltip>
				<md-icon class="mdi mdi-check-circle"></md-icon>
			</md-button>
		</div>
	</md-content>
	<md-divider ng-hide="QuickTransfer_ == false"></md-divider>
	<md-content class="ciuis9876578d bg-white">
		<img src="<?php echo base_url()?>assets/img/accountbg.png" style="width: 125px;" alt="" class="pull-right">
		<h4 class="text-bold money-area"><strong ng-bind-html="account.account_total | currencyFormat:cur_code:null:true:cur_lct"></strong></h4>
		<p>
			<strong><?php echo lang('accountstatus'); ?>: <span ng-show="account.status == true" class="text-success"><?php echo lang( 'active' ) ?></span><span ng-show="account.status == false" class="text-danger"><?php echo lang( 'inactive' ) ?></span></strong>
		</p>
		<div class="bar" ng-show="account.type == '1'">
			<div class="complete" ng-bind="account.bankname+' / '+account.branchbank"></div>
			<div class="complete"><strong><?php echo lang('account') ?>: </strong><span ng-bind="account.account"></span></div>
			<div class="complete"><strong><?php echo lang('iban') ?>: </strong><span ng-bind="account.iban"></span></div>
		</div>
	</md-content>
	<md-toolbar class="toolbar-white">
		<div class="md-toolbar-tools">
			<h2 class="md-pl-10" flex md-truncate><?php echo lang('accountactivity'); ?></h2>					
		</div>
	</md-toolbar>
	<md-content class="bg-white">
		<md-list flex class="md-p-0 sm-p-0 lg-p-0">
			<md-list-item ng-repeat="transaction in account.payments" ng-click="Detail(transaction.id)"  aria-label="Detail">
				<md-button ng-show="transaction.transactiontype =='0'" class="md-icon-button" aria-label="Actions">
					<md-tooltip md-direction="bottom"><?php echo lang('incomings') ?></md-tooltip>
					<md-icon class="ion-arrow-down-a text-success"></md-icon>
				</md-button>
				<md-button ng-show="transaction.transactiontype !='0'" class="md-icon-button" aria-label="Actions">
					<md-tooltip md-direction="bottom"><?php echo lang('outgoings') ?></md-tooltip>
					<md-icon class="ion-arrow-up-a text-danger"></md-icon>
				</md-button>
				<p flex md-truncate><strong ng-bind="transaction.date | date : 'MMM d, y h:mm:ss a'"></strong></p>
				<h4 class="md-secondary"><strong ng-bind-html="transaction.amount | currencyFormat:cur_code:null:true:cur_lct"></strong></h4>
			<md-divider></md-divider>
			<div style="visibility: hidden">
			<div  class="md-dialog-container" id="payment-{{transaction.id}}">
			<md-dialog aria-label="Payment Detail">
				<md-toolbar class="toolbar-white">
				  <div class="md-toolbar-tools">
					<h2 ng-show="transaction.transactiontype == '0' && transaction.is_transfer == '0'"><strong class="text-success"><?php echo lang('income') ?></strong></h2>
					<h2 ng-show="transaction.transactiontype == '0' && transaction.is_transfer == '1'"><strong class="text-warning"><?php echo lang('transfer') ?></strong></h2>
					<h2 ng-if="transaction.transactiontype == '1'  && transaction.is_transfer == '0'"><strong class="text-danger"><?php echo lang('outgoing') ?></strong></h2>
					<h2 ng-if="transaction.transactiontype == '1'  && transaction.is_transfer == '1'"><strong class="text-warning"><?php echo lang('transfer_transaction') ?></strong></h2>
					<span flex></span>
					<md-button class="md-icon-button" ng-click="close()">
					  <md-icon class="ion-close-round" aria-label="Close dialog" style="color:black"></md-icon>
					</md-button>
				  </div>
				</md-toolbar>
				<md-dialog-content style="max-width:800px;max-height:810px; ">
				   <md-content class="bg-white">
					<md-list flex>
						<md-list-item>
							<h4 class="text-bold money-area"><strong ng-bind-html="transaction.amount | currencyFormat:cur_code:null:true:cur_lct"></strong></h4>
						</md-list-item>
						<md-list-item ng-show="transaction.is_transfer == '0'">
							<span ng-show="transaction.transactiontype == '0'"><strong class="text-success"><?php echo lang('payment_by') ?> {{transaction.customer}}</strong></span>
							<span ng-show="transaction.transactiontype == '1' && transaction.for_customer == true"><strong class="text-danger"><?php echo lang('expense_for') ?> {{transaction.customer}}</strong></span>
							<span ng-show="transaction.transactiontype == '1' && transaction.for_customer == false"><strong class="text-danger"><?php echo lang('expenses_incurred_by_staff') ?></strong></span>
						</md-list-item>
						<md-divider></md-divider>
						<md-list-item>
						<span ng-bind-html="transaction.not|trustAsHtml"></span>
						</md-list-item>
						<md-divider></md-divider>
						<md-content layout-padding>
							<h3 class="md-mt-0" ng-bind="transaction.date | date : 'MMM d, y h:mm:ss a'"></h3>
						</md-content>
						<md-list-item>
						<span><strong>{{transaction.staff}}</strong> <?php echo lang('made_this_transaction') ?></span>
						</md-list-item>
					</md-list>
				  </md-content>     
				</md-dialog-content>
			</md-dialog>
			</div>
			</div>
			</md-list-item>
		</md-list>
		<md-content ng-show="!account.payments.length" class="md-padding bg-white no-item-data"><?php echo lang('notdata') ?></md-content>
	</md-content>
	</md-content>
	<ciuis-sidebar></ciuis-sidebar>

<md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="Update"  ng-cloak>
  <md-toolbar class="toolbar-white">
  <div class="md-toolbar-tools">
	<md-button ng-click="close()" class="md-icon-button" aria-label="Close">
		 <i class="ion-android-arrow-forward"></i>
	</md-button>
	<md-truncate><?php echo lang('update') ?></md-truncate>
  </div>
  </md-toolbar>
  <md-content layout-padding="">
	<md-content layout-padding>
		<md-input-container class="md-block">
			<label><?php echo lang('name') ?></label>
			<input required type="text" ng-model="account.name" class="form-control" id="title" placeholder="<?php echo lang('name'); ?>"/>
		</md-input-container>
		<md-input-container ng-show="account.type == '1'" class="md-block">
			<label><?php echo lang('bankname') ?></label>
			<input required type="text" ng-model="account.bankname" class="form-control" id="title" placeholder="<?php echo lang('bankname'); ?>"/>
		</md-input-container>
		<md-input-container ng-show="account.type == '1'" class="md-block">
			<label><?php echo lang('branchbank') ?></label>
			<input required type="text" ng-model="account.branchbank" class="form-control" id="title" placeholder="<?php echo lang('branchbank'); ?>"/>
		</md-input-container>
		<md-input-container ng-show="account.type == '1'" class="md-block">
			<label><?php echo lang('account') ?></label>
			<input required type="text" ng-model="account.account" class="form-control" id="title" placeholder="<?php echo lang('account'); ?>"/>
		</md-input-container>
		<md-input-container ng-show="account.type == '1'" class="md-block">
			<label><?php echo lang('iban') ?></label>
			<input required type="text" ng-model="account.iban" class="form-control" id="title" placeholder="<?php echo lang('iban'); ?>"/>
		</md-input-container>
		<md-switch class="pull-left" ng-model="account.status" aria-label="Status"><strong class="text-muted"><?php echo lang('active') ?></strong></md-switch>
		<section layout="row" layout-sm="column" class="pull-right" layout-wrap>
			  <md-button ng-click="UpdateAccount()" class="md-raised md-primary"><?php echo lang('update');?></md-button>
		</section>
	</md-content>
 </md-content>
</md-sidenav>

</div>
<script> var ACCOUNTID = "<?php echo $account['id'] ?>"</script>
