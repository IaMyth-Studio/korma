<?php

$buys =  QuestionData::getAll();
$coin = ConfigurationData::getByPreffix("general_coin")->val;

?>
        <!-- Main Content -->

          <div class="row">
          <div class="col-md-12">
          <h1>Preguntas</h1>
          </div>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <i class="fa fa-question"></i> Preguntas
                </div>
                <div class="widget-body medium no-padding">

                  <div class="table-responsive">
<?php if(count($buys)>0):?>
                    <table class="table table-bordered">
                    <thead>

                      <th>Comentario</th>
                      <th>Cliente</th>
                      <th>Producto</th>
                      <th>Estado</th>
                      <th>Fecha</th>
                      <th></th>
                    </thead>
<?php foreach($buys as $b):
$discount=0;
?>
                        <tr>

                        <td><?php echo $b->comment; ?></td>
                        <td><?php if($b->client_id){echo $b->getClient()->getFullname(); }?></td>
                        <td><?php echo $b->getProduct()->name; ?></td>
                        <td>
                          <?php if($b->status_id==0):?>
                            <span class="label label-warning">Pendiente</span>
                          <?php elseif($b->status_id==1):?>
                            <span class="label label-success">Aprobado</span>
                          <?php elseif($b->status_id==2):?>
                            <span class="label label-danger">Rechazado</span>
                          <?php endif; ?>
                        </td>
                        <td><?php echo $b->created_at; ?></td>
                        <th>


<button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#myModalquestion">
  <i class="fa fa-check"></i>
</button>

<!-- Modal -->
<div class="modal fade" id="myModalquestion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Responder y Publicar</h4>
      </div>
      <div class="modal-body">

<form method="post" action="./?action=questions&opt=aprove&id=<?php echo $b->id;?>&status=5" id="addquestion">
<input type="hidden" name="product_id" value="<?php echo $p->id; ?>">
<input type="hidden" name="question_id" value="<?php echo $b->id; ?>">

  <div class="form-group">
    <label for="exampleInputPassword1">Comentario</label>
    <textarea name="comment" required class="form-control"></textarea>
  </div>
  <button type="submit" class="btn btn-info">Responder y publicar</button>
</form>

      </div>

    </div>
  </div>
</div>


                            <a href="./?action=questions&opt=hide&id=<?php echo $b->id;?>&status=3" class="btn btn-danger btn-xs"><i class="fa fa-remove"></i></a>
                        </th>
                        </tr>
<?php endforeach; ?>
                    </table>
<?php else:?>
  <div class="panel-body">
  <h1>No hay preguntas</h1>
  </div>
<?php endif; ?>
                  </div>
                </div>
              </div>
            </div>

          </div>
