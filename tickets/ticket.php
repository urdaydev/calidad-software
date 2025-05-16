<?php
    date_default_timezone_set('America/Lima');
	# Incluyendo librerias necesarias #
    require "./code128.php";

    require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
    require_once ("../utils/functions.php");

    // CREATE TABLE Tienda (
    //     -- Llave primaria
    //     id_tienda INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    //     -- Atributos
    //     nom_tienda VARCHAR(100) NOT NULL,
    //     razon_social VARCHAR(250) NOT NULL,
    //     direccion VARCHAR(250) NOT NULL,
    //     estado BINARY NOT NULL DEFAULT 1
    // );

    // Json enviado desde el front-end por POST en formato json
    // Enviado desde el front-end por POST en formato json:
    // const data = {
    //     products,
    //     total,
    //     dni,
    //     cliente_text
    //   };
    //   // Enviar los datos al servidor a traves del metodo POST
    //   const url = "../tickets/ticket.php";
    //     fetch(url, {
    //         method: "POST",
    //         body: JSON.stringify(data),
    //     })
    //         .then((response) => {
    //         return response.json();
    //         })
    //         .then((data) => {
    //         console.log(data);
    //         })
    //         .catch((error) => {
    //         console.log(error);
    //         });
    // });

    // Obtener los datos enviados desde el front-end
    $data = json_decode(file_get_contents('php://input'), true);
    $products = $data['products'];
    // Products tiene la siguiente estructura: cantidad, descripcion, precio, nom_producto
    $total = $data['total'];
    $dni = $data['dni'];
    $cliente_text = $data['cliente_text'];
    $nombre_usuario = $data['nombre_usuario'];


    $sql = "SELECT nom_tienda, direccion FROM Tienda where estado = 1 and id_tienda = 2;";
    $ruc = '20607742953';
    $nom_tienda = getData($con, $sql, 'nom_tienda');
    $direccion = getData($con, $sql, 'direccion');
    

    $pdf = new PDF_Code128('P','mm',array(80,258));
    $pdf->SetMargins(4,10,4);
    $pdf->AddPage();
    
    # Encabezado y datos de la empresa #
    $pdf->SetFont('Arial','B',10);
    $pdf->SetTextColor(0,0,0);
    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1",strtoupper($nom_tienda)),0,'C',false);
    $pdf->SetFont('Arial','',9);
    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","RUC: $ruc"),0,'C',false);
    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Dirección: $direccion"),0,'C',false);
    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Teléfono: +51 923250210"),0,'C',false);
    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Email: elcordonylarosa@market.com"),0,'C',false);

    $pdf->Ln(1);
    $pdf->Cell(0,5,iconv("UTF-8", "ISO-8859-1","------------------------------------------------------"),0,0,'C');
    $pdf->Ln(5);
    // Una variable que contenga la fecha actual en formato dd/mm/yyyy
    $fechaActual = date("d/m/Y");
    // Una variable que contenga la hora actual en formato hh:mm:ss
    $horaActual = date("h:i:s A");

    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Fecha: ".date("d/m/Y", 
    strtotime($fechaActual))." Hora: ".date("h:i:s A", strtotime($horaActual))),0,'C',false);
    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Caja Nro: 1"),0,'C',false);
    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Cajero: ".$nombre_usuario),0,'C',false);
    $pdf->SetFont('Arial','B',10);
    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1",strtoupper("Ticket Nro: 1242323428")),0,'C',false);
    $pdf->SetFont('Arial','',9);

    $pdf->Ln(1);
    $pdf->Cell(0,5,iconv("UTF-8", "ISO-8859-1","------------------------------------------------------"),0,0,'C');
    $pdf->Ln(5);

    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Cliente: ".$cliente_text),0,'C',false);
    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Documento: DNI: $dni"),0,'C',false);

    $pdf->Ln(1);
    $pdf->Cell(0,5,iconv("UTF-8", "ISO-8859-1","-------------------------------------------------------------------"),0,0,'C');
    $pdf->Ln(3);

    # Tabla de productos #
    $pdf->Cell(10,5,iconv("UTF-8", "ISO-8859-1","Cant."),0,0,'C');
    $pdf->Cell(19,5,iconv("UTF-8", "ISO-8859-1","Precio"),0,0,'C');
    $pdf->Cell(15,5,iconv("UTF-8", "ISO-8859-1","Desc."),0,0,'C');
    $pdf->Cell(28,5,iconv("UTF-8", "ISO-8859-1","Total"),0,0,'C');

    $pdf->Ln(3);
    $pdf->Cell(72,5,iconv("UTF-8", "ISO-8859-1","-------------------------------------------------------------------"),0,0,'C');
    $pdf->Ln(3);



    /*----------  Detalles de la tabla  ----------*/
    // $pdf->MultiCell(0,4,iconv("UTF-8", "ISO-8859-1","Nombre de producto a vender"),0,'C',false);
    // $pdf->Cell(10,4,iconv("UTF-8", "ISO-8859-1","7"),0,0,'C');
    // $pdf->Cell(19,4,iconv("UTF-8", "ISO-8859-1","S/. 10"),0,0,'C');
    // $pdf->Cell(19,4,iconv("UTF-8", "ISO-8859-1","S/. 0.00"),0,0,'C');
    // $pdf->Cell(28,4,iconv("UTF-8", "ISO-8859-1","S/. 70.00"),0,0,'C');
    // $pdf->Ln(4);
    // Hacer bucle
    foreach ($products as $product) {
        $pdf->MultiCell(0,4,iconv("UTF-8", "ISO-8859-1",$product['nom_producto']),0,'C',false);
        $pdf->MultiCell(0,4,iconv("UTF-8", "ISO-8859-1",$product['descripcion']),0,'C',false);
        $pdf->Cell(10,4,iconv("UTF-8", "ISO-8859-1",$product['cantidad']),0,0,'C');
        $pdf->Cell(19,4,iconv("UTF-8", "ISO-8859-1","S/. ".$product['precio']),0,0,'C');
        $pdf->Cell(19,4,iconv("UTF-8", "ISO-8859-1","S/. 0.00"),0,0,'C');
        $pdf->Cell(28,4,iconv("UTF-8", "ISO-8859-1","S/. ".number_format(($product['precio']*$product['cantidad']), 2,'.','')),0,0,'C');
        $pdf->Ln(4);
    }
    $pdf->Ln(7);
    /*----------  Fin Detalles de la tabla  ----------*/



    $pdf->Cell(72,5,iconv("UTF-8", "ISO-8859-1","-------------------------------------------------------------------"),0,0,'C');

        $pdf->Ln(5);

    # Impuestos & totales #
    $pdf->Cell(18,5,iconv("UTF-8", "ISO-8859-1",""),0,0,'C');
    $pdf->Cell(22,5,iconv("UTF-8", "ISO-8859-1","SUBTOTAL"),0,0,'C');
    $pdf->Cell(32,5,iconv("UTF-8", "ISO-8859-1",'S/. '.number_format($total,2,'.','')),0,0,'C');

    $pdf->Ln(5);

    $pdf->Cell(18,5,iconv("UTF-8", "ISO-8859-1",""),0,0,'C');
    $pdf->Cell(22,5,iconv("UTF-8", "ISO-8859-1","IGV (18%)"),0,0,'C');
    $pdf->Cell(32,5,iconv("UTF-8", "ISO-8859-1",number_format($total*0.18,2,'.','')),0,0,'C');

    $pdf->Ln(5);

    $pdf->Cell(72,5,iconv("UTF-8", "ISO-8859-1","-------------------------------------------------------------------"),0,0,'C');

    $pdf->Ln(5);

    $pdf->Cell(18,5,iconv("UTF-8", "ISO-8859-1",""),0,0,'C');
    $pdf->Cell(22,5,iconv("UTF-8", "ISO-8859-1","TOTAL A PAGAR"),0,0,'C');
    $pdf->Cell(32,5,iconv("UTF-8", "ISO-8859-1",'S/. '.number_format($total,2,'.','') ),0,0,'C');

    $pdf->Ln(5);


    $pdf->Cell(18,5,iconv("UTF-8", "ISO-8859-1",""),0,0,'C');
    $pdf->Cell(22,5,iconv("UTF-8", "ISO-8859-1","USTED AHORRA"),0,0,'C');
    $pdf->Cell(32,5,iconv("UTF-8", "ISO-8859-1","S/. 0.00"),0,0,'C');

    $pdf->Ln(10);

    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","*** Precios de productos incluyen impuestos. Para poder realizar un reclamo o devolución debe de presentar este ticket ***"),0,'C',false);

    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(0,7,iconv("UTF-8", "ISO-8859-1","Gracias por su compra"),'',0,'C');

    $pdf->Ln(9);

    # Codigo de barras #
    $pdf->Code128(5,$pdf->GetY(),"COD000001V0001",70,20);
    $pdf->SetXY(0,$pdf->GetY()+21);
    $pdf->SetFont('Arial','',14);
    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","COD000001V0001"),0,'C',false);
    

    # Guardar el archivo PDF en el servidor #
    $path = "./pdf/";
    $pdf->Output($path."ticket.pdf","F");
    // Enviar el path al front-end
    header('Content-Type: application/json');
    echo json_encode(array('path' => $path."ticket.pdf"));
    exit;
    ?>
