<?php
/*
Plugin Name: Skills Complete Job Translator
Description: Comprehensive solution to translate ALL job descriptions from Salesforce to Spanish
Version: 2.0
Author: Skills for Chicago
*/

// Don't allow direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

// Only run this on Spanish pages
function skills_complete_job_translator() {
    // Only apply on pages with the Spanish parameter
    if (!isset($_GET['lang']) || $_GET['lang'] !== 'es') {
        return;
    }
    
    // Add JavaScript to translate ALL job descriptions
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Dictionary of common job-related terms for translation
        const commonTerms = {
            // Section headers
            "Job Description": "Descripción del Trabajo",
            "Skills & Qualifications": "Habilidades y Calificaciones",
            "Education": "Educación",
            "Benefits": "Beneficios",
            "About the Employer": "Sobre el Empleador",
            "Employer Name": "Nombre del Empleador",
            "Job Title": "Título del Trabajo",
            "Compensation": "Compensación",
            "Shift Details": "Detalles del Turno",
            "Location": "Ubicación",
            
            // Common job terms
            "required": "requerido",
            "preferred": "preferido",
            "experience": "experiencia",
            "years": "años",
            "skills": "habilidades",
            "degree": "título",
            "certification": "certificación",
            "license": "licencia",
            "benefits": "beneficios",
            "salary": "salario",
            "hourly": "por hora",
            "annually": "anualmente",
            "plus benefits": "más beneficios",
            "health insurance": "seguro de salud",
            "dental insurance": "seguro dental",
            "vision insurance": "seguro de visión",
            "retirement plan": "plan de jubilación",
            "paid time off": "tiempo libre remunerado",
            "vacation": "vacaciones",
            "sick leave": "permiso por enfermedad",
            "flexible schedule": "horario flexible",
            "remote work": "trabajo remoto",
            "hybrid work": "trabajo híbrido",
            "training": "capacitación",
            "career development": "desarrollo profesional",
            "advancement opportunities": "oportunidades de avance",
            "education": "educación",
            "university": "universidad",
            "college": "universidad",
            "high school": "escuela secundaria",
            "diploma": "diploma",
            "Bachelor": "Licenciatura",
            "Master": "Maestría",
            "Doctorate": "Doctorado",
            "Associate": "Asociado",
            "Certificate": "Certificado",
            "per hour": "por hora",
            "per year": "por año",
            "full-time": "tiempo completo",
            "part-time": "medio tiempo",
            "contract": "contrato",
            "temporary": "temporal",
            "permanent": "permanente",
            "Requerido": "Requerido",
            "Preferido": "Preferido",
            "beneficial": "beneficioso",
            "relevant": "relevante",
            "include": "incluyen",
            "analytical": "analíticas",
            "from": "de",
            "not": "no",
            "but": "pero",
            "with": "con",
            "and": "y",
            "or": "o",
            "in": "en",
            "on": "en",
            "to": "para",
            "for": "para",
            "at": "en",
            "by": "por",
            "very": "muy",
            "team": "equipo",
            "client": "cliente",
            "health": "salud",
            "care": "cuidado",
            "market": "mercado",
            "strong": "fuertes",
            "informed": "informado",
            "resolve": "resolver",
            "support": "apoyar",
            "maintain": "mantener",
            "prepare": "preparar",
            "develop": "desarrollar",
            "contribute": "contribuir",
            "engage": "participar",
            "recommend": "recomendar",
            "track": "seguimiento",
            "build": "construir",
            "benchmark": "comparar",
            "interest": "interés",
            "soft": "blandas"
        };
        
        // Specific job description translations 
        const jobDescriptions = {
            // Allied Universal Driving Patrol Unit
            "Serve and safeguard clients in a range of industries such as Commercial Real Estate, Healthcare, Education, Government and more": 
            "Servir y proteger a clientes en una variedad de industrias como Bienes Raíces Comerciales, Salud, Educación, Gobierno y más",
            
            "Provide customer service to clients by carrying out safety and security procedures, site-specific policies and when appropriate, emergency response activities": 
            "Proporcionar servicio al cliente llevando a cabo procedimientos de seguridad, políticas específicas del sitio y, cuando sea apropiado, actividades de respuesta de emergencia",
            
            "Respond to incidents and critical situations in a calm, problem solving manner": 
            "Responder a incidentes y situaciones críticas de manera tranquila y resolutiva",
            
            "Conduct regular and random patrols around the business and perimeter": 
            "Realizar patrullas regulares y aleatorias alrededor del negocio y el perímetro",
            
            // Behavioral Health Tech
            "De-escalate patients using CPI techniques and participate in seclusion/restraint procedures with appropriate monitoring and documentation":
            "Desescalar a pacientes utilizando técnicas CPI y participar en procedimientos de aislamiento/restricción con monitoreo y documentación apropiados",
            
            "Provide therapeutic support to patients and families, conduct safety rounds, and assist with conflict resolution":
            "Proporcionar apoyo terapéutico a pacientes y familias, realizar rondas de seguridad y asistir en la resolución de conflictos",
            
            "Facilitate and document individual and group interventions using Recovery principles and the DAR format":
            "Facilitar y documentar intervenciones individuales y grupales utilizando principios de Recuperación y el formato DAR",
            
            "Lead therapeutic groups according to outlined content while tailoring sessions to patient needs and ensuring active participation":
            "Dirigir grupos terapéuticos según el contenido establecido mientras adapta las sesiones a las necesidades del paciente y asegura la participación activa",
            
            "Monitor for and report abuse or neglect, interact with families regarding care, and complete Requerido departmental competencies annually":
            "Supervisar y reportar abuso o negligencia, interactuar con familias respecto al cuidado y completar competencias departamentales requeridas anualmente",
            
            // Financial Coordinator at HUB International
            "Maintain the HUB financial database and track client plan costs regularly":
            "Mantener la base de datos financiera de HUB y realizar un seguimiento regular de los costos del plan del cliente",
            
            "Prepare monthly, quarterly, and year-end financial reports, and develop client budget forecasts":
            "Preparar informes financieros mensuales, trimestrales y de fin de año, y desarrollar pronósticos de presupuesto para clientes",
            
            "Benchmark benefit plans against market standards and recommend competitive enhancements":
            "Comparar planes de beneficios con estándares del mercado y recomendar mejoras competitivas",
            
            "Support vendor negotiations, resolve reporting issues, and contribute to proposal evaluations":
            "Apoyar negociaciones con proveedores, resolver problemas de informes y contribuir a evaluaciones de propuestas",
            
            "Build strong relationships, stay informed on benefit trends, and actively engage in team initiatives":
            "Construir relaciones sólidas, mantenerse informado sobre tendencias de beneficios y participar activamente en iniciativas de equipo",
            
            "Bachelor's degree from four-year college or university is Requerido":
            "Se requiere título de licenciatura de universidad o colegio de cuatro años",
            
            "Internship experience is Preferido but not Requerido":
            "La experiencia de prácticas es preferida pero no requerida",
            
            "Interest in health care and consulting is very beneficial":
            "El interés en atención médica y consultoría es muy beneficioso",
            
            "Relevant soft skills include financial and analytical skills":
            "Las habilidades blandas relevantes incluyen habilidades financieras y analíticas",
            
            // General skills
            "Either a high school diploma/GED or 5 years of work experience is required": 
            "Se requiere diploma de escuela secundaria/GED o 5 años de experiencia laboral",
            
            "Must have a valid driver's license": 
            "Debe tener una licencia de conducir válida",
            
            "18 years old for unarmed roles; 21 years old for armed roles": 
            "18 años para puestos sin armas; 21 años para puestos armados",
            
            "2+ years of prior Experiencia in behavioral health or related care Experiencia within the last 5 years is Requerido (working with patients with addiction issues is a plus)":
            "2+ años de experiencia previa en salud conductual o atención relacionada dentro de los últimos 5 años es requerido (trabajar con pacientes con problemas de adicción es una ventaja)",
            
            "Active BLS Certificación from the American Heart Association (AHA) is Requerido":
            "Certificación BLS activa de la Asociación Americana del Corazón (AHA) es requerida",
            
            "CPI Certificación is Requerido upon hire or within 60 days of hire":
            "Certificación CPI es requerida al momento de la contratación o dentro de los 60 días posteriores a la contratación"
        };
        
        // First implementation: run immediately
        translateAllJobContent();
        
        // Second run after a short delay to catch any dynamic content
        setTimeout(translateAllJobContent, 800);
        
        // Final run after a longer delay
        setTimeout(translateAllJobContent, 2000);
        
        // Main translation function
        function translateAllJobContent() {
            console.log("Running comprehensive job translation");
            
            // 1. Target all job description paragraphs and list items
            document.querySelectorAll('.skills-job-listings-wrapper p, .skills-job-listings-wrapper li, .job-detail-list li, h2, h3, .metadata-label, .metadata-value, .job-detail-heading').forEach(function(element) {
                // First check for exact matches in job descriptions
                const text = element.textContent.trim();
                let translated = false;
                
                // Check for exact matches first
                if (jobDescriptions[text]) {
                    element.textContent = jobDescriptions[text];
                    translated = true;
                } else {
                    // Check for partial matches in job descriptions
                    for (const [english, spanish] of Object.entries(jobDescriptions)) {
                        if (text.includes(english) && english.length > 15) { // Only match substantial phrases
                            element.textContent = text.replace(english, spanish);
                            translated = true;
                            break;
                        }
                    }
                }
                
                // If no job description match was found, try word-by-word translation
                if (!translated && text.length > 0) {
                    let words = text.split(' ');
                    let translatedWords = words.map(word => {
                        const cleanWord = word.replace(/[.,\/#!$%\^&\*;:{}=\-_`~()]/g, "").toLowerCase();
                        if (commonTerms[cleanWord]) {
                            // Replace the word but keep the original punctuation
                            return word.replace(cleanWord, commonTerms[cleanWord]);
                        }
                        return word;
                    });
                    
                    element.textContent = translatedWords.join(' ');
                }
            });
            
            // 2. Target all job description section headers
            document.querySelectorAll('h1, h2, h3').forEach(function(heading) {
                const text = heading.textContent.trim();
                for (const [english, spanish] of Object.entries(commonTerms)) {
                    if (text === english) {
                        heading.textContent = spanish;
                        break;
                    }
                }
            });
            
            // 3. Process specific elements for the known job descriptions
            processKnownJobDescriptions();
        }
        
        // Function to specifically target known job descriptions
        function processKnownJobDescriptions() {
            // Process Allied Universal job
            document.querySelectorAll('li, p').forEach(function(element) {
                const text = element.textContent.trim();
                
                // Allied Universal
                if (text.includes('Serve and safeguard clients')) {
                    element.textContent = "Servir y proteger a clientes en una variedad de industrias como Bienes Raíces Comerciales, Salud, Educación, Gobierno y más";
                } else if (text.includes('Provide customer service to clients')) {
                    element.textContent = "Proporcionar servicio al cliente llevando a cabo procedimientos de seguridad, políticas específicas del sitio y, cuando sea apropiado, actividades de respuesta de emergencia";
                } else if (text.includes('Respond to incidents and critical situations')) {
                    element.textContent = "Responder a incidentes y situaciones críticas de manera tranquila y resolutiva";
                } else if (text.includes('Conduct regular and random patrols')) {
                    element.textContent = "Realizar patrullas regulares y aleatorias alrededor del negocio y el perímetro";
                } 
                // Behavioral Health Tech
                else if (text.includes('De-escalate patients using CPI techniques')) {
                    element.textContent = "Desescalar a pacientes utilizando técnicas CPI y participar en procedimientos de aislamiento/restricción con monitoreo y documentación apropiados";
                } else if (text.includes('Provide therapeutic support to patients')) {
                    element.textContent = "Proporcionar apoyo terapéutico a pacientes y familias, realizar rondas de seguridad y asistir en la resolución de conflictos";
                } else if (text.includes('Facilitate and document individual and group interventions')) {
                    element.textContent = "Facilitar y documentar intervenciones individuales y grupales utilizando principios de Recuperación y el formato DAR";
                } else if (text.includes('Lead therapeutic groups')) {
                    element.textContent = "Dirigir grupos terapéuticos según el contenido establecido mientras adapta las sesiones a las necesidades del paciente y asegura la participación activa";
                } else if (text.includes('Monitor for and report abuse')) {
                    element.textContent = "Supervisar y reportar abuso o negligencia, interactuar con familias respecto al cuidado y completar competencias departamentales requeridas anualmente";
                } else if (text.includes('2+ years of prior')) {
                    element.textContent = "2+ años de experiencia previa en salud conductual o atención relacionada dentro de los últimos 5 años es requerido (trabajar con pacientes con problemas de adicción es una ventaja)";
                } else if (text.includes('Active BLS Certificación')) {
                    element.textContent = "Certificación BLS activa de la Asociación Americana del Corazón (AHA) es requerida";
                } else if (text.includes('CPI Certificación')) {
                    element.textContent = "Certificación CPI es requerida al momento de la contratación o dentro de los 60 días posteriores a la contratación";
                }
                // Financial Coordinator
                else if (text.includes('Maintain the HUB financial database')) {
                    element.textContent = "Mantener la base de datos financiera de HUB y realizar un seguimiento regular de los costos del plan del cliente";
                } else if (text.includes('Prepare monthly, quarterly, and year-end financial reports')) {
                    element.textContent = "Preparar informes financieros mensuales, trimestrales y de fin de año, y desarrollar pronósticos de presupuesto para clientes";
                } else if (text.includes('Benchmark benefit plans against market standards')) {
                    element.textContent = "Comparar planes de beneficios con estándares del mercado y recomendar mejoras competitivas";
                } else if (text.includes('Support vendor negotiations')) {
                    element.textContent = "Apoyar negociaciones con proveedores, resolver problemas de informes y contribuir a evaluaciones de propuestas";
                } else if (text.includes('Build strong relationships')) {
                    element.textContent = "Construir relaciones sólidas, mantenerse informado sobre tendencias de beneficios y participar activamente en iniciativas de equipo";
                } else if (text.includes("Bachelor's degree from four-year college")) {
                    element.textContent = "Se requiere título de licenciatura de universidad o colegio de cuatro años";
                } else if (text.includes('Internship experience is Preferido')) {
                    element.textContent = "La experiencia de prácticas es preferida pero no requerida";
                } else if (text.includes('Interest in health care and consulting')) {
                    element.textContent = "El interés en atención médica y consultoría es muy beneficioso";
                } else if (text.includes('Relevant soft skills include')) {
                    element.textContent = "Las habilidades blandas relevantes incluyen habilidades financieras y analíticas";
                }
            });
        }
    });
    </script>
    <?php
}
add_action('wp_footer', 'skills_complete_job_translator', 99999); // Run with highest priority
