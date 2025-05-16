<?php
// Api de categorias
include_once '../../config/db.php';
include_once '../../config/conexion.php';

// Obtener los datos para la actualización
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Obtener los datos
    $data = json_decode(file_get_contents("php://input"));
    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        // Error al decodificar JSON
        http_response_code(400); // Bad Request
        // Enviar respuesta JSON
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Error al decodificar JSON']);
        exit;
    }
    // Validar los datos recibidos existan
    if (!isset($id, $ruc, $razon_social, $telefono, $direccion, $email)) {
        // Datos incompletos
        http_response_code(400); // Bad Request
        // Enviar respuesta JSON
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Datos incompletos']);
        exit();
    }
    $id = $data->id_proveedor;
    $ruc = $data->ruc;
    $razon_social = $data->razon_social;
    $telefono = $data->telefono;
    $direccion = $data->direccion;
    $email = $data->email;
    


    // Actualizar el proveedor
    $sql = "SELECT * FROM proveedor WHERE id_proveedor = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Obtengo el id de la empresa
        $row = $result->fetch_assoc();
        $id_empresa = $row['id_empresa'];

        // Actualizo la empresa
        $sql = "UPDATE empresa SET n_ruc = ?, razon_social = ?, n_telefono = ?, direccion = ?, email = ? WHERE id_empresa = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssssi", $ruc, $razon_social, $telefono, $direccion, $email, $id_empresa);
        $stmt->execute();
        // Cambiar el código de respuesta
        http_response_code(200); // OK
        // Enviar respuesta JSON
        header('Content-Type: application/json');
        echo json_encode(['success' => 'Proveedor actualizado correctamente']);
    } else {
        // Cambiar el código de respuesta
        http_response_code(404); // Not Found
        // Enviar respuesta JSON
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Proveedor no encontrado']);
    }
    $stmt->close();
    exit();
}


    // Obtener datos para mostrar
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM empresa INNER JOIN proveedor ON empresa.id_empresa = proveedor.id_empresa WHERE proveedor.id_proveedor = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $id_empresa = $row['id_empresa'];

        $sql = "SELECT * FROM empresa WHERE id_empresa = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id_empresa);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $ruc = $row['n_ruc'];
            $razon_social = $row['razon_social'];
            $telefono = $row['n_telefono'];
            $direccion = $row['direccion'];
            $email = $row['email'];

            // Enviar respuesta JSON
            header('Content-Type: application/json');
            echo json_encode([
                'id_proveedor' => $id,
                'ruc' => $ruc,
                'razon_social' => $razon_social,
                'telefono' => $telefono,
                'direccion' => $direccion,
                'email' => $email,
            ]);
        } else {
            // Proovedor no encontrado
            http_response_code(404); // Not Found
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Proveedor no encontrado']);
        }

        $stmt->close();
    } else {
        // Parámetros incompletos
        http_response_code(400); // Bad Request
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Parámetros incompletos']);
    }


$con->close();
?>