-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-02-2026 a las 14:56:30
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
-- Base de datos: `periodismo_utb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`id`, `usuario`, `contrasena`, `nombre`, `email`) VALUES
(1, 'admin', '$2y$10$DsANOkR7tieQ5Oldnp/BJuaDRTQ/uPdeHu.b2tVLbImrXW7cckRcC', 'Administrador', 'admin@utb.edu.ec');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `color` varchar(7) DEFAULT '#1a5fb4',
  `icono` varchar(50) DEFAULT 'fas fa-tag',
  `orden` int(11) DEFAULT 0,
  `estado` enum('activa','inactiva') DEFAULT 'activa',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `noticia_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `comentario` text NOT NULL,
  `fecha_comentario` datetime DEFAULT current_timestamp(),
  `estado` enum('aprobado','pendiente','rechazado') DEFAULT 'pendiente',
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticias`
--

CREATE TABLE `noticias` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion_corta` text NOT NULL,
  `contenido_principal` longtext NOT NULL,
  `imagen_principal` varchar(255) DEFAULT NULL,
  `video_principal` varchar(255) DEFAULT NULL,
  `audio_principal` varchar(255) DEFAULT NULL,
  `link_principal` varchar(500) DEFAULT NULL,
  `link_imagen_principal` varchar(500) DEFAULT NULL,
  `link_video_principal` varchar(500) DEFAULT NULL,
  `link_audio_principal` varchar(500) DEFAULT NULL,
  `link_externo_principal` varchar(500) DEFAULT NULL,
  `descripcion` text NOT NULL,
  `descripcion_larga` longtext DEFAULT NULL,
  `contenido` text NOT NULL,
  `contenido_completo` longtext DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `video_url` varchar(500) DEFAULT NULL,
  `tipo_contenido` enum('texto','video','embed') DEFAULT 'texto',
  `destacada` tinyint(1) DEFAULT 0,
  `vistas` int(11) DEFAULT 0,
  `fecha_publicacion` datetime DEFAULT current_timestamp(),
  `categoria` varchar(100) DEFAULT NULL,
  `autor` varchar(100) DEFAULT 'Estudiante de Periodismo UTB',
  `estado` enum('publicada','borrador') DEFAULT 'publicada',
  `fecha_creacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `noticias`
--

INSERT INTO `noticias` (`id`, `titulo`, `descripcion_corta`, `contenido_principal`, `imagen_principal`, `video_principal`, `audio_principal`, `link_principal`, `link_imagen_principal`, `link_video_principal`, `link_audio_principal`, `link_externo_principal`, `descripcion`, `descripcion_larga`, `contenido`, `contenido_completo`, `imagen`, `video`, `video_url`, `tipo_contenido`, `destacada`, `vistas`, `fecha_publicacion`, `categoria`, `autor`, `estado`, `fecha_creacion`) VALUES
(1, 'Se amplia la fecha de la etapa de inscripción. Se parte de la familia UTB!', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '¡Buenas noticias para aspirantes! La Universidad Técnica de Babahoyo (UTB) ha extendido el plazo de inscripción para su proceso de admisión, brindando más tiempo para que te unas a la familia UTB y asegura tu futuro profesional; ¡no dejes pasar esta oportunidad!. ', NULL, '¡Tu futuro no puede esperar! En respuesta al gran interés de la comunidad, la Universidad Técnica de Babahoyo ha decidido ampliar la fecha de la etapa de inscripción. Queremos que nadie se quede fuera de esta oportunidad de acceder a una formación de excelencia y transformar su vida profesional.\r\nSi aún no has completado tu registro, este es el momento ideal para asegurar tu lugar. Te invitamos a formar parte de nuestra prestigiosa comunidad académica, donde contarás con el respaldo de docentes calificados y una infraestructura diseñada para tu crecimiento.\r\nNo dejes pasar esta oportunidad extendida: ¡Súmate hoy mismo a la familia UTB y construye el camino hacia tu éxito!\r\n', NULL, '6972c5ad66160_1769129389.jpg', NULL, NULL, 'texto', 0, 0, '2026-01-22 18:05:37', 'Educación', 'Estudiante de Periodismo UTB', 'publicada', '2026-01-23 10:37:48'),
(2, 'Trump en Davos y la situación sobre Groenlandia, 21 de enero', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Trump convirtió el Foro Económico Mundial en un agitado debate sobre Groenlandia y la OTAN\r\nEl discurso del presidente Donald Trump en el Foro Económico Mundial expuso su visión sobre la nueva relación de Washington con Europa. En un tono muy combativo, cuestionó el compromiso de la OTAN para defender a EE.UU. y lanzó múltiples amenazas para quienes se oponen a la adquisición de Groenlandia.', NULL, 'Lo que sabemos\r\nAcuerdo sobre Groenlandia: el presidente de Estados Unidos, Donald Trump, dijo que ha “formado el marco de un acuerdo futuro” sobre Groenlandia tras reunión con el jefe de la OTAN, Mark Rutte, en el Foro Económico Mundial en Davos, Suiza. Trump señaló que ya no son necesarios nuevos aranceles para los países europeos que se opusieron a sus ambiciones.\r\nQué incluye el acuerdo: Parte del posible acuerdo incluye renegociar el acuerdo de 1951 que formalizó la presencia militar estadounidense en la isla, según una persona familiarizada con el tema. Por otra parte, un funcionario de la OTAN dijo a CNN que la alianza discutió la posibilidad de que Dinamarca permita a Estados Unidos construir más bases militares en tierras consideradas territorio soberano estadounidense.\r\nDiscurso en Davos: Durante sus declaraciones anteriores, el presidente de Estados Unidos descartó el uso de la fuerza militar para adquirir Groenlandia, al tiempo que redobló su exigencia de control sobre la isla. El presidente también lanzó duras críticas a Europa y promocionó su agenda nacional.\r\nFollow live updates in English here.', '', '6972b8d230a1f_1769126098.jpg', NULL, NULL, 'texto', 0, 0, '2026-01-22 18:54:58', 'Internacional', 'Estudiante de Periodismo UTB', 'publicada', '2026-01-23 10:37:48'),
(3, 'Suecia acoge con satisfacción la retirada de la amenaza de aranceles de Trump', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'La ministra de Asuntos Exteriores de Suecia, Maria Malmer Stenergard, dijo que era bueno que el presidente de EE.UU., Donald Trump, retirara su amenaz', NULL, 'La ministra de Asuntos Exteriores de Suecia, Maria Malmer Stenergard, dijo que era bueno que el presidente de EE.UU., Donald Trump, retirara su amenaza de imponer aranceles a varios países europeos por su apoyo a Groenlandia.\r\n\r\n“Es bueno que Trump haya dado marcha atrás en los aranceles para aquellos de nosotros que hemos apoyado a Dinamarca y Groenlandia”, dijo Stenergard en una publicación en X el miércoles por la noche.\r\n\r\n“Las exigencias sobre mover fronteras han recibido críticas bien merecidas. Por eso también hemos repetido que no vamos a dejarnos chantajear. Parece que nuestro trabajo junto con los aliados ha tenido un impacto”, afirmó.\r\n\r\nTrump había amenazado con imponer aranceles a productos de Dinamarca, Noruega, Suecia, Francia, Alemania, Países Bajos, Finlandia y el Reino Unido en medio de crecientes tensiones en torno al intento del presidente de EE.UU. de anexar la isla ártica.', NULL, '6972c503d112b_1769129219.avif', '', '', 'texto', 1, 0, '2026-01-22 19:46:59', 'Sociedad', 'MORA PAREDES MIGUEL OMAR', 'publicada', '2026-01-23 10:37:48'),
(4, 'El oro se dispara hasta rozar los $5,000 mientras los expertos debaten el bajo rendimiento de bitcoin', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '\"Los anuncios de adopción de [BTC] ya no están funcionando,\" dijo Jim Bianco, mientras que Eric Balchunas de Bloomberg instó a adoptar una perspectiva', NULL, 'Lo que debes saber:\r\nLos metales preciosos se dispararon durante la sesión vespertina en EE. UU. el jueves, llevando al oro a un nuevo récord de $4,930 por onza.\r\nBitcoin continuó rindiendo muy por debajo de lo esperado, cayendo nuevamente a poco más de $89,000.\r\nJim Bianco de Bianco Research sugirió que la narrativa de adopción de bitcoin está perdiendo fuerza, mientras que Eric Balchunas de Bloomberg afirmó que BTC se está desempeñando bien en un marco temporal más largo.', '', '6972d2ea5121f_1769132778.jpg', '', 'https://www.youtube.com/results?search_query=noticia+de+bitcoin+hoy', 'embed', 1, 0, '2026-01-22 20:17:12', 'Educacion', 'MORA PAREDES MIGUEL OMAR', 'publicada', '2026-01-23 10:37:48'),
(5, 'Bombas en un barco y asesinatos: cómo el crimen de Orlando Briones, hermano de alias ‘Mexicano’, incrementó la violencia en Manta', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'El asesinato de Orlando Briones, hermano de Leo Briones, exlíder de Los Lobos, estaría detrás de una nueva escalada de violencia en Manta. La Policía confirmó un sicariato en Leonidas Proaño.', NULL, '', 'El presidente Daniel Noboa dedicó una publicación al correísmo en sus redes sociales la tarde de este lunes, 12 de enero de 2026, en medio de los señalamientos que ha hecho el oficialismo a la oposición por sus presuntos vínculos con el narcotráfico.\r\n\r\nA través de su cuenta en la red social X, el mandatario escribió:\r\n\r\n“Los números los dejan en evidencia, no traten de posicionar algo que ustedes son… Los delincuentes votan por los narcos\".En su publicación, Noboa compartió una imagen con los porcentajes de los resultados en los centros de privación de libertad de los dos últimos procesos electorales: los comicios generales de abril de 2025 y la consulta popular y el referéndum de noviembre de ese mismo año.\r\n\r\nEn el gráfico se observa un 90,78 % y un 96,11 % en favor de la RC en los centros carcelarios.\r\n\r\nEl mensaje de Daniel Noboa refuerza la tesis del oficialismo de que la RC habría recibido financiamiento de organizaciones delictivas.', 'principal_69738bcbcbebb_1769180107.jpg', NULL, NULL, 'texto', 1, 0, '2026-01-23 09:55:07', 'Sociedad', 'MORA PAREDES MIGUEL OMAR', 'publicada', '2026-01-23 10:37:48'),
(6, 'Daniel Noboa afirma que la suscripción del Acuerdo de Facilitación de Inversiones Sostenibles pone a Ecuador en la vanguardia', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Según el mandatario, esto permitirá \"más inversión directa, más empleo para las familias ecuatorianas y reglas claras\".\r\n\r\nNataly Morillo sobre arancele', NULL, 'El presidente de la República, Daniel Noboa, resalta la firma del Acuerdo de Facilitación de Inversiones Sostenibles (SIFA) que concretó con representantes de la Unión Europea (UE) en Bruselas (Bélgica).\r\n\r\n“Ecuador es el primer país de la región en concretar un Acuerdo de Facilitación de Inversiones Sostenibles con la Unión Europea. Más inversión directa, más empleo para las familias ecuatorianas y reglas claras para quienes creen en este país. Hoy nos ponemos a la vanguardia: seguridad jurídica, transparencia y estándares modernos de desarrollo sostenible”, posteó Noboa en sus cuentas de redes sociales, este 23 de enero de 2026.', 'Noboa y una comitiva de ministros se encuentran en una gira internacional en Bruselas (Bélgica). Ahí mantuvo reuniones con empresarios para exponer un portafolio de inversiones en materia energética, minería, hidrocarburos.\r\n\r\nPublicidad\r\n\r\nTambién se reunió con la presidenta del Parlamento Europeo, Roberta Metsola; el primer ministro de Bélgica, Bart De Wever, y el rey Felipe VI de España.\r\n\r\nEstá previsto que retorne al Ecuador el fin de semana, para emprender un nuevo viaje a Panamá, en donde se desarrollará un foro económico, entre el 29 y 30 de enero; y, luego, a inicios de febrero se programa su presencia en Dubái para la Cumbre Mundial de Gobierno. (I)', '6973cb10b6974_1769196304.avif', NULL, NULL, 'texto', 1, 0, '2026-01-23 14:25:04', 'Internacional', 'MORA PAREDES MIGUEL OMAR', 'publicada', '2026-01-23 14:25:04'),
(8, 'Noboa y Correa protagonizan cruce en redes por la captura de Nicolás Maduro: \"terminarás como Noriega\"', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Tras un mensaje de apoyo a la oposición venezolana, el debate escaló a insultos personales y graves denuncias. Mientras el expresidente tildó de \"vasallo\" al mandatario, Noboa respondió llamándolo \"hijo de mula\" y prometió revelar pruebas inminentes de una supuesta colusión entre el correísmo y la banca.', NULL, '', 'El presidente Daniel Noboa y el exmandatario Rafael Correa protagonizaron una pelea en la red social X, motivada por la captura de Nicolás Maduro en manos estadounidenses.\r\n\r\nEn primer lugar, Daniel Noboa publicó un mensaje celebrando lo que calificó como la caída de la \"estructura\" chavista en el continente. En su publicación, Noboa expresó su respaldo directo a los líderes opositores venezolanos María Corina Machado y Edmundo González.\"A todos los criminales narco chavistas les llega su hora. Su estructura terminará de caer en todo el continente\", escribió Noboa, añadiendo: \"Tienen un aliado en Ecuador\".\r\n\r\nLa reacción del expresidente Rafael Correa fue inmediata. Citando el tuit del mandatario, Correa rechazó la postura de Noboa sobre Venezuela, calificando su intervención como una justificación a una agresión contra un \"país soberano\".Correa elevó el tono acusando a Noboa de ser \"el único criminal narco\" y le atribuyó una serie de delitos graves, incluyendo el robo de elecciones y torturas. También recordó la incursión en la Embajada de México en Quito para capturar a Jorge Glas.\r\n\r\n\"Tu alma de vasallo es una traición a la Patria Grande\", sentenció Correa, haciendo alusión a que responde a Estados Unidos, país donde nació.Lejos de ignorar el comentario, Noboa respondió con dureza: \"Empecemos por el hecho de que eres hijo de mula\".Noboa acusó a Correa de permitir que el narcotráfico operara libremente en Ecuador y de vivir con lujos en Bélgica mientras el país enfrenta las consecuencias. Además, el mandatario presentó una lista de siete acusaciones puntuales contra la administración correísta, que van desde la persecución a su familia y el asesinato de periodistas, hasta el financiamiento del régimen de Maduro a su partido.\r\n\r\nNoboa reveló una supuesta investigación en curso que vincularía al exmandatario con el sector financiero: \"Pronto, con pruebas, le mostraremos al país cómo tú, tu gente y un sector de la banca están coludidos en destruir a un país que ya no te necesita\", aseguró Noboa.\r\n\r\n“Terminarás como Noriega y Maduro, anota. Es una promesa”, concluyó Noboa.', 'principal_6973f47c3d5f6_1769206908.jpg', NULL, NULL, 'texto', 1, 0, '2026-01-23 17:21:48', 'Politica', 'MORA PAREDES MIGUEL OMAR', 'publicada', '2026-01-23 17:21:48'),
(9, 'Petroecuador apuesta por perforación y reactivación para revertir caída productiva', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'La estatal busca elevar la producción tras un 2025 marcado por paradas operativas, eventos ambientales y menores exportaciones', NULL, '', 'Quito- Petroecuador presentó a inversionistas internacionales un plan intensivo de inversión, perforación y reactivación de pozos en la cuenca Oriente, con el objetivo de recuperar producción en 2026 y estabilizar la oferta petrolera en el mediano plazo. La información consta en el Offering Circular de la emisión internacional de bonos de Ecuador realizada en enero de 2026, documento legal en el que el Estado detalla riesgos operativos, contexto sectorial y perspectivas del negocio petrolero. La estrategia apunta a llevar la producción a un pico cercano a 390.000 barriles diarios, desde un nivel actual de 367.844 barriles por día.\r\n\r\nEl programa contempla la adjudicación de tres contratos para perforar 36 pozos con siete taladros, con una inversión aproximada de USD 227,5 millones. La movilización de equipos estaba prevista desde el 15 de enero de 2026, con producción incorporándose desde finales de febrero. A esto se suman dos contratos adicionales que serán licitados para 24 pozos con cuatro taladros, por USD 160 millones. En paralelo, Petroecuador extenderá contratos de workover y desplegará siete taladros para reactivar cerca de 200 pozos cerrados tras eventos ambientales recientes, con un presupuesto de USD 25 millones por un año.\r\n\r\nLa hoja de ruta incluye modificaciones contractuales en bloques estratégicos para asegurar crecimiento de largo plazo. Los bloques 52 (Ocano Peña Blanca) y 54 (Eno Ron) extenderán contratos hasta 2040, con producción incremental combinada superior a 8 millones de barriles e inversiones por más de USD 110 millones. El bloque 65 (Pindo) se ampliará hasta 2037. Además, los bloques 14 (Nantu) y 17 (Hormiguero) prevén una producción incremental conjunta de más de 21 millones de barriles, respaldada por compromisos de inversión cercanos a USD 86 millones. En exploración, avanzan el bloque 94 (Tamya) y la ronda Intracampos III, con inversiones exploratorias por USD 359 millones.\r\n\r\nEl plan llega tras un 2025 complejo para la estatal. La producción anual de crudo fue de 127,4 millones de barriles (promedio 349.167 bpd), 8,5% menor interanual. El transporte por el SOTE cayó a 260.557 bpd, afectado por múltiples paradas por mantenimiento, deslizamientos, erosión de ríos, tomas clandestinas y riesgos ambientales. Las exportaciones de crudo Oriente y Napo sumaron 105,6 millones de barriles, 7% menos que en 2024, principalmente por las paralizaciones de oleoductos.\r\n\r\nEn ese contexto, la apuesta por perforación, reactivación y extensiones contractuales busca recomponer volúmenes y caja fiscal. El desafío será la ejecución: cumplir cronogramas, gestionar riesgos ambientales y asegurar financiamiento oportuno para que el plan se traduzca efectivamente en más barriles y mayor resiliencia operativa.', 'principal_6984f86b99ea5_1770322027.jpg', NULL, NULL, 'texto', 1, 0, '2026-02-05 15:07:07', 'Negocios', 'IRVINADONIS MORA PAREDES', 'publicada', '2026-02-05 15:07:07'),
(10, 'Daniel Noboa afirma que la suscripción del Acuerdo de Facilitación de Inversiones Sostenibles pone a Ecuador en la vanguardia', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Daniel Noboa afirma que la suscripción del Acuerdo de Facilitación de Inversiones Sostenibles pone a Ecuador en la vanguardia', NULL, '', 'Daniel Noboa afirma que la suscripción del Acuerdo de Facilitación de Inversiones Sostenibles pone a Ecuador en la vanguardiaDaniel Noboa afirma que la suscripción del Acuerdo de Facilitación de Inversiones Sostenibles pone a Ecuador en la vanguardiaDaniel Noboa afirma que la suscripción del Acuerdo de Facilitación de Inversiones Sostenibles pone a Ecuador en la vanguardia', 'principal_698525db7224d_1770333659.jpg', NULL, NULL, 'texto', 1, 0, '2026-02-05 18:20:59', 'Local/Regional', 'IRVIN MORA PAREDES', 'publicada', '2026-02-05 18:20:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticias_secciones`
--

CREATE TABLE `noticias_secciones` (
  `id` int(11) NOT NULL,
  `noticia_id` int(11) NOT NULL,
  `orden` int(11) DEFAULT 0,
  `tipo` enum('titulo','subtitulo','parrafo','imagen','video','audio','enlace') NOT NULL,
  `contenido` text DEFAULT NULL,
  `media_url` varchar(500) DEFAULT NULL,
  `media_tipo` varchar(50) DEFAULT NULL,
  `media_nombre` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `noticias_secciones`
--

INSERT INTO `noticias_secciones` (`id`, `noticia_id`, `orden`, `tipo`, `contenido`, `media_url`, `media_tipo`, `media_nombre`, `created_at`) VALUES
(1, 5, 0, 'parrafo', 'HOLAALTER TABLE noticias\r\nADD estado ENUM(\'publicada\',\'borrador\') DEFAULT \'publicada\';\r\n', '', '', '', '2026-01-23 09:55:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticias_subtitulos`
--

CREATE TABLE `noticias_subtitulos` (
  `id` int(11) NOT NULL,
  `noticia_id` int(11) NOT NULL,
  `numero_subtitulo` int(11) NOT NULL,
  `subtitulo` varchar(255) NOT NULL,
  `descripcion` longtext NOT NULL,
  `contenido` longtext NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `video` varchar(500) DEFAULT NULL,
  `audio` varchar(500) DEFAULT NULL,
  `link` varchar(500) DEFAULT NULL,
  `orden` int(11) DEFAULT 0,
  `fecha_creacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `noticias_subtitulos`
--

INSERT INTO `noticias_subtitulos` (`id`, `noticia_id`, `numero_subtitulo`, `subtitulo`, `descripcion`, `contenido`, `imagen`, `video`, `audio`, `link`, `orden`, `fecha_creacion`) VALUES
(1, 8, 1, 'Rafael Correa se refiere a Daniel Noboa como ‘Trumpito’ tras anuncio de arancel del 30 % a importaciones colombianas', 'El mensaje de Correa, en tono burlón, estuvo acompañado de una fotografía del presidente Noboa luciendo un traje visiblemente grande.', 'El expresidente Rafael Correa reaccionó este martes, 21 de enero, al anuncio del presidente Daniel Noboa sobre la aplicación de una tasa de “seguridad” del 30 % a las importaciones provenientes de Colombia, medida que el Gobierno ecuatoriano atribuye a la “falta de reciprocidad y acciones firmes” de ese país en la lucha contra el narcotráfico y el crimen organizado.\r\n\r\nA través de su cuenta en la red social X, Correa publicó un mensaje dirigido a Colombia en el que ofreció disculpas y se refirió a Noboa como “Trumpito”, a quien describió como “una miniatura cómica y mal hecha de Trump”. El mensaje, en tono burlón, estuvo acompañado de una fotografía del presidente ecuatoriano luciendo un traje visiblemente grande.\r\n\r\nalmente te pedimos disculpas por este 🤡.\r\nLe vamos a poner «Trumpito»: una miniatura cómica y mal hecha de Trump🤷🏻‍♂️ https://t.co/jlue669Lp9 pic.twitter.com/iVCJAqv8CM\r\n\r\n— Rafael Correa (@MashiRafael) January 21, 2026\r\nLa reacción del exmandatario se dio pocas horas después de que Noboa anunciara que la medida arancelaria entrará en vigencia el 1 de febrero y se mantendrá hasta que, según dijo, exista un compromiso real por parte de Colombia para enfrentar de manera conjunta el narcotráfico y la minería ilegal en la frontera.En una publicación en X, Noboa aseguró que Ecuador ha realizado “esfuerzos reales de cooperación” con Colombia, pese a mantener un déficit comercial que supera los $ 1.000 millones anuales. Sin embargo, afirmó que las fuerzas militares ecuatorianas continúan enfrentando a grupos criminales vinculados al narcotráfico en la frontera “sin cooperación alguna”.\r\n\r\nLa decisión del Gobierno ecuatoriano se produce en un contexto de tensiones políticas entre ambos. Un día antes del anuncio, el presidente colombiano Gustavo Petro calificó al exvicepresidente Jorge Glas como un preso político y señaló que ya cuenta con ciudadanía colombiana, lo que generó reacciones desde el Ejecutivo ecuatoriano.\r\n\r\nNoboa se encuentra actualmente en Suiza, donde participa en el Foro Económico Mundial de Davos. (I)', 'sub_1_6973f47c45d72.jpg', 'https://www.youtube.com/watch?v=FVqdhmyMrBA', '', '', 1, '2026-01-23 17:21:48'),
(2, 8, 2, 'Daniel Noboa le responde a Rafael Correa con una advertencia: Terminarás como Noriega y Maduro, anota', 'Ambos tienen posturas opuestas respecto a la situación de Venezuela.', 'El presidente Daniel Noboa le replicó al exmandatario Rafael Correa. Ambos protagonizan desde la mañana de este sábado, 3 de enero del 2026, un cruce de epítetos alrededor de la situación de Venezuela, sobre la cual tienen criterios opuestos.\r\n\r\n“Empecemos por el hecho de que eres hijo de mula y permitiste que el narcotráfico haga lo que le dé la gana en el Ecuador, problema que hasta el día de hoy combatimos, mientras tú sigues prófugo en Bélgica, viviendo con lujos”, le dijo Noboa a Correa en la red social X.\r\n\r\n“Mi Patria es el Ecuador, por el cual me he jugado la vida una y otra vez. Yo enfrento los problemas, tú huyes de ellos.La gente no es tonta y no se olvida:\r\n\r\n- Perseguiste a mi familia, que lleva más de 80 años dando empleo en el Ecuador\r\n\r\n- Asesinaste periodistas- Silenciaste con la muerte a oficiales de nuestras Fuerzas Armadas\r\n\r\n- Permitiste el financiamiento de narcos y del régimen de Maduro a tu propio partido, que hoy está en decadencia- Regalaste territorios\r\n\r\n- Dejaste que gente cercana a ti pague por tus crímenes\r\n\r\n- Desapareciste gente y destruiste familias\", enlistó el mandatario.\r\n\r\n“Pronto, con pruebas, le mostraremos al país cómo tú, tu gente y un sector de la banca están coludidos en destruir a un país que ya no te necesita y te rechaza”.', 'sub_2_6973f47c471d2.avif', '', '', '', 2, '2026-01-23 17:21:48'),
(3, 8, 3, '“Terminarás como Noriega y Maduro, anota. Es una promesa”, indicó el presidente Noboa.', 'Este posteo fue en respuesta a un mensaje de Correa que le dijo: “El único criminal narco eres tú. Justificar la agresión a un país soberano y latinoamericano demuestra tu pequeñez en todo sentido”.', 'El único criminal narco eres tú. Justificar la agresión a un país soberano y latinoamericano demuestra tu pequeñez en todo sentido. Tú eres el que roba elecciones, viola embajadas, miente, saquea, secuestra y tortura. Te va a pasar lo mismo que a (Lenín) Moreno: anota. Tu alma de vasallo es una traición a la Patria Grande. Sin embargo, en tu descargo, tu servilismo se entiende porque tu verdadera patria -si tienes alguna- es Estados Unidos\".\r\n\r\nCon ese mensaje en su cuenta personal de la red social X es como el expresidente Rafael Correa respondió a un mensaje emitido horas antes por el presidente Daniel Noboa una vez que se conoció de la detención, la madrugada de este sábado, 3 de diciembre, de Nicolás Maduro y su esposa, Cilia Flores.\r\n\r\nEl único criminal narco eres tú. Justificar la agresión a un país soberano y latinoamericano demuestra tu pequeñez en todo sentido.\r\nTú eres el que roba elecciones, viola embajadas, miente, saquea, secuestra y tortura.\r\nTe va a pasar lo mismo que a Moreno: anota.', 'sub_3_6973f6d15cfdb.jpg', 'https://www.youtube.com/watch?v=Hot1ianTteY', '', '', 3, '2026-01-23 17:21:48'),
(4, 9, 1, 'Lluvias devuelven el peso de la generación eléctrica a las hidroeléctricas', 'El embalse de Mazar alcanzó su nivel máximo operativo tras registrar un caudal de 135,41 metros cúbicos por segundo', 'Quito- La matriz eléctrica ecuatoriana registró un cambio en febrero de 2026, cuando la generación hidroeléctrica alcanzó el 87,7 % del total nacional, según datos del Operador Nacional de Electricidad (Cenace). El repunte responde al incremento de las lluvias y a la recuperación de los niveles de los embalses, especialmente el de Mazar.\r\n\r\nEl resultado contrasta con enero, cuando la producción hídrica no superó el 70 % y el país dependió en mayor medida de centrales térmicas a diésel y gas natural, lo que elevó los costos operativos. Además, el escenario coincidió con la suspensión de la importación de energía desde Colombia.\r\n\r\nPrincipales centrales sostienen la producción\r\n\r\nEntre las hidroeléctricas, Coca Codo Sinclair lidera el aporte con el 44 % de la generación hídrica, seguida por el complejo Paute con el 33 % y la central Sopladora con el 7 %. El resto corresponde a otras plantas del país.\r\n\r\nEl mayor aporte hidráulico permitió reducir la participación térmica al 11,7 %, lo que representa un alivio para el sistema eléctrico y menores costos de producción energética.\r\n\r\nRecuperación del embalse Mazar impulsa el sistema\r\n\r\nLa mejora se evidencia en el Complejo Hidroeléctrico Paute, que integra las centrales Mazar, Paute-Molino y Sopladora, con una capacidad conjunta de 1 700 megavatios, suficiente para cubrir más del 30 % de la demanda nacional.\r\n\r\nEl embalse de Mazar alcanzó su nivel máximo operativo tras registrar un caudal de 135,41 metros cúbicos por segundo, muy superior al promedio de enero. Su recuperación permitió estabilizar la generación eléctrica y mantener el suministro sin cortes, incluso tras el fin de las importaciones desde Colombia.', 'sub_1_6984f86ba0119.jpg', '', '', '', 1, '2026-02-05 15:07:07'),
(5, 9, 2, 'Las lluvias alejan, por ahora, el fantasma de los apagones', 'Quito- La situación energética de Ecuador combina preocupación operativa y mejoras incipientes del frente climático. El fin de semana, la Empresa Eléctrica Quito (EEQ) dispuso que las empresas del complejo Ekopark operen con generación eléctrica autónoma entre el 12 y el 16 de enero, de 11:00 a 13:00.', 'Quito- La situación energética de Ecuador combina preocupación operativa y mejoras incipientes del frente climático. El fin de semana, la Empresa Eléctrica Quito (EEQ) dispuso que las empresas del complejo Ekopark operen con generación eléctrica autónoma entre el 12 y el 16 de enero, de 11:00 a 13:00.\r\n\r\nLa instrucción obliga a los usuarios a generar su propia electricidad con grupos electrógenos a diésel, como medida preventiva para reducir la demanda sobre la red. La administración del complejo advirtió, además, que el período podría extenderse según la evolución del sistema. Pedidos similares se habrían cursado a centros comerciales de la capital, coordinados con autoridades.\r\n\r\nEstas medidas llegan en un contexto sensible: el embalse de Mazar, pieza clave del complejo Mazar–Sopladora–Paute que cubre cerca del 28 % de la demanda nacional, registra una caída sostenida desde inicios de diciembre de 2025. El nivel pasó de 2.143 msnm (tope) el 30 de noviembre a 2.137 msnm al 10 de enero de 2026.\r\n\r\nEl umbral crítico se ubica en 2.115 msnm, nivel a partir del cual deben salir de operación, por seguridad, las turbinas de Mazar. La disminución se aceleró en los últimos días, alimentando la cautela operativa, pese a que el Gobierno insiste en que el estiaje será sorteado sin cortes de luz.\r\n\r\nSin embargo, el escenario climático empieza a mostrar señales favorables. Desde el 9 de enero, las precipitaciones han permitido una recuperación gradual de los ríos de Cuenca que alimentan el sistema Paute. El 8 de enero, tres de los cuatro ríos registraban caudales bajos (por debajo de 2 m³/s), una señal preocupante; días después, los niveles comenzaron a mejorar.\r\n\r\nDe acuerdo con ETAPA, la probabilidad de lluvia alcanza 82 % de día y 76 % de noche, y el pronóstico hasta el 18 de enero anticipa tormentas eléctricas y chubascos en el Austro. En línea con ello, la cota de Mazar subió de 2.136,77 msnm (8 de enero) a 2.137,49 msnm (11 de enero), según Celec.\r\n\r\nEl balance es mixto: persisten las tensiones que obligan a gestionar demanda y activar respaldos privados, pero el retorno de las lluvias ofrece un alivio inmediato que, de sostenerse, podría estabilizar el frente hidroeléctrico en las próximas semanas.', 'sub_2_6984f86ba0ddb.jpg', '', '', '', 2, '2026-02-05 15:07:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `suscriptores`
--

CREATE TABLE `suscriptores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `comentario` text DEFAULT NULL,
  `fecha_suscripcion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `suscriptores`
--

INSERT INTO `suscriptores` (`id`, `nombre`, `email`, `comentario`, `fecha_suscripcion`) VALUES
(1, 'irvin adonis mora paredes', 'irvinadonismoraparedes@gmail.com', NULL, '2026-01-23 14:32:15'),
(2, 'MIGUEL OMAR MORA PAREDE', 'irvinadonismoraparedesqc@gmail.com', NULL, '2026-01-23 16:38:41'),
(3, 'MIRIAN VIOLETA PAREDES VELIZ', 'irvinadonismoraparedesvc@gmail.com', 'hola que tal me gustan sus noticias', '2026-01-23 18:11:07'),
(4, 'mora paredes miguel omar', 'irvinadonismoraparedesom@gmail.com', 'hola son muy buenas sus noticias', '2026-01-24 23:07:00');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD KEY `idx_orden` (`orden`),
  ADD KEY `idx_estado` (`estado`);

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_noticia` (`noticia_id`),
  ADD KEY `idx_estado` (`estado`),
  ADD KEY `idx_fecha` (`fecha_comentario`);

--
-- Indices de la tabla `noticias`
--
ALTER TABLE `noticias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `noticias_secciones`
--
ALTER TABLE `noticias_secciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_noticia` (`noticia_id`),
  ADD KEY `idx_orden` (`orden`),
  ADD KEY `idx_tipo` (`tipo`);

--
-- Indices de la tabla `noticias_subtitulos`
--
ALTER TABLE `noticias_subtitulos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_subtitulo` (`noticia_id`,`numero_subtitulo`);

--
-- Indices de la tabla `suscriptores`
--
ALTER TABLE `suscriptores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `noticias`
--
ALTER TABLE `noticias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `noticias_secciones`
--
ALTER TABLE `noticias_secciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `noticias_subtitulos`
--
ALTER TABLE `noticias_subtitulos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `suscriptores`
--
ALTER TABLE `suscriptores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`noticia_id`) REFERENCES `noticias` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `noticias_secciones`
--
ALTER TABLE `noticias_secciones`
  ADD CONSTRAINT `noticias_secciones_ibfk_1` FOREIGN KEY (`noticia_id`) REFERENCES `noticias` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `noticias_subtitulos`
--
ALTER TABLE `noticias_subtitulos`
  ADD CONSTRAINT `noticias_subtitulos_ibfk_1` FOREIGN KEY (`noticia_id`) REFERENCES `noticias` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
