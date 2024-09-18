

<?php 
if(isset($_GET["cat"])){
$cat = CategoryData::getByPreffix($_GET["cat"]);
//print_r($cat);
if($cat->category_id==null){
$products = ProductData::getPublicsByCategoryId($cat->id);

}else if($cat->category_id!=null){
$products = ProductData::getPublicsBySubCategoryId($cat->id);

}
}


$coin_symbol = ConfigurationData::getByPreffix("general_coin")->val;
$img_default = ConfigurationData::getByPreffix("general_img_default")->val;

 ?>

    <section class="container pt-md-3 pb-5 mb-md-3">
        <div class="container">

  <div class="row">
  <div class="col-md-3">
        <h1>Categorias</h1>

<?php
$cats = CategoryData::getPublicsRoot();
?>
<?php if(count($cats)>0):?>
<div class="list-group">
<?php foreach($cats as $catx):?>

  <a href="index.php?view=category&cat=<?php echo $catx->short_name; ?>" class="list-group-item"><i class="fa fa-chevron-right"></i>  <?php echo $catx->name; ?></a>
  <?php
$subcats = CategoryData::getPublicsByCatId($catx->id);
  ?>
  <?php foreach($subcats as $scat):?>
      <a href="index.php?view=category&cat=<?php echo $scat->short_name; ?>" class="list-group-item">&nbsp;&nbsp;<i class="fa fa-arrow-right"></i>  <?php echo $scat->name; ?></a>

<?php endforeach; ?>

<?php endforeach; ?>
</div>
<?php endif; ?>
  </div>
  <div class="col-md-9">

<h1><span><?php 
    if(isset($_GET["act"]) && $_GET["act"]!=""){ echo "Busqueda: ".$_GET["q"]; }else if(isset($_GET["cat"])) { echo $cat->name; }else { echo "Productos"; } ?></span></h1>
<br>
<?php

$nproducts = count($products);
$filas = $nproducts/3;
$extra = $nproducts%3;
if($filas>1&& $extra>0){ $filas++; }
$n=0;
?>
<?php if($nproducts>0):?>
<?php for($i=0;$i<$filas;$i++):?>
  <div class="row">
<?php for($j=0;$j<3;$j++):
$p=null;
if($n<$nproducts){
$p = $products[$n];
}
?>
<?php if($p!=null):
$img = "admin/storage/products/".$p->image;
if($p->image==""){
  $img=$img_default;
}
$in_cart=false;
if(isset($_SESSION["cart"])){
  foreach ($_SESSION["cart"] as $pc) {
    if($pc["product_id"]==$p->id){ $in_cart=true;  }
  }
  }
?>
  <div class="col-md-4">

<!--- ----------->
          <div class="card product-card">
            <button class="btn-wishlist btn-sm" type="button" data-toggle="tooltip" data-placement="left" title="Add to wishlist"><i class="czi-heart"></i></button><a class="card-img-top d-block overflow-hidden" href="shop-single-v1.html"><img src="<?php echo $img; ?>" alt="Product"></a>
            <div class="card-body py-2"><!--<a class="product-meta d-block font-size-xs pb-1" href="#">Sneakers &amp; Keds</a>-->
              <h3 class="product-title font-size-sm"><a href="shop-single-v1.html"><?php echo $p->name; ?></a></h3>
              <div class="d-flex justify-content-between">
                <?php if($p->price_offer=="" || $p->price_offer<0):?>
                <div class="product-price"><span class="text-accent"><?php echo $coin_symbol." ".number_format($p->price,2,".",","); ?></span></div>
                <?php else:?>
                <div class="product-price">
                  <span class="text-accent"><s><?php echo $coin_symbol." ".number_format($p->price,2,".",","); ?></s></span>
                  <span class="text-accent"><?php echo $coin_symbol." ".number_format($p->price_offer,2,".",","); ?></span>
                </div>
              <?php endif; ?>                <!--<div class="star-rating"><i class="sr-star czi-star-filled active"></i><i class="sr-star czi-star-filled active"></i><i class="sr-star czi-star-filled active"></i><i class="sr-star czi-star-filled active"></i><i class="sr-star czi-star"></i>
                </div>
              -->
              </div>
            </div>
            <div class="card-body ">

<?php if(!$p->in_existence):?>
  <button class="btn btn-primary btn-sm btn-block mb-2" type="button" data-toggle="toast" data-target="#cart-toast"><i class="fa fa-shopping-cart font-size-sm mr-1"></i>No disponible</button>
<?php elseif(!$in_cart):?>

              <a class="btn btn-primary btn-sm btn-block mb-2"  href="index.php?action=addtocart&product_id=<?php echo $p->id; ?>&href=cat"><i class="fa fa-shopping-cart font-size-sm mr-1"></i>Agregar al carrito</a>
<?php else:?>
                <a class="btn btn-success btn-sm btn-block mb-2"  href="javascript:void()"><i class="fa fa-shopping-cart font-size-sm mr-1"></i>Ya esta agregado</a>
<?php endif; ?>

              <div class="text-center"><a class="nav-link-style font-size-ms" href="index.php?view=producto&product_id=<?php echo $p->id; ?>"><i class="fa fa-eye align-middle mr-1"></i>Detalles</a></div>
            </div>
          </div>
          <hr class="d-sm-none">
<!--- ----------->

  </div>
<?php endif; ?>
<?php $n++; endfor; ?>
  </div>
<?php endfor; ?>
<?php else:?>
  <div class="jumbotron">
  <h2>No hay productos</h2>
  <p>No hay productos por mostrar</p>
  </div>
<?php endif;?>


  </div>
  </div>


  </div>
  </section>
  <br><br>
