<?php
    // recibo el id categoria
    $id_producto = $_GET['id'];
    include_once '../../config/db.php';
    include_once '../../config/conexion.php';
    
    if(empty($id_producto)){
        header("location: ../productos.php");
        exit;
    }else{
        // Cambio el estado de los productos a 0
        $sql = "UPDATE producto SET estado = 0 WHERE id_producto = $id_producto";
        $query = mysqli_query($con, $sql);
        if($query){
            header("location: ../productos.php?success=delete");
        }else{
            header("location: ../productos.php?error=bdError");
        }
    }
?>