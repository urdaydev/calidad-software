<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("head_module.php");?>
    <link rel="stylesheet" href="css/sign_up.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Minimarket | Registrate</title>
</head>
<body>
<section class="card">
        <div class="container-switch">
            <input id="checkbox" name="checkbox" type="checkbox">
            <label class="label-switch" for="checkbox">
            </label>
          </div>
        <h1 class="card-title">
            Registrate
        </h1>
        <p class="card-description">
            Ingrese tus datos para registrarte en Minimarket POS
        </p>
        <form class="card-form" action="./request/sign_up.php" method="POST" enctype="multipart/form-data"> 
            
            <div class="form-control-container">
                <div class="form-control">
                    <input type="value" required="" name ="username">
                    <!--Usuario-->
                    <label>
                        <span style="transition-delay:0ms">U</span><span style="transition-delay:50ms">s</span><span style="transition-delay:100ms">u</span><span style="transition-delay:150ms">a</span><span style="transition-delay:200ms">r</span><span style="transition-delay:250ms">i</span><span style="transition-delay:300ms">o</span>
                    </label>
                </div>
                <div class="form-control">
                    <!--Password-->
                    <input type="password" required="" name="password">
                    <label>
                        <span style="transition-delay:0ms">C</span><span style="transition-delay:50ms">o</span><span style="transition-delay:100ms">n</span><span style="transition-delay:150ms">t</span><span style="transition-delay:200ms">r</span><span style="transition-delay:250ms">a</span><span style="transition-delay:300ms">s</span><span style="transition-delay:350ms">e</span><span style="transition-delay:400ms">ñ</span><span style="transition-delay:450ms">a</span>
                    </label>
                </div>
                <div class="form-control">
                    <!--repeat password con espacio-->
                    <input type="password" required="" name="password_repeat">
                    <label>
                        <span style="transition-delay:0ms">R</span><span style="transition-delay:50ms">e</span><span style="transition-delay:100ms">p</span><span style="transition-delay:150ms">e</span><span style="transition-delay:200ms">t</span><span style="transition-delay:250ms">i</span><span style="transition-delay:300ms">r</span><span style="transition-delay:350ms"> </span><span style="transition-delay:400ms">C</span><span style="transition-delay:450ms">o</span><span style="transition-delay:500ms">n</span><span style="transition-delay:550ms">t</span><span style="transition-delay:600ms">r</span><span style="transition-delay:650ms">a</span><span style="transition-delay:700ms">s</span><span style="transition-delay:750ms">e</span><span style="transition-delay:800ms">ñ</span><span style="transition-delay:850ms">a</span>
                    </label>
                </div>
                <div class="form-control">
                    <select name="tipo_usuario" id="tipo_usuario" required="">
                        <option value="1">Administrador</option>
                        <option value="2">Vendedor</option>
                    </select>
                </div>
                <!-- Ingresar imagen -->
                <span class="span-imagen_perfil">
                    Imagen de perfil
                </span>
                <div class="form-control">
                    <input type="file" required="" name="imagen" id="imagen" accept="image/*">
                </div>
                <div class="form-control">
                    <!--DNI-->
                    <input required="" id="dni" name="dni" type="value">
                    <label>
                        <span style="transition-delay:0ms">D</span><span style="transition-delay:50ms">N</span><span style="transition-delay:100ms">I</span>
                    </label>
                </div>
                <div class="form-control">
                    <!--Nombres-->
                    <input type="value" required="" id="nombres" name="nombres">
                    <label>
                        <span style="transition-delay:0ms">N</span><span style="transition-delay:50ms">o</span><span style="transition-delay:100ms">m</span><span style="transition-delay:150ms">b</span><span style="transition-delay:200ms">r</span><span style="transition-delay:250ms">e</span><span style="transition-delay:300ms">s</span>
                    </label>
                </div>
                <div class="form-control">
                    <!--Apellidos-->
                    <input type="value" required="" id="apellidos" name="apellidos">
                    <label>
                        <span style="transition-delay:0ms">A</span><span style="transition-delay:50ms">p</span><span style="transition-delay:100ms">e</span><span style="transition-delay:150ms">l</span><span style="transition-delay:200ms">l</span><span style="transition-delay:250ms">i</span><span style="transition-delay:300ms">d</span><span style="transition-delay:350ms">o</span><span style="transition-delay:400ms">s</span>
                    </label>
                </div>
                <div class="form-control">
                    <input type="value" required="" name="direccion">
                    <!-- Dirección -->
                    <label>
                        <span style="transition-delay:0ms">D</span><span style="transition-delay:50ms">i</span><span style="transition-delay:100ms">r</span><span style="transition-delay:150ms">e</span><span style="transition-delay:200ms">c</span><span style="transition-delay:250ms">c</span><span style="transition-delay:300ms">i</span><span style="transition-delay:350ms">ó</span><span style="transition-delay:400ms">n</span>
                    </label>
                </div>
                <div class="form-control">
                    <!--N de telefono-->
                    <input type="value" required="" id="telefono" name="telefono">
                    <label>
                        <span style="transition-delay:0ms">T</span><span style="transition-delay:50ms">e</span><span style="transition-delay:100ms">l</span><span style="transition-delay:150ms">é</span><span style="transition-delay:200ms">f</span><span style="transition-delay:250ms">o</span><span style="transition-delay:300ms">n</span><span style="transition-delay:350ms">o</span>
                    </label>
                </div>
        
            </div>
            <button class="form-button_sign-in" type="submit">
                Registrate
            </button>
            <div class="form-links">
                <a class="form-link_restore" href=""> 
                    ¿ Olvidaste tu contraseña ? 
                </a>
                <a href="./login.php">
                    ¿ Ya tienes una cuenta ? Inicia sesión
                </a>
            </div>
        </form>
    </section>
    <script src="./js/theme.js"></script>
    <script src="./js/validation_data.js"></script>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const error = urlParams.get('error');
        const errorMessages = {
            'distinct_password': 'Las constraseñas no coinciden',
        };
        
        if (errorMessages[error]) {
            Swal.fire({
                icon: 'error',
                title: errorMessages[error],
                showConfirmButton: false,
                timer: 1500
            })
        }
    </script>
</body>
</html>
