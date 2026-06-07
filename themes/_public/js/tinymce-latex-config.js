/**
 * Global TinyMCE Configuration with LaTeX Support
 * This file contains the standardized TinyMCE configuration used throughout the application
 * to ensure consistent LaTeX rendering capabilities in question editing and display.
 */

// Initialize MathJax if not already loaded
function initializeMathJax() {
    if (typeof window.MathJax === 'undefined') {
        var script = document.createElement('script');
        script.src = 'https://polyfill.io/v3/polyfill.min.js?features=es6';
        document.head.appendChild(script);

        var mathjaxScript = document.createElement('script');
        mathjaxScript.id = 'MathJax-script';
        mathjaxScript.async = true;
        mathjaxScript.src = 'https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js';
        document.head.appendChild(mathjaxScript);

        // Configure MathJax
        window.MathJax = {
            tex: {
                inlineMath: [['$', '$'], ['\\(', '\\)']],
                displayMath: [['$$', '$$'], ['\\[', '\\]']]
            },
            svg: {
                fontCache: 'global'
            }
        };
    }
}

// Function to clean LaTeX content from unwanted HTML formatting
function cleanLatexContent(content) {
    if (!content) return content;
    
    // Remove all HTML tags but preserve LaTeX content
    // First, temporarily replace LaTeX blocks with placeholders to protect them
    var latexPlaceholders = [];
    var placeholderIndex = 0;
    
    // Match various LaTeX formats and replace with placeholders
    content = content.replace(/\$\$[\s\S]*?\$\$/g, function(match) {
        latexPlaceholders[placeholderIndex] = match;
        return '{LATEX_PLACEHOLDER_' + placeholderIndex++ + '}';
    });
    
    content = content.replace(/\$[\s\S]*?\$/g, function(match) {
        latexPlaceholders[placeholderIndex] = match;
        return '{LATEX_PLACEHOLDER_' + placeholderIndex++ + '}';
    });
    
    content = content.replace(/\\\[[\s\S]*?\\\]/g, function(match) {
        latexPlaceholders[placeholderIndex] = match;
        return '{LATEX_PLACEHOLDER_' + placeholderIndex++ + '}';
    });
    
    content = content.replace(/\\\([\s\S]*?\\\)/g, function(match) {
        latexPlaceholders[placeholderIndex] = match;
        return '{LATEX_PLACEHOLDER_' + placeholderIndex++ + '}';
    });
    
    // Remove all HTML tags
    content = content.replace(/<[^>]*>/g, '');
    
    // Replace placeholders back with original LaTeX content
    for (var i = 0; i < latexPlaceholders.length; i++) {
        content = content.replace('{LATEX_PLACEHOLDER_' + i + '}', latexPlaceholders[i]);
    }
    
    return content;
}

// Function to initialize TinyMCE with LaTeX support
function initializeTinyMCEWithLatex(selector = '.tinymce-init', options = {}) {
    // Initialize MathJax first
    initializeMathJax();

    // Default configuration with LaTeX support
    const defaultConfig = {
        selector: selector,
        plugins: 'advlist autolink lists link image charmap print preview anchor help fullscreen',
        toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | help',
        height: 300,
        // Completely disable TinyMCE's content cleaning and formatting
        cleanup: false,
        verify_html: false,
        convert_urls: false,
        paste_as_text: true, // Force paste as plain text to prevent HTML formatting
        // Prevent TinyMCE from processing LaTeX as HTML
        extended_valid_elements: 'script[src|async|defer|type]',
        custom_elements: 'mjx-container,mjx-assistive-mml',
        valid_children: '+body[mjx-container],+mjx-container[*]',
        // Preserve whitespace and prevent auto-formatting of LaTeX
        preserve_whitespace: true,
        forced_root_block: false, // Disable forced root blocks to prevent <p> wrapping
        force_p_newlines: false,
        force_br_newlines: false,
        entity_encoding: 'raw',
        entities: '160,nbsp,38,amp,34,quot,162,cent,8364,euro,163,pound,165,yen,169,copy,174,reg,8482,trade,8216,lsquo,8217,rsquo,8218,sbquo,8220,ldquo,8221,rdquo,8222,bdquo,8224,dagger,8225,Dagger,8240,permil,8249,lsaquo,8250,rsaquo,171,laquo,187,raquo',
        init_instance_callback: function(editor) {
            // Add MathJax support for live preview of LaTeX equations
            editor.on('init', function() {
                // Ensure MathJax is configured
                if (typeof window.MathJax === 'undefined') {
                    initializeMathJax();
                }
            });

            // Render MathJax when content is loaded
            editor.on('SetContent', function() {
                // Clean content first
                let content = editor.getContent({format: 'raw'});
                content = cleanLatexContent(content);
                editor.setContent(content, {format: 'raw'});
                
                // Delay MathJax rendering to ensure content is fully loaded
                setTimeout(function() {
                    if (window.MathJax && typeof window.MathJax.typeset === 'function') {
                        // Use typeset if available (MathJax 3.x)
                        MathJax.typeset([`#${editor.id}`]);
                    } else if (window.MathJax && typeof window.MathJax.Hub !== 'undefined') {
                        // Fallback to older Hub.Queue method (MathJax 2.x)
                        MathJax.Hub.Queue(["Typeset", MathJax.Hub, `#${editor.id}`]);
                    }
                }, 100);
            });

            // Render MathJax when content changes
            editor.on('keyup', function() {
                // Delay MathJax rendering to avoid performance issues
                setTimeout(function() {
                    if (window.MathJax && typeof window.MathJax.typeset === 'function') {
                        // Use typeset if available (MathJax 3.x)
                        MathJax.typeset([`#${editor.id}`]);
                    } else if (window.MathJax && typeof window.MathJax.Hub !== 'undefined') {
                        // Fallback to older Hub.Queue method (MathJax 2.x)
                        MathJax.Hub.Queue(["Typeset", MathJax.Hub, `#${editor.id}`]);
                    }
                }, 300);
            });
            
            // Handle paste events to ensure plain text paste and clean content
            editor.on('paste', function(event) {
                // Prevent default paste behavior
                event.preventDefault();
                
                // Get clipboard data
                var clipboardData = event.clipboardData || window.clipboardData;
                var pastedData = clipboardData.getData('text/plain');
                
                // Insert the plain text data
                editor.insertContent(pastedData);
                
                // Clean the content after paste
                setTimeout(function() {
                    let content = editor.getContent({format: 'raw'});
                    content = cleanLatexContent(content);
                    editor.setContent(content, {format: 'raw'});
                    
                    // Re-render MathJax after cleaning up content
                    setTimeout(function() {
                        if (window.MathJax && typeof window.MathJax.typeset === 'function') {
                            MathJax.typeset([`#${editor.id}`]);
                        } else if (window.MathJax && typeof window.MathJax.Hub !== 'undefined') {
                            MathJax.Hub.Queue(["Typeset", MathJax.Hub, `#${editor.id}`]);
                        }
                    }, 100);
                }, 10);
            });
        },
        setup: function(editor) {
            // Custom setup function for additional configuration
            editor.on('init', function() {
                console.log('TinyMCE with LaTeX support initialized for:', editor.id);
                
                // Initial typesetting after editor is fully loaded
                setTimeout(function() {
                    if (window.MathJax && typeof window.MathJax.typeset === 'function') {
                        MathJax.typeset([`#${editor.id}`]);
                    } else if (window.MathJax && typeof window.MathJax.Hub !== 'undefined') {
                        MathJax.Hub.Queue(["Typeset", MathJax.Hub, `#${editor.id}`]);
                    }
                }, 500);
            });
        }
    };

    // Merge user options with default configuration
    const config = Object.assign({}, defaultConfig, options);

    // Initialize TinyMCE
    tinymce.init(config);
}

// Function to render LaTeX in plain HTML elements (for display purposes)
function renderLatexInElement(element) {
    if (window.MathJax && typeof window.MathJax.typeset === 'function') {
        // Use typeset if available (MathJax 3.x)
        MathJax.typeset([element]);
    } else if (window.MathJax && typeof window.MathJax.Hub !== 'undefined') {
        // Fallback to older Hub.Queue method (MathJax 2.x)
        MathJax.Hub.Queue(["Typeset", MathJax.Hub, element]);
    }
}

// Function to render LaTeX in all elements with a specific class
function renderLatexInElements(className = '.latex-content') {
    const elements = document.querySelectorAll(className);
    if (elements.length > 0) {
        if (window.MathJax && typeof window.MathJax.typeset === 'function') {
            // Use typeset if available (MathJax 3.x)
            MathJax.typeset(elements);
        } else if (window.MathJax && typeof window.MathJax.Hub !== 'undefined') {
            // Fallback to older Hub.Queue method (MathJax 2.x)
            MathJax.Hub.Queue(["Typeset", MathJax.Hub, elements]);
        }
    }
}

// Initialize MathJax when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initializeMathJax();
    
    // Automatically initialize TinyMCE with LaTeX support for elements with .tinymce-init class
    if (typeof tinymce !== 'undefined') {
        // Wait a bit to ensure TinyMCE is fully loaded
        setTimeout(function() {
            initializeTinyMCEWithLatex();
        }, 100);
    }
});

// Export functions for use in other modules (if using modules)
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        initializeTinyMCEWithLatex,
        renderLatexInElement,
        renderLatexInElements
    };
}