<?php
   
   //CONTROLADOR
    error_reporting(E_ALL);
    ini_set('display_errors', TRUE);
    ini_set('display_startup_errors', TRUE);
    
    include "modelo.php";
    include "vista.php";

    //Función para acceder a las variables globales de sesion
    session_start();

    if (isset($_GET["accion"])) {
        $accion = $_GET["accion"];
    } else {
        if (isset($_POST["accion"])) {
            $accion = $_POST["accion"];
        } else {
            $accion = "inicio";
        }
    }

    if (isset($_GET["id"])) {
        $id = $_GET["id"];
    } else {
        if (isset($_POST["id"])) {
            $id = $_POST["id"];
        } else {
            $id = 1;
        }
    }

    $header = vmostrarnuevoheader();

    switch($accion) {
        //inicio
        case "inicio":
            switch($id) {
                case 1:
                    $header = vmostrarnuevoheader();
                    vmostrarinicio($header);
                    break;
            }
            break;
        //Login
        case "login":
            switch($id) {
                case 1:
                    $header = vmostrarnuevoheader();
                    vmostrarlogin($header);
                    break;
                case 2:
                    $resultado = mvalidarlogin($header);
                    if ($resultado == 1) {
                        $header = vmostrarnuevoheader();
                        $datosvuelo = mobtenervuelos();
                        vmostrarvuelos($header, $datosvuelo);
                    } else {
                        vmostrarlogin($header);
                    }
                    break;
            }
            break;
        //Registro
        case "registro":
            switch($id) {
                case 1:
                    $header = vmostrarnuevoheader();
                    vmostrarresgistro($header);
                    break;
                case 2:
                    $resultado = mvalidarregistro();
                    if ($resultado == 1) {
                        $header = vmostrarnuevoheader();
                        $datosvuelo = mobtenervuelos();
                        vmostrarvuelos($header, $datosvuelo);
                    }else{
                        vmostrarresgistro($header);
                    }
                    break;
            }
            break;
        //Cerrar sesion
        case "logout":
            switch($id) {
                case 1:
                    cerrarSesion();    
                    break;                
            }
            break;
        case "reservar":
            switch($id) {
                case 1:
                    $header = vmostrarnuevoheader();
                    $datosvuelo = mobtenervuelos();
                    if (isset($_SESSION["usuario"])) {
                        vmostrarvuelos($header, $datosvuelo);
                    } else {
                        vmostrarvuelos2($header, $datosvuelo);
                    }
                    
                    break;
                case 2: 
                    mreservar();
                    $header = vmostrarnuevoheader();
                    $datosvuelo = mobtenervuelos();
                    vmostrarvuelos($header, $datosvuelo);
                    break;
            }
            break;
        case "perfil":
            switch($id) {
                case 1:
                    $header = vmostrarnuevoheader();
                    $usuario = mobtenerdatosusuario();
                    $reservas = mobtenerreservas();
                    vmostrarperfil($header, $usuario, $reservas);
                    break;
            }
            break;
        case "buscar":
            switch($id) {
                case 1:
                    $datosvuelo = mbuscar();
                    //comprobar errores
                    if(!is_object($datosvuelo)){
                        $datosvuelo = mobtenervuelos();
                    }
                    if (isset($_SESSION["usuario"])) {
                        vmostrarvuelos($header, $datosvuelo);
                    }else {
                        vmostrarvuelos2($header, $datosvuelo);
                    }
                    break;
                case 2:
                    $datosvuelo = mobtenervuelos();
                    if (isset($_SESSION["usuario"])) {
                        vmostrarvuelos($header, $datosvuelo);
                    }else {
                        vmostrarvuelos2($header, $datosvuelo);
                    }
                    break;
                   
            }
            break;
    }

    function cerrarSesion() {
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
            }
            // Liberar todas las variables de la sesión
            session_unset();
            // Destruir/cerrar sesión
            session_destroy();
            // Informar al usuario y volver al menú principal
            echo "<script>
                    alert('Sesión cerrada');
                    window.location= 'index.php'
            </script>";
    }
