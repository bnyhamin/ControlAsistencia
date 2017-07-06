<?php
header("Expires: 0");

require_once("includes/Connection.php");
require_once("includes/Constantes.php");
require_once("includes/mantenimiento.php");
require_once("includes/clsCA_Asistencias.php");
require_once("includes/clsCA_Menu.php");
require_once("includes/clsArea.php");
require_once("includes/clsCA_Estadisticas.php");
require_once("includes/Seguridad.php");

$id=$_SESSION["empleado_codigo"];
$nombre=$_SESSION["empleado_nombres"];
$cad="";

$o = new ca_menu();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();
$o->MyDBName= db_name();

$obj = new ca_asistencia();
$obj->setMyUrl(db_host());
$obj->setMyUser(db_user());
$obj->setMyPwd(db_pass());
$obj->setMyDBName(db_name());

$obj->empleado_codigo=$id;
$cod=$obj->validar_asistencia();
$rp = $obj->validar();
$asistencia_codigo=$obj->asistencia_codigo;
$tip= $obj->tip;

$superiorEmpleados = "";
$flag_tipo_usuario = 0; // 0: usuario normal // 1: mando // 2: supervisor


$objE = new ca_estadisticas();
$objE->setMyUrl(db_host());
$objE->setMyUser(db_user());
$objE->setMyPwd(db_pass());
$objE->setMyDBName(db_name());

$mostrarEstadistica = isset($_GET["mostrarEstadistica"])?$_GET["mostrarEstadistica"]:"";
if($mostrarEstadistica=='E')
{
	//////////////////////----------------------------VALIDACION DE ESTADISTICAS--------------------------------------------
	$superiorEmpleados = $objE->retornaAreasDependientes($id);
	if($superiorEmpleados == null || $superiorEmpleados == "")
	{
		$superiorEmpleados = $objE->retornaAsignacionesSupervision($id);
		if($superiorEmpleados != null && $superiorEmpleados != "")
		{
			$flag_tipo_usuario = 2;
		}
	}
	else
	{
		$flag_tipo_usuario = 1;
	}
}

$menu_top = "80";

$o->empleado_codigo=$id;
$o->alineacion="H";
$o->myWidth="82";
$o->myHeight="18";
$o->myLeft="1";
$o->myTop=$menu_top; //298
$o->myImagen="";
$o->myColorFondo="#DEE2DF"; //#DEE2DF
$o->myColorSeleccion="#CBD9DC"; //#CBD9DC
$o->myStyle="vertical-align:middle; BORDER-TOP: 1px solid; BORDER-BOTTOM: 0px solid; BORDER-RIGHT: 1px inside; BORDER-LEFT: 2px inside; background: white url(images/fondoazul_menu.jpg); "; //fondoazul_menu.jpg
$o->myFont="color=#004080 style='font-size:xx-small; font-style: normal; font-weight: bold; font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; border-color: Blue; '";

//$cad=$o->Construir();

$cad = $o->Menu_vertical();


?>



<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.7
Version: 4.7.5
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="es" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="es" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en-US">
<!--<![endif]-->
<head>
    <meta charset="text/html; charset=iso-8859-1" />
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-param" content="_csrf" />
    <meta name="csrf-token" content="mLjDu89Z3YygHlrgTgwiF5ZiWa7QPqXuXM9U41LdbKo9FVy4fxQlcZkzYdeJgCzLGewIHkWQKS-L3gYyapilXw==" />
    <title>
        <?php echo tituloGAP() ?>Menu Principal
    </title>
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=all" rel="stylesheet" />
    <link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" />
    <link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" />
    <link href="assets/global/css/components.min.css" rel="stylesheet" />
    <link href="assets/global/css/plugins.min.css" rel="stylesheet" />
    <link href="assets/layouts/layout/css/layout.min.css" rel="stylesheet" />
    <link href="assets/layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" />
    <link href="assets/layouts/layout/css/custom.min.css" rel="stylesheet" />
    <link rel="shortcut icon" href="favicon.ico" />
    <style type="text/css">
        @media (min-width: 992px) {
            #contenido-atento {
                margin: 0px;
                min-height: 650px;
            }
        }

        .portlet.light {
            padding: 0px;
        }

            .portlet.light .portlet-body {
                padding: 0px;
            }
    </style>

</head>

<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">

    <div class="page-wrapper">


        <div class="page-header navbar navbar-fixed-top">
            <!-- BEGIN HEADER INNER -->
            <div class="page-header-inner ">
                <!-- BEGIN LOGO -->
                <div class="page-logo">
                    <a href="#">
                        <img src="http://localhost:81/yii/web/images/logo_atento.gif" alt="logo" class="logo-default" />
                    </a>
                    <div class="menu-toggler sidebar-toggler">
                        <span></span>
                    </div>
                </div>
                <!-- END LOGO -->
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
                    <span></span>
                </a>
                <!-- END RESPONSIVE MENU TOGGLER -->
                <!-- BEGIN TOP NAVIGATION MENU -->
                <div class="top-menu">
                    <ul class="nav navbar-nav pull-right">
                        <!-- BEGIN NOTIFICATION DROPDOWN -->
                        <!-- DOC: Apply "dropdown-dark" class after "dropdown-extended" to change the dropdown styte -->
                        <!-- DOC: Apply "dropdown-hoverable" class after below "dropdown" and remove data-toggle="dropdown" data-hover="dropdown" data-close-others="true" attributes to enable hover dropdown mode -->
                        <!-- DOC: Remove "dropdown-hoverable" and add data-toggle="dropdown" data-hover="dropdown" data-close-others="true" attributes to the below A element with dropdown-toggle class -->
                        <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <i class="icon-bell"></i>
                                <span class="badge badge-default">7 </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="external">
                                    <h3>
                                        <span class="bold">12 pending</span>notifications
                                    </h3>
                                    <a href="page_user_profile_1.html">view all</a>
                                </li>
                                <li>
                                    <ul class="dropdown-menu-list scroller" style="height: 250px;" data-handle-color="#637283">
                                        <li>
                                            <a href="javascript:;">
                                                <span class="time">just now</span>
                                                <span class="details">
                                                    <span class="label label-sm label-icon label-success">
                                                        <i class="fa fa-plus"></i>
                                                    </span>New user registered.
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <!-- END NOTIFICATION DROPDOWN -->
                        <!-- BEGIN INBOX DROPDOWN -->
                        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                        <li class="dropdown dropdown-extended dropdown-inbox" id="header_inbox_bar">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <i class="icon-envelope-open"></i>
                                <span class="badge badge-default">4 </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="external">
                                    <h3>
                                        You have
                                        <span class="bold">7 New</span>Messages
                                    </h3>
                                    <a href="app_inbox.html">view all</a>
                                </li>
                                <li>
                                    <ul class="dropdown-menu-list scroller" style="height: 275px;" data-handle-color="#637283">
                                        <li>
                                            <a href="#">
                                                <span class="photo">
                                                    <img src="http://localhost:82/yii/web/assets/layouts/layout3/img/avatar2.jpg" class="img-circle" alt="" />
                                                </span>
                                                <span class="subject">
                                                    <span class="from">Lisa Wong </span>
                                                    <span class="time">Just Now </span>
                                                </span>
                                                <span class="message">Vivamus sed auctor nibh congue nibh. auctor nibh auctor nibh... </span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <!-- END INBOX DROPDOWN -->
                        <!-- BEGIN TODO DROPDOWN -->
                        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                        <li class="dropdown dropdown-extended dropdown-tasks" id="header_task_bar">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <i class="icon-calendar"></i>
                                <span class="badge badge-default">3 </span>
                            </a>
                            <ul class="dropdown-menu extended tasks">
                                <li class="external">
                                    <h3>
                                        You have
                                        <span class="bold">12 pending</span>tasks
                                    </h3>
                                    <a href="app_todo.html">view all</a>
                                </li>
                                <li>
                                    <ul class="dropdown-menu-list scroller" style="height: 275px;" data-handle-color="#637283">
                                        <li>
                                            <a href="javascript:;">
                                                <span class="task">
                                                    <span class="desc">New release v1.2 </span>
                                                    <span class="percent">30%</span>
                                                </span>
                                                <span class="progress">
                                                    <span style="width: 40%;" class="progress-bar progress-bar-success" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
                                                        <span class="sr-only">40% Complete</span>
                                                    </span>
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <!-- END TODO DROPDOWN -->
                        <!-- BEGIN USER LOGIN DROPDOWN -->
                        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                        <li class="dropdown dropdown-user">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <img alt="" class="img-circle" src="http://10.252.194.225/SisPersonal01/fotosdni/07941479.jpg" />
                                <span class="username username-hide-on-mobile">Nick </span>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-default">
                                <li>
                                    <a href="page_user_profile_1.html">
                                        <i class="icon-user"></i>My Profile
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- END USER LOGIN DROPDOWN -->
                        <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                        <li class="dropdown dropdown-quick-sidebar-toggler">
                            <a href="cerrarc.php" class="dropdown-toggle">
                                <i class="icon-logout"></i>
                            </a>
                        </li>
                        <!-- END QUICK SIDEBAR TOGGLER -->
                    </ul>
                </div>
                <!-- END TOP NAVIGATION MENU -->
            </div>
            <!-- END HEADER INNER -->
        </div>
        <!-- END HEADER -->
        <!-- BEGIN HEADER & CONTENT DIVIDER -->
        <div class="clearfix"></div>
        <!-- END HEADER & CONTENT DIVIDER --><!-- BEGIN CONTAINER -->
        <div class="page-container">


            <!-- BEGIN SIDEBAR -->
            <div class="page-sidebar-wrapper">
                <!-- BEGIN SIDEBAR -->
                <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                <div class="page-sidebar navbar-collapse collapse">
                    <!-- BEGIN SIDEBAR MENU -->
                    <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
                    <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
                    <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
                    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                    <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
                    <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                    <?php echo $cad; ?>
                </div>
                <!-- END SIDEBAR -->
            </div>
            <!-- END SIDEBAR -->

            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- END THEME PANEL -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet light">
                                <div class="portlet-body">
                                    <div class="tabbable-custom nav-justified">
                                        <ul id="ul-buttom-atento" class="nav nav-tabs nav-justified">
                                            <li id="tab_0_0" class="active">
                                                <a href="#opt_0_0" data-toggle="tab" aria-expanded="true">Inicio</a>
                                            </li>
                                        </ul>
                                        <div id="contenido-atento" class="tab-content">
                                            <div class="tab-pane active" id="opt_0_0">
                                                <p>Bienvenido. </p>
                                                <p>
                                                    Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie
                                        consequat.
                                                </p>
                                                <p>
                                                    Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie
                                        consequat dolor in hendrerit in vulputate velit esse molestie consequat.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
        </div>
        <!-- END CONTAINER -->
        <!-- BEGIN FOOTER -->
        <div class="page-footer">
            <div class="page-footer-inner">
                2017 &copy; Desarrollo Sistemas Corporativos
                <a target="_blank" href="http://atento.com">Atento</a>&nbsp;|&nbsp;
                <a href="http://atento.com.pe" title="Atento Per&uacute;" target="_blank">Per&uacute;</a>
            </div>
            <div class="scroll-to-top">
                <i class="icon-arrow-up"></i>
            </div>
        </div>
        <!-- END FOOTER -->
    </div>

    
    <script src="/yii/web/assets/20a639ab/jquery.js"></script>
    <script src="/yii/web/assets/ecf4322c/yii.js"></script>
    <script src="/yii/web/assets/global/plugins/jquery.min.js"></script>
    <script src="/yii/web/assets/global/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="/yii/web/assets/global/plugins/js.cookie.min.js"></script>
    <script src="/yii/web/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <script src="/yii/web/assets/global/plugins/jquery.blockui.min.js"></script>
    <script src="/yii/web/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
    <script src="/yii/web/assets/global/scripts/app.min.js"></script>
    <script src="/yii/web/assets/layouts/layout/scripts/layout.min.js"></script>
    <script src="/yii/web/assets/layouts/layout/scripts/demo.min.js"></script>
    <script src="/yii/web/assets/layouts/global/scripts/quick-sidebar.min.js"></script>
    <script src="/yii/web/assets/layouts/global/scripts/quick-nav.min.js"></script>
    <script>
    $(document).ready(function()
    {
        /*
        $('#clickmewow').click(function()
        {
            $('#radio1003').attr('checked', 'checked');
        });
        */


        $("a[id^='tab_']").click(function() {


            console.info('ingresa click al tab');

            var id=this.id;
            var newId = id.replace("tab","opt")




            //validar si el ID es un padre para no hacer nada
            var validateTab = $('#ul-buttom-atento li#'+this.id)[0];

            if(typeof validateTab!="undefined"){
                console.info('existe')
                //primero desactivo todos los tab
                $('#ul-buttom-atento >li').removeClass("active");
                //desactivar los div
                $('#contenido-atento .tab-pane').removeClass("active");
                //activar el tab seleccionado
                $('#ul-buttom-atento li#'+id).addClass("active");
                //activar el div seleccionado
                $('#contenido-atento div#'+newId).addClass("active");
            }else{
                console.info('No existe')
                //desactivo todos los tab
                $('#ul-buttom-atento >li').removeClass("active");
                //desactivar los div
                $('#contenido-atento .tab-pane').removeClass("active");
                //crea el tab
                var url = "";
                var label = "";
                $.each(this.attributes, function(i, attrib){

                    console.info(attrib.name);
                    if(attrib.name==="ruta"){
                        url = "http://localhost:81/"+attrib.value;
                        console.info(attrib.value);
                    }
                    if(attrib.name==="etiqueta"){
                        label = attrib.value;
                        console.info(attrib.value);
                    }
                });


                var newLi = '<li id="'+id+'" class="active">'+
                            '<a href="#'+newId+'" data-toggle="tab">'+label+'</a>'+
                            '</li>';

                $('ul#ul-buttom-atento').append(newLi);
                //crear el div
                var newContent = '<div class="tab-pane" id="'+newId+'">'+
                                    '<iframe class="page-container" src="'+url+'" width="100%" height="100%" type="text/html">'+
                                '</object></div>';

                $('#contenido-atento').append(newContent);

                $('#contenido-atento div#'+newId).addClass("active");

                /*
                $(document).ready(function() {
                    $('li').click(function () {
                        var url = $(this).attr('rel');
                        $('#iframe').attr('src', url);
                        $('#iframe').reload();
                    });
                });
                <div class="full-height-content full-height-content-scrollable">
                            <div class="full-height-content-body">

                */

            }


        });


    })
    </script>
</body>
</html>


