-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS bd_cordon_y_la_rosa;

-- Usar la base de datos
USE bd_cordon_y_la_rosa;

-- Crear la tabla Departamento
CREATE TABLE Departamento (
    -- Llave primaria
    id_departamento INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    -- Atributos
    nombre VARCHAR(50) NOT NULL,
    estado BINARY NOT NULL DEFAULT 1
);

-- Crear la tabla Provincia
CREATE TABLE Provincia (
    -- Llave primaria
    id_provincia INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    -- Llave foranea
    id_departamento INT NOT NULL,
    -- Atributos
    nombre VARCHAR(50) NOT NULL,
    estado BINARY NOT NULL DEFAULT 1,
    FOREIGN KEY (id_departamento) REFERENCES Departamento(id_departamento)
);

-- Crear la tabla Distrito
CREATE TABLE Distrito (
    -- Llave primaria
    id_distrito INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    -- Llave foranea
    id_provincia INT NOT NULL,
    -- Atributos
    nombre VARCHAR(50) NOT NULL,
    estado BINARY NOT NULL DEFAULT 1,
    FOREIGN KEY (id_provincia) REFERENCES Provincia(id_provincia)
);

-- Crear la tabla Persona
CREATE TABLE Persona (
    -- Llave primaria
    id_persona INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    -- Llave foranea
    id_distrito INT,
    -- Atributos
    dni VARCHAR(10) NOT NULL,
    nombres VARCHAR(50) NOT NULL,
    a_paterno VARCHAR(50) NOT NULL,
    a_materno VARCHAR(50) NOT NULL,
    f_nacimiento DATE,
    genero CHAR(1) CHECK(genero IN ('M','F')),
    direccion VARCHAR(50) DEFAULT 'sin direccion',
    n_telefono VARCHAR(12) DEFAULT 'sin telefono',
    estado BINARY NOT NULL DEFAULT 1,
    FOREIGN KEY (id_distrito) REFERENCES Distrito(id_distrito)
);

-- Crear la tabla Empresa
CREATE TABLE Empresa (
    -- Llave primaria
    id_empresa INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    -- Llave foranea
    id_distrito INT,
    -- Atributos
    n_ruc VARCHAR(11) NOT NULL,
    razon_social VARCHAR(250) NOT NULL,
    n_telefono VARCHAR(12) DEFAULT 'sin telefono',
    email VARCHAR(50) DEFAULT 'sin email',
    direccion VARCHAR(50) DEFAULT 'sin direccion',
    fecha_registro DATE DEFAULT CURRENT_DATE(),
    estado BINARY NOT NULL DEFAULT 1,
    FOREIGN KEY (id_distrito) REFERENCES Distrito(id_distrito)
);

-- Crear la tabla Cliente
CREATE TABLE Cliente (
    -- Llave primaria
    id_cliente INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    -- Llave foranea
    id_persona INT,
    id_empresa INT,
    -- Atributos
    fecha_registro DATE DEFAULT CURRENT_DATE(),
    estado BINARY NOT NULL DEFAULT 1,
    FOREIGN KEY (id_persona) REFERENCES Persona(id_persona),
    FOREIGN KEY (id_empresa) REFERENCES Empresa(id_empresa)
);

-- Crear la tabla Tienda
CREATE TABLE Tienda (
    -- Llave primaria
    id_tienda INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    -- Atributos
    nom_tienda VARCHAR(100) NOT NULL,
    razon_social VARCHAR(250) NOT NULL,
    direccion VARCHAR(250) NOT NULL,
    estado BINARY NOT NULL DEFAULT 1
);

-- Crear la tabla Categoria
CREATE TABLE Categoria (
    -- Llave primaria
    id_categoria INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    -- Atributos
    nom_categoria VARCHAR(50) NOT NULL,
    descripcion VARCHAR(250) NOT NULL,
    estado BINARY NOT NULL DEFAULT 1
);

-- Crear la tabla Proveedor
CREATE TABLE Proveedor (
    id_proveedor INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    -- Llave foranea
    id_empresa INT NOT NULL,
    -- Atributos
    fecha_registro DATE NOT NULL DEFAULT CURRENT_DATE(),
    estado BINARY NOT NULL DEFAULT 1,
    FOREIGN KEY (id_empresa) REFERENCES Empresa(id_empresa)
);

-- Crear la tabla Marca
CREATE TABLE Marca (
    -- Llave primaria
    id_marca INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    -- Llave foranea
    id_empresa INT NOT NULL,
    -- Atributos
    nom_marca VARCHAR(50) NOT NULL,
    descripcion VARCHAR(250) NOT NULL,
    estado BINARY NOT NULL DEFAULT 1,
    FOREIGN KEY (id_empresa) REFERENCES Empresa(id_empresa)
);

-- Crear la tabla Producto
CREATE TABLE Producto (
    -- Llave primaria
    id_producto INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    -- Llave foranea
    id_categoria INT NOT NULL,
    id_proveedor INT,
    id_marca INT,
    -- Atributos
    nom_producto VARCHAR(50) NOT NULL,
    imagen VARCHAR(250) NOT NULL,
    descripcion VARCHAR(250) NOT NULL,
    precio DECIMAL(6,2) NOT NULL,
    stock INT NOT NULL,
    fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    estado BINARY NOT NULL DEFAULT 1,
    FOREIGN KEY (id_categoria) REFERENCES Categoria(id_categoria),
    FOREIGN KEY (id_proveedor) REFERENCES Proveedor(id_proveedor),
    FOREIGN KEY (id_marca) REFERENCES Marca(id_marca)
);

-- Crear la tabla TipoAcceso
CREATE TABLE TipoAcceso (
    -- Llave primaria
    id_tipo_acceso INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    -- Atributos
    nom_tipo_acceso VARCHAR(50) NOT NULL,
    estado BINARY NOT NULL DEFAULT 1
);

-- Insertar valores en la tabla TipoAcceso
INSERT INTO TipoAcceso (nom_tipo_acceso) VALUES ('Administrador');
INSERT INTO TipoAcceso (nom_tipo_acceso) VALUES ('Vendedor');
INSERT INTO TipoAcceso (nom_tipo_acceso) VALUES ('Almacenero');

-- Crear la tabla Usuario
CREATE TABLE Usuario (
    -- Llave primaria
    id_usuario INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    -- Llave foranea
    id_tipo_acceso INT NOT NULL,
    id_persona INT NOT NULL,
    -- Atributos
    usuario VARCHAR(250) NOT NULL UNIQUE,
    imagen VARCHAR(250) NOT NULL,
    contrasena VARCHAR(250) NOT NULL,
    estado BINARY NOT NULL DEFAULT 1,
    FOREIGN KEY (id_tipo_acceso) REFERENCES TipoAcceso(id_tipo_acceso),
    FOREIGN KEY (id_persona) REFERENCES Persona(id_persona)
);

-- Crear la tabla MetodoPago
CREATE TABLE MetodoPago (
    -- Llave primaria
    id_metodo_pago INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    -- Atributos
    nom_metodo_pago VARCHAR(50) NOT NULL,
    estado BINARY NOT NULL DEFAULT 1
);

-- Crear la tabla Venta
CREATE TABLE Venta (
    -- Llave primaria
    id_venta INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    -- Llave foranea
    id_cliente INT NOT NULL,
    id_usuario INT NOT NULL,
    id_metodo_pago INT NOT NULL,
    -- Atributos
    fecha_venta DATETIME NOT NULL,
    monto_total DECIMAL(6,2) NOT NULL,
    dscto DECIMAL(6,2) NOT NULL,
    dscto_total DECIMAL(6,2) NOT NULL,
    subtotal DECIMAL(6,2) NOT NULL, 
    total_pagar DECIMAL(6,2) NOT NULL,
    estado BINARY NOT NULL DEFAULT 1,
    FOREIGN KEY (id_cliente) REFERENCES Cliente(id_cliente),
    FOREIGN KEY (id_usuario) REFERENCES Usuario(id_usuario),
    FOREIGN KEY (id_metodo_pago) REFERENCES MetodoPago(id_metodo_pago)
);

-- Crear la tabla DetalleVenta
CREATE TABLE DetalleVenta (
    id_detalle_venta INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    -- Llave foranea
    id_producto INT NOT NULL,
    -- Atributos
    cantidad INT NOT NULL,
    precio_total DECIMAL(6,2) NOT NULL,
    estado BINARY NOT NULL DEFAULT 1,
    FOREIGN KEY (id_producto) REFERENCES Producto(id_producto)
);

-- Crear la tabla Boleta
CREATE TABLE Boleta (
    id_boleta INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    -- Llave foranea
    id_venta INT NOT NULL,
    -- Atributos
    fecha_emision DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    n_boleta VARCHAR(50) NOT NULL,
    serie VARCHAR(50) NOT NULL,
    estado BINARY NOT NULL DEFAULT 1,
    FOREIGN KEY (id_venta) REFERENCES Venta(id_venta)
);

-- Crear la tabla Factura
CREATE TABLE Factura (
    id_factura INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    -- Llave foranea
    id_venta INT NOT NULL,
    -- Atributos
    fecha_emision DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    n_factura VARCHAR(50) NOT NULL,
    serie VARCHAR(50) NOT NULL,
    estado BINARY NOT NULL DEFAULT 1,
    FOREIGN KEY (id_venta) REFERENCES Venta(id_venta)
);

-- Crear la tabla Compra
CREATE TABLE Compra (
    -- Llave primaria
    id_compra INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    -- Llave foranea
    id_proveedor INT NOT NULL,
    id_usuario INT NOT NULL,
    id_metodo_pago INT NOT NULL,
    -- Atributos
    fecha_compra DATETIME NOT NULL,
    monto_total DECIMAL(6,2) NOT NULL,
    dscto DECIMAL(6,2) NOT NULL,
    dscto_total DECIMAL(6,2) NOT NULL,
    subtotal DECIMAL(6,2) NOT NULL,
    total_pagar DECIMAL(6,2) NOT NULL,
    estado BINARY NOT NULL DEFAULT 1,
    FOREIGN KEY (id_proveedor) REFERENCES Proveedor(id_proveedor),
    FOREIGN KEY (id_usuario) REFERENCES Usuario(id_usuario),
    FOREIGN KEY (id_metodo_pago) REFERENCES MetodoPago(id_metodo_pago)
);

-- Crear la tabla DetalleCompra
CREATE TABLE DetalleCompra (
    id_detalle_compra INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    -- Llave foranea
    id_producto INT NOT NULL,
    id_compra INT NOT NULL,
    -- Atributos
    cantidad INT NOT NULL,
    precio_total DECIMAL(6,2) NOT NULL,
    estado BINARY NOT NULL DEFAULT 1,
    FOREIGN KEY (id_producto) REFERENCES Producto(id_producto),
    FOREIGN KEY (id_compra) REFERENCES Compra(id_compra)
);

-- Crear la tabla FacturaCompra
CREATE TABLE FacturaCompra (
    id_factura_compra INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    -- Llave foranea
    id_compra INT NOT NULL,
    -- Atributos
    fecha_emision DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    n_factura VARCHAR(50) NOT NULL,
    serie VARCHAR(50) NOT NULL,
    estado BINARY NOT NULL DEFAULT 1,
    FOREIGN KEY (id_compra) REFERENCES Compra(id_compra)
);
