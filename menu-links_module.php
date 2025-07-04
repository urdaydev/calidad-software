<?php
$home = $items_nav['home'] ?? '';
$categorias = $items_nav['categorias'] ?? '';
$proveedores = $items_nav['proveedores'] ?? '';
$productos = $items_nav['productos'] ?? '';
$clientes = $items_nav['clientes'] ?? '';
$ventas = $items_nav['ventas'] ?? '';
$vencimientos = $items_nav['vencimientos'] ?? '';

?>


<ul class="menu-links">
    <li class="nav-link <?= $home; ?>">
        <a href="./home.php">
            <i class='bx bx-home-alt icon'></i>
            <span class="text nav-text">Pantalla de inicio </span>
        </a>
    </li>
    <li class="nav-link <?= $categorias; ?>">
        <a href="./categorias.php">
            <i class='bx bx-list-ul icon'></i>
            <span class="text nav-text">Categorias</span>
        </a>
    </li>

    <li class="nav-link <?= $proveedores; ?>">
        <a href="./proveedores.php">
            <i class='bx bx-group icon'></i>
            <span class="text nav-text">Proveedores</span>
        </a>
    </li>

    <li class="nav-link <?= $productos; ?>">
        <a href="./productos.php">
            <i class='bx bx-cart icon'></i>
            <span class="text nav-text">Productos</span>
        </a>
    </li>

    <li class="nav-link <?= $clientes; ?>">
        <a href="./clientes.php">
            <i class='bx bx-user icon'></i>
            <span class="text nav-text">Clientes</span>
        </a>
    </li>

    <li class="nav-link <?= $ventas; ?>">
        <a href="./ventas.php">
            <i class='bx bx-store-alt icon'></i>
            <span class="text nav-text">Ventas</span>
        </a>
    </li>

    <li class="nav-link <?= $vencimientos; ?>">
        <a href="./vencimientos.php">
            <i class='bx bx-calendar icon'></i>
            <span class="text nav-text">Vencimientos</span>
        </a>
    </li>
</ul>