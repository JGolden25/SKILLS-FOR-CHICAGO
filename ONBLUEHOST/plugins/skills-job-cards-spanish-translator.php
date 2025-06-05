<?php
/*
Plugin Name: Skills Job Cards Spanish Translator
Description: Translates job card elements and additional fields on job listings pages
Version: 1.0
Author: Skills for Chicago
*/

// Don't allow direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

// Only run this on Spanish pages
function skills_job_cards_spanish_translator() {
    // Only apply on pages with the Spanish parameter
    if (!isset($_GET['lang']) || $_GET['lang'] !== 'es') {
        return;
    }
    
    // Add JavaScript to translate job card elements
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Dictionary of translations for common job elements
        const translations = {
            // Job titles
            "Driving Patrol Unit": "Unidad de Patrulla de Conducción",
            "Flex Security Officer": "Oficial de Seguridad Flex",
            "Behavioral Health Tech": "Técnico de Salud Conductual",
            "Financial Coordinator": "Coordinador Financiero",
            "Large Loss Property Adjuster Field Estimating": "Estimación de Campo de Ajustador de Propiedad de Grandes Pérdidas",
            "Shipping Clerk": "Empleado de Envíos",
            
            // Education
            "High School Diploma/GED": "Diploma de Escuela Secundaria/GED",
            "Bachelor's Degree": "Licenciatura",
            "Master's Degree": "Maestría",
            "Associate's Degree": "Título Asociado",
            
            // Shifts
            "1st Shift": "1er Turno",
            "2nd Shift": "2do Turno",
            "3rd Shift": "3er Turno",
            "Any Shift": "Cualquier Turno",
            "Weekends": "Fines de semana",
            
            // Job types
            "Full Time": "Tiempo Completo",
            "Part Time": "Medio Tiempo",
            
            // Locations
            "Chicago": "Chicago",
            "Harvey": "Harvey",
            
            // Companies
            "Allied Universal": "Allied Universal",
            "University of Chicago Medicine Ingalls": "Universidad de Chicago Medicina Ingalls",
            "HUB International": "HUB Internacional",
            "Allstate": "Allstate",
            "Freedman Seating": "Freedman Seating",
            
            // Headings and labels
            "Nombre del Empleador:": "Nombre del Empleador:",
            "Employer Name:": "Nombre del Empleador:",
            "Job Title": "Título del Trabajo",
            "Job Type": "Tipo de Trabajo",
            "Location": "Ubicación",
            "Shift Details": "Detalles del Turno",
            "Compensation": "Compensación",
            "per hour": "por hora",
            "Education": "Educación",
            "Benefits": "Beneficios",
            "Apply Now": "Aplicar Ahora",
            
            // Additional phrases
            "Professional demeanor con the ability para serve as a visible security presence": 
            "Comportamiento profesional con la capacidad para servir como una presencia de seguridad visible",
            
            "Effective communication habilidades y the ability para follow security protocols": 
            "Habilidades de comunicación efectiva y capacidad para seguir protocolos de seguridad",
            
            // Benefits section
            "Dental;Life Insurance;Long Term Disability;Medical;Parent Leave;Personal;Retirement/401K;Short Term Disability;Sick;Tuition Reimbursement;Vacation;Vision":
            "Dental;Seguro de Vida;Discapacidad a Largo Plazo;Médico;Permiso Parental;Personal;Jubilación/401K;Discapacidad a Corto Plazo;Enfermedad;Reembolso de Matrícula;Vacaciones;Visión"
        };
        
        // Run translation function immediately and after a short delay
        translateJobElements();
        setTimeout(translateJobElements, 1000);
        
        // Function to translate job elements
        function translateJobElements() {
            // Translate the about the employer section
            const aboutEmployerSection = document.querySelector('h2:contains("About the Employer"), h2:contains("Sobre el Empleador")');
            if (aboutEmployerSection && aboutEmployerSection.textContent === "About the Employer") {
                aboutEmployerSection.textContent = "Sobre el Empleador";
            }
            
            // 1. Translate job titles
            document.querySelectorAll('h1, h2, h3, .job-title').forEach(element => {
                const text = element.textContent.trim();
                if (translations[text]) {
                    element.textContent = translations[text];
                }
            });
            
            // 2. Translate education information
            document.querySelectorAll('span:contains("High School"), span:contains("Bachelor")').forEach(element => {
                const text = element.textContent.trim();
                if (translations[text]) {
                    element.textContent = translations[text];
                }
            });
            
            // 3. Translate shift information
            document.querySelectorAll('span:contains("Shift")').forEach(element => {
                const text = element.textContent.trim();
                for (const [english, spanish] of Object.entries(translations)) {
                    if (text.includes(english)) {
                        element.textContent = text.replace(english, spanish);
                    }
                }
            });
            
            // 4. Translate job type
            document.querySelectorAll('span:contains("Full Time"), span:contains("Part Time")').forEach(element => {
                const text = element.textContent.trim();
                if (translations[text]) {
                    element.textContent = translations[text];
                }
            });
            
            // 5. Translate headings and labels
            document.querySelectorAll('h2, h3, h4, .metadata-label').forEach(element => {
                const text = element.textContent.trim();
                if (translations[text]) {
                    element.textContent = translations[text];
                }
            });
            
            // 6. Special handling for job cards on the main listings page
            document.querySelectorAll('.job-card').forEach(card => {
                // Translate job title
                const titleElement = card.querySelector('h3, .job-title');
                if (titleElement) {
                    const text = titleElement.textContent.trim();
                    if (translations[text]) {
                        titleElement.textContent = translations[text];
                    }
                }
                
                // Translate education, shift and location info
                card.querySelectorAll('p').forEach(paragraph => {
                    const text = paragraph.textContent.trim();
                    
                    // Check for education info
                    if (text.includes('High School') || text.includes('Diploma') || text.includes('Bachelor')) {
                        for (const [english, spanish] of Object.entries(translations)) {
                            if (text.includes(english)) {
                                paragraph.textContent = text.replace(english, spanish);
                            }
                        }
                    }
                    
                    // Check for shift info
                    if (text.includes('Shift') || text.includes('Weekends')) {
                        for (const [english, spanish] of Object.entries(translations)) {
                            if (text.includes(english)) {
                                paragraph.textContent = text.replace(english, spanish);
                            }
                        }
                    }
                });
            });
            
            // 7. Translate job details in list format
            document.querySelectorAll('.job-listings-container li').forEach(item => {
                const text = item.textContent.trim();
                
                // For exact matches
                if (translations[text]) {
                    // Preserve any HTML structure inside the li
                    const html = item.innerHTML;
                    const bulletPoint = html.includes('<span class="green-bullet"></span>') ? 
                                      '<span class="green-bullet"></span> ' : '';
                    
                    item.innerHTML = bulletPoint + translations[text];
                } 
                // For partial matches in longer text
                else {
                    for (const [english, spanish] of Object.entries(translations)) {
                        if (text.includes(english) && english.length > 5) { // Only match substantial phrases
                            // Preserve any HTML structure inside the li
                            const html = item.innerHTML;
                            const bulletPoint = html.includes('<span class="green-bullet"></span>') ? 
                                              '<span class="green-bullet"></span> ' : '';
                            
                            item.innerHTML = bulletPoint + text.replace(english, spanish);
                            break;
                        }
                    }
                }
            });
            
            // 8. Translate text in job cards on the main page
            document.querySelectorAll('.job-card p, .job-card span').forEach(element => {
                const text = element.textContent.trim();
                
                for (const [english, spanish] of Object.entries(translations)) {
                    if (text.includes(english)) {
                        element.textContent = text.replace(english, spanish);
                        break;
                    }
                }
            });
            
            // 9. Translate benefits section specifically
            document.querySelectorAll('h2:contains("Beneficios") + * li').forEach(item => {
                const text = item.textContent.trim();
                if (text.includes('Dental') || text.includes('Life Insurance')) {
                    item.textContent = translations["Dental;Life Insurance;Long Term Disability;Medical;Parent Leave;Personal;Retirement/401K;Short Term Disability;Sick;Tuition Reimbursement;Vacation;Vision"];
                }
            });
        }
    });
    </script>
    <?php
}
add_action('wp_footer', 'skills_job_cards_spanish_translator', 99999); // Run with highest priority
