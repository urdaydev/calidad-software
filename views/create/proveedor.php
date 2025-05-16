
<?php


// Check if the fields are not empty
if (isset($_POST['ruc']) && isset($_POST['razon_social']) && isset($_POST['telefono']) && isset($_POST['correo']) && isset($_POST['direccion'])) {
    header("Location: ../proveedor.php?error=emptyField");
    exit;
} else {
    require_once('../../config/db.php');
    require_once('../../config/conexion.php');
        // Registrar proveedor
    $ruc = $_POST['ruc'];
    $razon_social = $_POST['razon_social'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $direccion = $_POST['direccion'];


    // Check if the provider already exists
    $sql = "SELECT * FROM empresa inner join proveedor on empresa.id_empresa = proveedor.id_empresa WHERE n_ruc = '$ruc'";
    $query = mysqli_query($con, $sql);
    $result = mysqli_fetch_array($query);

    $sql = "SELECT * FROM empresa where n_ruc = '$ruc'";
    $query = mysqli_query($con, $sql);
    $result2 = mysqli_fetch_array($query);
    if ($result > 0) {
        header("Location: ../proveedor.php?error=exist");
        exit;
    } else if($result == 0) {
        // Insert the provider into the database
        // n_ruc, razon_social, n_telefono, email, direccion
        // quitar comas y comillas simples
        $ruc = removeQuotes($ruc);
        $razon_social = removeQuotes($razon_social);
        $telefono = removeQuotes($telefono);
        $correo = removeQuotes($correo);
        $direccion = removeQuotes($direccion);
        
        // Inserto la empresa
        $sql = "INSERT INTO empresa (n_ruc, razon_social, n_telefono, email, direccion) VALUES ('$ruc', '$razon_social', '$telefono', '$correo', '$direccion')";
        $query = mysqli_query($con, $sql);
        
        // Obtengo el id de la empresa
        $sql = "SELECT id_empresa FROM empresa WHERE n_ruc = '$ruc'";
        $query = mysqli_query($con, $sql);
        $result = mysqli_fetch_array($query);
        $id_empresa = $result['id_empresa'];
        // Inserto el proveedor
        $sql = "INSERT INTO proveedor (id_empresa) VALUES ('$id_empresa')";
        $query = mysqli_query($con, $sql);
        

        if ($query) {
            header("Location: ../proveedores.php?success=create");
        } else {
            header("Location: ../proveedores.php?error=bdError");
        }
    } else if ($result2 > 0) {
        // Obtengo el id de la empresa
        $sql = "SELECT id_empresa FROM empresa WHERE n_ruc = '$ruc'";
        $query = mysqli_query($con, $sql);

        $result = mysqli_fetch_array($query);
        $id_empresa = $result['id_empresa'];
        // Inserto el proveedor
        $sql = "INSERT INTO proveedor (id_empresa) VALUES ('$id_empresa')";
        $query = mysqli_query($con, $sql);
    }
}
function removeQuotes($string) {
    $string = str_replace("'", "", $string);
    $string = str_replace('"', '', $string);
    return $string;
}
?>