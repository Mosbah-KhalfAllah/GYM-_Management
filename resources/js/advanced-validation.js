/**
 * Advanced Form Validation with Enhanced Security
 */

class AdvancedValidator {
    constructor() {
        this.patterns = {
            email: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
            phone: /^[\d\s\+\-\(\)]{10,}$/,
            strongPassword: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/,
            name: /^[a-zA-ZÀ-ÿ\s\'-]{2,50}$/,
            alphanumeric: /^[a-zA-Z0-9\s]+$/,
            numeric: /^\d+(\.\d+)?$/,
            url: /^https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)$/
        };
        
        this.securityRules = {
            maxLength: 1000,
            forbiddenChars: /<script|javascript:|data:|vbscript:|onload|onerror/i,
            sqlInjection: /(\b(SELECT|INSERT|UPDATE|DELETE|DROP|CREATE|ALTER|EXEC|UNION)\b)|('|('')|;|--|\/\*|\*\/)/i
        };
        
        this.init();
    }

    init() {
        document.addEventListener('DOMContentLoaded', () => {
            this.setupRealTimeValidation();
            this.setupSecurityChecks();
            this.setupPasswordStrength();
            this.setupFileValidation();
        });
    }

    setupRealTimeValidation() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input, textarea, select');
            
            inputs.forEach(input => {
                if (this.shouldValidate(input)) {
                    // Real-time validation
                    input.addEventListener('input', (e) => this.debounce(() => this.validateField(e.target), 300));
                    input.addEventListener('blur', (e) => this.validateField(e.target));
                    input.addEventListener('paste', (e) => this.handlePaste(e));
                }
            });

            form.addEventListener('submit', (e) => this.handleSubmit(e));
        });
    }

    setupSecurityChecks() {
        document.addEventListener('input', (e) => {
            if (e.target.matches('input, textarea')) {
                this.checkSecurity(e.target);
            }
        });
    }

    setupPasswordStrength() {
        const passwordInputs = document.querySelectorAll('input[type="password"]');
        passwordInputs.forEach(input => {
            this.createPasswordStrengthIndicator(input);
            input.addEventListener('input', () => this.updatePasswordStrength(input));
        });
    }

    setupFileValidation() {
        const fileInputs = document.querySelectorAll('input[type="file"]');
        fileInputs.forEach(input => {
            input.addEventListener('change', (e) => this.validateFile(e.target));
        });
    }

    validateField(field) {
        const value = field.value.trim();
        const fieldType = field.type;
        const fieldName = field.name || field.id;
        
        // Security check first
        if (!this.checkSecurity(field)) {
            return false;
        }

        // Required validation
        if (field.hasAttribute('required') && !value) {
            this.showError(field, 'Ce champ est obligatoire');
            return false;
        }

        // Skip if empty and not required
        if (!value && !field.hasAttribute('required')) {
            this.clearValidation(field);
            return true;
        }

        // Type-specific validation
        let isValid = true;
        let errorMessage = '';

        switch (fieldType) {
            case 'email':
                if (!this.patterns.email.test(value)) {
                    errorMessage = 'Format d\'email invalide';
                    isValid = false;
                }
                break;
            
            case 'tel':
                if (!this.patterns.phone.test(value)) {
                    errorMessage = 'Numéro de téléphone invalide (min. 10 chiffres)';
                    isValid = false;
                }
                break;
            
            case 'password':
                const strength = this.getPasswordStrength(value);
                if (strength < 3) {
                    errorMessage = 'Mot de passe trop faible (min. 8 caractères, majuscule, minuscule, chiffre, caractère spécial)';
                    isValid = false;
                }
                break;
            
            case 'number':
                if (!this.patterns.numeric.test(value)) {
                    errorMessage = 'Doit être un nombre valide';
                    isValid = false;
                } else {
                    const min = parseFloat(field.getAttribute('min'));
                    const max = parseFloat(field.getAttribute('max'));
                    const numValue = parseFloat(value);
                    
                    if (!isNaN(min) && numValue < min) {
                        errorMessage = `Valeur minimum: ${min}`;
                        isValid = false;
                    }
                    if (!isNaN(max) && numValue > max) {
                        errorMessage = `Valeur maximum: ${max}`;
                        isValid = false;
                    }
                }
                break;
            
            case 'url':
                if (!this.patterns.url.test(value)) {
                    errorMessage = 'URL invalide (doit commencer par http:// ou https://)';
                    isValid = false;
                }
                break;
            
            case 'date':
                const date = new Date(value);
                if (isNaN(date.getTime())) {
                    errorMessage = 'Date invalide';
                    isValid = false;
                }
                break;
            
            default:
                // Name fields
                if (fieldName.includes('name') || fieldName.includes('nom') || fieldName.includes('prenom')) {
                    if (!this.patterns.name.test(value)) {
                        errorMessage = 'Nom invalide (lettres, espaces, tirets et apostrophes uniquement)';
                        isValid = false;
                    }
                }
                // Email fields (text input with email name)
                else if (fieldName.includes('email') && !this.patterns.email.test(value)) {
                    errorMessage = 'Format d\'email invalide';
                    isValid = false;
                }
        }

        // Length validation
        const minLength = parseInt(field.getAttribute('minlength'));
        const maxLength = parseInt(field.getAttribute('maxlength'));
        
        if (!isNaN(minLength) && value.length < minLength) {
            errorMessage = `Minimum ${minLength} caractères`;
            isValid = false;
        }
        if (!isNaN(maxLength) && value.length > maxLength) {
            errorMessage = `Maximum ${maxLength} caractères`;
            isValid = false;
        }

        // Pattern validation
        const pattern = field.getAttribute('pattern');
        if (pattern && !new RegExp(pattern).test(value)) {
            errorMessage = 'Format invalide';
            isValid = false;
        }

        if (isValid) {
            this.showSuccess(field);
        } else {
            this.showError(field, errorMessage);
        }

        return isValid;
    }

    checkSecurity(field) {
        const value = field.value;
        
        // Check for XSS attempts
        if (this.securityRules.forbiddenChars.test(value)) {
            this.showError(field, 'Caractères interdits détectés');
            field.value = value.replace(this.securityRules.forbiddenChars, '');
            return false;
        }
        
        // Check for SQL injection attempts
        if (this.securityRules.sqlInjection.test(value)) {
            this.showError(field, 'Contenu suspect détecté');
            return false;
        }
        
        // Check maximum length
        if (value.length > this.securityRules.maxLength) {
            this.showError(field, `Longueur maximale dépassée (${this.securityRules.maxLength} caractères)`);
            field.value = value.substring(0, this.securityRules.maxLength);
            return false;
        }
        
        return true;
    }

    getPasswordStrength(password) {
        let strength = 0;
        
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/\d/.test(password)) strength++;
        if (/[@$!%*?&]/.test(password)) strength++;
        
        return strength;
    }

    createPasswordStrengthIndicator(input) {
        const indicator = document.createElement('div');
        indicator.className = 'password-strength-indicator mt-2';
        indicator.innerHTML = `
            <div class="flex gap-1 mb-1">
                <div class="strength-bar flex-1 h-2 bg-gray-200 rounded"></div>
                <div class="strength-bar flex-1 h-2 bg-gray-200 rounded"></div>
                <div class="strength-bar flex-1 h-2 bg-gray-200 rounded"></div>
                <div class="strength-bar flex-1 h-2 bg-gray-200 rounded"></div>
                <div class="strength-bar flex-1 h-2 bg-gray-200 rounded"></div>
            </div>
            <div class="strength-text text-sm text-gray-600"></div>
        `;
        input.parentElement.appendChild(indicator);
    }

    updatePasswordStrength(input) {
        const strength = this.getPasswordStrength(input.value);
        const indicator = input.parentElement.querySelector('.password-strength-indicator');
        
        if (!indicator) return;
        
        const bars = indicator.querySelectorAll('.strength-bar');
        const text = indicator.querySelector('.strength-text');
        
        const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-blue-500', 'bg-green-500'];
        const labels = ['Très faible', 'Faible', 'Moyen', 'Fort', 'Très fort'];
        
        bars.forEach((bar, index) => {
            bar.className = 'strength-bar flex-1 h-2 rounded ' + 
                (index < strength ? colors[strength - 1] : 'bg-gray-200');
        });
        
        text.textContent = input.value ? labels[strength - 1] || 'Très faible' : '';
    }

    validateFile(input) {
        const file = input.files[0];
        if (!file) return true;
        
        const maxSize = 5 * 1024 * 1024; // 5MB
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
        
        if (file.size > maxSize) {
            this.showError(input, 'Fichier trop volumineux (max. 5MB)');
            input.value = '';
            return false;
        }
        
        if (!allowedTypes.includes(file.type)) {
            this.showError(input, 'Type de fichier non autorisé');
            input.value = '';
            return false;
        }
        
        this.showSuccess(input);
        return true;
    }

    handlePaste(e) {
        setTimeout(() => {
            this.validateField(e.target);
        }, 10);
    }

    handleSubmit(e) {
        const form = e.target;
        const inputs = form.querySelectorAll('input, textarea, select');
        let isValid = true;
        
        inputs.forEach(input => {
            if (this.shouldValidate(input) && !this.validateField(input)) {
                isValid = false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            this.showAlert('Veuillez corriger les erreurs dans le formulaire', 'error');
            
            // Focus on first error
            const firstError = form.querySelector('.border-red-500');
            if (firstError) {
                firstError.focus();
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    }

    shouldValidate(input) {
        return input.type !== 'hidden' && 
               input.type !== 'submit' && 
               input.type !== 'button' &&
               !input.disabled;
    }

    showError(field, message) {
        field.classList.remove('border-green-500', 'border-gray-300');
        field.classList.add('border-red-500', 'bg-red-50');
        
        this.removeMessage(field);
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'validation-message text-red-600 text-sm mt-1 flex items-center gap-1';
        errorDiv.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ${message}`;
        field.parentElement.appendChild(errorDiv);
    }

    showSuccess(field) {
        field.classList.remove('border-red-500', 'border-gray-300', 'bg-red-50');
        field.classList.add('border-green-500');
        
        this.removeMessage(field);
        
        const successDiv = document.createElement('div');
        successDiv.className = 'validation-message text-green-600 text-sm mt-1 flex items-center gap-1';
        successDiv.innerHTML = `<i class="fas fa-check-circle"></i> Valide`;
        field.parentElement.appendChild(successDiv);
    }

    clearValidation(field) {
        field.classList.remove('border-red-500', 'border-green-500', 'bg-red-50');
        field.classList.add('border-gray-300');
        this.removeMessage(field);
    }

    removeMessage(field) {
        const existing = field.parentElement.querySelector('.validation-message');
        if (existing) existing.remove();
    }

    showAlert(message, type = 'success') {
        const alertDiv = document.createElement('div');
        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            warning: 'bg-yellow-500',
            info: 'bg-blue-500'
        };
        
        alertDiv.className = `fixed top-4 right-4 px-6 py-4 rounded-lg text-white font-medium shadow-lg z-50 ${colors[type]} flex items-center gap-2 transform translate-x-full transition-transform duration-300`;
        alertDiv.innerHTML = `<i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'check-circle'}"></i> ${message}`;
        
        document.body.appendChild(alertDiv);
        
        setTimeout(() => alertDiv.classList.remove('translate-x-full'), 100);
        setTimeout(() => {
            alertDiv.classList.add('translate-x-full');
            setTimeout(() => alertDiv.remove(), 300);
        }, 4000);
    }

    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
}

// Initialize
const advancedValidator = new AdvancedValidator();

// Export for global use
window.showAlert = (message, type) => advancedValidator.showAlert(message, type);
window.validateField = (field) => advancedValidator.validateField(field);