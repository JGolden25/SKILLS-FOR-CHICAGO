<?php
/*
Plugin Name: Skills Language Switcher Emergency Fix
Description: Emergency fix for language switcher functionality on job pages
Version: 1.0
Author: Skills for Chicago
*/

// Don't allow direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

// Add our emergency fix script at the very end of the page (highest priority)
function skills_language_switcher_emergency_fix() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log("Emergency language switcher fix running");
        
        // Function to fix all English language switcher links
        function fixAllEnglishLinks() {
            // Target all possible English language selector elements using multiple selectors
            const englishSelectors = [
                '.skills-lang-options a', // Dropdown options
                '.skills-mobile-lang-option', // Mobile language options
                '.skills-lang-dropdown a', // Any links in language dropdown
                '.skills-language-switcher a', // Any links in language switcher
                '.skills-lang-current', // Current language display
                '.wpml-ls-item a', // WPML language items
                'a[hreflang="en"]', // Links with English hreflang
                // Add more selectors if needed
            ];
            
            // Find all possible language switcher elements
            const allElements = document.querySelectorAll(englishSelectors.join(', '));
            
            allElements.forEach(function(element) {
                // Check if this is an English language link
                const isEnglishLink = 
                    element.textContent.trim() === 'Eng' || 
                    element.textContent.trim() === 'English' ||
                    (element.querySelector('img') && element.querySelector('img').alt === 'en') ||
                    element.getAttribute('hreflang') === 'en' ||
                    (element.parentNode && element.parentNode.classList.contains('wpml-ls-item-en'));
                
                if (isEnglishLink) {
                    console.log("Found English link to fix: ", element);
                    
                    // Create the correct English URL (base URL without lang parameter)
                    const currentUrl = window.location.href;
                    let cleanUrl;
                    
                    // Remove lang=es parameter
                    if (currentUrl.includes('?lang=es&')) {
                        cleanUrl = currentUrl.replace('?lang=es&', '?');
                    } else if (currentUrl.includes('&lang=es&')) {
                        cleanUrl = currentUrl.replace('&lang=es&', '&');
                    } else if (currentUrl.includes('&lang=es')) {
                        cleanUrl = currentUrl.replace('&lang=es', '');
                    } else if (currentUrl.includes('?lang=es')) {
                        cleanUrl = currentUrl.replace('?lang=es', '');
                    } else {
                        // Just use base URL if no lang parameter found
                        cleanUrl = currentUrl.split('?')[0];
                    }
                    
                    // Fix URLs that might end with ? after parameter removal
                    if (cleanUrl.endsWith('?')) {
                        cleanUrl = cleanUrl.slice(0, -1);
                    }
                    
                    // Set the correct href
                    if (element.tagName === 'A') {
                        element.href = cleanUrl;
                        console.log("Set href to:", cleanUrl);
                        
                        // Add a strong click handler that takes precedence
                        element.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            console.log("Language switcher clicked, navigating to:", cleanUrl);
                            window.location.href = cleanUrl;
                            return false;
                        }, true);
                        
                        // Mark this as fixed
                        element.setAttribute('data-fixed-lang-link', 'true');
                    } else if (element.classList.contains('skills-lang-current')) {
                        // For the current language display, add a click handler for the parent dropdown
                        element.addEventListener('click', function(e) {
                            // When dropdown is clicked, find and fix the English option inside it
                            setTimeout(function() {
                                const englishOption = element.parentNode.querySelector('a[data-fixed-lang-link="true"]');
                                if (!englishOption) {
                                    // If no fixed link found, find and fix all English options in this dropdown
                                    fixAllEnglishLinks();
                                }
                            }, 50);
                        });
                    }
                }
            });
        }
        
        // Run the fix immediately
        fixAllEnglishLinks();
        
        // Run again after a delay to catch any dynamically loaded content
        setTimeout(fixAllEnglishLinks, 500);
        setTimeout(fixAllEnglishLinks, 1500);
        
        // Also fix clicks on dropdown toggles
        document.querySelectorAll('.skills-lang-current, .skills-language-switcher .dropdown-toggle').forEach(function(toggle) {
            toggle.addEventListener('click', function() {
                // Short timeout to let the dropdown open
                setTimeout(fixAllEnglishLinks, 50);
            });
        });
        
        // Add an observer to detect DOM changes and fix any new language switcher elements
        const observer = new MutationObserver(function(mutations) {
            let needsUpdate = false;
            
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                    for (let i = 0; i < mutation.addedNodes.length; i++) {
                        const node = mutation.addedNodes[i];
                        if (node.nodeType === 1) { // Element node
                            if (
                                node.classList && (
                                    node.classList.contains('skills-lang-options') ||
                                    node.classList.contains('skills-mobile-lang-selector') ||
                                    node.classList.contains('skills-dropdown-menu')
                                )
                            ) {
                                needsUpdate = true;
                                break;
                            }
                        }
                    }
                }
            });
            
            if (needsUpdate) {
                fixAllEnglishLinks();
            }
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'skills_language_switcher_emergency_fix', 99999); // Highest priority