# 🗞️ Portal Informativo - Periodismo UTB

## Sistema Completo de Noticias con Multimedia y Subtítulos

---

## 📋 Tabla de Contenidos

1. [Características Principales](#características-principales)
2. [Instalación y Configuración](#instalación-y-configuración)
3. [Guía de Uso](#guía-de-uso)
4. [Estructura de Base de Datos](#estructura-de-base-de-datos)
5. [Archivos Creados](#archivos-creados)
6. [URLs y Accesos](#urls-y-accesos)

---

## ✨ Características Principales

### Para Noticias Principales:
- ✅ **Título Principal**
- ✅ **Descripción Corta** (100-500 caracteres)
- ✅ **Descripción Larga** (mínimo 500 caracteres)
- ✅ **Contenido Principal** (mínimo 500 caracteres)
- ✅ **Contenido Completo** (mínimo 5000 caracteres)
- ✅ **Multimedia Principal**: Imagen, Video, Audio, Link

### Para Subtítulos (5 mínimo):
Cada subtítulo incluye:
- ✅ **Título del Subtítulo**
- ✅ **Descripción** (mínimo 100 caracteres)
- ✅ **Contenido** (mínimo 500 caracteres)
- ✅ **Multimedia**: Imagen, Video, Audio, Link

### Interfaz de Usuario:
- ✅ Icono de **ojo** "Ver Más" para acceder a noticia completa
- ✅ Visualización organizada de subtítulos
- ✅ Reproducción de audio embebida
- ✅ Links a videos externos (YouTube, Vimeo, etc.)
- ✅ Interfaz responsiva (móvil, tablet, desktop)

---

## 🚀 Instalación y Configuración

### Paso 1: Ejecutar Migración de Base de Datos

```
URL: http://localhost:3000/migracion_multimedia.php
```

Este script automáticamente:
1. ✅ Crea campos en tabla `noticias`
2. ✅ Crea tabla `noticias_subtitulos`
3. ✅ Crea carpetas de almacenamiento
4. ✅ Verifica conexión a MySQL

**Estado esperado:** Todos los mensajes en verde ✓

---

## 📝 Guía de Uso

### Crear una Noticia Completa

**Acceso:**
```
Panel Admin → Crear Noticia Completa
URL: http://localhost:3000/admin/crear_noticia_completa.php
```

**Campos a completar:**

#### 1️⃣ Información Principal
- Título (requerido)
- Categoría (requerido)
- Autor (opcional)

#### 2️⃣ Descripción
- Descripción Corta: 100-500 caracteres (requerida)
- Descripción Larga: mínimo 500 caracteres (requerida)

#### 3️⃣ Contenido
- Contenido: mínimo 500 caracteres (requerido)
- Contenido Completo: mínimo 5000 caracteres (requerido)

#### 4️⃣ Multimedia Principal (Opcional)
- Imagen principal
- Link de video (YouTube, Vimeo, etc.)
- Archivo de audio (MP3, WAV, OGG, M4A)
- Link externo

#### 5️⃣ Subtítulos (5 requeridos)

Para cada subtítulo:
- **Título**: Nombre del subtítulo (requerido)
- **Descripción**: Mínimo 100 caracteres (requerida)
- **Contenido**: Mínimo 500 caracteres (requerido)
- **Multimedia** (Opcional):
  - Imagen
  - Link de video
  - Archivo de audio
  - Link externo

---

### Ver Noticia Completa

#### Opción 1: Desde Página Principal
1. Ir a: `http://localhost:3000/`
2. Buscar noticia
3. Hacer click en botón azul **"Ver Más"** (con icono de ojo 👁️)

#### Opción 2: Desde Secciones
1. Ir a: `http://localhost:3000/secciones.php`
2. Seleccionar categoría (opcional)
3. Hacer click en **"Ver Más"** (con icono de ojo 👁️)

#### Opción 3: URL Directa
```
http://localhost:3000/noticia.php?id=1
```

---

## 🗄️ Estructura de Base de Datos

### Tabla: `noticias`

| Campo | Tipo | Longitud | Requerido |
|-------|------|----------|-----------|
| id | INT | - | ✓ PK, AI |
| titulo | VARCHAR | 255 | ✓ |
| descripcion | TEXT | - | ✓ |
| descripcion_larga | LONGTEXT | - | ✓ |
| contenido | TEXT | - | ✓ |
| contenido_completo | LONGTEXT | - | ✓ |
| imagen | VARCHAR | 255 | ○ |
| video_principal | VARCHAR | 500 | ○ |
| audio_principal | VARCHAR | 500 | ○ |
| link_principal | VARCHAR | 500 | ○ |
| categoria | VARCHAR | 100 | ✓ |
| autor | VARCHAR | 100 | ✓ |
| fecha_publicacion | DATETIME | - | ✓ |

### Tabla: `noticias_subtitulos` (NUEVA)

| Campo | Tipo | Longitud | Requerido |
|-------|------|----------|-----------|
| id | INT | - | ✓ PK, AI |
| noticia_id | INT | - | ✓ FK |
| numero_subtitulo | INT | - | ✓ |
| subtitulo | VARCHAR | 255 | ✓ |
| descripcion | LONGTEXT | - | ✓ |
| contenido | LONGTEXT | - | ✓ |
| imagen | VARCHAR | 255 | ○ |
| video | VARCHAR | 500 | ○ |
| audio | VARCHAR | 500 | ○ |
| link | VARCHAR | 500 | ○ |
| orden | INT | - | ✓ |
| fecha_creacion | DATETIME | - | ✓ |

---

## 📁 Archivos Creados

### Archivos Nuevos

| Archivo | Ubicación | Descripción |
|---------|-----------|-------------|
| `migracion_multimedia.php` | Raíz | Migración de base de datos |
| `noticia.php` | Raíz | Página de noticia completa |
| `guia_implementacion.php` | Raíz | Guía interactiva |
| `crear_noticia_completa.php` | `/admin/` | Formulario de creación |
| `DOCUMENTACION_COMPLETA.txt` | Raíz | Documentación técnica |
| `README.md` | Raíz | Este archivo |

### Archivos Modificados

| Archivo | Cambios |
|---------|---------|
| `index.php` | Agregado botón "Ver Más" con icono de ojo |
| `secciones.php` | Agregado botón "Ver Más" con icono de ojo |

---

## 🔗 URLs y Accesos

### Públicas 🌍

| URL | Descripción |
|-----|-------------|
| `http://localhost:3000/` | Página principal |
| `http://localhost:3000/secciones.php` | Ver noticias por categoría |
| `http://localhost:3000/secciones.php?categoria=Universidad` | Filtrar por categoría |
| `http://localhost:3000/noticia.php?id=1` | Noticia completa (ID 1) |
| `http://localhost:3000/guia_implementacion.php` | Guía de uso |

### Administrativas 🔐

| URL | Descripción |
|-----|-------------|
| `http://localhost:3000/migracion_multimedia.php` | Migración de BD |
| `http://localhost:3000/admin/crear_noticia_completa.php` | Crear noticia |
| `http://localhost:3000/admin/dashboard.php` | Panel de control |
| `http://localhost:3000/admin/gestionar_noticias.php` | Gestor de noticias |

---

## 🎨 Categorías Disponibles

- 📚 Universidad
- ⚽ Deportes
- 🎭 Cultura
- 💻 Tecnología
- 👥 Sociedad
- 📖 Educación
- 📰 General

---

## 📊 Validaciones de Contenido

| Campo | Mínimo | Máximo | Requerido |
|-------|--------|--------|-----------|
| Título | - | 255 | ✓ |
| Descripción Corta | 100 | 500 | ✓ |
| Descripción Larga | 500 | ∞ | ✓ |
| Contenido | 500 | ∞ | ✓ |
| Contenido Completo | 5000 | ∞ | ✓ |
| Subtítulo Título | - | 255 | ✓ |
| Subtítulo Descripción | 100 | ∞ | ✓ |
| Subtítulo Contenido | 500 | ∞ | ✓ |

---

## 📦 Formatos Soportados

### Imágenes
- `.jpg`, `.jpeg`
- `.png`
- `.gif`

### Audio
- `.mp3`
- `.wav`
- `.ogg`
- `.m4a`

### Video
- Links de YouTube
- Links de Vimeo
- Cualquier URL de video

### Enlaces
- Cualquier URL válida (`http://`, `https://`)

---

## 🔒 Seguridad

- ✅ Prepared Statements (MySQLi)
- ✅ Validación de tipos de archivo
- ✅ Sanitización de inputs
- ✅ Verificación de sesión
- ✅ FOREIGN KEYS en BD
- ✅ Codificación UTF-8 MB4
- ✅ Eliminación en cascada

---

## 🚨 Troubleshooting

### ❌ "Migración no funciona"
**Solución:** Verifica que MySQL esté corriendo
```
http://localhost:3000/migracion_multimedia.php
```

### ❌ "No se pueden subir archivos"
**Solución:** Verifica permisos de carpetas
```
img/uploads/  (permisos 755 o 777)
admin/audios/uploads/  (permisos 755 o 777)
```

### ❌ "Noticia no aparece"
**Solución:** Asegúrate de tener 5 subtítulos con contenido

### ❌ "Multimedia no se ve"
**Solución:** Verifica rutas:
- Imágenes: `img/uploads/`
- Audios: `admin/audios/uploads/`

---

## 📞 Información de Contacto

**Universidad Técnica de Babahoyo**
- 📧 Email: periodismo@utb.edu.ec
- 📱 Teléfono: +593 987296574
- 🏠 Ubicación: Av. Universitaria, Babahoyo - Los Ríos, Ecuador

---

## 📝 Notas Importantes

1. **Mínimo 5 Subtítulos:** Cada noticia requiere exactamente 5 subtítulos
2. **Contenido Mínimo:** Se validan automáticamente los caracteres mínimos
3. **Multimedia Opcional:** Puedes dejar campos de multimedia vacíos
4. **Responsivo:** El diseño se adapta a todos los dispositivos
5. **UTF-8:** Todo está configurado con codificación UTF-8 MB4

---

## 🎯 Próximos Pasos

1. ✅ Ejecuta la migración: `migracion_multimedia.php`
2. ✅ Accede a crear noticia: `admin/crear_noticia_completa.php`
3. ✅ Completa los formularios
4. ✅ Publica tu primera noticia completa
5. ✅ Comparte con estudiantes

---

**Versión:** 1.0  
**Última actualización:** 23 de Enero 2026  
**Desarrollado para:** Estudiantes de Periodismo - UTB

Developed with ❤️ for Periodismo UTB

---

## 📚 Documentación Adicional

- [Guía de Implementación Interactiva](http://localhost:3000/guia_implementacion.php)
- [Documentación Técnica Completa](./DOCUMENTACION_COMPLETA.txt)
- [Panel de Admin](http://localhost:3000/admin/dashboard.php)
