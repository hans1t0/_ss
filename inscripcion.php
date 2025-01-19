<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Inscripción Campus de Fútbol | Racing Playa San Juan</title>

	<!-- CSS -->
	<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
	<link href="css/Site.css" rel="stylesheet" type="text/css" media="screen">

	<!-- JAVASCRIPT -->

	<script src="https://code.jquery.com/jquery-2.2.3.min.js" integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="Scripts/validation.min.js"></script>
	<script type="text/javascript" src="Scripts/script.js"></script>

</head>

<body>

	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
				<a class="navbar-brand" href="index.php" target="_self" title="Campus de Fútbol"><img style="margin-top: -1px; max-width:100px;" src="images/logo.png"></a>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<li class="active"><a href="inscripcion.php">Inscripción</a></li>
					<li><a href="sobre.php" target="_self">Organización</a></li>
					<li><a href="contacto.php" target="_self">Contacto</a></li>
					<li><a href="sitio.php" target="_self">Lugar</a></li>
				</ul>
			</div>
			<!--/.nav-collapse -->
		</div>
	</nav>


	<div class="signin-form">

		<div class="container">

			<form class="form-signin" method="post" id="register-form">

				<h2 class="form-signin-heading" id="tit">Inscripción Campus Semana Santa</h2>
				<hr />

				<div id="error">
					<!-- error will be showen here ! -->
				</div>

				<!-- NOMBRE -->
				<div class="form-group">
					<input type="text" class="form-control" placeholder="Nombre del jugador" name="nombre" id="nombre" />
				</div>
				<!-- APELLIDOS -->
				<div class="form-group">
					<input type="text" class="form-control" placeholder="Apellidos del jugador" name="apellidos" id="apellidos" />
				</div>

				<div class="row">

					<div class="col-sm-6">
						<div class="form-group">
							<input type="text" class="form-control" placeholder="DNI Jugador" name="dni" id="dni" />
						</div>
					</div>

					<div class="col-sm-6">
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Fecha de nacimiento" name="sip" id="sip" />
						</div>
					</div>

				</div>

					<div class="form-group">
					<p>Demarcación:</p>
					<div class="form-control">
						<label class="radio-inline">
              <input type="radio" name="puesto" value="J">Jugador</label>
						<label class="radio-inline">
              <input type="radio" name="puesto" value="P">Portero</label>
					</div>
				</div>

				<!-- TELEFONOS -->
				<div class="form-group">
					<input type="tel" class="form-control" placeholder="Teléfonos de contacto" name="telefonos" id="telefonos" />
				</div>
				<!-- EMAIL -->
				<div class="form-group">
					<input type="email" class="form-control" placeholder="Correo electrónico" name="email" id="email" />
					<span id="check-e"></span>
				</div>
				<!-- CLUB -->
				<div class="form-group">
					<input type="text" class="form-control" placeholder="Club o Colegio de procedencia" name="club" id="club" />
				</div>
				<!-- LESIONES -->
				<div class="form-group">
					<input type="text" class="form-control" placeholder="Lesiones y/o alergias" name="lesiones" id="lesiones" />
				</div>

				<div class="row">

					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label" placehoder="Grupo" for="grupo"></label>
							<select class="form-control" id="grupo" name="grupo">
            <option value="">Grupo</option>
            <option value="Querubin">Querubines (5 años)</option>
            <option value="Prebenjamin">Prebenjamin (6 y 7 años)</option>
            <option value="Benjamin">Benjamines (8 y 9 años)</option>
            <option value="Alevin">Alevines (10 y 11 años)</option>
            <!-- <option value="Iinfantil">Infantiles (12 años)</option> -->
          </select>
						</div>
					</div>

					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label" placehoder="Talla" for="talla"></label>
							<select class="form-control" id="talla" name="talla">
            <option value="">Selecciona Talla</option>
            <option value="XXS">XXS</option>
            <option value="XS">XS</option>
            <option value="S">S</option>
            <option value="M">M</option>
            <option value="L">L</option>
          </select>
						</div>
					</div>
				</div>

				<div class="row">
					<!-- SOCIO -->
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label" placehoder="socio" for="socio"></label>
							<select class="form-control" id="socio" name="socio">
            <option value="">Selecciona modalidad</option>
            <option value="si">Jugador@ RPSJ</option>
            <option value="no">No jugador@</option>
          </select>
						</div>
					</div>

					<!-- DESCUENTO FAMILIAR -->
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label" placehoder="Modalidad" for="familiar"></label>
							<select class="form-control" id="familiar" name="familiar">
            <option value="sin">Sin descuento</option>
            <option value="Segundo">Segundo Hermano</option>
            <option value="Tercer">Tercer Hermano</option>
          </select>
						</div>
					</div>
				</div>

				<!-- PAGO -->
				<div class="form-group">
					<label class="col-md-4 control-label" placeholder="Forma de pago" for="pago"></label>
					<select class="form-control" id="pago" name="pago">
            <option value="">Selecciona forma de pago</option>
            <option value="jmanolo">Transferencia bancaria (mandando justificante de pago a m_bustosramirez@yahoo.es)</option>
            <option value="jcoordi">Transferencia bancaria ( entrega de justificante de pago al coordinador)</option>
          </select>
				</div>
				<hr />

				<!-- CONSENTIMIENTO -->
				<div class="form-group">
					<p>Consentimiento para usar la imagen:</p>
					<div class="form-control">
						<label class="radio-inline">
              <input type="radio" name="con" value="si">Sí</label>
						<label class="radio-inline">
              <input type="radio" name="con" value="no">No</label>
					</div>
				</div>
				<hr />

				<div class="form-group">
					<button type="submit" class="btn disable btn-default hidden" name="btn-submit" id="btn-submit">
            <span class="glyphicon glyphicon-log-in"></span> &nbsp; Inscribir </button>
				</div>

				<div class='alert alert-success'>
        <button class='close' data-dismiss='alert'></button>
        <strong>IMPORTANTE</strong><br />
          Pago Transferencia Bancaria · Titular: Racing Playa San Juan C.D. · Concepto: CampusSS + Nombre jugador@ · IBAN ES29 30582519452720001546
      </div>

			</form>

		</div>

	</div>

</body>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

</html>
