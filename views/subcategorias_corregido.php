<head>
  <?php include("../head_module.php"); ?>
  <title>Subcategorías | <?php echo $nombre_tienda; ?></title>
  <link rel="stylesheet" href="../css/dashboard.css">
  <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Estilos para integración con el tema oscuro */
    body.dark .card {
      background-color: var(--sidebar-color);
      border: 1px solid var(--border-color);
      color: var(--text-color);
    }

    body.dark .card-header {
      border-bottom: 1px solid var(--border-color);
    }

    body.dark .table {
      --bs-table-bg: var(--sidebar-color);
      --bs-table-striped-bg: var(--primary-color-light);
      --bs-table-color: var(--text-color);
      --bs-table-border-color: var(--border-color);
    }

    body.dark .modal-content {
      background-color: var(--sidebar-color);
      color: var(--text-color);
    }

    body.dark .btn-close {
      filter: invert(1) grayscale(100%) brightness(200%);
    }

    body.dark .form-control,
    body.dark .form-select {
      background-color: var(--primary-color-light);
      border-color: var(--border-color);
      color: var(--text-color);
    }

    body.dark .form-control:focus,
    body.dark .form-select:focus {
      background-color: var(--primary-color-light);
      border-color: var(--primary-color);
      color: var(--text-color);
      box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), .25);
    }
  </style>
</head>

<body>
  <!-- ... (HTML existente) ... -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/theme.js"></script>
  <script src="../js/search_sections.js"></script>
  <script>
    // Sincronizar tema de Bootstrap con el tema del sistema
    const body = document.querySelector('body');
    const applyBootstrapTheme = () => {
      if (body.classList.contains('dark')) {
        document.documentElement.setAttribute('data-bs-theme', 'dark');
      } else {
        document.documentElement.removeAttribute('data-bs-theme');
      }
    };

    const observer = new MutationObserver((mutationsList) => {
      for (const mutation of mutationsList) {
        if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
          applyBootstrapTheme();
        }
      }
    });

    observer.observe(body, {
      attributes: true
    });
    applyBootstrapTheme(); // Aplicar al cargar la página

    // ... (resto del script de la página) ...
  </script>
</body>

</html>