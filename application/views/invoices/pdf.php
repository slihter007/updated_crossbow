<?php $appconfig = get_appconfig(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>
		<?php echo '' . $invoice['id'] . '-' . date( 'M-d-Y H:i:s' ) . '';?>
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
	</style>
</head>
<?php
if ( $invoice[ 'customercompany' ] === NULL ) { 
	$customer = $invoice[ 'namesurname' ];
} else $customer = $invoice[ 'customercompany' ];

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
			<div class="page-header">
				<img height="75px" src="<?php echo $logo ?>" alt="">
				<small class="pull-right" style="position:relative;top:20px;right:20px;"><strong><span class="text-uppercase"><?php echo lang('invoice') ?></span> <br># <?php echo '' . $appconfig['inv_prefix'] . '' . str_pad( $invoice['id'], 6, '0', STR_PAD_LEFT ) .$appconfig['inv_suffix']. '' ?><br> <?php echo ''. lang( 'serie' ) . ': ' . $invoice['serie'] . '-' . str_pad( $invoice['no'], 6, '0', STR_PAD_LEFT ) . '' ?></strong></small>
			</div>
			<div class="col-md-12 nav panel" style="padding-bottom: 20px">
				<div class="col-md-6 col-sm-6 col-xs-6" style="padding: 0">
					<strong><?php echo lang('from') ?></strong><br>
					<hr>
					<small>
						<strong><?php echo $settings['company']; ?></strong>
					</small>
					<br>
					<small>
						<?php echo '' . $settings[ 'zipcode' ] . '/ ' . $settings[ 'town' ] . '/' . $settings[ 'city' ] . ', ' . $settings[ 'country' ] . '' ?>
					</small><br>
					<small>
						<?php echo $settings[ 'phone' ]; ?>
					</small><br>
					<small>
						<strong><?php echo $appconfig['tax_label'].' '.lang('taxoffice') ?>: </strong><?php echo $settings[ 'taxoffice' ]; ?>
					</small><br>
					<small>
						<?php echo '<strong>' . lang( 'vatnumber' ) . ': </strong>' . $settings[ 'vatnumber' ] . ''; ?>
					</small>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-6" style="padding: 0">
					<strong><?php echo lang('billed_to') ?></strong><br>
					<hr>
					<small>
						<strong><?php echo $customer; ?></strong>
					</small>
					<br>
					<small>
						<?php echo $invoice[ 'billing_street' ]; ?> / <?php echo $invoice[ 'billing_city' ]; ?> / <?php echo $invoice[ 'billing_state' ]; ?> / <?php echo $invoice[ 'billing_zip' ]; ?> / <?php echo $invoice[ 'inv_billing_country' ]; ?>
					</small><br>
					<small>
						<?php echo $invoice[ 'phone' ]; ?>
					</small><br>
					<small>
						<strong><?php echo $appconfig['tax_label'].' '.lang('taxoffice') ?>: </strong><?php echo $invoice[ 'taxoffice' ]; ?>
					</small><br>
					<small>
						<strong><?php echo $appconfig['tax_label'].' '.lang('vatnumber') ?>: </strong><?php echo $invoice[ 'taxnumber' ]; ?>
					</small>
				</div>
			</div>
			<table class="table panel">
				<thead>
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
							<?php echo  lang( 'discount' ) ?>
						</th>
						<th class="col-md-1">
							<?php echo  $appconfig['tax_label'] ?>
						</th>
						<th class="col-md-2">
							<?php echo  lang( 'total' ) ?>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($items as $item){ ?>
					<tr>
						<td class="text-left">
							<?php echo '' . $item[ 'name' ] . '</b><br><small style="font-size:10px;line-height:10px">' . $item[ 'description' ] . '</small>'; ?>
						</td>
						<td class="text-left">
							<?php echo '' . number_format( $item[ 'quantity' ], 2, '.', ',' ) . '' ?>
						</td>
						<td class="text-left">
							<?php echo '' . number_format( $item[ 'price' ], 2, '.', ',' ) . ''; ?>
						</td>
						<td class="text-left">
							<?php echo '' . $item[ 'discount' ] . '%';?>
						</td>
						<td class="text-left">
							<?php echo '' . number_format( $item[ 'tax' ], 2, '.', ',' ) . '%';?>
						</td>
						<td class="text-left">
							<?php echo '' . number_format( $item[ 'total' ], 2, '.', ',' ) . ' ' . currency . '';?>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<div class="col-md-12 col-xs-12 col-sm-12 panel" style="padding:0px;box-shadow: unset;page-break-inside: avoid;">
				<div class="col-md-6 col-xs-6 col-sm-6 panel pull-left" style="padding: 0px;border: unset;box-shadow: unset;">
				</div>
				<div class="col-md-5 col-xs-5 col-sm-5 pull-right" style="padding: 0;">
					<div class="list-group">
						<li class="list-group-item">
							<strong>
								<?php echo lang( 'subtotal' ); ?>
							</strong>
							<span class="pull-right">
								<?php echo '' . number_format( $invoice[ 'sub_total' ], 2, '.', ',' ) . ' ' . currency . '' ?>
							</span>
						</li>
						<li class="list-group-item">
							<strong>
								<?php echo lang( 'linediscount' ); ?>
							</strong>
							<span class="pull-right">
								<?php echo '' . number_format( $invoice[ 'total_discount' ], 2, '.', ',' ) . ' ' . currency . '' ?>
							</span>
						</li>
						<li class="list-group-item">
							<strong>
								<?php echo $appconfig['tax_label']; ?>
							</strong>
							<span class="pull-right">
								<?php echo '' . number_format( $invoice[ 'total_tax' ], 2, '.', ',' ) . ' ' . currency . '' ?>
							</span>
						</li>
						<li class="list-group-item active">
							<strong>
								<?php echo lang( 'total' ); ?>
							</strong>
							<span class="pull-right">
								<?php echo '' . number_format( $invoice[ 'total' ], 2, '.', ',' ) . ' ' . currency . ''; ?>
							</span>
						</li>
					</div>
				</div>
			</div>
			<div class="row" style="page-break-inside: avoid;padding-top: 17%;">
				<div class="col-md-12 col-xs-12 col-sm-12 panel" style="padding:0px;border: unset;box-shadow: unset;margin-bottom:unset;">
					<div class="col-md-6 col-xs-6 col-sm-6 panel pull-left" style="padding: 0px;border: unset;box-shadow: unset;width: 40%;">
						<?php if ($invoice[ 'duenote' ]){echo '<div class="panel panel-warning" style="page-break-inside: avoid;"><div class="panel-heading text-uppercase"><strong>'. lang('duenote').' </strong></div><table style="width:100%;border:none;"><tr style="width:100%;"><td style="width:50%;"><li class="list-group-item"><small>'.$invoice[ 'duenote' ].'</small></li></td></tr></table></div>';} ?>
						<div class="panel panel-default" style="page-break-inside: avoid;">
							<div class="panel-heading text-uppercase">
								<strong>
									<?php echo $settings[ 'termtitle' ] ?>
								</strong>
							</div>
							<table style="width:100%;border:none;">
								<tr style="width:100%;">
									<td style="width:50%;">
										<li class="list-group-item">
											<small>
												<?php echo $settings['termdescription'] ?>
											</small>
										</li>
									</td>
								</tr>
							</table>
						</div>
					</div> 
					<div class="col-md-6 col-xs-6 col-sm-6" style="padding: 0;padding: 0;width: 55%;margin-left: 43%;">
						<?php if ($default_payment) { ?>
						<div style="padding: 0;padding: 0;border: 1px solid #90909045;border-radius: 4px;">
							<div class="panel-heading text-uppercase" style="border-bottom: 1px solid #90909045;    background: whitesmoke;">
								<p>
									<strong>
										<?php echo lang( 'payment_method' ); ?></strong>: <?php echo $default_payment; ?>
								</p>
							</div>
						</div><br>
					<?php } 
					if (count($payments) > 0) {
					?>
					<div style="padding: 0;padding: 0;border: 1px solid #90909045;border-radius: 4px;">
						<div class="panel-heading text-uppercase" style="border-bottom: 1px solid #90909045;    background: whitesmoke;">
							<strong>
								<?php echo lang( 'payments' ); ?>
							</strong>
						</div>
						<table class="table" style="page-break-inside: avoid;">
							<thead style="">
								<tr>
									<th class="col-md-2"><?php echo  lang( 'date' ) ?></th>
									<th class="col-md-2"><?php echo  lang( 'account' ) ?></th>
									<th style="text-align: center;" class="col-md-2"><?php echo  lang( 'amount' ) ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($payments as $payment){ ?>
								<tr>
									<td class="">
										<?php echo date("d-m-Y", strtotime($payment['date'])); ?>
									</td>
									<td class="">
										<?php echo $payment['name']; ?>
									</td>
									<td style="text-align: center;" class=""><?php echo '' . number_format( $payment[ 'amount' ], 2, '.', ',' ) . '' ?>
									</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php //if ($invoice[ 'shipping_street' ]){echo '<div class="row"><div class="col-md-12 col-xs-12 col-sm-12 panel" style="padding-bottom: 20px">     <strong>'.lang('ship_to').'</strong><br><hr><small><strong>'.$customer.'</strong></small><br><small>'.$invoice[ 'shipping_street' ].' / '.$invoice[ 'shipping_city' ].' / '.$invoice[ 'shipping_state' ].' / '.$invoice[ 'shipping_zip' ].' / '.$invoice[ 'inv_shipping_country' ].'</small></div></div>';} ?>
	</div>
</body>
</html>