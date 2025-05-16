<?php
$tipo_usuario = $_POST['tipo_usuario'];
$username = $_POST['username'];
$password = $_POST['password'];
$password_repeat = $_POST['password_repeat'];
$direccion = $_POST['direccion'];
$imagen = $_FILES['imagen']['name'];
$dni = $_POST['dni'];
$nombres = $_POST['nombres'];
$apellidos = $_POST['apellidos'];
$telefono = $_POST['telefono'];

# Incluimos la clase usuario
require_once('../config/db.php');
require_once('../config/conexion.php');
if ($password != $password_repeat) {
  # Redireccionar a la pagina de registro
  header("Location: ../sign_up.php?error=distinct_password");
  exit;
}
// verificar si todos los datos fueron ingresados.
if ($tipo_usuario == "" || $username == "" || $password == "" || $password_repeat == "" || $direccion == "" || $imagen == "" || $dni == "" || $nombres == "" || $apellidos == "" || $telefono == "") {
  # Mostrar una alerta con Swal.fire
  echo "<script> 
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Todos los campos son obligatorios',
        footer: '<a href=\"../sign_up.php\">Volver a intentar</a>'
    });
    '</script>";
} else {

  # Divido los apellidos
  $apellidos = explode(" ", $apellidos);

  $sql2 = "INSERT INTO `persona` (`n_doc`, `nombres`, `a_paterno`, `a_materno`, `direccion`, `n_telefono`) VALUES ('$dni', '$nombres', '$apellidos[0]', '$apellidos[1]', '$direccion', '$telefono');";

  # Ejecutamos la consulta    en la base de datos
  if ($con->query($sql2) === TRUE) {
    #obtenemos el id de la persona
    $id_persona = $con->query("SELECT id_persona FROM persona WHERE n_doc = '$dni'");

    $id_persona = $id_persona->fetch_assoc();
    $id_persona = $id_persona['id_persona'];
    # convertimos el password a hash
    $password_encriptado = password_hash($password, PASSWORD_DEFAULT);

    # Insertamos el usuario
    // datos tabla. usuario
    // id_usuario INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    // -- Llave foranea
    // id_tipo_acceso INT NOT NULL, 1 = administrador, 2 = vendedor, 3 = almacenero
    // id_persona INT NOT NULL,
    // -- Atributos
    // usuario VARCHAR(250) NOT NULL UNIQUE,
    // imagen VARCHAR(250) NOT NULL,
    // contrasena VARCHAR(250) NOT NULL,

    # Guardamos la imagen en la carpeta images con el id del usuario
    move_uploaded_file($_FILES['imagen']['tmp_name'], "../images/usuarios/$id_persona.jpg");
    $imagen = "images/usuarios/$id_persona.jpg";
    $sql3 = "INSERT INTO `usuario` (`id_tipo_acceso`, `id_persona`, `usuario`, `imagen`, `contrasena`) VALUES ('$tipo_usuario', '$id_persona', '$username', '$imagen', '$password_encriptado');";
    if ($con->query($sql3) === TRUE) {
      # Redireccionar a la pagina de login
      header("Location: ../login.php?success=1");
      exit;
    } else {
      # Redireccionar a la pagina de registro
      header("Location: ../sign_up.php?error=2");
      exit;
    }
  } else {
    # Redireccionar a la pagina de registro
    header("Location: ../sign_up.php?error=3");
    exit;
  }
}
