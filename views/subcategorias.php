<?php
session_start();
if (!isset($_SESSION['user_login_status']) || $_SESSION['user_login_status'] != 1) {
  header("location: ../login.php");
  exit;
}

require_once("../config/db.php");
require_once("../config/conexion.php");
require_once("../utils/functions.php");
require_once("../classes/Subcategoria.php");

$subcategoriaManager = new App\Classes\Subcategoria($con);
$id_usuario = $_SESSION['id_usuario'];

$tipos = $subcategoriaManager->listarTiposSubcategoria();
$subcategorias = $subcategoriaManager->listarSubcategorias();

$nombre_tienda = getData($con, "SELECT nom_tienda FROM Tienda;", 'nom_tienda');
$nombres = getData($con, "SELECT nombres FROM persona INNER JOIN usuario ON persona.id_persona = usuario.id_persona WHERE usuario.id_usuario = $id_usuario;", 'nombres');
$tipo_usuario = getData($con, "SELECT nom_tipo_acceso FROM TipoAcceso INNER JOIN usuario ON TipoAcceso.id_tipo_acceso = usuario.id_tipo_acceso WHERE usuario.id_usuario = $id_usuario;", 'nom_tipo_acceso');

include("../utils/vars.php");
$items_nav = getItemsNav($items_nav, 'subcategorias');
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <?php include("../head_module.php"); ?>
  <meta charset="UTF-8">
  <title>Subcategorías | <?php echo $nombre_tienda; ?></title>
  <link rel="stylesheet" href="../css/dashboard.css">
  <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --card-bg: #fff;
      --card-header-bg: #f8f9fa;
      --table-bg: #fff;
      --table-striped-bg: #f2f2f2;
      --text-color: #000;
      --modal-bg: #fff;
      --modal-header-footer-border-color: #dee2e6;
    }

    body.dark {
      --card-bg: #30373f;
      --card-header-bg: #3a424a;
      --table-bg: #3a424a;
      --table-striped-bg: #454d55;
      --text-color: #fff;
      --modal-bg: #30373f;
      --modal-header-footer-border-color: #454d55;
    }

    .card {
      background-color: var(--card-bg);
      color: var(--text-color);
    }

    .card-header {
      background-color: var(--card-header-bg);
    }

    .table {
      background-color: var(--table-bg);
      color: var(--text-color);
    }

    body.dark .table {
      --bs-table-color: var(--text-color);
      --bs-table-bg: var(--table-bg);
      --bs-table-border-color: var(--modal-header-footer-border-color);
      --bs-table-striped-bg: var(--table-striped-bg);
      --bs-table-striped-color: var(--text-color);
      --bs-table-hover-bg: var(--table-striped-bg);
      --bs-table-hover-color: var(--text-color);
    }

    .table-striped>tbody>tr:nth-of-type(odd) {
      --bs-table-accent-bg: var(--table-striped-bg);
    }

    .table-striped>tbody>tr:nth-of-type(even) {
      background-color: var(--table-bg)
    }

    .modal-content {
      background-color: var(--modal-bg);
      color: var(--text-color);
    }

    .modal-header,
    .modal-footer {
      border-bottom-color: var(--modal-header-footer-border-color);
      border-top-color: var(--modal-header-footer-border-color);
    }

    .form-control,
    .form-select {
      background-color: var(--card-bg);
      color: var(--text-color);
      border-color: var(--modal-header-footer-border-color);
    }

    .form-control:focus,
    .form-select:focus {
      background-color: var(--card-bg);
      color: var(--text-color);
    }
  </style>
</head>

<body>
  <nav class="sidebar close">
    <header>
      <div class="image-text">
        <span class="image"><img src=<?= "../" . $_SESSION['imagen'] ?> alt=""></span>
        <div class="text logo-text">
          <span class="name"><?php echo $nombres; ?></span>
          <span class="role"><?php echo $tipo_usuario; ?></span>
        </div>
      </div>
      <i class='bx bx-chevron-right toggle'></i>
    </header>
    <div class="menu-bar">
      <div class="menu">
        <li class="search-box"><i class='bx bx-search icon'></i><input type="text" placeholder="Search..." id="search-sections"></li>
        <?php include("../menu-links_module.php"); ?>
      </div>
      <div class="bottom-content">
        <li class=""><a href="../login.php?logout"><i class='bx bx-log-out icon'></i><span class="text nav-text">Logout</span></a></li>
        <li class="mode">
          <div class="sun-moon"><i class='bx bx-moon icon moon'></i><i class='bx bx-sun icon sun'></i></div>
          <span class="mode-text text">Dark mode</span>
          <div class="toggle-switch"><span class="switch"></span></div>
        </li>
      </div>
    </div>
  </nav>
  <section class="home">
    <div class="text">Gestión de Subcategorías</div>
    <div class="container-fluid pt-4">
      <div class="row">
        <div class="col-md-5">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title mb-0">Tipos de Subcategoría</h5>
              <button class="btn btn-primary btn-sm float-end" style="margin-top: -28px;" data-bs-toggle="modal" data-bs-target="#addTipoModal"><i class="bx bx-plus"></i> Nuevo Tipo</button>
            </div>
            <div class="card-body">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($tipos)) foreach ($tipos as $tipo): ?>
                    <tr>
                      <td><?php echo $tipo['id_subcategoria_tipo']; ?></td>
                      <td><?php echo htmlspecialchars($tipo['nombre_tipo']); ?></td>
                      <td>
                        <button class="btn btn-warning btn-sm edit-tipo-btn" data-id="<?php echo $tipo['id_subcategoria_tipo']; ?>">Editar</button>
                        <button class="btn btn-danger btn-sm delete-tipo-btn" data-id="<?php echo $tipo['id_subcategoria_tipo']; ?>">Eliminar</button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-md-7">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title mb-0">Valores de Subcategorías</h5>
              <button class="btn btn-primary btn-sm float-end" style="margin-top: -28px;" data-bs-toggle="modal" data-bs-target="#addSubcategoriaModal"><i class="bx bx-plus"></i> Nuevo Valor</button>
            </div>
            <div class="card-body">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Valor</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($subcategorias)) foreach ($subcategorias as $sub): ?>
                    <tr>
                      <td><?php echo $sub['id_subcategoria']; ?></td>
                      <td><?php echo htmlspecialchars($sub['nombre_tipo']); ?></td>
                      <td><?php echo htmlspecialchars($sub['valor']); ?></td>
                      <td>
                        <button class="btn btn-warning btn-sm edit-sub-btn" data-id="<?php echo $sub['id_subcategoria']; ?>">Editar</button>
                        <button class="btn btn-danger btn-sm delete-sub-btn" data-id="<?php echo $sub['id_subcategoria']; ?>">Eliminar</button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Modales -->
  <div class="modal fade" id="addTipoModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Añadir Nuevo Tipo</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="formAddTipo">
            <div class="mb-3"><label for="nombre_tipo" class="form-label">Nombre del Tipo</label><input type="text" class="form-control" id="nombre_tipo" name="nombre_tipo" required></div><button type="submit" class="btn btn-primary">Guardar</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="editTipoModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Editar Tipo</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="formEditTipo"><input type="hidden" id="edit_id_tipo" name="id_subcategoria_tipo">
            <div class="mb-3"><label for="edit_nombre_tipo" class="form-label">Nombre del Tipo</label><input type="text" class="form-control" id="edit_nombre_tipo" name="nombre_tipo" required></div><button type="submit" class="btn btn-primary">Actualizar</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="addSubcategoriaModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Añadir Nuevo Valor</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="formAddSubcategoria">
            <div class="mb-3"><label for="id_subcategoria_tipo" class="form-label">Tipo</label><select class="form-select" id="id_subcategoria_tipo" name="id_subcategoria_tipo" required>
                <option value="" disabled selected>Seleccione</option><?php if (!empty($tipos)) foreach ($tipos as $tipo): ?><option value="<?php echo $tipo['id_subcategoria_tipo']; ?>"><?php echo htmlspecialchars($tipo['nombre_tipo']); ?></option><?php endforeach; ?>
              </select></div>
            <div class="mb-3"><label for="valor_subcategoria" class="form-label">Valor</label><input type="text" class="form-control" id="valor_subcategoria" name="valor" required></div><button type="submit" class="btn btn-primary">Guardar</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="editSubcategoriaModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Editar Valor</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="formEditSubcategoria"><input type="hidden" id="edit_id_subcategoria" name="id_subcategoria">
            <div class="mb-3"><label for="edit_id_subcategoria_tipo" class="form-label">Tipo</label><select class="form-select" id="edit_id_subcategoria_tipo" name="id_subcategoria_tipo" required><?php if (!empty($tipos)) foreach ($tipos as $tipo): ?><option value="<?php echo $tipo['id_subcategoria_tipo']; ?>"><?php echo htmlspecialchars($tipo['nombre_tipo']); ?></option><?php endforeach; ?></select></div>
            <div class="mb-3"><label for="edit_valor_subcategoria" class="form-label">Valor</label><input type="text" class="form-control" id="edit_valor_subcategoria" name="valor" required></div><button type="submit" class="btn btn-primary">Actualizar</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/theme-home.js"></script>
  <script src="../js/search_sections.js"></script>
  <script>
    document.getElementById('formAddTipo').addEventListener('submit', function(e) {
      e.preventDefault();
      const t = new FormData(this);
      t.append('action', 'add_tipo'), fetch('../views/apis/subcategoria.php', {
        method: 'POST',
        body: t
      }).then(e => e.json()).then(e => {
        e.success ? location.reload() : alert('Error: ' + (e.message || 'Ocurrió un error.'))
      }).catch(e => alert('Error de conexión.'))
    });
    document.getElementById('formAddSubcategoria').addEventListener('submit', function(e) {
      e.preventDefault();
      const t = new FormData(this);
      t.append('action', 'add_subcategoria'), fetch('../views/apis/subcategoria.php', {
        method: 'POST',
        body: t
      }).then(e => e.json()).then(e => {
        e.success ? location.reload() : alert('Error: ' + (e.message || 'Ocurrió un error.'))
      }).catch(e => alert('Error de conexión.'))
    });
    document.addEventListener('click', function(e) {
      if (e.target.classList.contains('edit-tipo-btn')) {
        const t = e.target.getAttribute('data-id');
        fetch(`../views/apis/subcategoria.php?action=get_tipo&id=${t}`).then(e => e.json()).then(e => {
          e.success && (document.getElementById('edit_id_tipo').value = e.data.id_subcategoria_tipo, document.getElementById('edit_nombre_tipo').value = e.data.nombre_tipo, new bootstrap.Modal(document.getElementById('editTipoModal')).show())
        })
      } else if (e.target.classList.contains('delete-tipo-btn')) {
        const t = e.target.getAttribute('data-id');
        confirm('¿Seguro? Se eliminarán las subcategorías asociadas.') && fetch('../views/apis/subcategoria.php', {
          method: 'POST',
          body: Object.assign(new FormData(), {
            action: 'delete_tipo',
            id_subcategoria_tipo: t
          })
        }).then(e => e.json()).then(e => e.success ? location.reload() : alert('Error: ' + e.message))
      } else if (e.target.classList.contains('edit-sub-btn')) {
        const t = e.target.getAttribute('data-id');
        fetch(`../views/apis/subcategoria.php?action=get_sub&id=${t}`).then(e => e.json()).then(e => {
          e.success && (document.getElementById('edit_id_subcategoria').value = e.data.id_subcategoria, document.getElementById('edit_id_subcategoria_tipo').value = e.data.id_subcategoria_tipo, document.getElementById('edit_valor_subcategoria').value = e.data.valor, new bootstrap.Modal(document.getElementById('editSubcategoriaModal')).show())
        })
      } else if (e.target.classList.contains('delete-sub-btn')) {
        const t = e.target.getAttribute('data-id');
        confirm('¿Seguro?') && fetch('../views/apis/subcategoria.php', {
          method: 'POST',
          body: Object.assign(new FormData(), {
            action: 'delete_subcategoria',
            id_subcategoria: t
          })
        }).then(e => e.json()).then(e => e.success ? location.reload() : alert('Error: ' + e.message))
      }
    });
    document.getElementById('formEditTipo').addEventListener('submit', function(e) {
      e.preventDefault();
      const t = new FormData(this);
      t.append('action', 'edit_tipo'), fetch('../views/apis/subcategoria.php', {
        method: 'POST',
        body: t
      }).then(e => e.json()).then(e => e.success ? location.reload() : alert('Error: ' + e.message))
    });
    document.getElementById('formEditSubcategoria').addEventListener('submit', function(e) {
      e.preventDefault();
      const t = new FormData(this);
      t.append('action', 'edit_subcategoria'), fetch('../views/apis/subcategoria.php', {
        method: 'POST',
        body: t
      }).then(e => e.json()).then(e => e.success ? location.reload() : alert('Error: ' + e.message))
    });
  </script>
</body>

</html>