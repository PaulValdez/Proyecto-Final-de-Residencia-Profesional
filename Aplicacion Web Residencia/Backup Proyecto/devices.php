<?php
session_start();
$logged = $_SESSION['logged'];

if(!$logged){
  echo "Ingreso no autorizado";
  die();
}

$alias="";
$serie="";
$user_id = $_SESSION['user_id'];

//Conexion a la base de datos
$conn = mysqli_connect("localhost","admin_resiot","121212","admin_resiot");

if ($conn==false){
  echo "Hubo un problema al conectarse a María DB";
  die();
}

if( isset($_POST['id_to_delete']) && $_POST['id_to_delete']!="") {
  $id_to_delete = $_POST['id_to_delete'];
  $conn->query("DELETE FROM `admin_resiot`.`devices` WHERE  `devices_id`=$id_to_delete");
}

if( isset($_POST['serie']) && isset($_POST['alias'])) {

  $alias = strip_tags($_POST['alias']);
  $serie = strip_tags($_POST['serie']);
  $conn->query("INSERT INTO `devices` (`devices_alias`, `devices_serie`, `devices_user_id`) VALUES ('".$alias."', '".$serie."', '".$user_id."');");

}

$result = $conn->query("SELECT * FROM `devices` WHERE `devices_user_id` = '".$user_id."'");
$devices = $result->fetch_all(MYSQLI_ASSOC);

?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>IoT Web App</title>
  <meta name="description" content="Admin, Dashboard, Bootstrap, Bootstrap 4, Angular, AngularJS" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- for ios 7 style, multi-resolution icon of 152x152 -->
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-barstyle" content="black-translucent">
  <link rel="apple-touch-icon" href="assets/images/logo.png">
  <meta name="apple-mobile-web-app-title" content="Flatkit">
  <!-- for Chrome on Android, multi-resolution icon of 196x196 -->
  <meta name="mobile-web-app-capable" content="yes">
  <link rel="shortcut icon" sizes="196x196" href="assets/images/logo.png">

  <!-- style -->
  <link rel="stylesheet" href="assets/animate.css/animate.min.css" type="text/css" />
  <link rel="stylesheet" href="assets/glyphicons/glyphicons.css" type="text/css" />
  <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="assets/material-design-icons/material-design-icons.css" type="text/css" />

  <link rel="stylesheet" href="assets/bootstrap/dist/css/bootstrap.min.css" type="text/css" />
  <!-- build:css assets/styles/app.min.css -->
  <link rel="stylesheet" href="assets/styles/app.css" type="text/css" />
  <!-- endbuild -->
  <link rel="stylesheet" href="assets/styles/font.css" type="text/css" />
</head>
<body>
  <div class="app" id="app">

    <!-- ############ LAYOUT START-->

    <!-- BARRA IZQUIERDA -->
    <!-- aside -->
    <div id="aside" class="app-aside modal nav-dropdown">
      <!-- fluid app aside -->
      <div class="left navside dark dk" data-layout="column">
        <div class="navbar no-radius">
          <!-- brand -->
          <a class="navbar-brand">
            <div ui-include="'assets/images/logo.svg'"></div>
            <img src="assets/images/logo.png" alt="." class="hide">
            <span class="hidden-folded inline">IoT</span>
          </a>
          <!-- / brand -->
        </div>
        <div class="hide-scroll" data-flex>
          <nav class="scroll nav-light">

            <ul class="nav" ui-nav>
              <li class="nav-header hidden-folded">
                <small class="text-muted">Main</small>
              </li>

              <li>
                <a href="dashboard.php" >
                  <span class="nav-icon">
                    <i class="fa fa-building-o"></i>
                  </span>
                  <span class="nav-text">Principal</span>
                </a>
              </li>

              <li>
                <a href="devices.php" >
                  <span class="nav-icon">
                    <i class="fa fa-cogs"></i>
                  </span>
                  <span class="nav-text">Dispositivos</span>
                </a>
              </li>

            </ul>
          </nav>
        </div>
        <div class="b-t">
          <div class="nav-fold">
          </div>
        </div>
      </div>
    </div>
    <!-- / -->

    <!-- content -->
    <div id="content" class="app-content box-shadow-z0" role="main">
      <div class="app-header white box-shadow">
        <div class="navbar navbar-toggleable-sm flex-row align-items-center">
          <!-- Open side - Naviation on mobile -->
          <a data-toggle="modal" data-target="#aside" class="hidden-lg-up mr-3">
            <i class="material-icons">&#xe5d2;</i>
          </a>
          <!-- / -->

          <!-- Page title - Bind to $state's title -->
          <div class="mb-0 h5 no-wrap" ng-bind="$state.current.data.title" id="pageTitle"></div>

        </div>
      </div>


      <!-- PIE DE PAGINA -->
      <div class="app-footer">
        <div class="p-2 text-xs">
          <div class="pull-right text-muted py-1">
            &copy; Copyright <strong>Residenciaiot</strong> <span class="hidden-xs-down"></span>
            <a ui-scroll-to="content"><i class="fa fa-long-arrow-up p-x-sm"></i></a>
          </div>
        </div>
      </div>

      <div ui-view class="app-body" id="view"></div>


        <!-- SECCION CENTRAL -->
        <div class="padding">

          <div class="row">
            <div class="col-md-6">
              <div class="box">
                <div class="box-header">

                  <h2>Agregar Dispositivo</h2>
                  <small>Ingresa el nombre (alias) y el número de serie del dispositivo que quieres instalar.</small>

                </div>
                <div class="box-divider m-0"></div>
                <div class="box-body">


                  <form role="form" method="post" target="">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Alias</label>
                      <input name="alias" type="text" class="form-control" placeholder="Ej: Casa Campo">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">Serie</label>
                      <input name="serie" type="texzt" class="form-control" placeholder="Ej: 777222">
                    </div>

                    <button type="submit" class="btn white m-b">Registrar</button>

                  </form>


                </div>
              </div>
            </div>

          </div>

          <div class="row">
            <div class="col-sm-6">
              <div class="box">
                <div class="box-header">
                  <h2>Dispositivos</h2>
                </div>
                <table class="table table-striped b-t">
                  <thead>
                    <tr>
                      <th>Alias</th>
                      <th>Fecha</th>
                      <th>Serie</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($devices as $device) {?>
                      <tr>
                        <td><?php echo $device['devices_alias'] ?></td>
                        <td><?php echo $device['devices_date'] ?></td>
                        <td><?php echo $device['devices_serie'] ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div class="col-sm-6">
            <h5>Eliminar Dispositvos</h5>

            <form class=""  method="post">
              <div class="form-group">
                <select  name="id_to_delete" class="form-control select2" ui-jp="select2" ui-options="{theme: 'bootstrap'}">
                  <?php foreach ($devices as $device ) { ?>
                    <option value="<?php echo  $device['devices_id']?>"><?php echo $device['devices_alias']."<-->".$device['devices_serie'] ?></option>
                  <?php } ?>
                </select>
              </div>
              <button type="submit" class="btn btn-fw danger">Eliminar</button>
            </form>


          </div>

        </div>

      </div>

      <!-- ############ PAGE END-->

    </div>

  </div>
  <!-- / -->

  <!-- SELECTOR DE TEMAS -->
  <div id="switcher">
    <div class="switcher box-color dark-white text-color" id="sw-theme">
      <a href ui-toggle-class="active" target="#sw-theme" class="box-color dark-white text-color sw-btn">
        <i class="fa fa-gear"></i>
      </a>
      <div class="box-header">
        <h2>Theme Switcher</h2>
      </div>
      <div class="box-body">
        <p>Themes:</p>
        <div data-target="bg" class="row no-gutter text-u-c text-center _600 clearfix">
          <label class="p-a col-sm-6 light pointer m-0">
            <input type="radio" name="theme" value="" hidden>
            Light
          </label>
          <label class="p-a col-sm-6 grey pointer m-0">
            <input type="radio" name="theme" value="grey" hidden>
            Grey
          </label>
          <label class="p-a col-sm-6 dark pointer m-0">
            <input type="radio" name="theme" value="dark" hidden>
            Dark
          </label>
          <label class="p-a col-sm-6 black pointer m-0">
            <input type="radio" name="theme" value="black" hidden>
            Black
          </label>
        </div>
      </div>
    </div>
<!-- / -->

<!-- ############ LAYOUT END-->

</div>
<!-- build:js scripts/app.html.js -->
<!-- jQuery -->
<script src="libs/jquery/jquery/dist/jquery.js"></script>
<!-- Bootstrap -->
<script src="libs/jquery/tether/dist/js/tether.min.js"></script>
<script src="libs/jquery/bootstrap/dist/js/bootstrap.js"></script>
<!-- core -->
<script src="libs/jquery/underscore/underscore-min.js"></script>
<script src="libs/jquery/jQuery-Storage-API/jquery.storageapi.min.js"></script>
<script src="libs/jquery/PACE/pace.min.js"></script>

<script src="html/scripts/config.lazyload.js"></script>

<script src="html/scripts/palette.js"></script>
<script src="html/scripts/ui-load.js"></script>
<script src="html/scripts/ui-jp.js"></script>
<script src="html/scripts/ui-include.js"></script>
<script src="html/scripts/ui-device.js"></script>
<script src="html/scripts/ui-form.js"></script>
<script src="html/scripts/ui-nav.js"></script>
<script src="html/scripts/ui-screenfull.js"></script>
<script src="html/scripts/ui-scroll-to.js"></script>
<script src="html/scripts/ui-toggle-class.js"></script>

<script src="html/scripts/app.js"></script>

<!-- ajax -->
<script src="libs/jquery/jquery-pjax/jquery.pjax.js"></script>
<script src="html/scripts/ajax.js"></script>
<!-- endbuild -->
</body>
</html>
