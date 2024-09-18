        <!-- Main Content -->

          <div class="row">
            <div class="col-md-12">
  <!-- Button trigger modal -->

  <!-- Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Agregar Tipo de Envio</h4>
        </div>
        <div class="modal-body">
<form class="form-horizontal" role="form" method="post" action="index.php?action=addship">
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Nombre</label>
    <div class="col-lg-10">
      <input type="text" required class="form-control" name="name" placeholder="Nombre de la Tipo de Envio">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Descripcion</label>
    <div class="col-lg-10">
      <textarea required class="form-control" name="description" placeholder="Descripcion"></textarea>
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Precio</label>
    <div class="col-lg-10">
      <input type="text" required class="form-control" name="price" placeholder="Precio">
    </div>
  </div>
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <div class="checkbox">
        <label>
          <input type="checkbox" name="is_active"> Tipo de Envio Activa
        </label>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <button type="submit" class="btn btn-block btn-primary">Agregar Tipo de Envio</button>
    </div>
  </div>
</form>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
            <h1>Tipo de Envios</h1>
                  <a  data-toggle="modal" href="#myModal" class="btn btn-default">Agregar Tipo de Envio</a>
<br><br>
            </div>
            </div>

          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <i class="fa fa-th-list"></i> Tipo de Envios
                </div>
                <div class="widget-body medium no-padding">
<?php
$categories = ShipData::getAll();
 if(count($categories)>0):?>
                  <div class="table-responsive">
                    <table class="table table-bordered">
                    <thead>
                    <th></th>
                      <th>Nombre</th>
                      <th>Precio</th>
                      <th>Activo</th>
                      <th></th>
                    </thead>
                      <tbody>

<?php foreach($categories as $cat):?>
                        <tr>
                        <td style="width:30px;">
                        </td>
                        <td><?php echo $cat->name; ?>
                        <td>$ <?php echo number_format($cat->price, 2, ".",","); ?>
                        <td style="width:70px;"><?php if($cat->is_active):?><center><i class="fa fa-check"></i></center><?php endif;?></td>




                        </td>
                        <td style="width:90px;">
                        <a data-toggle="modal" href="#myModal-<?php echo $cat->id;?>" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></a> 
  <!-- Button trigger modal -->

  <!-- Modal -->
  <div class="modal fade" id="myModal-<?php echo $cat->id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Editar Tipo de Envio</h4>
        </div>
        <div class="modal-body">
<form class="form-horizontal" role="form" method="post" action="index.php?action=updateship">
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Nombre</label>
    <div class="col-lg-10">
      <input type="text" required class="form-control" name="name" value="<?php echo $cat->name;?>" placeholder="Nombre de la Tipo de Envio">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Descripcion</label>
    <div class="col-lg-10">
      <textarea required class="form-control" name="description" placeholder="Descripcion"><?php echo $cat->description; ?></textarea>
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Precio</label>
    <div class="col-lg-10">
      <input type="text" required class="form-control" value="<?php echo $cat->price; ?>" name="price" placeholder="Precio">
    </div>
  </div>

  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <div class="checkbox">
        <label>
          <input type="checkbox" name="is_active" <?php if($cat->is_active){ echo "checked"; }?>> Tipo de Envio Activa
        </label>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
    <input type="hidden" name="category_id" value="<?php echo $cat->id; ?>">
      <button type="submit" class="btn btn-block btn-success">Actualizar Tipo de Envio</button>
    </div>
  </div>
</form>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

                        <a href="index.php?action=delship&category_id=<?php echo $cat->id; ?>" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a> 
                        </td>
                        </tr>
<?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
 <?php endif; ?>
                </div>
              </div>
            </div>

          </div>
