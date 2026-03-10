<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Funciones locales y datos del Test de Kolb (preguntas, dimensiones, retroalimentación).
 *
 * @package   mod_kolbtest
 * @copyright 2025
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/** Dimensiones Kolb */
const KOLB_CE = 'CE'; // Experiencia Concreta
const KOLB_RO = 'RO'; // Observación Reflexiva
const KOLB_AC = 'AC'; // Conceptualización Abstracta
const KOLB_AE = 'AE'; // Experimentación Activa

/** Puntos por posición: 1°=4, 2°=3, 3°=2, 4°=1 */
const KOLB_POINTS = [4, 3, 2, 1];

/** Mapeo estilo = dos dimensiones con mayor puntaje */
const KOLB_STYLES = [
    'acomodador' => ['CE', 'AE'],
    'divergente'  => ['CE', 'RO'],
    'asimilador'  => ['RO', 'AC'],
    'convergente' => ['AC', 'AE'],
];

/**
 * Devuelve las 9 preguntas del test con sus 4 opciones y dimensión cada una.
 * Orden de opciones: índice 0 = primera frase, 1 = segunda, etc. Cada una tiene 'text' y 'dim'.
 *
 * @return array[]
 */
function kolbtest_get_questions() {
    return [
        1 => [
            'question' => 'Cuando ante un problema en el que debes dar una solución u obtener un resultado urgente, ¿cómo te comportas?',
            'options' => [
                ['text' => 'Seleccionando y discriminando una cosa de otra', 'dim' => 'AC'],
                ['text' => 'Intentando y probando acciones', 'dim' => 'AE'],
                ['text' => 'Involucrándome', 'dim' => 'CE'],
                ['text' => 'Poniendo en práctica lo aprendido', 'dim' => 'AE'],
            ],
        ],
        2 => [
            'question' => 'Cuando te encuentras frente a una realidad nueva, ¿cómo reaccionas?',
            'options' => [
                ['text' => 'Confío en mis corazonadas y sentimientos', 'dim' => 'CE'],
                ['text' => 'Trabajo duramente para que las cosas salgan bien', 'dim' => 'AE'],
                ['text' => 'Descomponiendo todo en sus partes', 'dim' => 'AC'],
                ['text' => 'Atiendo y observo cuidadosamente su utilidad', 'dim' => 'RO'],
            ],
        ],
        3 => [
            'question' => 'Ante un suceso desconocido hasta la fecha para ti, ¿cómo reaccionas?',
            'options' => [
                ['text' => 'Me involucro emocionalmente y experimento sensaciones', 'dim' => 'CE'],
                ['text' => 'Observando atentamente', 'dim' => 'RO'],
                ['text' => 'Examino todo con cuidado para hacerme una idea', 'dim' => 'RO'],
                ['text' => 'Me pongo en acción y realizo actividades', 'dim' => 'AE'],
            ],
        ],
        4 => [
            'question' => 'Ante los cambios, ¿cómo eres?',
            'options' => [
                ['text' => 'Los acepto bien dispuesto', 'dim' => 'CE'],
                ['text' => 'Me arriesgo', 'dim' => 'AE'],
                ['text' => 'Soy cuidadoso y examino el valor de los contenidos', 'dim' => 'RO'],
                ['text' => 'Me fijo en si las ideas son ciertas o correctas', 'dim' => 'AC'],
            ],
        ],
        5 => [
            'question' => 'Cuando aprendes:',
            'options' => [
                ['text' => 'Lo hago de forma bastante intuitiva', 'dim' => 'CE'],
                ['text' => 'Lo hago con resultados a la vista', 'dim' => 'AE'],
                ['text' => 'Intento descubrir de modo lógico, descomponiendo partes', 'dim' => 'AC'],
                ['text' => 'Preguntando a quien sabe más', 'dim' => 'RO'],
            ],
        ],
        6 => [
            'question' => 'En relación a tu forma de ser ante una tarea, ¿cómo te consideras?',
            'options' => [
                ['text' => 'Soy una persona lógica, separo lo esencial', 'dim' => 'AC'],
                ['text' => 'Observador, examino atentamente los detalles', 'dim' => 'RO'],
                ['text' => 'Soy concreto y me dedico a lo esencial', 'dim' => 'AE'],
                ['text' => 'Soy muy activo trabajando y manipulando todo', 'dim' => 'AE'],
            ],
        ],
        7 => [
            'question' => 'En la utilización del tiempo, ¿cómo eres?',
            'options' => [
                ['text' => 'Me proyecto en el presente, lo aprendido me servirá ahora', 'dim' => 'AE'],
                ['text' => 'Reflexivo, considero todo detenidamente', 'dim' => 'RO'],
                ['text' => 'Me proyecto hacia el futuro, lo aprendido me servirá después', 'dim' => 'AC'],
                ['text' => 'Soy pragmático, busco usos prácticos', 'dim' => 'AE'],
            ],
        ],
        8 => [
            'question' => 'En un proceso de aprendizaje consideras más importante:',
            'options' => [
                ['text' => 'La experiencia, vivir situaciones', 'dim' => 'CE'],
                ['text' => 'La observación', 'dim' => 'RO'],
                ['text' => 'La conceptualización', 'dim' => 'AC'],
                ['text' => 'La experimentación', 'dim' => 'AE'],
            ],
        ],
        9 => [
            'question' => 'En tu trabajo eres:',
            'options' => [
                ['text' => 'Bastante intuitivo, estimulado por mis emociones', 'dim' => 'CE'],
                ['text' => 'Voy con cautela y soy reservado', 'dim' => 'RO'],
                ['text' => 'Lógico y racional, discerniendo con la razón lo verdadero de lo falso', 'dim' => 'AC'],
                ['text' => 'Muy activo y aporto nuevas ideas siempre que puedo', 'dim' => 'AE'],
            ],
        ],
    ];
}

/**
 * Calcula puntajes por dimensión a partir del orden elegido por pregunta.
 * $order es un array: [ question_num => [index0, index1, index2, index3] ] donde index es la opción (0-3) en esa posición.
 *
 * @param array $order [ 1 => [2,0,1,3], 2 => [...], ... ]
 * @return array ['CE'=>x, 'RO'=>x, 'AC'=>x, 'AE'=>x]
 */
function kolbtest_calculate_scores($order) {
    $questions = kolbtest_get_questions();
    $scores = ['CE' => 0, 'RO' => 0, 'AC' => 0, 'AE' => 0];
    foreach ($order as $qnum => $positions) {
        if (!isset($questions[$qnum]) || count($positions) !== 4) {
            continue;
        }
        $opts = $questions[$qnum]['options'];
        foreach ($positions as $pos => $optionIndex) {
            $points = KOLB_POINTS[$pos] ?? 0;
            $dim = $opts[$optionIndex]['dim'] ?? null;
            if ($dim && isset($scores[$dim])) {
                $scores[$dim] += $points;
            }
        }
    }
    return $scores;
}

/**
 * Determina el estilo a partir de los puntajes (las dos dimensiones más altas).
 *
 * @param array $scores ['CE'=>x, 'RO'=>x, 'AC'=>x, 'AE'=>x]
 * @return string acomodador|divergente|asimilador|convergente
 */
function kolbtest_get_style_from_scores($scores) {
    arsort($scores, SORT_NUMERIC);
    $top2 = array_slice(array_keys($scores), 0, 2);
    sort($top2);
    $pair = implode('', $top2);
    $styleMap = [
        'ACAE' => 'convergente',
        'ACRO' => 'asimilador',
        'AECE' => 'acomodador',
        'CERO' => 'divergente',
    ];
    return $styleMap[$pair] ?? 'acomodador';
}

/**
 * Texto de retroalimentación para el estudiante según estilo (de RETROALIMENTACION PARA ESTUDIANTES).
 *
 * @param string $style acomodador|divergente|asimilador|convergente
 * @return string HTML
 */
function kolbtest_get_feedback_for_student($style) {
    $feedback = [
        'acomodador' => [
            'title' => 'Acomodador',
            'subtitle' => '(Aprendizaje basado en experiencia concreta y experimentación activa)',
            'body' => 'Tu forma de aprender se caracteriza por involucrarte, actuar, probar y adaptarte rápidamente a las situaciones. Tiendes a aprender "haciendo" y participando en experiencias nuevas. Te conectas con lo que estudias desde lo personal, confías en tus intuiciones, te gusta resolver problemas mientras avanzas y no temes experimentar para encontrar mejores resultados. Este estilo te permite moverte con soltura en contextos dinámicos, con tareas prácticas y con actividades que requieren iniciativa y flexibilidad.
Eres una persona que observa el entorno con atención, anticipa soluciones y relaciona rápidamente distintos contenidos. Piensas de manera imaginativa y gráfica, vivencias los temas que estudias y buscas que los aprendizajes se conecten con la realidad. Cuando el contenido tiene sentido inmediato, aprendes con rapidez y profundidad; cuando no ves la aplicación práctica, el aprendizaje se vuelve más lento.
Este estilo te ofrece ventajas en trabajos de interacción con otros, actividades prácticas, proyectos aplicados, simulaciones, ejercicios de ensayo y error, resolución de problemas y tareas donde puedas explorar libremente hasta llegar a una solución. También te desenvuelves bien en actividades comunicativas, artísticas o expresivas, porque te permiten integrar creatividad, emoción y participación activa.',
            'tips' => [
                'Participa en trabajos grupales o espacios colaborativos; aprendes mucho al interactuar y compartir ideas.',
                'Busca actividades donde puedas probar, experimentar y equivocarte sin miedo; el ensayo y error es parte natural de tu estilo.',
                'Prefiere lecturas breves y contenidos que puedas relacionar con ejemplos reales o situaciones concretas.',
                'Usa gráficos, esquemas, mapas visuales o cualquier recurso que te permita "ver" la idea.',
                'Si el contenido parece abstracto, intenta crear una metáfora, un ejemplo práctico o un caso cotidiano que lo represente.',
                'Realiza actividades expresivas como escritura creativa, entrevistas o análisis de experiencias para integrar mejor lo aprendido.',
            ],
            'difficulty' => 'Puede costarte más aprender cuando el contenido no se relaciona con tus necesidades inmediatas, cuando no ves una finalidad clara o cuando las actividades parecen muy teóricas. En esos casos, te servirá preguntarte: "¿Cómo podría aplicar esto en mi vida, trabajo o entorno?"; esa conexión hará que todo fluya con mayor facilidad.',
        ],
        'divergente' => [
            'title' => 'Estilo Divergente',
            'subtitle' => '(Aprendizaje basado en experiencia concreta y observación reflexiva)',
            'body' => 'Tu forma de aprender combina sensibilidad, creatividad y capacidad para observar el entorno con mucha profundidad. Cuando enfrentas un contenido nuevo, te gusta explorarlo desde distintos ángulos, imaginar posibilidades, conectar ideas y considerar múltiples perspectivas antes de llegar a una conclusión. Este estilo te permite captar matices que otros no ven, generar ideas novedosas y comprender las situaciones desde lo humano y lo concreto.
Eres una persona kinestésica, que aprende moviéndose, probando y "tocando" los contenidos desde la experiencia. Reproduces lo aprendido con naturalidad, eres flexible para adaptarte cuando algo no resulta a la primera y posees una creatividad espontánea que te lleva a ofrecer soluciones originales. A veces prefieres un ambiente más informal y menos rígido para expresar tus ideas, lo que favorece tu aprendizaje.
Este estilo brilla en actividades que requieren creatividad, exploración, experimentación emocional y visualización de alternativas. Las dinámicas participativas, los ejercicios que involucran movimiento, las simulaciones y los desafíos que invitan a ver "más allá de lo evidente" suelen impulsarte a rendir al máximo.',
            'tips' => [
                'Participa activamente en lluvias de ideas o espacios donde puedas proponer enfoques distintos.',
                'Realiza simulaciones, experimentos pequeños o actividades donde puedas poner a prueba tus hipótesis.',
                'Usa analogías, metáforas o casos cotidianos para conectar lo nuevo con lo que ya conoces.',
                'Construye mapas conceptuales, esquemas o representaciones visuales; te permitirán organizar ideas sin perder tu creatividad.',
                'Resuelve puzzles, rompecabezas, acertijos o desafíos lógicos que te ayuden a ejercitar tu agilidad mental.',
                'Cuando un contenido parezca muy abstracto, intenta imaginarlo en acción, visualizar un ejemplo o "ponerlo en movimiento".',
            ],
            'difficulty' => 'Puede costarte más aprender cuando debes asumir un rol pasivo, cuando el aprendizaje exige análisis frío o interpretaciones muy técnicas, o cuando te dejan trabajar completamente solo sin interacción. En esos casos, busca maneras de activar tu creatividad: haz preguntas, inventa un ejemplo propio, dibuja un esquema o comparte tus ideas con otros para volver a sentir el contenido como algo vivo.',
        ],
        'asimilador' => [
            'title' => 'Estilo Asimilador',
            'subtitle' => '(Aprendizaje basado en conceptualización abstracta y observación reflexiva)',
            'body' => 'Tu forma de aprender se caracteriza por analizar, razonar y comprender en profundidad los conceptos antes de aplicarlos. Necesitas que el contenido tenga una estructura clara, una lógica interna y un fundamento sólido. Cuando estudias, sueles descomponer la información, organizarla paso a paso y reconstruirla en un modelo coherente que refleje cómo funciona. Disfrutas de los conceptos, las explicaciones teóricas y las ideas complejas que te desafían a pensar.
Eres una persona reflexiva y analítica, que prefiere comprender "el porqué" de las cosas antes de tomar acción. Te mueves con comodidad en ambientes donde el aprendizaje es ordenado, secuencial y sistemático. Valoras la rigurosidad, la lógica y la claridad; te concentras profundamente y estudias con intención de dominar el tema. Este estilo favorece enormemente la capacidad de explicar, organizar, planificar y elaborar conclusiones bien sustentadas.
Este estilo brilla en actividades teóricas, investigaciones, análisis de datos, elaboración de informes y estudios detallados. Tienes facilidad para identificar patrones, construir argumentos, integrar información dispersa y generar comprensiones profundas que otros estilos no siempre alcanzan con la misma precisión.',
            'tips' => [
                'Realiza lecturas estructuradas, tomando apuntes y subrayando ideas clave.',
                'Ordena los contenidos en esquemas, resúmenes o cuadros comparativos que te permitan ver la estructura completa.',
                'Profundiza los temas mediante investigación adicional, revisando artículos, documentos y definiciones técnicas.',
                'Participa en debates, especialmente aquellos que exigen argumentar con fundamentos y desarrollar ideas en secuencia lógica.',
                'Elabora informes o análisis escritos, porque este tipo de tareas se ajusta muy bien a tu forma de aprender.',
                'Ordena datos o información de pequeñas investigaciones, ya que te permitirá aplicar tu capacidad lógica sin perder el enfoque conceptual.',
            ],
            'difficulty' => 'Puede costarte más aprender en actividades ambiguas, donde predomina la intuición, las emociones o la experiencia inmediata sin un sustento claro. También puede ser desafiante cuando se te pide actuar rápidamente sin un modelo teórico previo. En esos momentos, te puede ayudar preguntarte: "¿Cuál sería la idea principal detrás de esta actividad?" o "¿Qué patrón puedo identificar aquí?". Encontrar esa base te permitirá avanzar con seguridad.',
        ],
        'convergente' => [
            'title' => 'Estilo Convergente',
            'subtitle' => '(Aprendizaje basado en conceptualización abstracta y experimentación activa)',
            'body' => 'Tu forma de aprender se caracteriza por usar lo que sabes para resolver problemas concretos y llegar a soluciones claras y eficientes. Te sientes cómodo aplicando teorías, probando métodos, ajustando procedimientos y moviéndote rápidamente hacia un resultado. Este estilo combina razonamiento lógico con acción práctica, lo que te permite avanzar con precisión y eficacia cuando se trata de desafíos técnicos o actividades donde existe una respuesta correcta posible.
Eres una persona práctica y orientada a los resultados. Captas la información con rapidez, entras directamente en la materia y eres hábil para aplicar los contenidos de manera inmediata. Tiendes a especializarte, a concentrarte en lo que funciona y a estructurar tus conocimientos de forma que puedan usarse al momento de resolver un problema real. Te gusta actuar con un plan claro, comprobar hipótesis y tomar decisiones basadas en evidencia.
Este estilo destaca en proyectos prácticos, actividades manuales, resolución de problemas, ejercicios aplicados y tareas donde puedas ver el resultado de tu esfuerzo. La combinación entre teoría y acción que te caracteriza es una fortaleza muy valiosa en el trabajo técnico y profesional.',
            'tips' => [
                'Realiza proyectos prácticos donde puedas aplicar lo aprendido de manera inmediata.',
                'Usa gráficos, mapas, tablas y esquemas, ya que te permiten organizar la información con claridad.',
                'Trabaja con clasificación de datos, comparaciones o análisis estructurados.',
                'Practica ejercicios donde exista una respuesta concreta o un procedimiento claro.',
                'Cuando leas teoría, pregúntate de inmediato: "¿Cómo podría aplicar esto en un caso real?"',
                'Si el contenido es extenso, ordénalo en pasos operativos o pequeñas tareas que puedas ejecutar una por una.',
            ],
            'difficulty' => 'Puede ser más difícil aprender cuando te convierten en el centro de la atención, cuando te apresuran a cambiar de actividad sin terminar la anterior o cuando se espera que actúes sin poder planificar previamente. En esos casos, te servirá detenerte un momento, ordenar la información y definir un pequeño plan antes de avanzar.',
        ],
    ];
    return $feedback[$style] ?? $feedback['acomodador'];
}
