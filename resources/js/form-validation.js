/**
 * Advanced Form Validation System
 * Validates all forms with real-time feedback and visual indicators
 */

class FormValidator {
    constructor() {
        this.init();
    }

    init() {
        document.addEventListener('DOMContentLoaded', () => {
            this.setupAllForms();
        });
    }

    setupAllForms() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => this.setupForm(form));
    }

    setupForm(form) {
        const inputs = form.querySelectorAll('input, textarea, select');
        
        inputs.forEach(input => {
            if (input.type === 'hidden' || input.type === 'submit' || input.type === 'button') {
                return;
            }

            // Real-time validation on blur
            input.addEventListener('blur', () => this.validateField(input));

            // Real-time validation on input (with debounce)
            let validationTimeout;
            input.addEventListener('input', () => {
                clearTimeout(validationTimeout);
                validationTimeout = setTimeout(() => this.validateField(input), 300);
            });

            // Validation on change
            input.addEventListener('change', () => this.validateField(input));
        });

        // Form submit validation
        form.addEventListener('submit', (e) => {
            if (!this.validateForm(form)) {
                e.preventDefault();
                this.showAlert('Veuillez corriger les erreurs dans le formulaire', 'error');
            }
        });
    }

    validateField(field) {
        const value = field.value.trim();
        const fieldType = field.type;
        const fieldName = field.name || field.id;
        const isRequired = field.hasAttribute('required');
        const minLength = field.getAttribute('minlength');
        const maxLength = field.getAttribute('maxlength');
        const pattern = field.getAttribute('pattern');
        const min = field.getAttribute('min');
        const max = field.getAttribute('max');
        
        let errorMessage = '';

        // Check required
        if (isRequired && !value) {
            errorMessage = 'Ce champ est obligatoire';
            this.showFieldError(field, errorMessage);
            return false;
        }

        // Skip validation if empty and not required
        if (!isRequired && !value) {
            this.clearFieldError(field);
            this.showFieldSuccess(field);
            return true;
        }

        // Type-specific validation
        switch (fieldType) {
            case 'email':
                if (!this.isValidEmail(value)) {
                    errorMessage = 'Adresse email invalide';
                }
                break;
            case 'tel':
                if (!this.isValidPhone(value)) {
                    errorMessage = 'Numéro de téléphone invalide (minimum 10 chiffres)';
                }
                break;
            case 'number':
                if (isNaN(value)) {
                    errorMessage = 'Doit être un nombre valide';
                } else {
                    if (min && parseInt(value) < parseInt(min)) {
                        errorMessage = `Minimum: ${min}`;
                    }
                    if (max && parseInt(value) > parseInt(max)) {
                        errorMessage = `Maximum: ${max}`;
                    }
                }
                break;
            case 'date':
                if (!this.isValidDate(value)) {
                    errorMessage = 'Date invalide';
                }
                break;
            case 'password':
                if (value.length < 6) {
                    errorMessage = 'Le mot de passe doit contenir au moins 6 caractères';
                }
                break;
            case 'url':
                if (!this.isValidUrl(value)) {
                    errorMessage = 'URL invalide';
                }
                break;
            case 'text':
                // Check if it's meant to be email
                if (fieldName.toLowerCase().includes('email') && value && !this.isValidEmail(value)) {
                    errorMessage = 'Email invalide';
                }
                break;
        }

        // Check length constraints
        if (!errorMessage && minLength && value.length < parseInt(minLength)) {
            errorMessage = `Minimum ${minLength} caractères`;
        }
        if (!errorMessage && maxLength && value.length > parseInt(maxLength)) {
            errorMessage = `Maximum ${maxLength} caractères`;
        }

        // Check pattern
        if (!errorMessage && pattern && value && !new RegExp(pattern).test(value)) {
            errorMessage = 'Format invalide';
        }

        if (errorMessage) {
            this.showFieldError(field, errorMessage);
            return false;
        } else {
            this.clearFieldError(field);
            this.showFieldSuccess(field);
            return true;
        }
    }

    validateForm(form) {
        const inputs = form.querySelectorAll('input, textarea, select');
        let isValid = true;

        inputs.forEach(input => {
            if (input.type !== 'hidden' && input.type !== 'submit' && input.type !== 'button') {
                if (!this.validateField(input)) {
                    isValid = false;
                }
            }
        });

        return isValid;
    }

    showFieldError(field, message) {
        // Update field styles
        field.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500', 'bg-red-50');
        field.classList.remove('border-green-500', 'border-gray-300', 'focus:ring-green-500', 'focus:border-green-500', 'focus:ring-blue-500', 'focus:border-blue-500', 'bg-white');

        // Remove old error message
        const oldError = field.parentElement.querySelector('.form-error-message');
        if (oldError) oldError.remove();

        // Add new error message
        const errorDiv = document.createElement('p');
        errorDiv.className = 'form-error-message text-red-600 text-sm mt-1 flex items-center gap-1';
        errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
        field.parentElement.appendChild(errorDiv);
    }

    showFieldSuccess(field) {
        // Update field styles
        field.classList.add('border-green-500', 'focus:ring-green-500', 'focus:border-green-500');
        field.classList.remove('border-red-500', 'border-gray-300', 'focus:ring-red-500', 'focus:border-red-500', 'focus:ring-blue-500', 'focus:border-blue-500', 'bg-red-50');

        // Remove error message if exists
        const errorDiv = field.parentElement.querySelector('.form-error-message');
        if (errorDiv) errorDiv.remove();
    }

    clearFieldError(field) {
        field.classList.remove('border-red-500', 'border-green-500', 'focus:ring-red-500', 'focus:border-red-500', 'focus:ring-green-500', 'focus:border-green-500', 'bg-red-50');
        field.classList.add('border-gray-300', 'focus:ring-blue-500', 'focus:border-blue-500');

        const errorDiv = field.parentElement.querySelector('.form-error-message');
        if (errorDiv) errorDiv.remove();
    }

    // Validation utilities
    isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    isValidPhone(phone) {
        return /^[\d\s\-\+\(\)]{10,}$/.test(phone);
    }

    isValidDate(date) {
        return !isNaN(Date.parse(date)) && /^\d{4}-\d{2}-\d{2}/.test(date);
    }

    isValidUrl(url) {
        try {
            new URL(url);
            return true;
        } catch (e) {
            return false;
        }
    }

    // Alert system
    showAlert(message, type = 'success') {
        const alertDiv = document.createElement('div');
        const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
        const icon = type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';
        
        alertDiv.className = `fixed top-4 right-4 px-6 py-4 rounded-lg text-white font-medium shadow-lg z-50 ${bgColor} flex items-center gap-2`;
        alertDiv.innerHTML = `<i class="fas ${icon}"></i> <span>${message}</span>`;
        document.body.appendChild(alertDiv);

        // Show animation
        alertDiv.style.opacity = '0';
        alertDiv.style.transform = 'translateX(400px)';
        alertDiv.style.transition = 'all 0.3s ease';
        setTimeout(() => {
            alertDiv.style.opacity = '1';
            alertDiv.style.transform = 'translateX(0)';
        }, 10);

        // Auto-remove after 4 seconds
        setTimeout(() => {
            alertDiv.style.opacity = '0';
            alertDiv.style.transform = 'translateX(400px)';
            setTimeout(() => alertDiv.remove(), 300);
        }, 4000);
    }
}

// Initialize validator
const formValidator = new FormValidator();

// Expose for external use
window.showAlert = (message, type = 'success') => formValidator.showAlert(message, type);
window.validateField = (field) => formValidator.validateField(field);
window.validateForm = (form) => formValidator.validateForm(form);
