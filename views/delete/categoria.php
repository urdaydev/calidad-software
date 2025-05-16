<?php
    // recibo el id categoria
    $id_categoria = $_GET['id'];
    include_once '../../config/db.php';
    include_once '../../config/conexion.php';
    
    if(empty($id_categoria)){
        header("location: ../categorias.php");
        exit;
    }else{
        // Cambio el estado de la categoria a 0
        $sql = "UPDATE categoria SET estado = 0 WHERE id_categoria = $id_categoria";
        $query = mysqli_query($con, $sql);
        if($query){
            header("location: ../categorias.php?success=delete");
        }else{
            header("location: ../categorias.php?error=bdError");
        }
    }
?>