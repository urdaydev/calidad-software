<?php
// checking for minimum PHP version
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
    require_once("libraries/password_compatibility_library.php");
}

// include the configs / constants for the database connection
require_once("config/db.php");

// load the login class
require_once("classes/Login.php");

// create a login object. when this object is created, it will do all login/logout stuff automatically
// so this single line handles the entire login process. in consequence, you can simply ...
$login = new Login();
$sucess = $_GET['success'] ?? null;

// ... ask if we are logged in here:
if ($login->isUserLoggedIn() == true) {
    // the user is logged in. you can do whatever you want here.
    // for demonstration purposes, we simply show the "you are logged in" view.
   header("location: ./views/home.php");

} else {
    // the user is not logged in. you can do whatever you want here.
    // for demonstration purposes, we simply show the "you are not logged in" view.
    ?>
	<!DOCTYPE html>
	<head>
		<!-- Traer el head.php -->
		<?php include("head_module.php");?>
		<!-- Agregar el titulo -->
		<link rel="stylesheet" href="./css/login.css">
		<title>Minimarket | Iniciar sesión</title>
        
	</head>
	<body>
    <div class="loading-page">

        <div class="container">
            <div class="item" style="--i:0;"></div>
            <div class="item" style="--i:1;"></div>
            <div class="item" style="--i:2;"></div>
            <div class="item" style="--i:3;"></div>
            <div class="item" style="--i:4;"></div>
            <div class="item" style="--i:5;"></div>
            <div class="item" style="--i:6;"></div>
            <div class="item" style="--i:7;"></div>
            <div class="item" style="--i:8;"></div>
            <div class="item" style="--i:9;"></div>
            <div class="item" style="--i:10;"></div>
            <div class="item" style="--i:11;"></div>
            <div class="item" style="--i:12;"></div>
            <div class="item" style="--i:13;"></div>
            <div class="item" style="--i:14;"></div>
            <div class="item" style="--i:15;"></div>
            <div class="item" style="--i:16;"></div>
            <div class="item" style="--i:17;"></div>
            <div class="item" style="--i:18;"></div>
            <div class="item" style="--i:19;"></div>
            <div class="item" style="--i:20;"></div>
        </div>
    </div>
	<section class="card">
        <div class="container-switch">
            <input id="checkbox" name="checkbox" type="checkbox">
            <label class="label-switch" for="checkbox">
            </label>
          </div>
        <div class="card-logo">
        </div>
        <h1 class="card-title">
            Minimarket
        </h1>
        <p class="card-description">
            Ingrese tus datos para iniciar sesión
        </p>
        <form class="card-form" action="login.php" method="post" name="loginform">
            <div class="form-input-container">
                <div class="input-container_username">
                    <span class="input-icon_username"></span>
                    <input type="text" placeholder="Username" name="usuario">
                </div>
                <div class="input-container_password">
                    <span class="input-icon_password"></span>
                    <input type="password" placeholder="Password" name="constrasena">
                </div>
            </div>
            <button class="form-button_login" type="submit" name="login" value="Log in">
                Iniciar sesión
            </button>
            <div class="form-links">
                <a class="form-link_restore" href=""> 
                    ¿ Olvidaste tu contraseña ? 
                </a>
                <a class="form-link_sign-in" href="./sign_up.php">
                    ¿ No tienes una cuenta ? Registrate
                </a>
            </div>
        </form>
    </section>
	</body>
    <script src="./js/loading_page.js"></script>
	<script src="./js/theme.js"></script>
    <?php
				// show potential errors / feedback (from login object)
				if (isset($login)) {
					if ($login->errors) {
						?>
						<script>
						
						<?php 
						foreach ($login->errors as $error) {
							echo 'Swal.fire({
							  type: "error",
							  title: "Oops...",
							  text: "'.$error.'",
                              confirmButtonText: "Aceptar",
							});';
						}
						?>
						</script>
						<?php
					}
					if ($login->messages) {
						?>
						<script>
						<?php
						foreach ($login->messages as $message) {
							echo 'Swal.fire({
							  type: "success",
							  title: "Hola",
							  text: "'.$message.'",
                              confirmButtonText: "Aceptar",
							});';
						}
						?>
						</script>
						<?php 
					}
				}
                if(isset($sucess)){
                    if ($sucess == 1) {
                        ?>
                        <script>
                        <?php
                        echo 'Swal.fire({
                          type: "success",
                          title: "Bienvenido",
                          text: "Usuario registrado correctamente",
                          confirmButtonText: "Aceptar",
                        });';
                        ?>
                        </script>
                        <?php 
                    }
                }
	?>
    </html>
    <?php


}
