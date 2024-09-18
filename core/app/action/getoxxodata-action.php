<?php
$body = @file_get_contents('php://input');
$data = json_decode($body);

http_response_code(200); // Return 200 OK
/// <p>Referencia Charge paid:'.$data->data->object->payment_method->reference.'</p>
/// <p>Referencia Order paid:'.$data->data->object->charges->data[0]->payment_method->reference.'</p>

if ($data->type=="charge.paid"){
//file_put_contents($data->type.".txt", $body);
$iva = ConfigurationData::getByPreffix("general_iva")->val;
$coin = ConfigurationData::getByPreffix("general_coin")->val;
$ivatxt = ConfigurationData::getByPreffix("general_iva_txt")->val;

$sell = BuyData::getByOxxoCode($data->data->object->payment_method->reference);
//$sell = BuyData::getById($_GET["id"]);
$client = ClientData::getById($sell->client_id);

$adminemail = ConfigurationData::getByPreffix("general_main_email")->val;
$total = $sell->getTotal()+$sell->getTotal()*($iva/100);

$sell->status_id = 2;//$_GET["status"];
$sell->change_status();

$h = new HistoryData();
$h->buy_id = $sell->id;
$h->status_id=2;// $_GET["status"];
$h->add();


/*
$proof = "";

foreach($data as $k => $v) { //foreach element in $arr
    $proof .= $k." => ".$v." ".count($v)." <br>";
}
*/
//<p>Referencia :'.$data->data->object->payment_method->reference.'</p>

$themessage = '
<meta content="es-mx" http-equiv="Content-Language" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<body>
<h1>Tienda en linea</h1>	
<p>Se ha recibido un pago via Oxxo Pay.</p>
 <p>Referencia Charge paid:'.$data->data->object->payment_method->reference.'</p>
<p>Folio de Venta :'.$sell->id.'</p>
<p>Cliente :'.$client->name.' '.$client->lastname.'</p>
<p>Email del cliente :'.$client->email.'</p>
<p>Telefono del cliente :'.$client->phone.'</p>
<p>Pago total : $'.number_format($total, 2, ".",".").'</p>
<hr>
<p>Debes acceder al sistema para tomar las acciones correspondientes.</p>
</body>';

@mail("$adminemail",
     "Pago Recibido - OxxoPay",
     "$themessage",
	 "From: $adminemail\nReply-To: $adminemail\nContent-Type: text/html; charset=ISO-8859-1");
//<p>Referencia :'.$data->data->object->payment_method->reference.'</p>

$themessage2 = '
<meta content="es-mx" http-equiv="Content-Language" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<body>
<h1>Tienda en linea</h1>	
<p>Tu pago se ha recibido exitosamente.</p>
 <p>Referencia Charge paid:'.$data->data->object->payment_method->reference.'</p>
<p>Folio de Venta :'.$sell->id.'</p>
<p>Cliente :'.$client->name.' '.$client->lastname.'</p>
<p>Email del cliente :'.$client->email.'</p>
<p>Telefono del cliente :'.$client->phone.'</p>
<p>Pago total : $'.number_format($total, 2, ".",".").'</p>
<hr>
<p>Se esta procesando tu orden, puedes acceder al sistema al area de cliente o contactar al vendedor.</p>
</body>';

@mail("$client->email",
     "Pago Recibido - OxxoPay",
     "$themessage",
	 "From: $adminemail\nReply-To: $adminemail\nContent-Type: text/html; charset=ISO-8859-1");

}

?>