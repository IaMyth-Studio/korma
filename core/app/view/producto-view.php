<?php 
$p = ProductData::getById($_GET["product_id"]);
$img_default = ConfigurationData::getByPreffix("general_img_default")->val;
$coin_symbol = ConfigurationData::getByPreffix("general_coin")->val;
$ratings = RatingData::getPublicsByProductId($p->id);
$questions = QuestionData::getPublicsByProductId($p->id);
Viewer::addView($p->id,"product_id","product_view");
$cat = CategoryData::getById($p->category_id);
$subcat = CategoryData::getById($p->subcategory_id);
$general_base = ConfigurationData::getByPreffix("general_base")->val;
$general_whatsapp = ConfigurationData::getByPreffix("general_whatsapp")->val;
$texto = "Me interesa este producto $p->name - ".$general_base."?view=producto&product_id=".$p->id;
$urlwhatsapp = "https://wa.me/".$general_whatsapp."?text=".urlencode($texto);
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
<?php foreach($cats as $cat):?>

  <a href="index.php?view=category&cat=<?php echo $cat->short_name; ?>" class="list-group-item"><i class="fa fa-chevron-right"></i>  <?php echo $cat->name; ?></a>
  <?php
$subcats = CategoryData::getPublicsByCatId($cat->id);
  ?>
  <?php foreach($subcats as $scat):?>
      <a href="index.php?view=category&cat=<?php echo $scat->short_name; ?>" class="list-group-item">&nbsp;&nbsp;<i class="fa fa-arrow-right"></i>  <?php echo $scat->name; ?></a>

<?php endforeach; ?>

<?php endforeach; ?>
</div>
<?php endif; ?>

  </div>
  <div class="col-md-9">
              <h2 class="entry-title"><span><?php echo $p->name; ?></span></h2>
   <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="./?view=category&cat=<?php echo $cat->short_name; ?>"><?php echo $cat->name; ?></a></li>
    <li class="breadcrumb-item"><a href="./?view=category&cat=<?php echo $subcat->short_name; ?>"><?php echo $subcat->name; ?></a></li>
  </ol>

<p class="lead"><span class="text-warning"><input type="hidden" name="rating" disabled class="rating" value="<?php echo RatingData::getAvg($p->id)->avg;?>"></span> (<?php echo count($ratings);?> calificaciones)</p>
<!--
              <div class="breadcrumb">
                <span></span>
                <a href="./">Inicio</a>
                <span class="crumbs-spacer"><i class="fa fa-angle-double-right"></i></span>
                <span class="current"><?php echo $p->name; ?></span>
              </div>
              -->
<br>
<?php if($p!=null):
$img = "admin/storage/products/".$p->image;
if($p->image==""){
  $img=$img_default;
}
?>
  <div class="row">
  <div class="col-md-8">
 <center>   <img src="<?php echo $img; ?>"  class="img-responsive"></center>

  </div>
  <div class="col-md-4">
                <?php if($p->price_offer=="" || $p->price_offer<0):?>
<h1 class="text-primary"><?php echo $coin_symbol; ?> <?php echo number_format($p->price,2,".",","); ?></h1>

                <?php else:?>
<h1 class="text-primary">
<s>  <?php echo $coin_symbol; ?> <?php echo number_format($p->price,2,".",","); ?></s>
  <?php echo $coin_symbol; ?> <?php echo number_format($p->price_offer,2,".",","); ?>
    
  </h1>

              <?php endif; ?>


<?php 
$in_cart=false;
if(isset($_SESSION["cart"])){
  foreach ($_SESSION["cart"] as $pc) {
    if($pc["product_id"]==$p->id){ $in_cart=true;  }
  }
  }

  ?>
<?php if(!$p->in_existence):?>
<a href="javascript:void()" class="btn btn-labeled btn-warning tip" title="No Disponible">
                <span class="btn-label"><i class="fa fa-shopping-cart"></i></span> No Disponible</a>

<?php elseif(!$in_cart):?>
<a href="index.php?action=addtocart&product_id=<?php echo $p->id; ?>&href=product" class="btn btn-labeled btn-primary tip" title="A&ntilde;adir al carrito">
                <span class="btn-label"><i class="fa fa-shopping-cart"></i></span> Comprar</a>
<?php else:?>
<a href="index.php?action=addtocart&product_id=<?php echo $p->id; ?>&href=product" class="btn btn-labeled btn-success tip" title="Ya esta en el carrito">
                <span class="btn-label"><i class="fa fa-shopping-cart"></i></span> Ya esta agregado</a>

<?php endif; ?>    
    <?php if($p->in_existence):?>
      <p class="text-success">Producto en Existencia</p>
    <?php else:?>
      <p class="text-warning">Producto no disponible</p>
    <?php endif; ?>
<?php
?>
<a href="<?php echo $urlwhatsapp; ?>" target="_blank" class="btn btn-labeled btn-success tip" >
                <span class="btn-label"><i class="fab fa-whatsapp"></i></span> Solicitar Informacion</a>

  </div>
  </div>
  <br><br>
  <div class="row">
  <div class="col-md-12">
  <hr>
  <h4>Codigo: <?php echo $p->code; ?></h4>
  <p><?php echo $p->description; ?></p>




        <?php 
$cnt=0;
$slides = ProductImageData::getAllByProductId($p->id);
        if(count($slides)>0):?>
        <div class="row">

<div class="col-md-8 col-md-offset-2">









<div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
    <li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>
    <li data-target="#carouselExampleCaptions" data-slide-to="1"></li>
    <li data-target="#carouselExampleCaptions" data-slide-to="2"></li>
  </ol>
  <div class="carousel-inner">
<?php $cnt=0; foreach($slides as $s):
$url = "admin/storage/products/$p->id/".$s->src;
?>    <div class="carousel-item <?php if($cnt==0){ echo "active";}?>">
      <img src="<?php echo $url; ?>" class="d-block w-100" >
      <div class="carousel-caption d-none d-md-block" style="background: rgba(0,0,0,0.5)">
        <h5><?php echo $s->title; ?></h5>
        <p><?php echo $s->description;?></p>
      </div>
    </div>
  <?php $cnt++; endforeach; ?>

  <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>








  <?php endif; ?>

<hr>
<h3>Calificaciones</h3>
<!-- Button trigger modal -->
<?php 
if(isset($_SESSION["client_id"])):
$r = RatingData::getByCP($_SESSION["client_id"],$p->id);
?>
<?php if($r==null):?>
<button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">
  Publicar calificacion
</button>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Calificar</h4>
      </div>
      <div class="modal-body">

<form method="post" action="./?action=calificate" id="calificate">
<input type="hidden" name="product_id" value="<?php echo $p->id; ?>">
  <div class="form-group">
    <label for="exampleInputEmail1">Calificacion</label>
    <select class="form-control" name="rating" required>
      <option value="5">5</option>
      <option value="4">4</option>
      <option value="3">3</option>
      <option value="2">2</option>
      <option value="1">1</option>
    </select>
<!--
<h2 >
<span class="text-warning"><input type="hidden" name="rating" id="rating" class="rating" name=""></span>
</h2>
-->
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Comentario</label>
    <textarea name="comment" required class="form-control"></textarea>
  </div>
  <button type="submit" class="btn btn-info">Calificar</button>
</form>

      </div>

    </div>
  </div>
</div>

<script type="text/javascript">
  $("#calificate").submit(function(e){
    if($("#rating").val()==""){
      alert("Debes seleccionar una calificacion!");
      e.preventDefault();
    }

  });
</script>
<?php endif; ?>
<?php endif; ?>
<?php

if(count($ratings)>0):
?>
<table class="table table-bordered table-hover">
<?php foreach($ratings as $r):
$cli = $r->getClient();
?>
<tr>

 <td>
 
  <p><b><?php echo $cli->name." ".$cli->lastname; ?></b></p>
<p>
<?php for($i=0; $i<$r->rating; $i++):?>
  <i class="fa fa-star"></i>
<?php endfor; ?>
<?php for($i=0; $i<5-$r->rating; $i++):?>
  <i class="fa fa-star-o"></i>
<?php endfor; ?>
</i>
  <div><?php echo $r->comment; ?></div>
 <p class="text-muted"><?php echo $r->created_at; ?></p>
   </td>
  </tr>
  <?php endforeach;?>
  </table>
<?php else:?>
  <p class="alert alert-warning">Aun no hay calificaciones.</p>
 <?php endif; ?>
<!---- ------------->
<h3>Preguntas <small><small>Hacer preguntas al vendedor</small></small></h3>
<!-- Button trigger modal -->
<?php 
if(isset($_SESSION["client_id"])):
?>


<button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModalquestion">
  Preguntar al vendedor
</button>

<!-- Modal -->
<div class="modal fade" id="myModalquestion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Preguntar</h4>
      </div>
      <div class="modal-body">

<form method="post" action="./?action=addquestion" id="addquestion">
<input type="hidden" name="product_id" value="<?php echo $p->id; ?>">

  <div class="form-group">
    <label for="exampleInputPassword1">Comentario</label>
    <textarea name="comment" required class="form-control"></textarea>
  </div>
  <button type="submit" class="btn btn-info">Calificar</button>
</form>

      </div>

    </div>
  </div>
</div>



<br><br>
<?php endif; ?>
<?php

if(count($questions)>0):
?>
<table class="table table-bordered table-hover">
<?php foreach($questions as $r):
 $cli = null;
  if($r->client_id){
$cli = $r->getClient();
}
?>
<tr>
<td style="width: 200px; ">
<?php if($cli!=null):?>
<!--  <p><b><?php echo $cli->name." ".$cli->lastname; ?></b></p> -->
  <?php else:?>
<!--  <p><b>Vendedor</b></p> -->
<?php endif; ?>
  <div>
    <p><b>P: <?php echo $r->comment; ?></b></p>
    <?php $answersx = QuestionData::getAnswerByQId($r->id); ?>
      <?php foreach($answersx as $ansx):?>
    <p><i>R: <?php echo $ansx->comment; ?></i></p>
      <?php endforeach; ?>
    </div>
  <p class="text-muted"><?php echo $r->created_at; ?></p>
  </td>
  </tr>
  <?php endforeach;?>
  </table>
<?php else:?>
  <p class="alert alert-warning">Aun no hay preguntas.</p>
 <?php endif; ?>
<!-- --------------->

</div>
</div>

<?php endif; ?>



  </div>
  </div>


  </div>
  </section>
<br>