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
    
    $sql = "SELECT * FROM proveedor  inner join empresa on proveedor.id_empresa = empresa.id_empresa where proveedor.estado = 1";
    
    $query = mysqli_query($con, $sql);
    $id_usuario = $_SESSION['id_usuario'];
    $nombre_tienda = getData($con, "SELECT nom_tienda FROM Tienda;", 'nom_tienda');
    $nombres = getData($con, "SELECT nombres FROM persona inner join usuario on persona.id_persona = usuario.id_persona where usuario.id_usuario = $id_usuario;", 'nombres');
    $tipo_usuario = getData($con, "SELECT nom_tipo_acceso FROM TipoAcceso inner join usuario on TipoAcceso.id_tipo_acceso = usuario.id_tipo_acceso where usuario.id_usuario = $id_usuario;", 'nom_tipo_acceso');


    include("../utils/vars.php");

    $items_nav = getItemsNav($items_nav, 'proveedores');
?>
<!DOCTYPE html>
<html lang="es">
<head>
		<?php include("../head_module.php");?>
		<title> Proveedores | Minimarket</title>
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
	<section class="home">
        <div class="text">
			<?php
				echo $nombre_tienda;
			?>
		</div>
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h3 class="title">Proveedores</h3>
                    <div class="search">
                        <span class="bx bx-search"></span>
                        <input type="text" placeholder="Buscar..." id="search-rows">
                    </div>
                    <div class="btn-container">
                        <a class="btn btn-add">
                            <span class="text">
                                Nueva Proveedor
                            </span>
                            <i class='bx bx-plus'></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Razón social</th>
                                <th>Telefono</th>
                                <th>Correo</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="tbody-rows">
                            <?php while ($row = mysqli_fetch_array($query)): ?>
                                <tr>
                                    <td><?= $row['id_proveedor'] ?></td>
                                    <td><?= $row['razon_social']?></td>
                                    <td><?= $row['n_telefono']?></td>
                                    <td><?= $row['email']?></td>
                                    <td><a class="proveedor-update btn-edit" >Editar</a></td>
                                    <td><a href="./delete/proveedor.php?id=<?= $row['id_proveedor'] ?>"
                                    class="proveedor-delete" >Eliminar</a></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <div class="modal modalCreate">
            <div class="modal-content">
                <div class="header">
                    <span class="title">Crear Proveedor</span>
                    <span class="close">&times;</span>
                </div>
                <div class="body">
                    <form action="./create/proveedor.php" method="POST" class="form">
                        <div class="form-control">
                            <label for="">RUC</label>
                            <input type="text" placeholder="Ingrese su RUC" required name="ruc" id="ruc">
                        </div>
                        <div class="form-control">
                            <label for="">Razón social</label>
                            <input type="text" placeholder="Razón social" required name="razon_social" id="razon_social">
                        </div>
                        <div class="form-control">
                            <label for="">Teléfono</label>
                            <input type="text" placeholder="Teléfono" required name="telefono" id="telefono">
                        </div>
                        <div class="form-control">
                            <label for="">Correo</label>
                            <input type="text" placeholder="Correo" required name="correo" id="correo">
                        </div>
                        <div class="form-control">
                            <label for="">Dirección</label>
                            <input type="text" placeholder="Dirección" required name="direccion" id="direccion">
                        </div>
                        <div class="btns-container">
                            <btn class="btn btn-cancel">Cancelar</btn>
                            <input type="submit" class="btn btn-add" value="Crear" id="btn-create">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal modalUpdate">
            <div class="modal-content">
                <div class="header">
                    <span class="title">Editar Proveedor</span>
                    <span class="close">&times;</span>
                </div>
                <div class="body">
                    <form class="form">
                        <div class="form-control">
                            <label for="">RUC</label>
                            <input type="text" placeholder="Ingrese su RUC" required name="ruc" id="ruc">
                        </div>
                        <div class="form-control">
                            <label for="">Razón social</label>
                            <input type="text" placeholder="Razón social" required name="razon_social" id="razon_social">
                        </div>
                        <div class="form-control">
                            <label for="">Teléfono</label>
                            <input type="text" placeholder="Teléfono" required name="telefono" id="telefono">
                        </div>
                        <div class="form-control">
                            <label for="">Correo</label>
                            <input type="text" placeholder="Correo" required name="correo" id="correo">
                        </div>
                        <div class="form-control">
                            <label for="">Dirección</label>
                            <input type="text" placeholder="Dirección" required name="direccion" id="direccion">
                        </div>
                        <div class="btns-container">
                            <btn class="btn btn-cancel">Cancelar</btn>
                            <input type="button" class="btn btn-add" value="Actualizar" id="btn-update">
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </section>
	</body>
	<script src="../js/theme-home.js">
	</script>
    <script src="../js/search_sections.js">
    </script>
    <script src="../modal-js/modal.js">
    </script>
    <script>
        // Alerts del servidor
        const urlParams = new URLSearchParams(window.location.search);
        const success = urlParams.get('success');
        const error = urlParams.get('error');
        const successMessages = {
            'create': 'Proveedor creado exitosamente',
            'update': 'Proveedor actualizado exitosamente',
            'delete': 'Proveedor eliminado exitosamente'
        };
        const errorMessages = {
            'emptyField': 'Por favor, llene todos los campos',
            'exist': 'El proveedor ya esta registrado',
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
    <script src="../modal-js/data_proveedores.js"></script>
    <script src="../js/search_rows.js"></script>
    <script>
        const inputRucCreate = document.querySelector('.modalCreate #ruc');
        // change event
        inputRucCreate.addEventListener('change', (e) => {
            const ruc = e.target.value;
            const rucClean = cleanString(ruc);
            const token = 'token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Im1hcmNhbG9heXphZUBnbWFpbC5jb20ifQ.nz_IvsXd6uWlpSzS6YQyx0TDVSQFpkHYqp0E5o2jf10';
            const apiUrl = `https://dniruc.apisperu.com/api/v1/ruc/${rucClean}?${token}`;
            if (rucClean.length === 11) {
                fetch(apiUrl)
                    .then(response => response.json())
                    .then(data => {
                        const { ruc, razonSocial, direccion } = data;
                        document.querySelector('.modalCreate #ruc').value = ruc || '';
                        document.querySelector('.modalCreate #razon_social').value = razonSocial || '';
                        document.querySelector('.modalCreate #direccion').value = direccion || '';
                        if(!ruc||!razonSocial||!direccion){
                            Swal.fire({
                                icon: 'error',
                                title: 'RUC no encontrado',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                    })
                    .catch(error => {
                        console.log(error);
                });
            }
        });

        const inputRucUpdate = document.querySelector('.modalUpdate #ruc');
        // change event
        inputRucUpdate.addEventListener('change', (e) => {
            const ruc = e.target.value;
            const rucClean = cleanString(ruc);
            
        });
        // funcion para quitar los espacios en blanco, puntos y comas
        function cleanString(string) {
            string = string.replace(/\s/g, '');
            string = string.replace(/\./g, '');
            string = string.replace(/\,/g, '');
            return string;
        }
    </script>
</html>