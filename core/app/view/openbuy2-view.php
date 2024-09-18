<?php if(!isset($_SESSION["client_id"])){ Core::redir("./");}?>

<br><br><br><br><br><br><style type="text/css">
	/* Reset -------------------------------------------------------------------- */
* 	 { margin: 0;padding: 0; }
body { font-size: 14px; }

/* OPPS --------------------------------------------------------------------- */

h3 {
	margin-bottom: 10px;
	font-size: 15px;
	font-weight: 600;
	text-transform: uppercase;
}

.opps {
	width: 496px; 
	border-radius: 4px;
	box-sizing: border-box;
	padding: 0 45px;
	margin: 40px auto;
	overflow: hidden;
	border: 1px solid #b0afb5;
	font-family: 'Open Sans', sans-serif;
	color: #4f5365;
}

.opps-reminder {
	position: relative;
	top: -1px;
	padding: 9px 0 10px;
	font-size: 11px;
	text-transform: uppercase;
	text-align: center;
	color: #ffffff;
	background: #000000;
}

.opps-info {
	margin-top: 26px;
	position: relative;
}

.opps-info:after {
	visibility: hidden;
     display: block;
     font-size: 0;
     content: " ";
     clear: both;
     height: 0;

}

.opps-brand {
	width: 45%;
	float: left;
}

.opps-brand img {
	max-width: 150px;
	margin-top: 2px;
}

.opps-ammount {
	width: 55%;
	float: right;
}

.opps-ammount h2 {
	font-size: 36px;
	color: #000000;
	line-height: 24px;
	margin-bottom: 15px;
}

.opps-ammount h2 sup {
	font-size: 16px;
	position: relative;
	top: -2px
}

.opps-ammount p {
	font-size: 10px;
	line-height: 14px;
}

.opps-reference {
	margin-top: 14px;
}

.reference {
	font-size: 27px;
	color: #000000;
	text-align: center;
	margin-top: -1px;
	padding: 6px 0 7px;
	border: 1px solid #b0afb5;
	border-radius: 4px;
	background: #f8f9fa;
}

.opps-instructions {
	margin: 32px -45px 0;
	padding: 32px 45px 45px;
	border-top: 1px solid #b0afb5;
	background: #f8f9fa;
}


a {
	color: #1155cc;
}

.opps-footnote {
	margin-top: 22px;
	padding: 22px 20 24px;
	color: #108f30;
	text-align: center;
	border: 1px solid #108f30;
	border-radius: 4px;
	background: #ffffff;
}
</style>
<?php if(isset($_SESSION["client_id"])):
$client = ClientData::getById($_SESSION["client_id"]);
$iva = ConfigurationData::getByPreffix("general_iva")->val;
$coin = ConfigurationData::getByPreffix("general_coin")->val;
$ivatxt = ConfigurationData::getByPreffix("general_iva_txt")->val;
?>

<?php

$buy = BuyData::getById($_GET["id"]);
$ship = ShipData::getById($buy->ship_id);
$products = BuyProductData::getAllByBuyId($buy->id);

$total = $buy->getTotal()+$buy->getTotal()*($iva/100);
$total+=$ship->price;

?>
<div class="container">
<div class="row">

<div class="col-md-12">
<p>Datos de la compra #<?php echo $buy->id;?>, <a href="./?view=client">regresar a la lista</a></p>
</div>


</div>
</div>
<div class="container">
<div class="row">
	<div class="col-md-12">









<div class="opps">
			<div class="opps-header">
				<div class="opps-reminder">Ficha Digital. No es necesario imprimir.</div>
				<div class="opps-info">
					<div class="opps-brand"><img src="oxxopay_brand.png" alt="OXXOPay"></div>
					<div class="opps-ammount">
						<h3>Monto a pagar:</h3>
						<h2>$ <?php echo number_format($total,2,".",",");?> <sup>MXN</sup></h2>
						<p>OXXO puede cobrar una comision al momento del pago.</p>
					</div>
				</div>
				<div class="opps-reference">
					<h3>Num. de Referencia</h3>
					<h1 class="reference"><?php echo $buy->oxxo_code; ?></h1>
				</div>
			</div>
			<div class="opps-instructions">
				<h3>Instructions</h3>
				<ol>
					<li>Ir a tu tienda oxxo mas cercana. <a href="https://www.google.com.mx/maps/search/oxxo/" target="_blank">Buscar aqui</a>.</li>
					<li>Decirle al cajero que quieres hacer un pago OXXOPay.</li>
					<li>Dictar el numero de referencia de esta ficha al cajero..</li>
					<li>Hacer el pago en efectivo.</li>
					<li>Para confirmar el pago, el cajero te dara un recibo impreso. </li>
				</ol>
				<div class="opps-footnote">Cuando completes estos pasos, recibiras un correo de <strong>Tienda en linea</strong>.</div>
			</div>
		</div>	
	</body>
<?php if(count($products)>0):?>







<?php endif; ?>
	</div>
</div>
</div>



<?php endif; ?>
