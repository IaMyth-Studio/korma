<br><div class="container">
	<div class="row">
		<div class="col-md-4">
    <h3>Registrate para poder participar y comprar!</h3>  
    <p>Si gustas participar en preguntas o deseas comprar, es requerimiento obligatorio registrarse utilizando el formulario de la derecha y ofrecer datos fidedignos.</p>
    </div>
		<div class="col-md-4">
			<div class="card">
        <div class="card-header">REGISTRO DE CLIENTES</div>
				<div class="card-body">
<form class="form-horizontal" role="form" method="post" action="index.php?action=register">
  <div class="form-group">
    <label for="inputEmail1" class="control-label">*Nombre</label>
    <div class="">
      <input type="text" required name="name" class="form-control" id="inputEmail1" placeholder="Nombre">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="control-label">*Apellido</label>
    <div class="">
      <input type="text" required name="lastname" class="form-control" id="inputEmail1" placeholder="Apellido">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="control-label">Telefono</label>
    <div class="">
      <input type="text" name="phone" class="form-control" id="inputEmail1" placeholder="Telefono">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="control-label">Direccion</label>
    <div class="">
      <input type="text" name="address" class="form-control" id="inputEmail1" placeholder="Direccion">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="control-label">*Correo Electronico</label>
    <div class="">
      <input type="email" name="email" required class="form-control" id="inputEmail1" placeholder="Correo Electronico">
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword1" class="control-label">*Contrase&ntilde;a</label>
    <div class="">
      <input type="password" required name="password" class="form-control" id="inputPassword1" placeholder="Contrase&ntilde;a">
    </div>
  </div>
  <div class="form-group">
    <div class="">
      <div class="checkbox">
        <label>
          <input type="checkbox" name="accept" required> Acepto terminos y condiciones de uso
        </label>
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="">
      <button type="submit" class="btn btn-block btn-primary">Registrarme</button>
    </div>
  </div>
</form>
      <p class="text-muted">* Campos obligatorios</p>
				</div>
			</div>
		</div>
    <div class="col-md-4 ">
      <div class="card">
        <div class="card-header">Ingresar al sistema</div>
        <div class="card-body">
<form class="form-horizontal" role="form" method="post" action="index.php?action=clientaccess">

  <div class="form-group">
    <label for="inputEmail1" class="control-label">Correo Electronico</label>
    <div class="">
      <input type="email" name="email" class="form-control" id="inputEmail1" placeholder="Correo Electronico">
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword1" class="control-label">Contrase&ntilde;a</label>
    <div class="">
      <input type="password" name="password" class="form-control" id="inputPassword1" placeholder="Contrase&ntilde;a">
    </div>
  </div>
  <div class="form-group">
    <div class="">
      <button type="submit" class="btn btn-block btn-info">Iniciar sesion</button>
    </div>
  </div>
</form>


        </div>
      </div>
    </div>
	</div>
</div>