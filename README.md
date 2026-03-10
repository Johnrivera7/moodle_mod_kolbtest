# Test de Kolb (Estilos de Aprendizaje) - Módulo para Moodle 4.5+

Actividad de **Test de Estilos de Aprendizaje de Kolb** con interfaz de arrastrar y soltar, registro de resultados y reportes por curso y por estudiante.

## Autor

**John Rivera González**

[![Email](https://img.shields.io/badge/Email-johnriveragonzalez7%40gmail.com-blue?style=flat-square&logo=gmail)](mailto:johnriveragonzalez7@gmail.com)  
[![GitHub](https://img.shields.io/badge/GitHub-Johnrivera7-181717?style=flat-square&logo=github)](https://github.com/Johnrivera7)

## Requisitos

- Moodle 4.5 o superior

## Instalación

1. Copia la carpeta `mod_kolbtest` dentro de la carpeta `mod/` de tu instalación Moodle.
2. Visita **Administración del sitio → Notificaciones** y acepta la instalación del plugin.
3. En un curso, añade una actividad **"Test de Kolb"** desde el selector de actividades.
4. En **Administración → Módulos de actividad → Test de Kolb** puede seleccionar qué roles (p. ej. Dirección académica) podrán abrir el **Reporte completo (todos los cursos)**. Esos usuarios verán el botón al entrar al reporte desde cualquier curso (Test de Kolb → Ver reporte).

## Características

- **Actividad tipo test**: 9 preguntas con 4 opciones cada una; el estudiante ordena las frases arrastrando (de la que más le describe a la que menos).
- **Puntuación según Kolb**: 1.º lugar = 4 puntos, 2.º = 3, 3.º = 2, 4.º = 1. Se suman por dimensión (CE, RO, AC, AE) y se determina el estilo (Acomodador, Divergente, Asimilador, Convergente).
- **Resultado al estudiante**: Tras enviar, ve sus puntuaciones por dimensión, su estilo y la retroalimentación según el documento "Retroalimentación para estudiantes".
- **Reportes** (para profesores/managers):
  - **Por actividad**: Ver intentos de esa instancia del Test de Kolb.
  - **Por curso**: Ver todos los intentos de todos los Tests de Kolb del curso.
  - **Filtros**: Por estilo de aprendizaje y por estudiante.
- **Al entrar al curso**: En la lista de Tests de Kolb del curso (índice del módulo) se muestra un enlace a **"Reporte de todo el curso"** para ver el reporte por estudiantes.

## Documentos de referencia

El plugin sigue la operativización y textos de:

- OPERATIVIZAR RESULTADOS - TEST DE KOLB (mapeo CE/RO/AC/AE, puntuación 4-3-2-1, estilos).
- RETROALIMENTACION PARA ESTUDIANTES (textos por estilo).
- TEST DE KOLB CON DESCRIPCIÓN DE LOS ESTILOS DE APRENDIZAJES (preguntas y descripciones).

## Estructura del plugin

- `view.php`: Realización del test (arrastrar y soltar) y vista del resultado.
- `report.php`: Reportes con filtros (por actividad o por curso).
- `index.php`: Listado de instancias en el curso y enlace al reporte del curso.
- `locallib.php`: Preguntas, cálculo de puntuaciones y retroalimentación.
- `templates/`: Plantillas Mustache para el formulario y el resultado.
- `amd/src/dragdrop.js`: Lógica de arrastrar y soltar.
- `styles.css`: Estilos de la actividad y del resultado.

## Capacidades

- `mod/kolbtest:view` – Ver la actividad.
- `mod/kolbtest:addinstance` – Añadir Test de Kolb en un curso.
- `mod/kolbtest:viewreports` – Ver reportes (por defecto profesores y managers).

## Licencia

GPL v3 o posterior.

---

Desarrollado por [John Rivera González](https://github.com/Johnrivera7) · [Contacto](mailto:johnriveragonzalez7@gmail.com)
