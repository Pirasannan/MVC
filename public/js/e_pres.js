document.addEventListener('DOMContentLoaded', () => {
  // Mock drug database
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

  // Elements present in the current HTML
  const form = document.getElementById('prescriptionForm');
  const drugNameInput = document.getElementById('drugName');
  const drugList = document.getElementById('drugList');
  const formulationInput = document.getElementById('formulation');
  const prnCheckbox = document.getElementById('prn');
  const prnFields = document.getElementById('prnFields');
  const frequencySelect = document.getElementById('frequency');
  const customFrequencyField = document.getElementById('customFrequencyField');
  const durationValueInput = document.getElementById('durationValue');
  const durationTypeSelect = document.getElementById('durationType');
  const validUntilInput = document.getElementById('validUntil');
  const previewContent = document.getElementById('previewContent') || null;
  const confirmBtn = document.querySelector('button[type="submit"]');


  // Autocomplete for drug name (safe: check element exists)
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
      updatePreview();
    });

    // close autocomplete when clicking outside
    document.addEventListener('click', function (e) {
      if (!e.target.closest('.autocomplete-container')) {
        drugList.classList.add('hidden');
      }
    });
  }

  // PRN toggle
  prnCheckbox?.addEventListener('change', () => {
    if (prnFields) prnFields.classList.toggle('hidden', !prnCheckbox.checked);
  });

  // Frequency select behavior (show custom field, autopopulate times, toggle PRN)
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

    // If frequency selected as PRN, show PRN fields
    if (this.value === 'PRN') {
      if (prnCheckbox) {
        prnCheckbox.checked = true;
        if (prnFields) prnFields.classList.remove('hidden');
      }
    }
  });

  // Duration calculation and valid until date
  if (durationValueInput) {
    durationValueInput.addEventListener('input', calculateValidUntil);
  }
  if (durationTypeSelect) {
    durationTypeSelect.addEventListener('change', calculateValidUntil);
  }

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


  function getFormData() {
    return {
      drugName: document.getElementById('drugName').value,
      formulation: document.getElementById('formulation').value,
      route: document.getElementById('route').value,
      brandSubstitution: false, // Set to false since field is commented out
      prn: false, // Set to false since field is commented out
      maxPer24h: null, // Set to null since field is commented out
      prnIndication: null, // Set to null since field is commented out
      doseAmount: document.getElementById('doseAmount').value,
      doseUnit: document.getElementById('doseUnit').value,
      frequency: document.getElementById('frequency').value,
      customFrequency: document.getElementById('customFrequency').value,
      timeOfDay: document.getElementById('timeOfDay').value,
      mealRelation: document.getElementById('mealRelation').value,
      durationValue: document.getElementById('durationValue').value,
      durationType: document.getElementById('durationType').value,
      specialInstructions: document.getElementById('specialInstructions').value,
      dispenseQuantity: null, // Set to null since field is commented out
      unitType: null, // Set to null since field is commented out
      repeats: document.getElementById('repeats') ? document.getElementById('repeats').value : null,
      repeatInterval: document.getElementById('repeatInterval') ? document.getElementById('repeatInterval').value : null,
      unusualQuantity: document.getElementById('unusualQuantity') ? document.getElementById('unusualQuantity').checked : false,
      unusualQuantityJustification: document.getElementById('unusualQuantityJustification') ? document.getElementById('unusualQuantityJustification').value : null,
      unusualDose: document.getElementById('unusualDose') ? document.getElementById('unusualDose').checked : false,
      unusualDoseJustification: document.getElementById('unusualDoseJustification') ? document.getElementById('unusualDoseJustification').value : null,
      emergencySupply: document.getElementById('emergencySupply') ? document.getElementById('emergencySupply').checked : false,
      diagnosis: document.getElementById('diagnosis').value,
      validUntil: document.getElementById('validUntil').value,
      pharmacy: document.getElementById('pharmacy') ? document.getElementById('pharmacy').value : null,
      repeatAuthority: document.getElementById('repeatAuthority') ? document.getElementById('repeatAuthority').value : null,
      noASL: document.getElementById('noASL') ? document.getElementById('noASL').checked : false,
      pharmacyNote: document.getElementById('pharmacyNote').value,
      doctorNotes: document.getElementById('doctorNotes').value
    };
  }

  // Validation
  function validateForm() {
    let isValid = true;
    clearErrors();

    const data = getFormData();

    // Drug name required
    if (!data.drugName.trim()) {
      showError('drugNameError', 'Drug name is required');
      isValid = false;
    }

    // Route required
    if (!data.route) {
      showError('routeError', 'Route of administration is required');
      isValid = false;
    }

    // Dose amount required
    if (!data.doseAmount || Number(data.doseAmount) <= 0) {
      showError('doseAmountError', 'Dose amount is required');
      isValid = false;
    }

    // Dose unit required
    if (!data.doseUnit) {
      showError('doseUnitError', 'Dose unit is required');
      isValid = false;
    }

    // Frequency required
    if (!data.frequency) {
      showError('frequencyError', 'Frequency is required');
      isValid = false;
    }

    // Duration required unless "Until stopped"
    if (data.durationType !== 'Until stopped') {
      if (!data.durationValue || Number(data.durationValue) <= 0) {
        showError('durationError', 'Duration is required');
        isValid = false;
      }
    }

    // Duration max 1 year
    const durationInDays = calculateDurationInDays(data.durationValue, data.durationType);
    if (durationInDays > 365) {
      showError('durationError', 'Duration should not exceed 1 year');
      isValid = false;
    }

    // PRN validation - commented out since PRN fields are disabled
    // if (data.prn) {
    //   if (!data.maxPer24h || Number(data.maxPer24h) <= 0) {
    //     showError('maxPer24hError', 'Max per 24h is required for PRN');
    //     isValid = false;
    //   }
    //   if (!data.prnIndication.trim()) {
    //     showError('prnIndicationError', 'PRN indication is required');
    //     isValid = false;
    //   }
    // }

    // Dispense quantity required - commented out since dispensing section is disabled
    // if (!data.dispenseQuantity || Number(data.dispenseQuantity) <= 0) {
    //   showError('dispenseQuantityError', 'Dispense quantity is required');
    //   isValid = false;
    // }

    // Unit type required - commented out since dispensing section is disabled
    // if (!data.unitType) {
    //   showError('unitTypeError', 'Unit type is required');
    //   isValid = false;
    // }

    // Repeat interval required if repeats > 0
    if (Number(data.repeats) > 0 && (!data.repeatInterval || Number(data.repeatInterval) <= 0)) {
      showError('repeatIntervalError', 'Minimum repeat interval is required when repeats are specified');
      isValid = false;
    }

    // Diagnosis required
    if (!data.diagnosis.trim()) {
      showError('diagnosisError', 'Diagnosis/indication is required');
      isValid = false;
    }

    return isValid;
  }

  function calculateDurationInDays(value, type) {
    const duration = parseInt(value);
    switch (type) {
      case 'Days': return duration;
      case 'Weeks': return duration * 7;
      case 'Months': return duration * 30;
      default: return 0;
    }
  }

  function showError(elementId, message) {
    const errorElement = document.getElementById(elementId);
    if (errorElement) {
      errorElement.textContent = message;
      const inputElement = document.getElementById(elementId.replace('Error', ''));
      if (inputElement) {
        inputElement.classList.add('input-error');
      }
    }
  }

  function clearErrors() {
    const errors = document.querySelectorAll('.error');
    errors.forEach(error => error.textContent = '');

    const inputs = document.querySelectorAll('.input-error');
    inputs.forEach(input => input.classList.remove('input-error'));
  }

  // Prevent PHP submission if validation fails
  if (form) {
    form.addEventListener('submit', function (e) {
      if (!validateForm()) {
        e.preventDefault(); // stop normal form submission
        alert('Please correct the highlighted errors before submitting.');
      }
    });
  }

  // Initialize PRN fields visibility based on existing data
if (prnCheckbox && prnFields) {
  prnFields.classList.toggle('hidden', !prnCheckbox.checked);
}

// Initialize custom frequency field visibility
if (frequencySelect && customFrequencyField) {
  customFrequencyField.classList.toggle('hidden', frequencySelect.value !== 'custom');
}


});
