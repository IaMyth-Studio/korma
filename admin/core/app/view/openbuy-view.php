
<?php

$buy = BuyData::getById($_GET["buy_id"]);
$products = BuyProductData::getAllByBuyId($_GET["buy_id"]);
$client = ClientData::getById($buy->client_id);
$paymethod = $buy->getPaymethod();
$iva = ConfigurationData::getByPreffix("general_iva")->val;
$coin = ConfigurationData::getByPreffix("general_coin")->val;
$ivatxt = ConfigurationData::getByPreffix("general_iva_txt")->val;
$ship = ShipData::getById($buy->ship_id);

?>
<div class="row">
	<div class="col-md-12">
	<h2> Compra #<?php echo $buy->id; ?> [<?php echo $buy->getStatus()->name; ?>]</h2>
	<h4>Cliente: <?php echo $client->getFullname(); ?></h4>
	<h4>Metodo de pago : <?php echo $paymethod->name; ?></h4>
<?php if(count($products)>0):?>
	<div class="box box-primary">
<table class="table table-bordered">
	<thead>
		<th></th>
		<th>Codigo</th>
		<th>Producto</th>
		<th>Total</th>
	</thead>
	<?php foreach($products as $p):
$px = $p->getProduct();
	?>
	<tr>
		<td><a target="_blank" href="../index.php?view=producto&product_id=<?php echo $px->id; ?>">Detalles</a></td>
		<td><?php echo $px->code; ?></td>
		<td><?php echo $px->name; ?></td>
		<td><?php echo $coin; ?> <?php echo number_format($p->price*$p->q,2,".",","); ?></td>
	</tr>

	<?php endforeach; ?>
</table>
</div>

<div class="row">
<div class="col-md-5 col-md-offset-7">
	<div class="box box-primary">
	<table class="table table-bordered">
		<tr>
			<td>Subtotal</td><td><?php echo $coin; ?> <?php echo number_format($buy->getTotal(),2,".",","); ?></td>
		</tr>
		<tr>
			<td><?php echo $ivatxt; ?></td><td><?php echo $coin; ?> <?php echo number_format($buy->getTotal()*($iva/100),2,".",","); ?></td>
		</tr>
		<?php 
$total = $buy->getTotal();
		if($buy->coupon_id!=null):
$coupon = CouponData::getById($buy->coupon_id);
		$discount = $coupon->val;
		$subtotal=$total+(($total*($iva/100)));
		$xdiscount=($subtotal )*($discount/100);
		?>
		<tr>
			<td>SubTotal</td><td><?php echo $coin; ?> <?php echo number_format($subtotal,2,".",","); ?></td>
		</tr>
		<tr>
			<td>Descuento: <b><?php echo $coupon->name." ($coupon->val%)";?></b></td><td><?php echo $coin; ?> <?php echo number_format($xdiscount,2,".",","); ?></td>
		</tr>
		<tr>
			<td>Total</td><td><?php echo $coin; ?> <?php echo number_format($subtotal-$xdiscount,2,".",","); ?></td>
		</tr>
		<tr>
			<td>Total+Envio</td><td><?php echo $coin; ?> <?php echo number_format(($subtotal-$xdiscount)+$ship->price,2,".",","); ?></td>
		</tr>

	<?php else:?>
		<tr>
			<td>Total</td><td><?php echo $coin; ?> <?php echo number_format($total+($total*($iva/100)),2,".",","); ?></td>
		</tr>
		<tr>
			<td>Total+Envio</td><td><?php echo $coin; ?> <?php echo number_format(($total+($total*($iva/100)))+$ship->price,2,".",","); ?></td>
		</tr>
	<?php endif; ?>
	</table>




	</div>
<br>




</div>



</div>
<div class="row">
<div class="col-md-12">



	<h2>Persona que recibe</h2>
	<div class="box box-primary">
<table class="table table-bordered">
	<thead>
		<th>Clave</th>
		<th>Valor</th>
	</thead>
	<tr>
		<td>Nombre</td>
		<td><?php echo $buy->person_name; ?></td>
	</tr>
	<tr>
		<td>Telefono</td>
		<td><?php echo $buy->person_phone; ?></td>
	</tr>
	<tr>
		<td>Direccion / Domicilio</td>
		<td><?php echo $buy->person_address; ?></td>
	</tr>
	<tr>
		<td>Ciudad</td>
		<td><?php echo $buy->person_city; ?></td>
	</tr>
	<tr>
		<td>Codigo Postal / Zip</td>
		<td><?php echo $buy->person_zip; ?></td>
	</tr>
</table>
</div>


<!--
<form class="form-horizontal" role="form" method="post" action="index.php?action=changestatus">
  <div class="form-group">
    <label for="inputEmail1" class="col-md-3 control-label">Estado</label>
    <div class="col-md-6">
<select name="status_id" class="form-control">
<?php foreach(StatusData::getAll() as $s):?>
<option value="<?php echo $s->id; ?>" <?php if($s->id==$buy->status_id){ echo "selected"; }?>><?php echo $s->name; ?></option>
<?php endforeach; ?>
</select>
    </div>

    <div class="col-md-3">
      <input type="hidden" name="buy_id" value="<?php echo $buy->id; ?>">
      <button type="submit" class="btn btn-default">Cambiar Estado</button>

    </div>

  </div>
</form>
-->

</div>
</div>


<?php endif; ?>
	</div>
</div>





