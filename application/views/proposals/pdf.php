<?php $appconfig = get_appconfig(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>
		<?php echo '' . $proposals['id'] . '-' . date( 'M-d-Y H:i:s' ) . '';?>
	</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/lib/bootstrap/dist/css/bootstrap.css'); ?>" type="text/css"/>
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
<?php if($proposals['relation_type'] == 'customer'){if($proposals['customercompany']===NULL){$customer = $proposals['namesurname'];} else $customer = $proposals['customercompany'];} ?>
<?php if($proposals['relation_type'] == 'lead'){$customer = $proposals['leadname'];} 
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
				<small class="pull-right" style="position:relative;top:20px;right:20px;"><strong><span class="text-uppercase"><?php echo lang('proposal') ?></span> <br># <?php echo '' . $appconfig['proposal_prefix'] . '-' . str_pad( $proposals['id'], 6, '0', STR_PAD_LEFT ).$appconfig['proposal_suffix'] . '' ?>
					<br> <?php echo lang( 'date' )?> : <?php echo $proposals['date'] ?> - <?php echo lang( 'opentill' )?> : <?php echo $proposals['opentill'] ?>
					</strong></small>
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
						<?php echo '<strong>' . $appconfig['tax_label'].' '.lang( 'vatnumber' ) . ': </strong>' . $settings[ 'vatnumber' ] . ''; ?>
					</small>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-6" style="padding: 0">
					<strong><?php echo lang('to') ?></strong><br>
					<hr>
					<small>
						<strong><?php echo $customer; ?></strong>
					</small>
					<br>
					<small>
						<?php echo $proposals[ 'toemail' ] ?>
					</small><br>
					<small>
						<?php echo $proposals[ 'toaddress' ] ?>
					</small><br>
				</div>
			</div>
			<div class="col-md-12">
			<?php echo $proposals[ 'content' ] ?>
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
							<?php echo '' . number_format( $item[ 'tax' ], 2, ',', '.' ) . '%';?>
						</td>
						<td class="text-left">
							<?php echo '' . number_format( $item[ 'total' ], 2, '.', ',' ) . ' ' . currency . '';?>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<div class="col-md-12 col-xs-12 col-sm-12 panel" style="padding:0px">
				<div class="col-md-6 col-xs-6 col-sm-6 panel pull-left" style="padding: 0px;">
					
				</div>
				<div class="col-md-5 col-xs-5 col-sm-5 pull-right" style="padding: 0">
					<div class="list-group">
						<li class="list-group-item">
							<strong>
								<?php echo lang( 'subtotal' ); ?>
							</strong>
							<span class="pull-right">
								<?php echo '' . number_format( $proposals[ 'sub_total' ], 2, '.', ',' ) . ' ' . currency . '' ?>
							</span>
						</li>
						<li class="list-group-item">
							<strong>
								<?php echo lang( 'linediscount' ); ?>
							</strong>
							<span class="pull-right">
								<?php echo '' . number_format( $proposals[ 'total_discount' ], 2, '.', ',' ) . ' ' . currency . '' ?>
							</span>
						</li>
						<li class="list-group-item">
							<strong>
								<?php echo $appconfig['tax_label']; ?>
							</strong>
							<span class="pull-right">
								<?php echo '' . number_format( $proposals[ 'total_tax' ], 2, '.', ',' ) . ' ' . currency . '' ?>
							</span>
						</li>
						<li class="list-group-item active">
							<strong>
								<?php echo lang( 'total' ); ?>
							</strong>
							<span class="pull-right">
								<?php echo '' . number_format( $proposals[ 'total' ], 2, '.', ',' ) . ' ' . currency . ''; ?>
							</span>
						</li>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>