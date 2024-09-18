<?php $categories = CategoryData::getAllByCatId($_GET["cat_id"]); ?>

<option value="">-- SELECCIONE SUBCATEGORIAS [<?php echo count($categories); ?>] --</option>

<?php foreach($categories as $cat):?>
<option value="<?php echo $cat->id; ?>"><?php echo $cat->name; ?></option>
<?php endforeach; ?>