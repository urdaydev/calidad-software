<?php


// Comprobar que los campos no esten vacios
if (!isset($_POST['nombre']) || !isset($_POST['descripcion'])) {
    header("Location: ../categorias.php?error=emptyField");
    exit;
} else {

    require_once('../../config/db.php');
    require_once('../../config/conexion.php');
    // recibir el nombre y la descripcion de la categoria
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    // Quitar ' y " para evitar errores en la base de datos
    $nombre = removeQuotes($nombre);
    $descripcion = removeQuotes($descripcion);

    // Comprobar que el nombre de la categoria no exista
    $sql = "SELECT * FROM categoria WHERE nom_categoria = '$nombre'";
    $query = mysqli_query($con, $sql);
    $result = mysqli_fetch_array($query);

    if ($result > 0) {
        header("Location: ../categorias.php?error=exist");
        exit;
    } else {
        // Insertar la categoria en la base de datos
        $sql = "INSERT INTO categoria (nom_categoria, descripcion) VALUES ('$nombre', '$descripcion')";
        $query = mysqli_query($con, $sql);

        if ($query) {
            header("Location: ../categorias.php?success=create");
        } else {
            header("Location: ../categorias.php?error=bdError");
        }
    }
}
function removeQuotes($string) {
    $string = str_replace("'", "", $string);
    $string = str_replace('"', '', $string);
    return $string;
}
?>