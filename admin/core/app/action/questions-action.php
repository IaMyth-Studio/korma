<?php


if(isset($_GET["opt"]) && $_GET["opt"]=="aprove"){
$r = QuestionData::getById($_POST["question_id"]);
$r->status_id=1;
$r->update_status();

$ans = new QuestionData();
$ans->comment = $_POST["comment"];
$ans->question_id = $_POST["question_id"];
$ans->user_id = $_SESSION["admin_id"];
$ans->status_id=1;
$ans->product_id = $r->product_id;
$ans->add_answer();

Core::alert("Aprobado exitosamente!");
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="hide"){
$r = QuestionData::getById($_GET["id"]);
$r->status_id=2;
$r->update_status();
Core::alert("Oculto exitosamente!");
}
Core::redir("./?view=questions");
?>