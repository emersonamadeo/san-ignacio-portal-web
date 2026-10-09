-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-10-2026 a las 23:18:09
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bd_san_ignacio`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes_contacto`
--

CREATE TABLE `mensajes_contacto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `asunto` varchar(100) NOT NULL,
  `mensaje` text NOT NULL,
  `estado` varchar(30) DEFAULT 'Pendiente',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `mensajes_contacto`
--

INSERT INTO `mensajes_contacto` (`id`, `nombre`, `email`, `telefono`, `asunto`, `mensaje`, `estado`, `fecha_registro`) VALUES
(1, 'Jonas Rios Ccalle', 'Jonas@gmail.com', '914141414', 'Certificados y Trámites', 'Como es el proceso', 'Pendiente', '2026-10-09 21:14:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticias_eventos`
--

CREATE TABLE `noticias_eventos` (
  `id_noticia` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `categoria` enum('Noticia Institucional','Circular Oficial','Logro Académico','Evento Cívico') NOT NULL,
  `contenido` text NOT NULL,
  `fecha_publicacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `noticias_eventos`
--

INSERT INTO `noticias_eventos` (`id_noticia`, `id_usuario`, `titulo`, `categoria`, `contenido`, `fecha_publicacion`) VALUES
(1, 1, 'Bienvenida al Año Académico 2026 en Sicuani', 'Noticia Institucional', 'La plana directiva de la I.E.P. San Ignacio de Loyola expresa un afectuoso saludo a toda la comunidad educativa al iniciar un nuevo ciclo escolar lleno de retos y aprendizajes.', '2026-09-12 05:48:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(50) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre_rol`, `descripcion`) VALUES
(1, 'Administrador', 'Control global del portal, gestión de cuentas y publicaciones'),
(2, 'Docente', 'Acceso al módulo pedagógico y circulares internas'),
(3, 'Usuario General', 'Padres de familia o apoderados que realizan consultas y trámites de matrícula');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes_admision`
--

CREATE TABLE `solicitudes_admision` (
  `id_solicitud` int(11) NOT NULL,
  `nombre_apoderado` varchar(150) NOT NULL,
  `correo` varchar(120) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `nivel_postulacion` enum('Inicial','Primaria','Secundaria') NOT NULL,
  `grado` varchar(50) NOT NULL,
  `mensaje` text NOT NULL,
  `estado` enum('Pendiente','En Revisión','Atendido') NOT NULL DEFAULT 'Pendiente',
  `fecha_envio` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `solicitudes_admision`
--

INSERT INTO `solicitudes_admision` (`id_solicitud`, `nombre_apoderado`, `correo`, `telefono`, `nivel_postulacion`, `grado`, `mensaje`, `estado`, `fecha_envio`) VALUES
(1, 'Juan Carlos Quispe Mamani', 'jcquispe@gmail.com', '984556677', 'Secundaria', '1° de Secundaria', 'Deseo consultar vacantes disponibles y requisitos de traslado para mi menor hijo.', 'Pendiente', '2026-09-12 05:48:21'),
(2, 'Emerson Amadeo Condori Baca', 'emersonamadeoc@gmail.com', '917444906', 'Inicial', '5 años', 'Quiero que mi hijo estudie en esta institucion', 'Pendiente', '2026-10-09 19:18:39'),
(3, 'Yair emanuel Condori Pachavilca', 'yair@gmail.com', '917444906', 'Inicial', '5 años', 'Es un niño feliz', 'Pendiente', '2026-10-09 20:30:27'),
(4, 'Eimy Pachavilca Condori', 'Eimy@gmail.com', '123456789', 'Inicial', '3 años', 'Quiero vacante para esta edad', 'Pendiente', '2026-10-09 20:33:47'),
(5, 'Rony Padin Mendoza', 'rony@gmail.com', '987878787', 'Primaria', '1° Primaria', 'Necesito mas información', 'Pendiente', '2026-10-09 20:56:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL DEFAULT 3,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `correo` varchar(120) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `id_rol`, `nombres`, `apellidos`, `correo`, `password_hash`, `telefono`, `activo`, `fecha_registro`, `password`) VALUES
(1, 1, 'Director', 'General', 'admin@sanignacio.edu.pe', '$2y$10$eO0V2.K9zO24bZzS5cKLeOQyqg7719M3L6c4T1Vw3n98F1p8L.mQ.', '984123456', 1, '2026-09-12 05:48:21', ''),
(2, 3, 'Emerson Amadeo', 'Condori Baca', '47608149@continental.edu.pe', 'dosmasdos2', '917444906', 1, '2026-09-12 07:52:01', ''),
(3, 3, 'Juan', 'Condori', 'juan.prueba@correo.com', '$2y$10$kl43TW.cYO88YZYoyvB2X.Kj/6jQgBy8VqjMXUVDdWskMmGkx/7FO', '917444906', 1, '2026-09-28 03:10:24', ''),
(4, 3, 'Leidy Pachavilca Gutierrez', '', 'Leidy@gmail.com', '', '917444906', 1, '2026-10-09 20:26:15', '$2y$10$IWtUmCTzIAcX6D9I6GCm7OxtFq53Em619zKhg2pkBleI7/dKNPua.');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `noticias_eventos`
--
ALTER TABLE `noticias_eventos`
  ADD PRIMARY KEY (`id_noticia`),
  ADD KEY `fk_noticias_usuarios` (`id_usuario`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`),
  ADD UNIQUE KEY `nombre_rol` (`nombre_rol`);

--
-- Indices de la tabla `solicitudes_admision`
--
ALTER TABLE `solicitudes_admision`
  ADD PRIMARY KEY (`id_solicitud`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD KEY `fk_usuarios_roles` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `noticias_eventos`
--
ALTER TABLE `noticias_eventos`
  MODIFY `id_noticia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `solicitudes_admision`
--
ALTER TABLE `solicitudes_admision`
  MODIFY `id_solicitud` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `noticias_eventos`
--
ALTER TABLE `noticias_eventos`
  ADD CONSTRAINT `fk_noticias_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_roles` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
