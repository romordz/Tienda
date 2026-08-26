Create database tienda;
use tienda;

select *from usuarios;
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    correo VARCHAR(255) UNIQUE NOT NULL,
    nombre_usuario VARCHAR(50) UNIQUE NOT NULL,
    contraseña VARCHAR(255) NOT NULL,
    rol ENUM('vendedor', 'cliente', 'administrador') NOT NULL,
    avatar BLOB,
    nombre_completo VARCHAR(255) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    sexo ENUM('masculino', 'femenino', 'otro') NOT NULL,
    fecha_ingreso TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    privacidad ENUM('público', 'privado') NOT NULL
);
select *from categorias;
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    usuario_creador INT,
    FOREIGN KEY (usuario_creador) REFERENCES usuarios(id)
);

select *from productos;
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    imagenes BLOB,
    video JSON,
    categoria_id INT,
    para_cotizar BOOLEAN NOT NULL,
    precio DECIMAL(10, 2) NULL,
    cantidad_disponible INT NOT NULL,
    likes INT DEFAULT 0,
    dislikes INT DEFAULT 0,
    estado ENUM('pendiente', 'aprobado') DEFAULT 'pendiente',
    aprobado_por INT,
    vendedor_id INT,
    imagenes_json TEXT,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id),
    FOREIGN KEY (aprobado_por) REFERENCES usuarios(id)
);

ALTER TABLE productos MODIFY COLUMN precio DECIMAL(10, 2) NULL;
ALTER TABLE productos MODIFY COLUMN imagenes_json LONGTEXT;
ALTER TABLE productos ADD CONSTRAINT fk_aprobado_por FOREIGN KEY (aprobado_por) REFERENCES usuarios(id);

select *from valoraciones;
CREATE TABLE valoraciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    producto_id INT,
    valoracion ENUM('like', 'dislike') NOT NULL,
    UNIQUE (usuario_id, producto_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

select * from mensajes;
CREATE TABLE mensajes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    conversacion_id INT,
    usuario_id INT,
    mensaje TEXT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    producto_id INT NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (conversacion_id) REFERENCES conversaciones(id)
);
ALTER TABLE mensajes ADD COLUMN producto_id INT NOT NULL;
                           
select * from conversaciones;
CREATE TABLE conversaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT,
    comprador_id INT,
    vendedor_id INT,
    fecha_inicio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (producto_id) REFERENCES productos(id),
    FOREIGN KEY (comprador_id) REFERENCES usuarios(id),
    FOREIGN KEY (vendedor_id) REFERENCES usuarios(id)
);

select *from comentarios;
CREATE TABLE comentarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT,
    usuario_id INT,
    comentario TEXT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('activo', 'eliminado') DEFAULT 'activo',
    FOREIGN KEY (producto_id) REFERENCES productos(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

ALTER TABLE comentarios ADD COLUMN estado ENUM('activo', 'eliminado') DEFAULT 'activo';

select *from listas;
CREATE TABLE listas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    nombre_lista VARCHAR(100) NOT NULL,
    descripcion TEXT,
    imagenes JSON,
    privacidad ENUM('pública', 'privada') NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

select *from lista_productos;
CREATE TABLE lista_productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lista_id INT NOT NULL,
    producto_id INT NOT NULL,
    estado ENUM('activo', 'eliminado') DEFAULT 'activo',
    FOREIGN KEY (lista_id) REFERENCES listas(id),
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

ALTER TABLE lista_productos ADD COLUMN estado ENUM('activo', 'eliminado') DEFAULT 'activo';

select *from compras;
CREATE TABLE compras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    fecha_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10, 2),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

select *from detalles_compras;
CREATE TABLE detalles_compras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    compra_id INT,
    producto_id INT,
    cantidad INT NOT NULL,
    FOREIGN KEY (compra_id) REFERENCES compras(id),
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

select *from tarjetas;
CREATE TABLE tarjetas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    numero_tarjeta VARCHAR(16) NOT NULL,
    nombre_tarjeta VARCHAR(255) NOT NULL,
    fecha_vencimiento DATE NOT NULL,
    cvv VARCHAR(3) NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

