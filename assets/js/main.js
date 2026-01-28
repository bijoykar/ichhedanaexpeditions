/**
 * Wings of Desire - Main JavaScript
 * jQuery-based functionality
 */

(function($) {
    'use strict';
    
    // Document ready
    $(document).ready(function() {
        
        // Mobile menu toggle
        $('#mobileMenuToggle').on('click', function() {
            $(this).toggleClass('active');
            $('#navMenu').toggleClass('active');
        });
        
        // Close mobile menu when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.nav-wrapper').length) {
                $('#mobileMenuToggle').removeClass('active');
                $('#navMenu').removeClass('active');
            }
        });
        
        // Close mobile menu on link click
        $('.nav-menu a').on('click', function() {
            $('#mobileMenuToggle').removeClass('active');
            $('#navMenu').removeClass('active');
        });
        
        // Scroll to top button
        var scrollToTopBtn = $('#scrollToTop');
        
        $(window).scroll(function() {
            if ($(this).scrollTop() > 300) {
                scrollToTopBtn.addClass('show');
            } else {
                scrollToTopBtn.removeClass('show');
            }
        });
        
        scrollToTopBtn.on('click', function() {
            $('html, body').animate({scrollTop: 0}, 600);
        });
        
        // Flash message close
        $('.close-flash').on('click', function() {
            $(this).parent().fadeOut();
        });
        
        // Auto-hide flash messages after 5 seconds
        setTimeout(function() {
            $('.flash-message').fadeOut();
        }, 5000);
        
        // Contact modal
        var contactModal = $('#contactModal');
        
        // Open modal on button click
        $('[data-modal="contact"]').on('click', function(e) {
            e.preventDefault();
            contactModal.fadeIn();
        });
        
        // Close modal
        $('.modal-close, .modal').on('click', function(e) {
            if ($(e.target).hasClass('modal') || $(e.target).hasClass('modal-close')) {
                contactModal.fadeOut();
            }
        });
        
        // Close modal on ESC key
        $(document).keydown(function(e) {
            if (e.keyCode === 27 && contactModal.is(':visible')) {
                contactModal.fadeOut();
            }
        });
        
        // Contact form submission (AJAX)
        $('#contactForm').on('submit', function(e) {
            e.preventDefault();
            
            var form = $(this);
            var submitBtn = form.find('button[type="submit"]');
            var messageDiv = $('#contactFormMessage');
            
            // Disable submit button
            submitBtn.prop('disabled', true).text('Sending...');
            
            $.ajax({
                url: form.attr('action') || '/api/contact.php',
                method: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        messageDiv.html('<div class="alert alert-success">' + response.message + '</div>');
                        form[0].reset();
                        
                        setTimeout(function() {
                            contactModal.fadeOut();
                            messageDiv.html('');
                        }, 3000);
                    } else {
                        messageDiv.html('<div class="alert alert-error">' + response.message + '</div>');
                    }
                },
                error: function() {
                    messageDiv.html('<div class="alert alert-error">An error occurred. Please try again.</div>');
                },
                complete: function() {
                    submitBtn.prop('disabled', false).text('Send Message');
                }
            });
        });
        
        // Image lazy loading
        if ('loading' in HTMLImageElement.prototype) {
            $('img[data-src]').each(function() {
                $(this).attr('src', $(this).attr('data-src')).removeAttr('data-src');
            });
        }
        
        // Gallery lightbox
        $('.gallery-item').on('click', function() {
            var imgSrc = $(this).find('img').attr('src');
            var title = $(this).find('h4').text();
            
            var lightbox = $('<div class="lightbox"></div>');
            var content = $('<div class="lightbox-content"></div>');
            var img = $('<img src="' + imgSrc + '" alt="' + title + '">');
            var caption = $('<p class="lightbox-caption">' + title + '</p>');
            var close = $('<span class="lightbox-close">&times;</span>');
            
            content.append(close, img, caption);
            lightbox.append(content);
            $('body').append(lightbox);
            
            setTimeout(function() {
                lightbox.addClass('show');
            }, 10);
            
            // Close lightbox
            lightbox.on('click', function(e) {
                if ($(e.target).hasClass('lightbox') || $(e.target).hasClass('lightbox-close')) {
                    lightbox.removeClass('show');
                    setTimeout(function() {
                        lightbox.remove();
                    }, 300);
                }
            });
        });
        
        // Smooth scroll for anchor links
        $('a[href^="#"]').on('click', function(e) {
            var target = $(this.getAttribute('href'));
            if (target.length) {
                e.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 80
                }, 800);
            }
        });
        
        // Form validation
        $('form').on('submit', function(e) {
            var form = $(this);
            var isValid = true;
            
            // Check required fields
            form.find('[required]').each(function() {
                if ($(this).val().trim() === '') {
                    isValid = false;
                    $(this).addClass('error');
                } else {
                    $(this).removeClass('error');
                }
            });
            
            // Email validation
            form.find('input[type="email"]').each(function() {
                var email = $(this).val().trim();
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (email !== '' && !emailRegex.test(email)) {
                    isValid = false;
                    $(this).addClass('error');
                } else {
                    $(this).removeClass('error');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields correctly.');
            }
        });
        
        // Remove error class on input
        $('input, textarea, select').on('focus', function() {
            $(this).removeClass('error');
        });
        
        // Animated counter for statistics
        $('.stat-card h3').each(function() {
            var $this = $(this);
            var countTo = parseInt($this.text());
            
            $({countNum: 0}).animate({
                countNum: countTo
            }, {
                duration: 2000,
                easing: 'swing',
                step: function() {
                    $this.text(Math.floor(this.countNum));
                },
                complete: function() {
                    $this.text(this.countNum);
                }
            });
        });
        
        // Sticky header on scroll
        var header = $('.site-header');
        var headerHeight = header.outerHeight();
        
        $(window).scroll(function() {
            if ($(this).scrollTop() > headerHeight) {
                header.addClass('sticky');
            } else {
                header.removeClass('sticky');
            }
        });
        
        // Read more / Read less toggle
        $('.read-more-btn').on('click', function() {
            var content = $(this).prev('.expandable-content');
            if (content.hasClass('expanded')) {
                content.removeClass('expanded');
                $(this).text('Read More');
            } else {
                content.addClass('expanded');
                $(this).text('Read Less');
            }
        });
        
        // Tooltip initialization
        $('[data-tooltip]').each(function() {
            var $this = $(this);
            var tooltipText = $this.attr('data-tooltip');
            
            $this.hover(
                function() {
                    var tooltip = $('<div class="tooltip">' + tooltipText + '</div>');
                    $('body').append(tooltip);
                    
                    var offset = $this.offset();
                    tooltip.css({
                        top: offset.top - tooltip.outerHeight() - 10,
                        left: offset.left + ($this.outerWidth() / 2) - (tooltip.outerWidth() / 2)
                    }).fadeIn();
                },
                function() {
                    $('.tooltip').fadeOut(function() {
                        $(this).remove();
                    });
                }
            );
        });
        
        // Search functionality
        $('#searchForm').on('submit', function(e) {
            e.preventDefault();
            var searchQuery = $('#searchInput').val().trim();
            if (searchQuery !== '') {
                window.location.href = '/search.php?q=' + encodeURIComponent(searchQuery);
            }
        });
        
        // Add to favorites (localStorage)
        $('.add-to-favorites').on('click', function(e) {
            e.preventDefault();
            var itemId = $(this).data('id');
            var itemType = $(this).data('type');
            
            var favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
            var index = favorites.findIndex(function(item) {
                return item.id === itemId && item.type === itemType;
            });
            
            if (index === -1) {
                favorites.push({id: itemId, type: itemType});
                $(this).addClass('favorited');
                showNotification('Added to favorites!', 'success');
            } else {
                favorites.splice(index, 1);
                $(this).removeClass('favorited');
                showNotification('Removed from favorites!', 'info');
            }
            
            localStorage.setItem('favorites', JSON.stringify(favorites));
        });
        
        // Show notification
        function showNotification(message, type) {
            var notification = $('<div class="notification notification-' + type + '">' + message + '</div>');
            $('body').append(notification);
            
            setTimeout(function() {
                notification.addClass('show');
            }, 10);
            
            setTimeout(function() {
                notification.removeClass('show');
                setTimeout(function() {
                    notification.remove();
                }, 300);
            }, 3000);
        }
        
        // Print page
        $('.print-btn').on('click', function(e) {
            e.preventDefault();
            window.print();
        });
        
        // Share functionality
        $('.share-btn').on('click', function(e) {
            e.preventDefault();
            if (navigator.share) {
                navigator.share({
                    title: document.title,
                    url: window.location.href
                }).catch(function(error) {
                    console.log('Error sharing:', error);
                });
            } else {
                // Fallback: copy to clipboard
                var url = window.location.href;
                navigator.clipboard.writeText(url).then(function() {
                    showNotification('Link copied to clipboard!', 'success');
                });
            }
        });
        
    });
    
    // Window load
    $(window).on('load', function() {
        // Hide preloader if exists
        $('.preloader').fadeOut();
        
        // Animate elements on scroll
        animateOnScroll();
    });
    
    // Animate on scroll function
    function animateOnScroll() {
        var elements = $('.animate-on-scroll');
        
        function check() {
            var windowHeight = $(window).height();
            var scrollTop = $(window).scrollTop();
            
            elements.each(function() {
                var element = $(this);
                var elementTop = element.offset().top;
                
                if (elementTop < scrollTop + windowHeight - 100) {
                    element.addClass('animated');
                }
            });
        }
        
        $(window).on('scroll', check);
        check();
    }
    
})(jQuery);
