<?php
/*
Plugin Name: Skills Job Listings Spanish
Description: Translates job listings to Spanish when ?lang=es is in URL
Version: 1.0
Author: Skills for Chicago
*/

// Don't allow direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

// Common job-related terms translations
function skills_job_listings_get_translations() {
    return array(
        // Page Titles & Headers
        'Find a Job' => 'Encuentra un Trabajo',
        'Find Your Next Job. Build Your Future.' => 'Encuentra Tu Próximo Trabajo. Construye Tu Futuro.',
        'At Skills for Chicago, we connect job seekers with real job opportunities by partnering with employers ready to hire. Whether you\'re looking for a new job, a better opportunity, or career support, we provide the resources, guidance, and employer connections to help you succeed.' => 'En Skills for Chicago, conectamos a los buscadores de empleo con oportunidades laborales reales asociándonos con empleadores listos para contratar. Ya sea que busques un nuevo trabajo, una mejor oportunidad o apoyo profesional, proporcionamos los recursos, la orientación y las conexiones con empleadores para ayudarte a tener éxito.',
        
        // Filter Buttons & Labels
        'Employer' => 'Empleador',
        'Job Type' => 'Tipo de Trabajo',
        'Location' => 'Ubicación',
        'Search' => 'Buscar',
        'Reset' => 'Reiniciar',
        'All Filters' => 'Todos los Filtros',
        'Apply' => 'Aplicar',
        'Showing' => 'Mostrando',
        'of' => 'de',
        'Results' => 'Resultados',
        
        // Pagination
        'Previous' => 'Anterior',
        'Next' => 'Siguiente',
        'Page' => 'Página',
        
        // Job Types
        'Full Time' => 'Tiempo Completo',
        'Part Time' => 'Medio Tiempo',
        'Temporary' => 'Temporal',
        'Contract' => 'Contrato',
        'Permanent' => 'Permanente',
        'Remote' => 'Remoto',
        'Hybrid' => 'Híbrido',
        'In-Person' => 'Presencial',
        'Internship' => 'Pasantía',
        
        // Job Detail Labels
        'Back to all jobs' => 'Volver a todos los trabajos',
        'Apply Now' => 'Aplicar Ahora',
        'Employer Name' => 'Nombre del Empleador',
        'Job Title' => 'Título del Trabajo',
        'Shift Details' => 'Detalles del Turno',
        'Compensation' => 'Compensación',
        'Job Description' => 'Descripción del Trabajo',
        'Skills & Qualifications' => 'Habilidades y Calificaciones',
        'Education' => 'Educación',
        'Benefits' => 'Beneficios',
        'About the Employer' => 'Sobre el Empleador',
        'No detailed job description available.' => 'No hay descripción detallada del trabajo disponible.',
        'No specific skills or qualifications listed.' => 'No se enumeran habilidades o calificaciones específicas.',
        'No specific education requirements listed.' => 'No se enumeran requisitos educativos específicos.',
        'No specific benefits listed.' => 'No se enumeran beneficios específicos.',
        'No detailed information about the employer available.' => 'No hay información detallada sobre el empleador disponible.',
        
        // Common Education Levels
        'High School or GED' => 'Secundaria o GED',
        'Associate Degree' => 'Grado Asociado',
        'Bachelor\'s Degree' => 'Licenciatura',
        'Master\'s Degree' => 'Maestría',
        'PhD' => 'Doctorado',
        'No Education Required' => 'No Requiere Educación',
        'Vocational Training' => 'Formación Profesional',
        'Certification' => 'Certificación',
        
        // Common Job Terms
        'years of experience' => 'años de experiencia',
        'year of experience' => 'año de experiencia',
        'experience in' => 'experiencia en',
        'salary' => 'salario',
        'per hour' => 'por hora',
        'per year' => 'por año',
        'skills' => 'habilidades',
        'required' => 'requerido',
        'preferred' => 'preferido',
        'Monday' => 'Lunes',
        'Tuesday' => 'Martes',
        'Wednesday' => 'Miércoles',
        'Thursday' => 'Jueves',
        'Friday' => 'Viernes',
        'Saturday' => 'Sábado',
        'Sunday' => 'Domingo',
        'morning' => 'mañana',
        'afternoon' => 'tarde',
        'evening' => 'noche',
        'shift' => 'turno',
        'Job not found' => 'Trabajo no encontrado',
        'The job you are looking for is not available or has been removed.' => 'El trabajo que estás buscando no está disponible o ha sido eliminado.',
        'Back to Job Listings' => 'Volver a la Lista de Trabajos',
        'No jobs found matching your criteria.' => 'No se encontraron trabajos que coincidan con tus criterios.',
        'County Job' => 'Trabajo del Condado',
        'Credential Job' => 'Trabajo con Credenciales',
    );
}

// Check if URL has lang=es and modify the output
function skills_job_listings_check_language() {
    if (isset($_GET['lang']) && $_GET['lang'] === 'es') {
        // Priority 20 to run after the original shortcode
        add_filter('the_content', 'skills_job_listings_translate_content', 20);
    }
}
add_action('wp', 'skills_job_listings_check_language');

// Filter function to translate job listings content
function skills_job_listings_translate_content($content) {
    // Only process if content contains job listings
    if (has_shortcode($content, 'skills_job_listings') || strpos($content, 'job-listings-container') !== false) {
        // First, replace exact phrases
        $translations = skills_job_listings_get_translations();
        foreach ($translations as $english => $spanish) {
            // Use str_replace to avoid issues with HTML tags
            $content = str_replace('>' . $english . '<', '>' . $spanish . '<', $content);
            
            // Also try with HTML entities for special characters
            $english_entity = htmlentities($english, ENT_QUOTES, 'UTF-8');
            $spanish_entity = htmlentities($spanish, ENT_QUOTES, 'UTF-8');
            $content = str_replace('>' . $english_entity . '<', '>' . $spanish_entity . '<', $content);
            
            // For text that might not be within tags but in attributes
            $content = str_replace('"' . $english . '"', '"' . $spanish . '"', $content);
            
            // Also handle cases where there might be a colon after the text
            $content = str_replace('>' . $english . ':', '>' . $spanish . ':', $content);
        }

        // Add lang=es to internal links
        $content = preg_replace('/href="(https?:\/\/skillsforchicago\.org\/[^"]*?)(?:\?|")/', 'href="$1?lang=es"', $content);
        $content = preg_replace('/href="([^"]*?)(?:\?job_id=|&job_id=)/', 'href="$1?lang=es&job_id=', $content);
        
        // Fix any duplicate lang parameters
        $content = str_replace('lang=es&lang=es', 'lang=es', $content);
        
        // Special handling for Apply Now links
        $content = preg_replace('/href="(https?:\/\/skillsforchicago\.org\/candidate-login\/\?tfa_3=[^"]*?)"/i', 
            'href="$1&lang=es"', $content);
    }
    
    return $content;
}

// Direct filter for the shortcode output
function skills_job_listings_filter_shortcode() {
    if (isset($_GET['lang']) && $_GET['lang'] === 'es') {
        add_filter('do_shortcode_tag', 'skills_job_listings_translate_shortcode', 10, 3);
    }
}
add_action('init', 'skills_job_listings_filter_shortcode');

// Filter function specifically for the shortcode
function skills_job_listings_translate_shortcode($output, $tag, $attr) {
    if ($tag === 'skills_job_listings') {
        $translations = skills_job_listings_get_translations();
        
        // Apply translations
        foreach ($translations as $english => $spanish) {
            // Use str_replace for exact text matches
            $output = str_replace('>' . $english . '<', '>' . $spanish . '<', $output);
            
            // Also try with HTML entities
            $english_entity = htmlentities($english, ENT_QUOTES, 'UTF-8');
            $spanish_entity = htmlentities($spanish, ENT_QUOTES, 'UTF-8');
            $output = str_replace('>' . $english_entity . '<', '>' . $spanish_entity . '<', $output);
            
            // For attribute values
            $output = str_replace('"' . $english . '"', '"' . $spanish . '"', $output);
            
            // For text followed by colon
            $output = str_replace('>' . $english . ':', '>' . $spanish . ':', $output);
        }
        
        // Translate common job related phrases that might be in the middle of content
        $output = preg_replace('/(\d+) years? experience/i', '$1 años de experiencia', $output);
        $output = str_replace('per hour', 'por hora', $output);
        $output = str_replace('per year', 'por año', $output);
        
        // Add lang=es to internal links 
        $output = preg_replace('/href="(https?:\/\/skillsforchicago\.org\/[^"]*?)(?:\?|")/', 'href="$1?lang=es"', $output);
        $output = preg_replace('/href="([^"]*?)(?:\?job_id=|&job_id=)/', 'href="$1?lang=es&job_id=', $output);
        
        // Fix any duplicate lang parameters
        $output = str_replace('lang=es&lang=es', 'lang=es', $output);
    }
    
    return $output;
}

// Custom function to translate database content on the fly
function skills_translate_db_text($text, $context = '') {
    // Skip if not Spanish mode
    if (!isset($_GET['lang']) || $_GET['lang'] !== 'es') {
        return $text;
    }
    
    // Translation dictionary for common terms
    $translations = skills_job_listings_get_translations();
    
    // If there's an exact match in our dictionary
    if (isset($translations[$text])) {
        return $translations[$text];
    }
    
    // For job descriptions and other longer text
    // Try to match and replace common patterns
    
    // Replace education levels
    foreach (['High School', 'Associate', 'Bachelor', 'Master', 'PhD', 'Certification'] as $level) {
        if (isset($translations[$level])) {
            $text = str_replace($level, $translations[$level], $text);
        }
    }
    
    // Replace common experience phrases
    $text = preg_replace('/(\d+) years? experience/i', '$1 años de experiencia', $text);
    $text = str_replace('per hour', 'por hora', $text);
    $text = str_replace('per year', 'por año', $text);
    
    return $text;
}

// Filter database output for job listings
function skills_filter_job_listings_db_output() {
    // Only proceed if we're in Spanish mode
    if (isset($_GET['lang']) && $_GET['lang'] === 'es') {
        // Add filter to database queries
        add_filter('skills_job_detail_text', 'skills_translate_db_text', 10, 2);
    }
}
add_action('init', 'skills_filter_job_listings_db_output');

// Add special hooks to modify the output of the job listings functions
function skills_modify_job_render_functions() {
    if (isset($_GET['lang']) && $_GET['lang'] === 'es') {
        // For skills_render_job_listings function
        add_filter('skills_render_job_listings', 'skills_translate_job_listings_output', 20);
        
        // For skills_render_job_detail function
        add_filter('skills_render_job_detail', 'skills_translate_job_detail_output', 20);
    }
}
add_action('init', 'skills_modify_job_render_functions');

// Function to translate job listings output
function skills_translate_job_listings_output($output) {
    $translations = skills_job_listings_get_translations();
    
    // Apply translations
    foreach ($translations as $english => $spanish) {
        $output = str_replace('>' . $english . '<', '>' . $spanish . '<', $output);
        $output = str_replace('>' . $english . ':', '>' . $spanish . ':', $output);
    }
    
    // Add lang=es to internal links
    $output = preg_replace('/href="(https?:\/\/skillsforchicago\.org\/[^"]*?)(?:\?|")/', 'href="$1?lang=es"', $output);
    
    return $output;
}

// Function to translate job detail output
function skills_translate_job_detail_output($output) {
    $translations = skills_job_listings_get_translations();
    
    // Apply translations
    foreach ($translations as $english => $spanish) {
        $output = str_replace('>' . $english . '<', '>' . $spanish . '<', $output);
        $output = str_replace('>' . $english . ':', '>' . $spanish . ':', $output);
    }
    
    // Add lang=es to internal links
    $output = preg_replace('/href="(https?:\/\/skillsforchicago\.org\/[^"]*?)(?:\?|")/', 'href="$1?lang=es"', $output);
    
    return $output;
}
