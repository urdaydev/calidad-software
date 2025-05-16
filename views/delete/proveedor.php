<?php
    // recibo el id deñ proveedor
    $id_proveedor = $_GET['id'];
    include_once '../../config/db.php';
    include_once '../../config/conexion.php';
    
    if(empty($id_proveedor)){
        header("location: ../proveedores.php?error=emptyField");
        exit;
    }else{
        // Cambio el estado de la categoria a 0
        $sql = "UPDATE proveedor SET estado = 0 WHERE id_proveedor = $id_proveedor";
        $query = mysqli_query($con, $sql);
        if($query){
            header("location: ../proveedores.php?success=delete");
        }else{
            header("location: ../proveedores.php?error=bdError");
        }
    }
?>