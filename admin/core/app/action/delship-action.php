<?php

$cat = ShipData::getById($_GET["category_id"]);
$cat->del();

Core::redir("index.php?view=ships");
?>