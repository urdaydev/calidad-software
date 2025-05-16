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
        exit;
    }

    // 'id_cliente' => $id_cliente,
    // 'dni' => $dni,
    // 'nombres' => $nombres,
    // 'a_paterno' => $a_paterno,
    // 'a_materno' => $a_materno
        // Validar los datos
    if (!isset($data->id_cliente, $data->dni, $data->nombres, $data->a_paterno, $data->a_materno)) {
        // Cambiar el código de respuesta
        http_response_code(400); // Bad Request
        // Enviar respuesta JSON
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Parámetros incompletos']);
        exit;
        }
    $id_cliente = $data->id_cliente;
    $dni = $data->dni;
    $nombres = $data->nombres;
    $a_paterno = $data->a_paterno;
    $a_materno = $data->a_materno;


    $id_cliente = removeQuotes($id_cliente);
    $dni = removeQuotes($dni);
    $nombres = removeQuotes($nombres);
    $a_paterno = removeQuotes($a_paterno);
    $a_materno = removeQuotes($a_materno);
    // Actualizar la categoría
    $sql = "SELECT * FROM persona INNER JOIN cliente ON persona.id_persona = cliente.id_persona WHERE cliente.id_cliente = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $id_persona = $row['id_persona'];
    $sql = "UPDATE persona SET dni = ?, nombres = ?, a_paterno = ?, a_materno = ? WHERE id_persona = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssssi", $dni, $nombres, $a_paterno, $a_materno, $id_persona);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        http_response_code(200); // OK
        // Enviar respuesta JSON
        header('Content-Type: application/json');
        echo json_encode(['success' => 'Categoría actualizada']);
        exit;
    } else {
        http_response_code(500); // Internal Server Error
        // Enviar respuesta JSON
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Error al actualizar la categoría']);
    }
    $stmt->close();
    
} else {
    // Obtener datos para mostrar
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $sql = "SELECT cliente.id_cliente,persona.dni,persona.nombres, persona.a_paterno, persona.a_materno, cliente.estado FROM cliente inner join persona on cliente.id_persona = persona.id_persona WHERE cliente.id_cliente = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id_cliente = $row['id_cliente'];
            $dni = $row['dni'];
            $nombres = $row['nombres'];
            $a_paterno = $row['a_paterno'];
            $a_materno = $row['a_materno'];


            // Enviar respuesta JSON
            header('Content-Type: application/json');
            echo json_encode([
                'id_cliente' => $id_cliente,
                'dni' => $dni,
                'nombres' => $nombres,
                'a_paterno' => $a_paterno,
                'a_materno' => $a_materno
            ]);
        } else {
            // Categoría no encontrada
            http_response_code(404); // Not Found
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Cliente no encontrado']);
        }

        $stmt->close();
    } else if ($_GET['dni']){
        $dni = $_GET['dni'];
        $sql = "SELECT cliente.id_cliente,persona.dni,persona.nombres, persona.a_paterno, persona.a_materno, cliente.estado FROM cliente inner join persona on cliente.id_persona = persona.id_persona WHERE persona.dni = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $dni);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id_cliente = $row['id_cliente'];
            $dni = $row['dni'];
            $nombres = $row['nombres'];
            $a_paterno = $row['a_paterno'];
            $a_materno = $row['a_materno'];
            // Enviar respuesta JSON
            header('Content-Type: application/json');
            echo json_encode([
                'id_cliente' => $id_cliente,
                'dni' => $dni,
                'nombres' => $nombres,
                'a_paterno' => $a_paterno,
                'a_materno' => $a_materno
            ]);
        }
        else {
            // Categoría no encontrada
            http_response_code(404); // Not Found
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Cliente no encontrado']);
        }
        // Enviar respuesta JSON

    }
    else {
        // Parámetros incompletos
        http_response_code(400); // Bad Request
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Parámetros incompletos']);
        // exit;
    }
}
function removeQuotes($string) {
    $string = str_replace("'", "", $string);
    $string = str_replace('"', '', $string);
    return $string;
}

$con->close();
?>
