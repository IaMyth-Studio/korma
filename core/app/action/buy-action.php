<?php
// print_r($_SESSION);
if(!empty($_POST) && isset($_SESSION["client_id"])){
$iva = ConfigurationData::getByPreffix("general_iva")->val;
$ship = ShipData::getById($_POST["ship_id"]);
$buy = new BuyData();
$buy->ship_id = $_POST["ship_id"];
$buy->person_name = $_POST["person_name"];
$buy->person_phone = $_POST["person_phone"];
$buy->person_address = $_POST["person_address"];
$buy->person_city = $_POST["person_city"];
$buy->person_zip = $_POST["person_zip"];
$alphabeth ="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWYZ1234567890_-";
$code = "";
$k = "";
for($i=0;$i<11;$i++){
    $code .= $alphabeth[rand(0,strlen($alphabeth)-1)];
    $k .= $alphabeth[rand(0,strlen($alphabeth)-1)];
}

$buy->k = $k;
$buy->code = $code;
$buy->coupon_id = isset($_SESSION["coupon"])?$_SESSION["coupon"]:"NULL";
$buy->client_id = $_SESSION["client_id"];
$buy->paymethod_id= $_POST["paymethod_id"];
$buy->status_id= 1;
$b = $buy->add();

$total_total = 0;
foreach ($_SESSION["cart"] as $c) {
	$pro = ProductData::getById($c["product_id"]);
$price = 0;
if($pro->price_offer==""|| $pro->price_offer<0){ $price = $pro->price; }else{ $price = $pro->price_offer; }
	$p = new BuyProductData();
	$p->buy_id = $b[1];
	$p->product_id = $c["product_id"];
	$p->q = $c["q"];
	$p->price = $price;
	$p->add();
	$total_total+=$c["q"]*$price;
}

// agregamos un history

$h = new HistoryData();
$h->buy_id = $b[1];
$h->status_id=1;
$h->add();
/////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////// Emailing
$client = ClientData::getById($_SESSION["client_id"]);
$adminemail = 	$paypal_business = ConfigurationData::getByPreffix("general_main_email")->val;


$replymessage = '
<meta content="es-mx" http-equiv="Content-Language" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<body>
<h2>Tienda en Linea</h2>
<h3>Compra Pendiente</h3>
<p><span class="style3"><strong>Estimado '.( $client->getFullname()) .'</strong></span></p>
<p>Se a agregado una compra a tu lista de pendientes, te invitamos a seguir el procedimiento de pago correspondiente para recibir tus productos.</p>
<p>Gracias por tu compra.</p>
<hr>
<p>Powered By <a href="http://evilnapsis.com/product/katana/" target="_blank"> Katana PRO</a></p>
</body>';

$products = BuyProductData::getAllByBuyId($b[1]);
$data = "";
$total = 0;
foreach ($products as $px) {
	$product = $px->getProduct();
	$data .= "<tr>";
	$data .= "<td>$px->q</td>";
	$data .= "<td>".($product->name)."</td>";
	$data .= "<td> $".number_format($product->price,2,".",",")."</td>";
	$data .= "<td> $".number_format($px->q*$product->price,2,".",",")."</td></tr>";
	$total+= $px->q*$product->price;
}
$themessage = '
<meta content="es-mx" http-equiv="Content-Language" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<body>
<h1>Tienda en linea</h1>
<h3>Nueva compra pendiente</h3>
<h4>Cliente: '.($client->getFullname()).'</h4>
<table align="center" border=1 cellspacing="4" class="style2" style="width: 700">
	<tr>
		<td>Cant.</td><td>Producto</td><td>P.U</td><td>Total</td>
	</tr>
	'.$data.'
</table>
<h3>Total = $ '.number_format($total+($total*($iva/100)),2,".",",").' </h3>
<hr>
<p>Powered By <a href="http://evilnapsis.com/product/katana/" target="_blank"> Katana PRO</a></p>
</body>';

@mail("$adminemail",
     "Nueva compra Pendiente",
     "$themessage",
	 "From: $adminemail\nReply-To: $adminemail\nContent-Type: text/html; charset=ISO-8859-1");

@mail("$client->email",
     "Nueva compra Pendiente",
     "$replymessage",
	 "From: $adminemail\nReply-To: $adminemail\nContent-Type: text/html; charset=ISO-8859-1");

/////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////////////
if($_POST["paymethod_id"]==PaymethodData::getByName("oxxo")->id){
	include "core/conekta-php/lib/Conekta.php";
	 $oxxo_apikey = ConfigurationData::getByPreffix("oxxo_apikey")->val;
\Conekta\Conekta::setApiKey($oxxo_apikey);
\Conekta\Conekta::setApiVersion("2.0.0");
		////////////////////////////////////////////////////////////
$iva = ConfigurationData::getByPreffix("general_iva")->val;
$total_total2 = $total_total + ($total_total*($iva/100));
$total_total2+= 0; //($_POST["ship_amount"]);
$total_total3 = number_format($total_total2,2,".","");
 $total_total4 =$ship->price+$total_total3*100;
try{
  $order = \Conekta\Order::create(
    array(
      "line_items" => array(
        array(
          "name" => "Total de venta",
          "unit_price" => $total_total4,//$total_total2*100,
          "quantity" => 1
        )//first line_item
      ), //line_items
      "currency" => "MXN",
      "customer_info" => array(
        "name" => $client->name." ".$client->lastname,
        "email" => $client->email,
        "phone" => $client->phone
      ), //customer_info
      "charges" => array(
          array(
              "payment_method" => array(
                "type" => "oxxo_cash"
              )//payment_method
          ) //first charge
      ) //charges
    )//order
  );
//print_r($order->charges[0]->payment_method->reference);
$buy = BuyData::getById($b[1]);
$buy->oxxo_code = $order->charges[0]->payment_method->reference;
$buy->update_oxxo();
/*
echo "ID: ". $order->id;
echo "Payment Method:". $order->charges[0]->payment_method->service_name;
echo "Reference: ". $order->charges[0]->payment_method->reference;
echo "$". $order->amount/100 . $order->currency;
echo "Order";
echo $order->line_items[0]->quantity .
      "-". $order->line_items[0]->name .
      "- $". $order->line_items[0]->unit_price/100;
*/



} catch (\Conekta\ParameterValidationError $error){
  echo $error->getMessage();
} catch (\Conekta\Handler $error){
  echo $error->getMessage();
}


	////////////////////////////////////////////////////////////////

}
/////////////////////////////////////////////////////////////////////////////////////////////////
else if($_POST["paymethod_id"]==PaymethodData::getByName("conekta")->id){
	include "core/conekta-php/lib/Conekta.php";
	 $conekta_secret = ConfigurationData::getByPreffix("conekta_secret")->val;
\Conekta\Conekta::setApiKey($conekta_secret);
\Conekta\Conekta::setApiVersion("2.0.0");
		////////////////////////////////////////////////////////////
$iva = ConfigurationData::getByPreffix("general_iva")->val;
$total_total2 = $total_total + ($total_total*($iva/100));
$total_total2+= 0; //($_POST["ship_amount"]);
$total_total3 = number_format($total_total2,2,".","");
 $total_total4 =$ship->price+$total_total3*100;
try{
  $order = \Conekta\Order::create(
    array(
      "line_items" => array(
        array(
          "name" => "Total de venta",
          "unit_price" => $total_total4,//$total_total2*100,
          "quantity" => 1
        )//first line_item
      ), //line_items
      "currency" => "MXN",
      "customer_info" => array(
        "name" => $client->name." ".$client->lastname,
        "email" => $client->email,
        "phone" => $client->phone
      ), //customer_info
      "charges" => array(
          array(
              "payment_method" => array(
                "type" => "card"
              )//payment_method
          ) //first charge
      ) //charges
    )//order
  );
  echo $order->payment_status;
//print_r($order->charges[0]->payment_method->reference);
//$buy = BuyData::getById($b[1]);
//$buy->oxxo_code = $order->charges[0]->payment_method->reference;
//$buy->update_oxxo();
/*
echo "ID: ". $order->id;
echo "Payment Method:". $order->charges[0]->payment_method->service_name;
echo "Reference: ". $order->charges[0]->payment_method->reference;
echo "$". $order->amount/100 . $order->currency;
echo "Order";
echo $order->line_items[0]->quantity .
      "-". $order->line_items[0]->name .
      "- $". $order->line_items[0]->unit_price/100;
*/



} catch (\Conekta\ParameterValidationError $error){
  echo $error->getMessage();
} catch (\Conekta\Handler $error){
  echo $error->getMessage();
}


	////////////////////////////////////////////////////////////////

}
//////// si el metodo de pago es Paypal
else if($_POST["paymethod_id"]==PaymethodData::getByName("paypal")->id){
	$paypal_business = ConfigurationData::getByPreffix("paypal_business")->val;
	$paypal_currency = ConfigurationData::getByPreffix("paypal_currency")->val;
	$paypal_cursymbol = ConfigurationData::getByPreffix("paypal_cursymbol")->val;
	$paypal_location = ConfigurationData::getByPreffix("paypal_location")->val;
	$paypal_returnurl = ConfigurationData::getByPreffix("paypal_returnurl")->val;
	$paypal_returntxt = ConfigurationData::getByPreffix("paypal_returntxt")->val;
	$paypal_cancelurl = ConfigurationData::getByPreffix("paypal_cancelurl")->val;

	// complete the return and cancel URL

	$paypal_returnurl .= "&id=".$b[1]."&k=$k";
	$paypal_cancelurl .= "&id=".$b[1]."&k=$k";

	// from wc
	// https://www.paypal.com/cgi-bin/webscr?cmd=_cart&business=evilnapsis%40gmail.com&no_note=1&currency_code=USD&charset=utf-8&rm=1&upload=1&return=http%3A%2F%2Flocalhost%2Fwp%2Fcheckout%2Forder-received%2F76%3Fkey%3Dwc_order_567671a554da3%26%23038%3Butm_nooverride%3D1&cancel_return=http%3A%2F%2Flocalhost%2Fwp%2Fcart%2F%3Fcancel_order%3Dtrue%26%23038%3Border%3Dwc_order_567671a554da3%26%23038%3Border_id%3D76%26%23038%3Bredirect%26%23038%3B_wpnonce%3Dd2d1c85888&page_style=&paymentaction=sale&bn=WooThemes_Cart&invoice=WC-76&custom=%7B%22order_id%22%3A76%2C%22order_key%22%3A%22wc_order_567671a554da3%22%7D&notify_url=http%3A%2F%2Flocalhost%2Fwp%2Fwc-api%2FWC_Gateway_Paypal%2F&first_name=Agustin&last_name=Ramos&company=&address1=Cardenas&address2=&city=Cardenas&state=CA&zip=86680&country=US&email=evilnapsis%40gmail.com&night_phone_a=222&night_phone_b=0&night_phone_c=0&day_phone_a=222&day_phone_b=0&day_phone_c=0&no_shipping=1&item_name_1=Laptop+HP&quantity_1=1&amount_1=100.00&item_number_1=&tax_cart=0.00
	// https://www.paypal.com/cgi-bin/webscr?cmd=_cart&business=&no_note=1&currency_code=USD&charset=utf-8&rm=1&upload=1&business=&return=http%3A%2F%2Flocalhost%2Fkatana-pro%2F%3Faction%3Dppdone%26id%3D0%26k%3DSJj6_c7ClhF&cancel_return=http%3A%2F%2Flocalhost%2Fkatana-pro%2F%3Faction%3Dppcancel%26id%3D0%26k%3DSJj6_c7ClhF&page_style=&paymentaction=sale&bn=katanapro_cart&invoice=KP-0
	
//https://www.paypal.com/webapps/hermes?token=9YS24545BM057913V&useraction=commit&rm=1&mfid=1497406891685_b47e83e948242#/checkout/login

	$ppurl = "https://www.paypal.com/cgi-bin/webscr?cmd=_cart";
	$ppurl .= "&business=".$paypal_business;
	$ppurl .= "&no_note=1";
	$ppurl .= "&currency_code=".$paypal_currency;
	$ppurl .= "&charset=utf-8&rm=1&upload=1";
	$ppurl .= "&business=".$paypal_business;
	$ppurl .= "&return=".urlencode($paypal_returnurl);
	$ppurl .= "&cancel_return=".urlencode($paypal_cancelurl);
	$ppurl .= "&page_style=&paymentaction=sale&bn=katanapro_cart&invoice=KP-$b[1]";
//	echo $ppurl;


	$i=1;
	$total=0;
//	$total+=$ship->price; 
//	print_r($_SESSION["cart"]);
if(!isset($_SESSION["coupon"])){
	foreach ($_SESSION["cart"] as $c) {
		$product = ProductData::getById($c["product_id"]);
		$c["product_id"];
		$q = $c["q"];
		$ppurl.="&item_name_$i=".urlencode($product->name)."&quantity_$i=$q&amount_$i=".($product->price+($product->price*($iva/100)))."&item_number_$i=";
		$i++;
		$total+=$product->price*$q;
	}
		$ppurl.="&item_name_$i=".urlencode("Envio")."&quantity_$i=1&amount_$i=".($ship->price)."&item_number_$i=";

}
if(isset($_SESSION["coupon"])){
	$total=0;
	foreach ($_SESSION["cart"] as $c) {
		$total+=$product->price*$c["q"];
	}
		$coupon = CouponData::getById($_SESSION["coupon"]);
		$discount = $coupon->val;
		$subtotal=$total+(($total*($iva/100)));
		$xdiscount=($subtotal )*($discount/100);
//		echo $subtotal-$xdiscount;	
		$ppurl .= "&item_name_$i=".urlencode("Total compra #".$b[1])."&quantity_$i=1&amount_$i=".(($subtotal-$xdiscount)+$ship->price)."&item_number_$i=";
}


	$ppurl.= "&tax_cart=0.00";
//echo $ppurl;
//	echo urldecode("http%3A%2F%2Flocalhost%2Fwp%2Fcheckout%2Forder-received%2F76%3Fkey%3Dwc_order_567671a554da3%26%23038%3Butm_nooverride%3D1");
//	$ppurl .= "&business=".$paypal_business;
unset($_SESSION["cart"]);
unset($_SESSION["coupon"]);

Core::redir($ppurl);

}
//////// si el metodo de pago es MercadoPago
else if($_POST["paymethod_id"]==PaymethodData::getByName("mp")->id){
	$mp_id = ConfigurationData::getByPreffix("mp_id")->val;
	$mp_secret = ConfigurationData::getByPreffix("mp_secret")->val;

	$mp = new MP ("$mp_id", $mp_secret);
	$items = array();
	foreach ($_SESSION["cart"] as $c) {
		$product = ProductData::getById($c["product_id"]);
		$items[] = array(
			"title"=>$product->name,
			"quantity"=>$c["q"],
			"currency_id"=>"MXN",
			"unit_price"=>6//doubleval($product->price)
			);
	}

$preference_data = array (
//"back_url" => "http://localhost/?acion=mpback",
    "items" => $items
);
$preference = $mp->create_preference($preference_data);
//print_r($preference);
//echo '<a href="'.$preference["response"]["init_point"].'">Pagar por MercadoPago</a>';
	Core::redir($preference["response"]["init_point"]);
}
unset($_SESSION["cart"]);
unset($_SESSION["coupon"]);
Core::alert("Exito!");
Core::redir("index.php?view=client");
}
?>