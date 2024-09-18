<?php
$total = 0;
$pm  = PaymethodData::getById($_POST["paymethod_id"]);
$iva = ConfigurationData::getByPreffix("general_iva")->val;
$coin_symbol = ConfigurationData::getByPreffix("general_coin")->val;
$ivatxt = ConfigurationData::getByPreffix("general_iva_txt")->val;
$ship = ShipData::getById($_POST["ship_id"]);
?>
<div class="container">
	<div class="row">

		<div class="col-md-12">
			<?php if(!isset($_SESSION["client_id"])):?>
				<p class="alert alert-danger">Debes registrarte e iniciar sesion para proceder.</p>
			<?php endif; ?>
		</div>
</div>

	<div class="row">

		<div class="col-md-12">
			<?php if(isset($_SESSION["cart"]) && count($_SESSION["cart"])>0):?>
		<h2>Confirmacion de compra</h2>
		<h4>Metodo de pago: <b><?php echo $pm->name; ?></b></h4>
<table class="table table-bordered">
<thead>
	<th>Codigo</th>
	<th>Producto</th>
	<th>Cantidad</th>
	<th>Precio Unitario</th>
	<th>Total</th>
</thead>
<?php 
foreach($_SESSION["cart"] as $s):?>
<?php $p = ProductData::getById($s["product_id"]); 

$price = 0;
if($p->price_offer==""|| $p->price_offer<0){ $price = $p->price; }else{ $price = $p->price_offer; }
?>
<tr>
<td><?php echo $p->code; ?></td>
<td><?php echo $p->name; ?></td>
<td style="width:100px;">
<?php echo $s["q"]; ?>
</td>
<td><h4><?php echo $coin_symbol; ?> <?php echo $price; ?></h4> </td>
<td><h4><?php echo $coin_symbol; ?> <?php echo $price*$s["q"]; ?></h4> </td>
<?php
$total += $s["q"]*$price;
 endforeach; ?>
</tr>
</table>


<div class="row">

<div class="col-md-6">
	<table class="table table-bordered">
		<tr>
			<td>Envio</td><td><?php echo $coin_symbol; ?> <?php echo number_format($ship->price,2,".",","); ?></td>
		</tr>
		<tr>
			<td>Subtotal</td><td><?php echo $coin_symbol; ?> <?php echo number_format($total,2,".",","); ?></td>
		</tr>
		<tr>
			<td><?php echo $ivatxt; ?></td><td><?php echo $coin_symbol; ?> <?php echo number_format($total*($iva/100),2,".",","); ?></td>
		</tr>
		<?php if(isset($_SESSION["coupon"])):
$coupon = CouponData::getById($_SESSION["coupon"]);
		$discount = $coupon->val;
		$subtotal=$total+(($total*($iva/100)));
		$xdiscount=($subtotal )*($discount/100);
		?>
		<tr>
			<td>SubTotal</td><td><?php echo $coin_symbol; ?> <?php echo number_format($subtotal,2,".",","); ?></td>
		</tr>
		<tr>
			<td>Descuento: <b><?php echo $coupon->name." ($coupon->val%)";?></b></td><td><?php echo $coin_symbol; ?> <?php echo number_format($xdiscount,2,".",","); ?></td>
		</tr>
		<tr>
			<td>Total</td><td><?php echo $coin_symbol; ?> <?php echo number_format($subtotal-$xdiscount,2,".",","); ?></td>
		</tr>
		<tr>
			<td>Total+Envio</td><td><?php echo $coin_symbol; ?> <?php echo number_format(($total+($total*($iva/100)))+$ship->price,2,".",","); ?></td>
		</tr>
	<?php else:?>
		<tr>
			<td>Total</td><td><?php echo $coin_symbol; ?> <?php echo number_format(($total+($total*($iva/100))),2,".",","); ?></td>
		</tr>
		<tr>
			<td>Total+Envio</td><td><?php echo $coin_symbol; ?> <?php echo number_format(($total+($total*($iva/100)))+$ship->price,2,".",","); ?></td>
		</tr>

	<?php endif; ?>

	</table>
<br>
<?php if($pm->short_name=="conekta"):?>
	<div class="card">
		<div class="card-header">Datos de la tarjeta</div>
		<div class="card-body">

  <span class="card-errors"></span>
  <div>
    <label>
      <span>Nombre del tarjetahabiente</span>
      <input type="text" size="20" data-conekta="card[name]">
    </label>
  </div>
  <div>
    <label>
      <span>Número de tarjeta de crédito</span>
      <input type="text" size="20" data-conekta="card[number]">
    </label>
  </div>
  <div>
    <label>
      <span>CVC</span>
      <input type="text" size="4" data-conekta="card[cvc]">
    </label>
  </div>
  <div>
    <label>
      <span>Fecha de expiración (MM/AAAA)</span>
      <input type="text" size="2" data-conekta="card[exp_month]">
    </label>
    <span>/</span>
    <input type="text" size="4" data-conekta="card[exp_year]">
  </div>
</div>
</div>
<br>
<?php endif; ?>
</div>
<div class="col-md-6">
<?php if(isset($_SESSION["client_id"])):?>
<form action="index.php?action=buy" method="post">
	
  <div class="form-group">
    <label for="exampleInputEmail1">Persona que recibe</label>
    <input type="text" name="person_name" class="form-control" id="exampleInputEmail1" placeholder="Persona que recibe">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Numero de Telefono</label>
    <input type="text" name="person_phone" class="form-control" id="exampleInputEmail1" placeholder="Numero de Telefono">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Direccion / Domicilio</label>
    <input type="text" name="person_address" class="form-control" id="exampleInputEmail1" placeholder="Direccion / Domicilio">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Ciudad</label>
    <input type="text" name="person_city" class="form-control" id="exampleInputEmail1" placeholder="Ciudad">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Codigo Postal / ZIP</label>
    <input type="text" name="person_zip" class="form-control" id="exampleInputEmail1" placeholder="Codigo Postal / ZIP">
  </div>


	<input type="hidden" name="ship_id" value="<?php echo $_POST["ship_id"]; ?>">
<input type="hidden" class="form-control" required name="paymethod_id" value="<?php echo $_POST["paymethod_id"];?>">
<button class="btn btn-primary btn-block">Proceder a Comprar</button>
</form>
<?php endif; ?>
<br>
<a href="index.php?view=mycart" class="btn btn-warning btn-block">Regresar al Carrito</a>
<br><a href="index.php?action=cleancart" class="btn btn-danger btn-block">Cancelar</a>

</div>
</div>



			<?php else:
			?>
				<div class="jumbotron">
				<h2>No hay productos</h2>
				<p>No ha agregado productos al carrito.</p>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>