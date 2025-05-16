<?php
    // recibo el id categoria
    $id_cliente = $_GET['id'];
    include_once '../../config/db.php';
    include_once '../../config/conexion.php';
    
    if(empty($id_cliente)){
        header("location: ../clientes.php");
        exit;
    }else{
        // Cambio el estado de los productos a 0
        $sql = "UPDATE cliente SET estado = 0 WHERE id_cliente = $id_cliente";
        $query = mysqli_query($con, $sql);
        if($query){
            header("location: ../clientes.php?success=delete");
        }else{
            header("location: ../clientes.php?error=bdError");
        }
    }
?>