<?php
    function conexion() {
        //Conexion local
        $con = mysqli_connect("localhost", "root", "", "agenciavuelos");
        return $con;
    }

    function mvalidarlogin() {
        $con = conexion();

        $usuario = $_POST["usuario"];
        $password1 = $_POST["password"];
        $password = $password1;
        $consulta = "SELECT usuario, contraseña, idusuario FROM usuario WHERE usuario = '$usuario' 
                AND contraseña = '$password'";
        try {
            if ($resultado = $con->query($consulta)) {
                //Comprobar que el contenido de la consulta es valido
                $datos = $resultado->fetch_assoc();

                if ((empty($datos["usuario"])) || (empty($datos["contraseña"]))) {
                    echo "<script> alert('Usuario o contraseña no son correctos')</script>";
                    return -1;
                } else {
                    echo "<script> alert('Usuario correctamente logeado')</script>";
                    //Guardamos en la sesion lo necesario para identificar a un usuario al logear
                    $_SESSION["usuario"] = $datos["usuario"];
                    return 1;
                }
            }
        } catch (Exception $ex) {}
    }

    function mvalidarregistro() {
        $con = conexion();
        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $usuario = $_POST["usuario"];
        $password1 = $_POST['password1'];
        $password2 = $_POST['password2'];
        //MAS TARDE TRATAR IFS
        //veo si contraseña la misma
        if($password1 == $password2){
            $prueba = "select usuario from usuario where usuario = '$usuario'";
            $resultado = $con->query($prueba);
            //si el nombre de usuario es diferenre 
            if($resultado->num_rows == 0){
                //cifro la contraseña (temas de seguridad)
                $password = $password1;
                //onserto usuario en la bbdd
                $consulta = "insert into usuario (nombre, apellido, usuario, contraseña) values ('$nombre', '$apellido', '$usuario', '$password')";
                $resultado = $con->query($consulta);
                //si consulta ha ido OK.
                if($resultado){
                    echo "<script> alert('Usuario correctamente registrado')</script>";
                    //Guardamos en la sesion lo necesario para identificar a un usuario al logear
                    $_SESSION["usuario"] =  $usuario ;
                    return 1;
                }else{
                    echo "<script> alert('Usuario no se ha podido registrar correctamente ')</script>";
                    return -1;
                }
            }else{
                echo "<script> alert('Usuario no se ha podido registrar correctamente')</script>";
              return -1;
            }
        }else{
             echo "<script> alert('Usuario no se ha podido registrar correctamente ')</script>";
              return -1;
        }
       
       
    }

    function mobtenervuelos() {
        $con = conexion();
        $consulta = "SELECT idvuelo, origen, destino, fecha, precio FROM vuelo ORDER BY fecha";
        $resultado = $con -> query($consulta);
        return $resultado;
    }

    function mreservar() {
        $con = conexion();
        //$idusuario = $_SESSION["idusuario"];
        if (isset($_SESSION["usuario"])) {
            $usuario = $_SESSION["usuario"];
            //Obtengo el id de Usuario
            $prueba = "select idusuario from usuario where usuario = '$usuario'"; 
            $resultado = $con->query($prueba);
            $datos = $resultado->fetch_assoc();
            $idusuario = $datos["idusuario"];
            $idvuelo = $_GET["idvuelo"];
            $cantidad = $_POST['cantidad'];
            $consulta = "insert into reserva (idusuario, idvuelo,cantidad) values ('$idusuario','$idvuelo','$cantidad')";
            $con->query($consulta);
            echo "<script> alert('Reserva realizada')</script>";
        } 
    }

    function mobtenerdatosusuario() {
        $con = conexion();
        $nombreusuario = $_SESSION["usuario"];

        $consulta = "SELECT nombre, apellido, usuario FROM usuario WHERE usuario.usuario = '$nombreusuario'";
        $resultado = $con->query($consulta);

        return $resultado;
    }

    function mobtenerreservas() {
        $con = conexion();
        $nombreusuario = $_SESSION["usuario"];
        //Obtener el id del usuario que se ha registrado/iniciado sesion
        $consulta = "SELECT idusuario FROM usuario WHERE usuario.usuario = '$nombreusuario'";
        $resultado = $con->query($consulta);
        $datos = $resultado->fetch_assoc();
        $idusuario = $datos["idusuario"];
        //Obtener los datos de los vuelos que ha reservado el usuario
        $consulta = "SELECT origen, destino, fecha, cantidad, precio FROM vuelo, reserva WHERE reserva.idusuario = '$idusuario' AND reserva.idvuelo = vuelo.idvuelo";
        $resultado = $con -> query($consulta);
        
        return $resultado;
    }
    function mbuscar(){
        //establezco conexion
        $con = conexion();
        //obtengo los datos
        $origen = $_POST["origen"];
        $destino = $_POST["destino"];
        $fechaDia = $_POST["fechadia"];
        $fechaMes = $_POST["fechames"];
        $fechaAño = $_POST["fechaaño"];
        //compruebo si ha insertado datos en la fecha
        if( $fechaDia == 0 || $fechaMes == 0 || $fechaAño == 0){
            echo "<script> alert(' Datos en la fecha incompletos')</script>";
            return -1;
        }
        //cambio el formato de la fecha para hacer la consulta
        $fechaVuelo = $fechaAño."-".$fechaMes."-".$fechaDia;
        $fechaVueloIni = $fechaVuelo." 00:00:00";
        $fechaVueloFin = $fechaVuelo." 23:59:59";
        //creo la consulta
        $consulta = "select * from vuelo where origen = '$origen' and destino = '$destino' and fecha >= '$fechaVueloIni' and fecha <= '$fechaVueloFin'";
        //realizo la consulta
        $resultado = $con->query($consulta);
        return $resultado;
    }
?>