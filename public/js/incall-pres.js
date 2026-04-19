// In-call Prescription Logic (Modified from e_pres.js for AJAX and side panel)
document.addEventListener('DOMContentLoaded', () => {
    // Mock drug database (same as original)
    const drugDatabase = [
        { name: 'Amoxicillin', formulation: '500mg capsule' },
        { name: 'Paracetamol', formulation: '500mg tablet' },
        { name: 'Ibuprofen', formulation: '400mg tablet' },
        { name: 'Metformin', formulation: '850mg tablet' },
        { name: 'Omeprazole', formulation: '20mg capsule' },
        { name: 'Atorvastatin', formulation: '10mg tablet' },
        { name: 'Lisinopril', formulation: '10mg tablet' },
        { name: 'Aspirin', formulation: '75mg tablet' },
        { name: 'Salbutamol', formulation: '100mcg inhaler' },
        { name: 'Levothyroxine', formulation: '100mcg tablet' }
    ];

    const form = document.getElementById('incallPrescriptionForm');
    const drugNameInput = document.getElementById('drugName');
    const drugList = document.getElementById('drugList');
    const formulationInput = document.getElementById('formulation');
    const frequencySelect = document.getElementById('frequency');
    const customFrequencyField = document.getElementById('customFrequencyField');
    const durationValueInput = document.getElementById('durationValue');
    const durationTypeSelect = document.getElementById('durationType');
    const validUntilInput = document.getElementById('validUntil');

    // Autocomplete for drug name
    if (drugNameInput && drugList) {
        drugNameInput.addEventListener('input', function () {
            const value = this.value.trim().toLowerCase();
            if (value.length < 2) {
                drugList.classList.add('hidden');
                return;
            }
            const filtered = drugDatabase.filter(d => d.name.toLowerCase().includes(value));
            if (filtered.length > 0) {
                drugList.innerHTML = filtered.map(d =>
                    `<div class="autocomplete-item" data-name="${d.name}" data-formulation="${d.formulation}">${d.name} - ${d.formulation}</div>`
                ).join('');
                drugList.classList.remove('hidden');
            } else {
                drugList.classList.add('hidden');
            }
        });

        drugList.addEventListener('click', function (e) {
            const item = e.target.closest('.autocomplete-item');
            if (!item) return;
            drugNameInput.value = item.dataset.name || '';
            if (formulationInput) formulationInput.value = item.dataset.formulation || '';
            drugList.classList.add('hidden');
            if (window.updatePreview) updatePreview();
        });

        document.addEventListener('click', function (e) {
            if (!e.target.closest('.autocomplete-container')) {
                drugList.classList.add('hidden');
            }
        });
    }

    // Frequency behavior
    frequencySelect?.addEventListener('change', function () {
        if (customFrequencyField) customFrequencyField.classList.toggle('hidden', this.value !== 'custom');

        const timeOfDayInput = document.getElementById('timeOfDay');
        const frequencies = {
            'OD': '9:00 AM',
            'BD': '9:00 AM, 9:00 PM',
            'TDS': '9:00 AM, 2:00 PM, 9:00 PM',
            'QID': '9:00 AM, 1:00 PM, 5:00 PM, 9:00 PM',
            'Q6H': '9:00 AM, 3:00 PM, 9:00 PM, 3:00 AM',
            'Q8H': '9:00 AM, 5:00 PM, 1:00 AM'
        };
        if (timeOfDayInput && frequencies[this.value]) {
            timeOfDayInput.value = frequencies[this.value];
        }
    });

    // Duration and valid until date
    function calculateValidUntil() {
        if (!durationValueInput || !durationTypeSelect || !validUntilInput) return;
        
        const duration = parseInt(durationValueInput.value);
        const type = durationTypeSelect.value;

        if (!duration || type === 'Until stopped') return;

        const today = new Date();
        let validDate = new Date(today);

        switch (type) {
            case 'Days':
                validDate.setDate(today.getDate() + duration);
                break;
            case 'Weeks':
                validDate.setDate(today.getDate() + (duration * 7));
                break;
            case 'Months':
                validDate.setMonth(today.getMonth() + duration);
                break;
        }

        validUntilInput.value = validDate.toISOString().split('T')[0];
    }

    if (durationValueInput) durationValueInput.addEventListener('input', calculateValidUntil);
    if (durationTypeSelect) durationTypeSelect.addEventListener('change', calculateValidUntil);

    // Form Validation (Simple version since it's the same inputs)
    function validateIncallForm() {
        let hasError = false;
        const requiredFields = ['drugName', 'route', 'doseAmount', 'doseUnit', 'frequency', 'diagnosis'];
        
        requiredFields.forEach(id => {
            const el = document.getElementById(id);
            const errorEl = document.getElementById(id + 'Error');
            if (el && !el.value.trim()) {
                if (errorEl) errorEl.textContent = 'Required';
                el.classList.add('input-error');
                hasError = true;
            } else {
                if (errorEl) errorEl.textContent = '';
                if (el) el.classList.remove('input-error');
            }
        });

        return !hasError;
    }

    // AJAX Submission
    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            if (!validateIncallForm()) {
                alert('Please fill in all required fields.');
                return;
            }

            const formData = new FormData(form);
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                // Since the controller redirects, we check response.url or redirected
                if (response.ok || response.redirected || response.url.includes('created=1')) {
                    alert('Prescription created successfully!');
                    form.reset();
                    if (window.togglePrescription) togglePrescription();
                } else {
                    const errorMsg = await response.text();
                    alert('Error: ' + errorMsg);
                }
            } catch (error) {
                console.error('Submission failed', error);
                alert('Submission failed. Please try again.');
            }
        });
    }
});
