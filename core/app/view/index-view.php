<?php 
$coin_symbol = ConfigurationData::getByPreffix("general_coin")->val;
$img_default = ConfigurationData::getByPreffix("general_img_default")->val;
$cnt=0;
$slides = SlideData::getPublics();
$featureds = ProductData::getFeatureds();
?>

<?php if(count($slides)>0):?>
<section class="container pt-md-3 pb-5 mb-md-3">
<div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
    <?php $cnt=0; foreach($slides as $sli):?>
    <li data-target="#carouselExampleCaptions" data-slide-to="0" <?php if($cnt==0):?>class="active"<?php endif; ?> ></li>
    <?php $cnt++; endforeach; ; ?>

  </ol>
  <div class="carousel-inner">
    <?php $cnt=0; foreach($slides as $sli):?>
    <div class="carousel-item <?php if($cnt==0):?>active<?php endif; ?>">
      <img src="./admin/storage/slides/<?php echo $sli->image; ?>" class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5><?php echo $sli->title; ?></h5>
<!--        <p>Some representative placeholder content for the first slide.</p> -->
      </div>
    </div>
    <?php $cnt++; endforeach; ; ?>



  </div>
  <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
</section>
<?php endif; ?>
    <!-- Products grid (Trending products)-->
    <section class="container pt-md-3 pb-5 mb-md-3">
      <h2 class="h3 text-center">Productos Destacados</h2>




<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
<?php

$nproducts = count($featureds);
$filas = $nproducts/3;
$extra = $nproducts%3;
if($filas>1&& $extra>0){ $filas++; }
$n=0;
?>
<?php if(count($featureds)>0):?>
<!--<a href="./"><div style="background:#333;font-size:25px;color:white;padding:5px;">Productos Destacados</div></a> -->
<br>
<?php for($i=0;$i<$filas;$i++):?>
  <div class="row">
<?php for($j=0;$j<3;$j++):
$p=null;
if($n<$nproducts){
$p = $featureds[$n];
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
              <?php endif; ?>
                <!--<div class="star-rating"><i class="sr-star czi-star-filled active"></i><i class="sr-star czi-star-filled active"></i><i class="sr-star czi-star-filled active"></i><i class="sr-star czi-star-filled active"></i><i class="sr-star czi-star"></i>
                </div>
              -->
              </div>
            </div>
            <div class="card-body card-body-hidden">

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
  <h2>No hay productos destacados.</h2>
  <p>En la pagina principal solo se muestran productos marcados como destacados.</p>
  </div>
<?php endif; ?>
<!-------------------------------------------->












<!--
      <div class="text-center pt-3"><a class="btn btn-outline-accent" href="shop-grid-ls.html">More products<i class="fa fa-arrow-right ml-1"></i></a></div>
    -->
    </section>
