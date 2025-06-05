<?php
/*
Plugin Name: Skills Job Content Translator
Description: Translates job description content from Salesforce when ?lang=es is in URL
Version: 1.0
Author: Skills for Chicago
*/

// Don't allow direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

// Main function to translate job description content
function skills_translate_job_content() {
    // Only run when Spanish parameter is present
    if (!isset($_GET['lang']) || $_GET['lang'] !== 'es') {
        return;
    }
    
    // Add high-priority filter to translate job detail content
    add_filter('the_content', 'skills_translate_job_descriptions', 999);
    add_filter('skills_render_job_detail', 'skills_translate_job_descriptions', 999);
}
add_action('wp', 'skills_translate_job_content');

// Function to translate job description content
function skills_translate_job_descriptions($content) {
    // Common job description phrases and their translations
    $job_phrases = array(
        // Allied Universal Driving Patrol Unit specific content
        "Serve and safeguard clients in a range of industries such as Commercial Real Estate, Healthcare, Education, Government and more" => 
        "Servir y proteger a clientes en una variedad de industrias como Bienes Raíces Comerciales, Salud, Educación, Gobierno y más",
        
        "Provide customer service to clients by carrying out safety and security procedures, site-specific policies and when appropriate, emergency response activities" => 
        "Proporcionar servicio al cliente llevando a cabo procedimientos de seguridad, políticas específicas del sitio y, cuando sea apropiado, actividades de respuesta de emergencia",
        
        "Respond to incidents and critical situations in a calm, problem solving manner" => 
        "Responder a incidentes y situaciones críticas de manera tranquila y resolutiva",
        
        "Conduct regular and random patrols around the business and perimeter" => 
        "Realizar patrullas regulares y aleatorias alrededor del negocio y el perímetro",
        
        "Either a high school diploma/GED or 5 years of work experience is required" => 
        "Se requiere diploma de escuela secundaria/GED o 5 años de experiencia laboral",
        
        "Must have a valid driver's license" => 
        "Debe tener una licencia de conducir válida",
        
        "18 years old for unarmed roles; 21 years old for armed roles" => 
        "18 años para puestos sin armas; 21 años para puestos con armas",
        
        // General job description phrases
        "years of experience" => "años de experiencia",
        "year of experience" => "año de experiencia",
        "High School Diploma/GED" => "Diploma de Escuela Secundaria/GED",
        "Bachelor's Degree" => "Licenciatura",
        "Master's Degree" => "Maestría",
        "No benefits listed." => "No se enumeran beneficios específicos.",
        
        "Job Description" => "Descripción del Trabajo",
        "Skills & Qualifications" => "Habilidades y Calificaciones",
        "Education" => "Educación",
        "Benefits" => "Beneficios",
        "Location" => "Ubicación",
        "Employer Name" => "Nombre del Empleador",
        "Job Title" => "Título del Trabajo",
        "Compensation" => "Compensación",
        "Shift Details" => "Detalles del Turno",
        "About the Employer" => "Sobre el Empleador",
        
        // Additional common phrases in job descriptions
        "Required Skills" => "Habilidades Requeridas",
        "Preferred Skills" => "Habilidades Preferidas",
        "Responsibilities" => "Responsabilidades",
        "Requirements" => "Requisitos",
        "Experience" => "Experiencia",
        "Qualifications" => "Calificaciones",
        "must have" => "debe tener",
        "preferred" => "preferido",
        "required" => "requerido",
        "Full Time" => "Tiempo Completo",
        "Part Time" => "Medio Tiempo",
        "Temporary" => "Temporal",
        "Permanent" => "Permanente",
        "Contract" => "Contrato",
        "Entry Level" => "Nivel de Entrada",
        "Mid Level" => "Nivel Medio",
        "Senior Level" => "Nivel Senior",
        "Executive" => "Ejecutivo",
        "Monday" => "Lunes",
        "Tuesday" => "Martes",
        "Wednesday" => "Miércoles",
        "Thursday" => "Jueves",
        "Friday" => "Viernes",
        "Saturday" => "Sábado",
        "Sunday" => "Domingo",
        "Morning" => "Mañana",
        "Afternoon" => "Tarde",
        "Evening" => "Noche",
        "Night" => "Noche",
        "Shift" => "Turno",
        "per hour" => "por hora",
        "hourly" => "por hora",
        "per year" => "por año",
        "annually" => "anualmente",
        "plus benefits" => "más beneficios",
        "health insurance" => "seguro de salud",
        "dental insurance" => "seguro dental",
        "vision insurance" => "seguro de visión",
        "retirement plan" => "plan de jubilación",
        "401(k)" => "401(k)",
        "paid time off" => "tiempo libre remunerado",
        "vacation" => "vacaciones",
        "sick leave" => "permiso por enfermedad",
        "flexible schedule" => "horario flexible",
        "remote work" => "trabajo remoto",
        "hybrid work" => "trabajo híbrido",
        "training" => "capacitación",
        "career development" => "desarrollo profesional",
        "advancement opportunities" => "oportunidades de avance",
        "employee discount" => "descuento para empleados",
        "bonus" => "bono",
        "commission" => "comisión",
        "overtime" => "horas extras",
        "weekend" => "fin de semana",
        "weekday" => "día de semana",
        "first shift" => "primer turno",
        "second shift" => "segundo turno",
        "third shift" => "tercer turno",
        "day shift" => "turno de día",
        "night shift" => "turno de noche",
        "rotating shift" => "turno rotativo"
    );
    
    // Create regex patterns for bigger phrases that should be translated first
    $big_phrases = array(
        'Serve and safeguard clients in a range of industries',
        'Provide customer service to clients by carrying out safety',
        'Respond to incidents and critical situations',
        'Conduct regular and random patrols',
        'Either a high school diploma/GED or 5 years',
        'Must have a valid driver\'s license',
        '18 years old for unarmed roles'
    );
    
    // Process big phrases first to avoid partial matches
    foreach ($big_phrases as $phrase) {
        if (isset($job_phrases[$phrase]) && strpos($content, $phrase) !== false) {
            $content = str_replace($phrase, $job_phrases[$phrase], $content);
        }
    }
    
    // Then do a direct replacement for all phrases
    foreach ($job_phrases as $english => $spanish) {
        // Use case-insensitive replacement for better matches
        $content = str_ireplace($english, $spanish, $content);
    }
    
    // Pattern-based replacements
    $patterns = array(
        // Match phrases like "1 year experience", "2 years experience", etc.
        '/(\d+)\s+year(s)?\s+experience/i' => '$1 año$2 de experiencia',
        // Match dollar amounts with hourly rate
        '/\$(\d+(?:\.\d+)?)\s*(?:\/|per)\s*h(?:ou)?r/i' => '$1$ por hora',
        // Match dollar amounts with yearly salary
        '/\$(\d+(?:,\d+)?(?:\.\d+)?)\s*(?:\/|per)\s*year/i' => '$1$ por año',
    );
    
    foreach ($patterns as $pattern => $replacement) {
        $content = preg_replace($pattern, $replacement, $content);
    }
    
    // Word boundary replacements for specific terms
    $boundary_terms = array(
        '\bexperience\b' => 'experiencia',
        '\brequired\b' => 'requerido',
        '\bpreferred\b' => 'preferido',
        '\bskills\b' => 'habilidades',
        '\beducation\b' => 'educación',
        '\bqualifications\b' => 'calificaciones',
        '\bresponsibilities\b' => 'responsabilidades',
        '\bbenefits\b' => 'beneficios',
        '\bsalary\b' => 'salario',
        '\bwage\b' => 'sueldo',
        '\bhourly\b' => 'por hora',
        '\bannual\b' => 'anual',
        '\bposition\b' => 'puesto',
        '\bjob\b' => 'trabajo',
        '\bwork\b' => 'trabajo',
        '\brole\b' => 'rol',
        '\bduties\b' => 'deberes',
        '\btasks\b' => 'tareas',
        '\brequirements\b' => 'requisitos',
        '\bindustry\b' => 'industria',
        '\bcompany\b' => 'empresa',
        '\bemployer\b' => 'empleador',
        '\bteam\b' => 'equipo',
        '\bstaff\b' => 'personal',
        '\bemployee\b' => 'empleado',
        '\bworker\b' => 'trabajador',
        '\bcustomer\b' => 'cliente',
        '\bclient\b' => 'cliente',
        '\bservice\b' => 'servicio',
        '\btraining\b' => 'capacitación',
        '\bdevelopment\b' => 'desarrollo',
        '\bopportunity\b' => 'oportunidad',
        '\bgrowth\b' => 'crecimiento',
        '\bcareer\b' => 'carrera',
        '\badvancement\b' => 'avance',
        '\bpromotion\b' => 'promoción',
        '\bleadership\b' => 'liderazgo',
        '\bmanagement\b' => 'gestión',
        '\bsupervision\b' => 'supervisión',
        '\bteamwork\b' => 'trabajo en equipo',
        '\bcommunication\b' => 'comunicación',
        '\borganization\b' => 'organización',
        '\bproblem-solving\b' => 'resolución de problemas',
        '\btime management\b' => 'gestión del tiempo',
        '\bflexibility\b' => 'flexibilidad',
        '\badaptability\b' => 'adaptabilidad',
        '\binitiative\b' => 'iniciativa',
        '\bmotivation\b' => 'motivación',
        '\battention to detail\b' => 'atención al detalle',
        '\baccuracy\b' => 'precisión',
        '\befficiency\b' => 'eficiencia',
        '\bproductivity\b' => 'productividad',
        '\bquality\b' => 'calidad',
        '\bprofessionalism\b' => 'profesionalismo',
        '\breliability\b' => 'confiabilidad',
        '\bdependability\b' => 'fiabilidad',
        '\bpunctuality\b' => 'puntualidad',
        '\battendance\b' => 'asistencia'
    );
    
    foreach ($boundary_terms as $english_pattern => $spanish) {
        $content = preg_replace('/' . $english_pattern . '/i', $spanish, $content);
    }
    
    return $content;
}

// Special fix for job detail content with inline styles
function skills_add_job_detail_css() {
    if (isset($_GET['lang']) && $_GET['lang'] === 'es' && isset($_GET['job_id'])) {
        ?>
        <style>
        /* Fix for any styling issues with translated content */
        .job-detail-list li {
            margin-bottom: 10px;
        }
        
        .green-bullet {
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: #1F3707;
            border-radius: 50%;
            margin-right: 10px;
        }
        </style>
        <?php
    }
}
add_action('wp_head', 'skills_add_job_detail_css');

// Additional function to handle AJAX-loaded content
function skills_add_ajax_translation_script() {
    if (isset($_GET['lang']) && $_GET['lang'] === 'es') {
        ?>
        <script>
        // This helps translate content that might be loaded dynamically after page load
        document.addEventListener('DOMContentLoaded', function() {
            // Wait a moment for any AJAX content to load
            setTimeout(function() {
                // Try to translate any text that might have been missed
                translateDynamicContent();
            }, 1000);
        });
        
        function translateDynamicContent() {
            // Dictionary of translations for dynamic content
            const translations = {
                "Serve and safeguard clients": "Servir y proteger a clientes",
                "Provide customer service": "Proporcionar servicio al cliente",
                "Respond to incidents": "Responder a incidentes",
                "Conduct regular": "Realizar patrullas regulares",
                "Either a high school": "Se requiere diploma de escuela secundaria",
                "Must have a valid": "Debe tener una licencia",
                "18 years old for": "18 años para puestos sin armas"
            };
            
            // Get all text nodes in the document
            const textNodes = [];
            const walk = document.createTreeWalker(
                document.body, 
                NodeFilter.SHOW_TEXT, 
                null, 
                false
            );
            
            let node;
            while(node = walk.nextNode()) {
                textNodes.push(node);
            }
            
            // Process each text node
            textNodes.forEach(function(textNode) {
                let content = textNode.nodeValue;
                let modified = false;
                
                // Check against our translations
                for (const [english, spanish] of Object.entries(translations)) {
                    if (content.includes(english)) {
                        content = content.replace(english, spanish);
                        modified = true;
                    }
                }
                
                // Update the node if we made changes
                if (modified) {
                    textNode.nodeValue = content;
                }
            });
        }
        </script>
        <?php
    }
}
add_action('wp_footer', 'skills_add_ajax_translation_script', 999);
