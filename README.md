# Test de Kolb (Estilos de Aprendizaje)

Módulo de actividad para **Moodle 4.5+** que implementa el **Inventario de Estilos de Aprendizaje de Kolb**. Los estudiantes ordenan frases mediante arrastrar y soltar; el plugin calcula las dimensiones CE, RO, AC, AE y asigna el estilo (Acomodador, Divergente, Asimilador, Convergente), con retroalimentación y reportes para docentes.

[![Moodle 4.5+](https://img.shields.io/badge/Moodle-4.5%2B-orange?style=flat-square&logo=moodle)](https://moodle.org)
[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue.svg?style=flat-square)](https://www.gnu.org/licenses/gpl-3.0)

---

## Tabla de contenidos

- [Requisitos](#requisitos)
- [Instalación](#instalación)
- [Configuración](#configuración)
- [Características](#características)
- [Reportes](#reportes)
- [Documentos de referencia](#documentos-de-referencia)
- [Estructura del plugin](#estructura-del-plugin)
- [Capacidades](#capacidades)
- [Autor](#autor)
- [Agradecimientos](#agradecimientos)
- [Licencia](#licencia)

---

## Requisitos

- **Moodle** 4.5 o superior
- Navegador con soporte para arrastrar y soltar (HTML5 Drag and Drop)

---

## Instalación

1. Descarga o clona este repositorio y copia la carpeta `mod_kolbtest` en la carpeta `mod/` de tu instalación Moodle.
2. Ve a **Administración del sitio → Notificaciones** y acepta la instalación del plugin.
3. En cualquier curso, activa el modo de edición y añade una actividad **«Test de Kolb»** desde **Añadir una actividad o recurso**.

---

## Configuración

- **Administración del sitio → Módulos de actividad → Test de Kolb**
  - **Reporte completo (todos los cursos)**: enlace directo desde el menú de administración.
  - **Roles con acceso al reporte completo**: selecciona qué roles (p. ej. Gestor, Dirección académica) pueden ver el reporte de todos los cursos.
  - **Permitir más de un intento**: opción en desarrollo; por defecto un intento por estudiante.
  - Autor y agradecimientos se muestran en la misma página.

---

## Características

| Aspecto | Descripción |
|--------|-------------|
| **Formato del test** | 9 preguntas con 4 frases cada una; el estudiante ordena arrastrando de «la que más me describe» a «la que menos». |
| **Puntuación** | 1.º lugar = 4 pts, 2.º = 3, 3.º = 2, 4.º = 1. Se suman por dimensión (CE, RO, AC, AE). |
| **Estilos** | Acomodador, Divergente, Asimilador, Convergente según el modelo de Kolb. |
| **Retroalimentación** | Tras enviar, el estudiante ve sus puntuaciones, su estilo y un texto de retroalimentación según documento de referencia. |
| **Interfaz** | Arrastrar y soltar con feedback visual; diseño adaptable. |

---

## Reportes

- **Por actividad**: intentos de una instancia concreta del Test de Kolb (desde la actividad → *Ver reporte*).
- **Por curso**: todos los intentos de todos los Tests de Kolb del curso; enlace desde el listado del módulo en el curso.
- **Reporte completo**: todos los cursos (acceso según roles configurados en Administración).
- **Filtros**: por estilo de aprendizaje y por estudiante.

---

## Documentos de referencia

El plugin sigue la operativización y textos de:

- **OPERATIVIZAR RESULTADOS - TEST DE KOLB**: mapeo CE/RO/AC/AE, puntuación 4-3-2-1 y asignación de estilos.
- **RETROALIMENTACION PARA ESTUDIANTES**: textos de retroalimentación por estilo.
- **TEST DE KOLB CON DESCRIPCIÓN DE LOS ESTILOS DE APRENDIZAJES**: preguntas y descripciones de estilos.

---

## Estructura del plugin

| Ruta | Descripción |
|------|-------------|
| `view.php` | Realización del test (arrastrar y soltar) y vista del resultado. |
| `report.php` | Reportes con filtros (por actividad o por curso). |
| `reportall.php` | Reporte completo (todos los cursos). |
| `index.php` | Listado de instancias en el curso y enlace al reporte del curso. |
| `locallib.php` | Preguntas, cálculo de puntuaciones y retroalimentación. |
| `lib.php` | Callbacks del módulo, capacidades, crédito de autor. |
| `templates/` | Plantillas Mustache para el formulario y el resultado. |
| `amd/src/dragdrop.js` | Lógica de arrastrar y soltar. |
| `styles.css` | Estilos de la actividad y del resultado. |

---

## Capacidades

| Capacidad | Descripción |
|-----------|-------------|
| `mod/kolbtest:view` | Ver la actividad. |
| `mod/kolbtest:addinstance` | Añadir Test de Kolb en un curso. |
| `mod/kolbtest:viewreports` | Ver reportes (por defecto profesores y gestores). |

---

## Autor

**John Rivera González**

- [![Email](https://img.shields.io/badge/Email-johnriveragonzalez7%40gmail.com-blue?style=flat-square&logo=gmail)](mailto:johnriveragonzalez7@gmail.com)
- [![GitHub](https://img.shields.io/badge/GitHub-Johnrivera7-181717?style=flat-square&logo=github)](https://github.com/Johnrivera7)

---

## Agradecimientos

Agradecimiento especial a **Gabriel Olave Henríquez** ([olave.gabriel@gmail.com](mailto:olave.gabriel@gmail.com)) por la solicitud y el impulso para el desarrollo de este plugin.

---

## Licencia

GPL v3 o posterior. Ver [LICENSE](https://www.gnu.org/licenses/gpl-3.0.html) para más detalles.

---

*Desarrollado por [John Rivera González](https://github.com/Johnrivera7) · [Contacto](mailto:johnriveragonzalez7@gmail.com)*
