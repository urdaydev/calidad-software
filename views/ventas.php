<?php

	session_start();
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
        header("location: login.php");
		exit;
        }

	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
    require_once ("../utils/functions.php");
    

    $id_usuario = $_SESSION['id_usuario'];
    $nombre_tienda = getData($con, "SELECT nom_tienda FROM Tienda;", 'nom_tienda');
    $nombres = getData($con, "SELECT nombres FROM persona inner join usuario on persona.id_persona = usuario.id_persona where usuario.id_usuario = $id_usuario;", 'nombres');
    $nombres_completos = "SELECT nombres, a_paterno, a_materno FROM persona inner join usuario on persona.id_persona = usuario.id_persona where usuario.id_usuario = $id_usuario;";
    $nombres_completos = mysqli_query($con,$nombres_completos);
    $nombres_completos = mysqli_fetch_array($nombres_completos);
    $nombres_completos = $nombres_completos['nombres']." ".$nombres_completos['a_paterno']." ".$nombres_completos['a_materno'];
    $tipo_usuario = getData($con, "SELECT nom_tipo_acceso FROM TipoAcceso inner join usuario on TipoAcceso.id_tipo_acceso = usuario.id_tipo_acceso where usuario.id_usuario = $id_usuario;", 'nom_tipo_acceso');
    include("../utils/vars.php");
    $items_nav = getItemsNav($items_nav, 'ventas');
    $sql = "SELECT producto.nom_producto, producto.precio, producto.imagen, producto.descripcion, categoria.nom_categoria, producto.id_producto
    FROM producto inner join categoria on producto.id_categoria = categoria.id_categoria where producto.estado = 1;";
    $query = mysqli_query($con,$sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
		<?php include("../head_module.php");?>
		<title> Ventas | Minimarket</title>
		<!----======== CSS ======== -->
		<link rel="stylesheet" href="../css/dashboard.css">
    	<!----===== Boxicons CSS ===== -->
		<link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
	</head>
	<body>
	<nav class="sidebar close">
        <header>
            <div class="image-text">
                <span class="image">
                    <!--<img src="logo.png" alt="">-->
                    <img src=<?="../".$_SESSION['imagen'] ?> alt="">
                </span>

                <div class="text logo-text">
                    <span class="name">
						<?php
							echo $nombres;
						?>
					</span>
                    <span class="role">
						<?php
							echo $tipo_usuario;
						?>

					</span>
                </div>
            </div>
            <i class='bx bx-chevron-right toggle'></i>
        </header>

        <div class="menu-bar">
            <div class="menu">

                <li class="search-box">
                    <i class='bx bx-search icon'></i>
                    <input type="text" placeholder="Search..." id="search-sections">
                </li>

                <?php include("../menu-links_module.php");?>

            <div class="bottom-content">
                <li class="">
                    <a href="../login.php?logout">
                        <i class='bx bx-log-out icon' ></i>
                        <span class="text nav-text">Logout</span>
						
                    </a>
                </li>

                <li class="mode">
                    <div class="sun-moon">
                        <i class='bx bx-moon icon moon'></i>
                        <i class='bx bx-sun icon sun'></i>
                    </div>
                    <span class="mode-text text">Dark mode</span>

                    <div class="toggle-switch">
                        <span class="switch"></span>
                    </div>
                </li>
                
            </div>
        </div>
    </nav>
	<section class="home ventas">
        <div class="text">
			<?php
				echo $nombre_tienda;
			?>
		</div>
        <div class="container">

            <div class="container__productos">
                <h3 class="title">Ventas</h3>
                <div class="search">
                    <span class="bx bx-search"></span>
                    <input type="text" placeholder="Buscar..." id="search-cards-productos">
                </div>
                <!-- select de categorias -->
                <select name="categorias" id="selectCategorias">
                    <option value="0">Todas las categorías</option>
                    <?php
                        $sqlCategoria = "SELECT * FROM categoria where estado = 1;";
                        $queryCategorias = mysqli_query($con,$sqlCategoria);
                        while($row = mysqli_fetch_array($queryCategorias)){
                            echo '<option value="'.$row['id_categoria'].'">'.$row['nom_categoria'].'</option>';
                        }
                    ?>
                </select>
                <h3 class="cards-title">
                        Productos
                </h3>
                <div class="cards-container">
                </div>
            </div>
            <div class="cart">
                <div class="cart-icon-container open-cart">
                    <p class="cart-icon-container__text quantity-products">
                        0
                    </p>
                    <i class='bx bx-cart cart-icon'></i>
                </div>
                <div class="content-cart">
                    <i class='bx bx-x close-cart'></i>
                        <i class='bx bx-cart cart-icon icon-empty_card'></i>
                        </i>
                        <p class="content-cart__text empty-card_text">
                            No hay productos en el carrito
                        </p>

                    <div class="content-cart__input-container inactive">
                        <input type="number" min="0" placeholder="DNI del cliente" class="content-cart__input" id="dni-cliente"> 
                    </div>
                    <p class="content-cart__client-text inactive dni-container-text">
                        Cliente: <span class="content-cart__client"></span>
                    </p>
                    <div class="content-cart__products">

                    </div>
                    <p class="content-cart__total inactive" id="total_price_sale">
                        Total a pagar: S/ 20.00
                    </p>
                        <button class="btn btn-primary btn-register-sale inactive" id="btnRegisterSale">
                            Registrar venta
                        </button>
                </div>
            </div>
        </div>
    </section>
	</body>
    <script>
        var nombre_usuario = "<?php echo $nombres_completos; ?>";
        
    </script>
	<script src="../js/theme-home.js">
	</script>
    <script src="../js/search_sections.js">
    </script>
    <script>
        // Alerts del servidor
        const urlParams = new URLSearchParams(window.location.search);
        const success = urlParams.get('success');
        const error = urlParams.get('error');
        const successMessages = {
            'create': 'Venta creada exitosamente',
            'update': 'Venta actualizada exitosamente',
            'delete': 'Venta eliminada exitosamente'
        };
        const errorMessages = {
            'emptyField': 'Por favor, llene todos los campos',
            'exist': 'La categoría ya existe',
            'dbError': 'Error al conectar con la base de datos'
        };

        if (successMessages[success]) {
            Swal.fire({
                icon: 'success',
                title: successMessages[success],
                showConfirmButton: false,
                timer: 1500
            })
        }

        if (errorMessages[error]) {
            Swal.fire({
                icon: 'error',
                title: errorMessages[error],
                showConfirmButton: false,
                timer: 1500
            })
        }
    </script>
    <script src="../js/test.js"></script>
    <script type="module" src="../js/ventas.js"></script>
    <script src="../js/validation_dni.js"></script>
</html>