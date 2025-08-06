// Hotel Management System JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize carousel
    initCarousel();
    
    // Initialize booking form
    initBookingForm();
    
    // Initialize mobile menu
    initMobileMenu();
    
    // Initialize scroll effects
    initScrollEffects();
    
    // Initialize smooth scrolling
    initSmoothScrolling();
});

// Carousel functionality
function initCarousel() {
    const slides = document.querySelectorAll('.carousel-slide');
    const dots = document.querySelectorAll('.dot');
    let currentSlide = 0;
    
    if (slides.length === 0) return;
    
    function showSlide(index) {
        // Hide all slides
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));
        
        // Show current slide
        slides[index].classList.add('active');
        dots[index].classList.add('active');
    }
    
    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }
    
    // Auto advance slides every 5 seconds
    setInterval(nextSlide, 5000);
    
    // Dot click handlers
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentSlide = index;
            showSlide(currentSlide);
        });
    });
}

// Booking form functionality
function initBookingForm() {
    const form = document.getElementById('bookingForm');
    
    if (form) {
        // Price calculation
        const roomTypeSelect = document.getElementById('room_type');
        const checkInInput = document.getElementById('check_in');
        const checkOutInput = document.getElementById('check_out');
        
        const roomPrices = {
            'standard': 120000,
            'deluxe': 180000,
            'suite': 280000
        };
        
        function calculatePrice() {
            const roomType = roomTypeSelect.value;
            const checkIn = new Date(checkInInput.value);
            const checkOut = new Date(checkOutInput.value);
            
            if (roomType && checkIn && checkOut && checkOut > checkIn) {
                const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
                const roomRate = roomPrices[roomType];
                const subtotal = roomRate * nights;
                const tax = subtotal * 0.18;
                const total = subtotal + tax;
                
                document.getElementById('roomRate').textContent = 'RWF ' + roomRate.toLocaleString();
                document.getElementById('nights').textContent = nights;
                document.getElementById('subtotal').textContent = 'RWF ' + subtotal.toLocaleString();
                document.getElementById('tax').textContent = 'RWF ' + tax.toLocaleString();
                document.getElementById('total').textContent = 'RWF ' + total.toLocaleString();
            }
        }
        
        // Event listeners for price calculation
        roomTypeSelect.addEventListener('change', calculatePrice);
        checkInInput.addEventListener('change', calculatePrice);
        checkOutInput.addEventListener('change', calculatePrice);
        
        // Set minimum date for check-in and check-out
        const today = new Date().toISOString().split('T')[0];
        checkInInput.setAttribute('min', today);
        checkOutInput.setAttribute('min', today);
        
        // Update check-out minimum date when check-in changes
        checkInInput.addEventListener('change', function() {
            if (this.value) {
                const nextDay = new Date(this.value);
                nextDay.setDate(nextDay.getDate() + 1);
                checkOutInput.setAttribute('min', nextDay.toISOString().split('T')[0]);
            }
        });
        
        // Form validation
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (validateBookingForm()) {
                // Show loading state
                const submitBtn = form.querySelector('.btn-submit');
                const originalText = submitBtn.textContent;
                submitBtn.textContent = 'Processing...';
                submitBtn.disabled = true;
                
                // Submit the form
                setTimeout(() => {
                    form.submit();
                }, 1000);
            }
        });
        
        // Real-time validation
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                // Clear error when user starts typing
                const errorElement = document.getElementById(this.id + 'Error');
                if (errorElement) {
                    errorElement.textContent = '';
                }
                this.classList.remove('error-input');
            });
        });
        

    }
}

// Form validation functions
function validateBookingForm() {
    const requiredFields = [
        'room_type', 'guests', 'check_in', 'check_out',
        'first_name', 'last_name', 'email', 'phone', 'address'
    ];
    
    let isValid = true;
    
    requiredFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field && !validateField(field)) {
            isValid = false;
        }
    });
    
    // Validate checkboxes
    const agreeTerms = document.getElementById('agree_terms');
    const agreeCancellation = document.getElementById('agree_cancellation');
    
    if (agreeTerms && !agreeTerms.checked) {
        showError(agreeTerms, 'agreeTermsError', 'You must agree to the terms and conditions');
        isValid = false;
    }
    
    if (agreeCancellation && !agreeCancellation.checked) {
        showError(agreeCancellation, 'agreeCancellationError', 'You must agree to the cancellation policy');
        isValid = false;
    }
    
    return isValid;
}

function validateField(field) {
    const errorElement = document.getElementById(field.id + 'Error');
    if (!errorElement) return true;
    
    let isValid = true;
    let errorMessage = '';
    
    switch(field.id) {
        case 'first_name':
        case 'last_name':
            if (!field.value.trim() || field.value.trim().length < 2) {
                isValid = false;
                errorMessage = field.value.trim() ? 'Name must be at least 2 characters long' : 'This field is required';
            }
            break;
            
        case 'email':
            if (!field.value.trim() || !isValidEmail(field.value)) {
                isValid = false;
                errorMessage = field.value.trim() ? 'Please enter a valid email address' : 'Email is required';
            }
            break;
            
        case 'phone':
            if (!field.value.trim() || !isValidPhone(field.value)) {
                isValid = false;
                errorMessage = field.value.trim() ? 'Please enter a valid phone number' : 'Phone number is required';
            }
            break;
            

            
        case 'check_in':
        case 'check_out':
            if (!field.value) {
                isValid = false;
                errorMessage = 'Date is required';
            } else if (field.id === 'check_out') {
                const checkIn = new Date(document.getElementById('check_in').value);
                const checkOut = new Date(field.value);
                if (checkOut <= checkIn) {
                    isValid = false;
                    errorMessage = 'Check-out date must be after check-in date';
                }
            }
            break;
            
        default:
            if (field.hasAttribute('required') && !field.value.trim()) {
                isValid = false;
                errorMessage = 'This field is required';
            }
    }
    
    if (!isValid) {
        showError(field, field.id + 'Error', errorMessage);
    } else {
        clearError(field, field.id + 'Error');
    }
    
    return isValid;
}

// Utility functions
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function isValidPhone(phone) {
    const phoneRegex = /^[\+]?[0-9\s\-\(\)]{10,}$/;
    return phoneRegex.test(phone);
}



function showError(field, errorId, message) {
    const errorElement = document.getElementById(errorId);
    if (errorElement) {
        errorElement.textContent = message;
    }
    field.classList.add('error-input');
}

function clearError(field, errorId) {
    const errorElement = document.getElementById(errorId);
    if (errorElement) {
        errorElement.textContent = '';
    }
    field.classList.remove('error-input');
}

// Mobile menu functionality
function initMobileMenu() {
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');
    
    if (hamburger && navMenu) {
        hamburger.addEventListener('click', function() {
            hamburger.classList.toggle('active');
            navMenu.classList.toggle('active');
        });
        
        // Close menu when clicking on a link
        const navLinks = document.querySelectorAll('.nav-menu a');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
            });
        });
    }
}

// Scroll effects
function initScrollEffects() {
    window.addEventListener('scroll', function() {
        const header = document.querySelector('header');
        if (window.scrollY > 100) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });
}

// Smooth scrolling
function initSmoothScrolling() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Availability check form
function initAvailabilityCheck() {
    const availabilityForm = document.querySelector('form[action="check_availability.php"]');
    
    if (availabilityForm) {
        availabilityForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const checkIn = document.getElementById('check_in');
            const checkOut = document.getElementById('check_out');
            const guests = document.getElementById('guests');
            const roomType = document.getElementById('room_type');
            
            if (!checkIn.value || !checkOut.value || !guests.value || !roomType.value) {
                alert('Please fill in all required fields');
                return;
            }
            
            // Show loading state
            const submitBtn = availabilityForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Checking...';
            submitBtn.disabled = true;
            
            // Simulate availability check
            setTimeout(() => {
                showAvailabilityResult('Rooms are available for your selected dates!');
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }, 1500);
        });
    }
}

function showAvailabilityResult(message) {
    const resultDiv = document.createElement('div');
    resultDiv.className = 'availability-result';
    resultDiv.innerHTML = `
        <h3>Availability Check</h3>
        <p>${message}</p>
        <a href="booking.php" class="btn-primary">Book Now</a>
    `;
    
    const form = document.querySelector('form[action="check_availability.php"]');
    form.parentNode.insertBefore(resultDiv, form.nextSibling);
    
    setTimeout(() => {
        resultDiv.remove();
    }, 10000);
}

// Initialize availability check
initAvailabilityCheck(); 