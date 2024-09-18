<?php


  $dateB = new DateTime(date('Y-m-d')); 
  $dateA = $dateB->sub(DateInterval::createFromDateString('30 days'));
  $sd= strtotime(date_format($dateA,"Y-m-d"));
  $ed = strtotime(date("Y-m-d"));
  $ntot = 0;
  $nsells = 0;
  $sumatot = array();
for($i=$sd;$i<=$ed;$i+=(60*60*24)){
  $operations = BuyData::getGroupByDateOp(date("Y-m-d",$i),date("Y-m-d",$i),5);

  $sum=0;
  foreach ($operations as $op) {
    $bps = BuyProductData::getAllByBuyId($op->id);
    foreach ($bps as $bp) {
      $sum+=$bp->getProduct()->price;
    }
  }
    $sumatot[date("Y-m-d",$i)]=$sum;
}
//print_r($sumatot);
?>
<div class="content">
<div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo count(ClientData::getAll());?></h3>

              <p>Clientes</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="./?view=clients" class="small-box-footer">Ver <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo count(ProductData::getAll()); ?></h3>

              <p>Productos</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="./?view=products" class="small-box-footer">Ver <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php $d= BuyData::countByStatusId(1); echo $d->c; ?></h3>

              <p>Ventas Pendientes</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="./?view=sells" class="small-box-footer">Ver <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-purple">
            <div class="inner">
              <h3><?php $d= BuyData::countByStatusId(5); echo $d->c; ?></h3>

              <p>Ventas Finalizadas</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="./?view=sells" class="small-box-footer">Ver <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
<div class="row">
<div class="col-md-12">





<div class="box box-primary">
<div class="box-header">
<div class="box-title">Ventas finalizadas en los ultimos 30 dias</div>
</div>
<div class="box-body">
<div id="graph" class="animate" data-animate="fadeInUp" ></div>
</div>
</div>

<script>

<?php 
echo "var c=0;";
echo "var dates=Array();";
echo "var data=Array();";
echo "var total=Array();";
for($i=$sd;$i<=$ed;$i+=(60*60*24)){
  echo "dates[c]=\"".date("Y-m-d",$i)."\";";
  echo "data[c]=".$sumatot[date("Y-m-d",$i)].";";
  echo "total[c]={x: dates[c],y: data[c]};";
  echo "c++;";
}
?>
// Use Morris.Area instead of Morris.Line
Morris.Area({
  element: 'graph',
  data: total,
  xkey: 'x',
  ykeys: ['y',],
  labels: ['Y']
}).on('click', function(i, row){
  console.log(i, row);
});
</script>








</div>
</div>
</div>

<br><br>