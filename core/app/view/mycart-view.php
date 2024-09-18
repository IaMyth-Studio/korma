<?php
$total = 0;
$iva = ConfigurationData::getByPreffix("general_iva")->val;
$coin_symbol = ConfigurationData::getByPreffix("general_coin")->val;
$ivatxt = ConfigurationData::getByPreffix("general_iva_txt")->val;

?>
    <section class="container pt-md-3 pb-5 mb-md-3">

<div class="page-header">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <h2 class="entry-title"><span>Carrito de Compras</span></h2>
              <div class="breadcrumb">
                <span></span>
                <a href="./">Inicio</a>
                <span class="crumbs-spacer"><i class="fa fa-angle-double-right"></i></span>
                <span class="current">Carrito</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <br>
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
<table class="table table-bordered">
<thead>
	<th>Codigo</th>
	<th>Producto</th>
	<th>Cantidad</th>
	<th>Precio Unitario</th>
	<th>Total</th>
	<th></th>
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
<form id="p-<?=$s["product_id"];?>">
<input type="hidden" name="p_id" value="<?=$s["product_id"];?>">
<input type="number" min="1" name="q" id="num-<?=$s["product_id"];?>" value="<?php echo $s["q"]; ?>" class="form-control">
</form>
<script>
	$("#num-<?=$s['product_id'];?>").change(function(){
		$.post("./?action=editcart",$("#p-<?=$s['product_id']?>").serialize()	,function(data){
			window.location = window.location;

		});
	});
</script>
</td>
<td><h4>
<?php if($p->price_offer==""|| $p->price_offer<0 ){} else { echo "<s> ".$coin_symbol." $p->price</s>";} ?>

	<?php echo $coin_symbol; ?> <?php echo $price; ?></h4> 

</td>
<td><h4><?php echo $coin_symbol; ?> <?php echo $price*$s["q"]; ?></h4> </td>
<td style="width:30px;"><a href="index.php?action=deletefromcart&product_id=<?php echo $p->id; ?>&href=cart" class="btn btn-danger btn-xs"><i class="fa fa-remove"></i></a></td>
<?php
$total += $s["q"]*$price;
 endforeach; ?>
</tr>
</table>


<div class="row">
<div class="col-md-6">
<form class="form-horizontal" role="form" method="post" action="./?action=usecoupon">
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Cupon</label>
    <div class="col-lg-10">
      <input type="text" name="coupon" class="form-control" value="<?php if(isset($_SESSION["coupon"])){ echo CouponData::getById($_SESSION["coupon"])->name; } ?>" id="inputEmail1" placeholder="Cupon" required>
    </div>
  </div>
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <button type="submit" class="btn btn-success">Usar Cupon</button>
<?php if(isset($_SESSION["coupon"])):?>
<a href="./?action=cancelcoupon" class="btn btn-danger">Cancelar Cupon</a>
<?php endif;?>
    </div>
  </div>
</form>
</div>
<div class="col-md-6">
	<table class="table table-bordered">
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
			<td>Descuento: <b><?php echo $coupon->name." ($coupon->val%)";?></b></td><td>$ <?php echo number_format($xdiscount,2,".",","); ?></td>
		</tr>
		<tr>
			<td>Total</td><td><?php echo $coin_symbol; ?> <?php echo number_format($subtotal-$xdiscount,2,".",","); ?></td>
		</tr>
	<?php else:?>
		<tr>
			<td>Total</td><td><?php echo $coin_symbol; ?> <?php echo number_format($total+($total*($iva/100)),2,".",","); ?></td>
		</tr>

	<?php endif; ?>

	</table>
<br>
			<?php if(isset($_SESSION["client_id"])):?>
<form action="index.php?view=checkout" method="post">
<label>Metodo de Envio</label>
<select class="form-control" required name="ship_id">
<?php foreach(ShipData::getPublics() as $pay):?>
	<option value="<?php echo $pay->id; ?>"><?php echo $pay->name; ?> - $ <?php echo $pay->price; ?></option>
<?php endforeach; ?>
</select><br>
<label>Metodo de pago</label>
<select class="form-control" required name="paymethod_id">
<?php foreach(PaymethodData::getActives() as $pay):?>
	<option value="<?php echo $pay->id; ?>"><?php echo $pay->name; ?></option>
<?php endforeach; ?>
</select><br>
<button class="btn btn-primary btn-block">Confirmar Comprar</button>
</form>
<?php endif; ?>
<br>
<a href="index.php?action=cleancart" class="btn btn-danger btn-block">Limpar Carrito</a>
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
<br>
</section>