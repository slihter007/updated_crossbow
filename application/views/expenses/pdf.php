<?php $appconfig = get_appconfig(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>
		<?php echo '' . $expense['id'] . '-' . date( 'M-d-Y H:i:s' ) . '';?>
	</title>
	<link rel='stylesheet prefetch' href='<?php echo base_url('assets/lib/material-design-icons/css/material-design-iconic-font.min.css'); ?>'>
	<link rel='stylesheet prefetch' href='<?php echo base_url('assets/lib/bootstrap/dist/css/bootstrap.min.css'); ?>'>
	<style>
		.list-group-item.active,
		.list-group-item.active:focus,
		.list-group-item.active:hover {
			z-index: 2;
			color: #fff;
			background-color: #555;
			border-color: #555;
		}
		.page-header.row {
			margin: 30px 0 10px 0 !important;
		}
		.page-header .logo {
			padding-right: 0 !important;
			padding-left: 0 !important;
		}
		.panel {
			box-shadow: 0 1px 1px rgb(255, 255, 255) !important;
		}
	</style> 
</head>
<?php
if ($expense[ 'internal' ] == '1') {
	$customer = lang('internal'). ' ' .lang('expense');
} else {
	if ( $expense[ 'customer' ] == NULL ) { 
		$customer = $expense[ 'individual' ];
	} else $customer = $expense[ 'customer' ];
}
$logo =  file_exists(FCPATH.'uploads/ciuis_settings/'.$settings['app_logo']);
if(file_exists(FCPATH.'uploads/ciuis_settings/'.$settings['app_logo'])) {
	$logo = FCPATH.'uploads/ciuis_settings/'.$settings['app_logo'];
} else {
	$logo = FCPATH.'uploads/ciuis_settings/'.$settings['logo'];
}
?>
<body>
	<div class="container">
		<div class="row">
			<div class="page-header row">
				<div class="col-md-3 col-sm-3 col-xs-3 logo">
					<img height="75px" src="<?php echo $logo ?>" alt=""><br>
					<small>
						<strong style="font-size: 11px;"><?php echo $settings['company']; ?></strong>
					</small><br>
					<small style="font-size: 11px;">
						<?php echo '' .$settings[ 'town' ] . ', ' . $settings[ 'city' ] . ', ' . $settings[ 'zipcode' ] . ', ' . $settings[ 'country' ] . '' ?>
					</small><br>
				</div>
				<div class="col-md-4 col-sm-4 col-xs-4" style="padding-top: 2%;">
					<small>
						<strong style="font-size: 11px;"><?php echo $appconfig['tax_label'].' '.lang('taxoffice') ?>: </strong><?php echo $settings[ 'taxoffice' ]; ?>
					</small><br>
					<small>
						<strong style="font-size: 11px;"><?php echo $appconfig['tax_label'].' '.lang('vatnumber') ?>: </strong><?php echo $settings[ 'vatnumber' ]; ?>
					</small>
				</div>
				<div class="col-md-5 col-sm-5 col-xs-5">
					<small class="" style="position:relative;top:20px;right:20px;padding-right: 40px;text-align: left;float: right;">
						<strong><span class="text-uppercase"><?php echo lang('expense') ?></span> <br>
							# <?php echo '' . $appconfig['expense_prefix'] . '-' . str_pad( $expense['id'], 6, '0', STR_PAD_LEFT ). $appconfig['expense_suffix'] . '' ?>
							<br> 
							<?php echo ''. lang( 'reference' ) . ': ' . $expense['number'] . '' ?>
							<br>
							<?php echo '' . lang( 'expense' ) .' '.lang( 'date' ). ': ' . date("d-m-Y", strtotime($expense['created'])) . '' ?>
						</strong>
					</small>
				</div>
			</div>
			<div class="col-md-12 nav panel" style="padding-bottom: 20px;box-shadow: unset;padding-right: 0;">
				<?php if ($expense[ 'internal' ] == '1') { ?> 
					<div style="border-bottom: 1px solid #eee;padding-bottom: 10px;">
						<strong><?php echo lang('internal'). ' '.lang('expense') ?></strong>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-6" style="padding: 0">
				<?php } else { ?>
				<div style="border-bottom: 1px solid #eee;padding-bottom: 10px;">
					<strong><?php echo lang('customer').' '.lang('expense') ?></strong>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-6" style="padding: 0">
					<br>
					<small>
						<strong><?php echo $customer; ?></strong>
					</small><br>
					<small>
						<?php echo $expense[ 'billing_street' ]; ($expense[ 'billing_city' ] != '')? ',' : ''; ?>, 
						<?php echo $expense[ 'billing_city' ]; ?><br>
						<?php echo $expense[ 'billing_state' ]; ($expense[ 'billing_zip' ] != '')? ',' : ''; ?>
					</small><br>
					<small>
						<?php echo $expense[ 'customer_phone' ]; ?>
					</small><br>
					<small>
						<strong><?php echo $appconfig['tax_label'].' '.lang('taxoffice') ?>: </strong><?php echo $expense[ 'customer_tax' ]; ?>
					</small><br>
					<small>
						<?php echo '<strong>' . $appconfig['tax_label'].' '.lang( 'vatnumber' ) . ': </strong>' . $expense[ 'customer_taxnum' ] . ''; ?>
					</small>
				</div>
			<?php } ?><br>
			</div>
			<table class="table panel" style="box-shadow: 0 1px 1px rgb(255, 255, 255);margin-top: 3%;">
				<thead style="border-top: 2px solid #e4e4e4;">
					<tr>
						<th class="col-md-6">
							<?php echo  lang( 'invoiceitemdescription' ) ?>
						</th>
						<th class="col-md-1">
							<?php echo  lang( 'quantity' ) ?>
						</th>
						<th class="col-md-1">
							<?php echo  lang( 'price' ) ?>
						</th>
						<th class="col-md-1">
							<?php echo  $appconfig['tax_label'] ?>
						</th>
						<th class="col-md-2" style="text-align: center;">
							<?php echo  lang( 'total' ) ?>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($items as $item){ ?>
					<tr style="border-bottom: 1px solid #eaeaea;">
						<td class="text-left">
							<?php echo '<b>' . $item[ 'name' ] . '</b><br><small style="font-size:10px;line-height:10px">' . $item[ 'description' ] . '</small>'; ?>
						</td>
						<td class="text-left">
							<?php echo '' . number_format( $item[ 'quantity' ], 2, '.', ',' ) . '' ?>
						</td>
						<td class="text-left">
							<?php echo '' . number_format( $item[ 'price' ], 2, '.', ',' ) . ''; ?>
						</td>
						<td class="text-left">
							<?php echo '' . number_format( $item[ 'tax' ], 2, '.', '.' ) . '%';?>
						</td>
						<td class="text-left" style="text-align: center;">
							<?php echo '' . number_format( $item[ 'total' ], 2, '.', ',' ) . ' ' . currency . '';?>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<div class="col-md-12 col-xs-12 col-sm-12 panel" style="padding:0px;margin-top: 4%;page-break-inside: avoid;">
				<div class="col-md-6 col-xs-6 col-sm-6 panel pull-left" style="padding: 0;padding: 0;border: 1px solid #90909045;border-radius: 4px;width: 55%;page-break-inside: avoid;">
					<div class="panel-heading text-uppercase" style="border-bottom: 1px solid #90909045;    background: whitesmoke;">
						<strong>
							<?php echo lang( 'paidvia' ); ?>
						</strong>
					</div>
					<table class="table" style="page-break-inside: avoid;">
						<thead style="">
							<tr>
								<th class="col-md-12"><?php echo  $expense['account'] ?></th>
							</tr>
						</thead>
					</table>
				</div>
				<div class="col-md-5 col-xs-5 col-sm-5 pull-right" style="padding: 0">
					<div class="list-group">
						<li class="list-group-item">
							<strong>
								<?php echo lang( 'subtotal' ); ?>
							</strong>
							<span class="pull-right">
								<?php echo '' . number_format( $expense[ 'sub_total' ], 2, '.', ',' ) . ' ' . currency . '' ?>
							</span>
						</li>
						<li class="list-group-item">
							<strong>
								<?php echo $appconfig['tax_label']; ?>
							</strong>
							<span class="pull-right">
								<?php echo '' . number_format( $expense[ 'total_tax' ], 2, '.', ',' ) . ' ' . currency . '' ?>
							</span>
						</li>
						<li class="list-group-item active">
							<strong>
								<?php echo lang( 'total' ); ?>
							</strong>
							<span class="pull-right">
								<?php echo '' . number_format( $expense[ 'amount' ], 2, '.', ',' ) . ' ' . currency . ''; ?>
							</span>
						</li>
					</div>
				</div>
			</div>
		</div>
			<?php if (count($otherFiles) > 0) { ?>
			<div style="padding-bottom: 10px;font-size: 17px;margin-top: 10px;border-top: 1px solid #efefef;border-bottom: 1px solid #dddddd;">
				<strong><?php echo lang('attached'). ' ' .lang('files') ?></strong>
			</div>
			<table class="table panel" style="box-shadow: 0 1px 1px rgb(255, 255, 255);border-bottom: 1px solid #dddddd;">
				<tbody>
					<?php foreach($otherFiles as $file){ ?>
					<tr>
						<td class="text-left">
							<?php echo '' . $file[ 'file_name' ] . ''; ?>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		<?php } ?>
			<?php if (count($images) > 0) { ?>
				<div style="border-bottom: 1px solid #eee;padding-bottom: 10px;margin-bottom: 5%;font-size: 17px;margin-top: 40px;">
					<strong><?php echo lang('attached'). ' ' .lang('receipts') ?></strong>
				</div>
				<?php foreach($images as $file) { ?>
					<img src="<?php echo $file['path'] ?>" style="max-width: 100%;"> <br><br>
				<?php } ?>
			<?php } ?>
		</div>
	</div>
</body>
</html>