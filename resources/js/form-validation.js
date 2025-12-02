document.addEventListener('DOMContentLoaded', function() {
    // Validation des formulaires en temps réel
    const forms = document.querySelectorAll('form');

    forms.forEach(form => {
        const inputs = form.querySelectorAll('input, textarea, select');

        inputs.forEach(input => {
            // Validation au blur (perte de focus)
            input.addEventListener('blur', function() {
                validateField(this, form);
            });

            // Validation à la saisie (debounced)
            input.addEventListener('input', function() {
                clearTimeout(this.validationTimeout);
                this.validationTimeout = setTimeout(() => {
                    validateField(this, form);
                }, 500);
            });

            // Validation au change (pour selects et checkboxes)
            input.addEventListener('change', function() {
                validateField(this, form);
            });
        });

        // Validation au submit
        form.addEventListener('submit', function(e) {
            let isValid = true;
            inputs.forEach(input => {
                if (!validateField(input, form)) {
                    isValid = false;
                }
            });

            if (!isValid) {
                e.preventDefault();
                showAlert('Veuillez corriger les erreurs du formulaire', 'error');
            }
        });
    });

    function validateField(field, form) {
        const fieldName = field.name;
        const fieldValue = field.value.trim();
        const fieldType = field.type;
        const isRequired = field.hasAttribute('required');
        let errorMessage = '';

        // Réinitialiser le champ
        removeFieldError(field);

        // Vérification de la saisie obligatoire
        if (isRequired && !fieldValue) {
            errorMessage = `${fieldName} est obligatoire`;
        }

        // Validations spécifiques par type
        if (!errorMessage && fieldValue) {
            switch (fieldType) {
                case 'email':
                    if (!isValidEmail(fieldValue)) {
                        errorMessage = 'Email invalide';
                    }
                    break;
                case 'number':
                    if (isNaN(fieldValue)) {
                        errorMessage = 'Veuillez entrer un nombre valide';
                    }
                    break;
                case 'tel':
                    if (!/^[\d\s\-\+\(\)]+$/.test(fieldValue) || fieldValue.length < 10) {
                        errorMessage = 'Numéro de téléphone invalide';
                    }
                    break;
                case 'date':
                    if (!isValidDate(fieldValue)) {
                        errorMessage = 'Date invalide';
                    }
                    break;
                case 'password':
                    if (fieldValue.length < 6) {
                        errorMessage = 'Le mot de passe doit contenir au moins 6 caractères';
                    }
                    break;
                case 'text':
                    if (fieldName.includes('email') && !isValidEmail(fieldValue)) {
                        errorMessage = 'Email invalide';
                    }
                    break;
            }
        }

        // Affichage de l'erreur
        if (errorMessage) {
            displayFieldError(field, errorMessage);
            return false;
        } else {
            displayFieldSuccess(field);
            return true;
        }
    }

    function displayFieldError(field, message) {
        // Ajouter la classe d'erreur
        field.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
        field.classList.remove('border-gray-300', 'focus:ring-blue-500', 'focus:border-blue-500', 'border-green-500', 'focus:ring-green-500', 'focus:border-green-500');

        // Créer ou mettre à jour le message d'erreur
        let errorDiv = field.parentElement.querySelector('.text-red-600');
        if (!errorDiv) {
            errorDiv = document.createElement('p');
            errorDiv.className = 'mt-2 text-sm text-red-600 flex items-center';
            errorDiv.innerHTML = `
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18.101 12.93a1 1 0 00-1.414-1.414L10 15.586l-6.687-6.687a1 1 0 00-1.414 1.414l8.1 8.1a1 1 0 001.414 0l8.1-8.1z" clip-rule="evenodd" />
                </svg>
                <span>${message}</span>
            `;
            field.parentElement.appendChild(errorDiv);
        } else {
            errorDiv.querySelector('span').textContent = message;
        }
    }

    function displayFieldSuccess(field) {
        field.classList.add('border-green-500', 'focus:ring-green-500', 'focus:border-green-500');
        field.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500', 'border-gray-300', 'focus:ring-blue-500', 'focus:border-blue-500');

        // Supprimer le message d'erreur
        removeFieldError(field);
    }

    function removeFieldError(field) {
        const errorDiv = field.parentElement.querySelector('.text-red-600');
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function isValidDate(date) {
        return !isNaN(Date.parse(date));
    }

    function showAlert(message, type = 'success') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `fixed top-4 right-4 px-6 py-4 rounded-lg text-white font-medium shadow-lg z-50 ${
            type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'
        }`;
        alertDiv.textContent = message;

        document.body.appendChild(alertDiv);

        // Animation d'apparition
        alertDiv.style.opacity = '0';
        alertDiv.style.transition = 'opacity 0.3s ease';
        setTimeout(() => alertDiv.style.opacity = '1', 10);

        // Auto-suppression après 4 secondes
        setTimeout(() => {
            alertDiv.style.opacity = '0';
            setTimeout(() => alertDiv.remove(), 300);
        }, 4000);
    }

    // Exposer showAlert pour utilisation externe
    window.showAlert = showAlert;
});
