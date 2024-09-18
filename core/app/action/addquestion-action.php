<?php


//print_r($_POST);


	$rat = new QuestionData();
	$rat->comment = $_POST["comment"];
	$rat->product_id = $_POST["product_id"];
	$rat->client_id = $_SESSION["client_id"];
	$rat->add();
	Core::alert("Pregunta enviada exitosamente!");

Core::redir("./?view=producto&product_id=".$_POST["product_id"]);
?>
