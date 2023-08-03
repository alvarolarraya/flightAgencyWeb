<?php
    function vmostrarnuevoheader() {
        $header = file_get_contents("header.html");
        $trozos = explode("##fila##", $header);

        //Comprobación para ver si un usuario ha iniciado sesión o no y mostrar diferentes headers
        if (isset($_SESSION["usuario"])) {
            $trozos[2] = str_replace("##nombreusuario##", $_SESSION["usuario"], $trozos[2]);
            return $trozos[0] . $trozos[2] . $trozos[3];
        } else {
            return $trozos[0] . $trozos[1] . $trozos[3];
        }
    }

    function vmostrarinicio($header) {
        $inicio = file_get_contents("inicio.html");
        $footer = file_get_contents("footer.html");

        $inicio = str_replace("##header##", $header, $inicio);
        $inicio = str_replace("##footer##", $footer, $inicio);

        echo $inicio;
    }

    function vmostrarlogin($header) {
        $login = file_get_contents("login.html");
        $footer = file_get_contents("footer.html");

        $login = str_replace("##header##", $header, $login);
        $login = str_replace("##footer##", $footer, $login);

        echo $login;
    }

    function vmostrarresgistro($header) {
        $registro = file_get_contents("registro.html");
        $footer = file_get_contents("footer.html");

        $registro = str_replace("##header##", $header, $registro);
        $registro = str_replace("##footer##", $footer, $registro);

        echo $registro;
    }

    function vmostrarvuelos($header, $datosvuelo) {
        $vuelos = file_get_contents("vuelos.html");
        $footer = file_get_contents("footer.html");

        $vuelos = str_replace("##header##", $header, $vuelos);
        $vuelos = str_replace("##footer##", $footer, $vuelos);

        //----------------- DIAS ---------------------
        $trozos = explode("##selectdias##",$vuelos);
        $cuerpoDias = "";
        for($i = 1; $i <=31;$i++){
            $aux = $trozos[1];
            $aux = str_replace("##dia##",$i, $aux);
            $cuerpoDias .= $aux;
        }
        //--------------------------------------------------

        //---------------- MESES --------------------
        $trozos2 = explode("##selectmeses##",$trozos[2]);
        $cuerpoMeses = "";
        for($i = 1; $i <=12;$i++){
             $aux = $trozos2[1];
             $aux = str_replace("##mes##",$i, $aux);
             $cuerpoMeses .= $aux;
        }
        //-------------------------------------------

        $trozos3 = explode("##fila##", $trozos2[2]);
        $cuerpo = "";

        //Mientras hayan vuelos obtener datos de cada vuelos
        while ($datos = $datosvuelo->fetch_assoc()) {
            $aux = $trozos3[1];
            $aux = str_replace("##origen##", $datos["origen"], $aux);
            $aux = str_replace("##destino##", $datos["destino"], $aux);
            $aux = str_replace("##fecha##", $datos["fecha"], $aux);
            $aux = str_replace("##precio##", $datos["precio"], $aux);
            $aux = str_replace("##idvuelo##", $datos["idvuelo"], $aux);
            $cuerpo .= $aux;
        }
        //--------------------------------------------------
        echo $trozos[0] . $cuerpoDias . $trozos2[0] . $cuerpoMeses . $trozos3[0] . $cuerpo . $trozos3[2];
    }

    function vmostrarvuelos2($header, $datosvuelo) {
        $vuelos = file_get_contents("vuelos2.html");
        $footer = file_get_contents("footer.html");

        $vuelos = str_replace("##header##", $header, $vuelos);
        $vuelos = str_replace("##footer##", $footer, $vuelos);

        //----------------- DIAS ---------------------
        $trozos = explode("##selectdias##",$vuelos);
        $cuerpoDias = "";
        for($i = 1; $i <=31;$i++){
            $aux = $trozos[1];
            $aux = str_replace("##dia##",$i, $aux);
            $cuerpoDias .= $aux;
        }
        //--------------------------------------------------

        //---------------- MESES --------------------
        $trozos2 = explode("##selectmeses##",$trozos[2]);
        $cuerpoMeses = "";
        for($i = 1; $i <=12;$i++){
             $aux = $trozos2[1];
             $aux = str_replace("##mes##",$i, $aux);
             $cuerpoMeses .= $aux;
        }
        //-------------------------------------------

        $trozos3 = explode("##fila##", $trozos2[2]);
        $cuerpo = "";

        //Mientras hayan vuelos obtener datos de cada vuelos
        while ($datos = $datosvuelo->fetch_assoc()) {
            $aux = $trozos3[1];
            $aux = str_replace("##origen##", $datos["origen"], $aux);
            $aux = str_replace("##destino##", $datos["destino"], $aux);
            $aux = str_replace("##fecha##", $datos["fecha"], $aux);
            $aux = str_replace("##precio##", $datos["precio"], $aux);
            $aux = str_replace("##idvuelo##", $datos["idvuelo"], $aux);
            $cuerpo .= $aux;
        }
        //--------------------------------------------------
        echo $trozos[0] . $cuerpoDias . $trozos2[0] . $cuerpoMeses . $trozos3[0] . $cuerpo . $trozos3[2];
    }

    function vmostrarperfil($header, $datosusuario, $datosreserva) {
        $perfil = file_get_contents("perfil.html");
        $footer = file_get_contents("footer.html");

        $perfil = str_replace("##header##", $header, $perfil);
        $perfil = str_replace("##footer##", $footer, $perfil);

        //Obtener datos de usuario
        $usuario = $datosusuario->fetch_assoc();
        $perfil = str_replace("##nombre##", $usuario["nombre"], $perfil);
        $perfil = str_replace("##apellido##", $usuario["apellido"], $perfil);
        $perfil = str_replace("##usuario##", $usuario["usuario"], $perfil);

        $trozos = explode("##fila##", $perfil);
        $cuerpo = "";

        $i = 1;
        while ($datos = $datosreserva->fetch_assoc()) {
            $aux = $trozos[1];
            $aux = str_replace("##origen##", $datos["origen"], $aux);
            $aux = str_replace("##destino##", $datos["destino"], $aux);
            $aux = str_replace("##fecha##", $datos["fecha"], $aux);
            $aux = str_replace("##cantidad##", $datos["cantidad"], $aux);
            $aux = str_replace("##precioTotal##", $datos["cantidad"]*$datos["precio"]." euros", $aux);
            $aux = str_replace("##idFactura##", "Factura_".$i, $aux);
            $cuerpo .= $aux;
            $i++;
        }
        echo $trozos[0] . $cuerpo . $trozos[2];
    }

?>