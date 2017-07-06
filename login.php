<?php
    require_once("includes/Connection.php");
?>


<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="es">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <title><?php echo tituloGAP() ?> - Login</title>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="Preview page of Metronic Admin Theme #1 for " name="description" />
    <meta content="" name="author" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
    <link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="assets/pages/css/login.min.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <!-- END THEME LAYOUT STYLES -->
    <link rel="shortcut icon" href="favicon.ico" />



    <!--[if lt IE 9]>
    <script src="../assets/global/plugins/respond.min.js"></script>
    <script src="../assets/global/plugins/excanvas.min.js"></script>
    <script src="../assets/global/plugins/ie8.fix.min.js"></script>
    <![endif]-->
    <!-- BEGIN CORE PLUGINS -->
    <script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
    <script src="assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
    <script src="assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
    <script src="assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL SCRIPTS -->
    <script src="assets/global/scripts/app.min.js" type="text/javascript"></script>
    <!-- END THEME GLOBAL SCRIPTS -->

    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    <!-- END THEME LAYOUT SCRIPTS -->



    <!-- SCRIP GAP-->
    <script type="text/javascript" src="default.js"></script>
    <script type="text/javascript" src="jscript.js"></script>
    <script type="text/javascript" src="no_teclas.js"></script>
    <script type="text/javascript" src="js/app/app.js"></script>
    <script type="text/javascript" src="js/app/app.messages.js"></script>
    <script type="text/javascript" src="js/app/app.ui.message.js"></script>
    <script type="text/javascript" src="jscript/app.login.menu.js"></script>

    <style type="text/css">
        /*MESSAGES*/
        .information, .error, .warning, .success {
            background-position: 10px center;
            /*background-position:center;*/
            background-repeat: no-repeat;
            border: 1px solid;
            margin: 10px 0;
            padding: 5px 10px 15px 50px;
            /*padding:15px 10px 15px 50px;*/
            font-family: "tahoma";
            font-size: 11px;
            height: 25px;
        }

        .information {
            background-image: url(images/cargas/information.png);
            color: #00529B;
            background-color: #BDE5F8;
            text-align: left;
        }

        .error {
            background-image: url(images/cargas/error.png);
            color: #D8000C;
            background-color: #FFBABA;
        }

        .warning {
            background-image: url(images/cargas/warning.png);
            color: #9F6000;
            background-color: #FEEFB3;
            text-align: left;
        }

        .success {
            background-image: url(images/cargas/success.png);
            color: #4F8A10;
            background-color: #DFF2BF;
            text-align: left;
            text-align: left;
        }
    </style>
</head>
<!-- END HEAD -->

<body class=" login">
    <!-- BEGIN LOGO -->
    <div class="logo">
        <a href="#">
            <img src="assets/pages/img/logo-big.png" alt=""/>
        </a>
    </div>
    <!-- END LOGO -->
    <!-- BEGIN LOGIN -->
    <div class="content">
        <!-- BEGIN LOGIN FORM -->
        <form name="frm" id="frm" class="login-form" accept-charset="utf-8">
            <h3 class="form-title font-green">Acceso</h3>
            
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">Email</label>
                <input type="text" name="txtUSR" value="" class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="DNI" id="txtUSR" />
            </div>

            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">Clave</label>
                <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="Clave" name="txtPWD" id="txtPWD" />
            </div>
            <div class="form-actions">
                <label class="btn green btn-block uppercase" id="b_login">Ingresar</label>
            </div>
            <div class="create-account">
                <p>
                    <a href="javascript:;" id="register-btn" class="uppercase">Cambiar Clave</a>
                </p>
            </div>
            <div class="alert alert-danger display-hide" id="MensajeCarga">
                
            </div>
            <div class="alert alert-danger display-hide ">
                <div id="mensaje_cargando" align="center"></div>
            </div>
    </form>
        <!-- END LOGIN FORM -->
    </div>
    <div class="copyright"> 2017 &copy; Sistemas Corporativos </div>

</body>

</html>
