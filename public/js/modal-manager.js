// Modal Manager - Centralized modal handling
class ModalManager {
  constructor() {
    this.activeModal = null;
    this.init();
  }

  init() {
    this.bindGlobalEvents();
  }

  bindGlobalEvents() {
    // Close modals with Escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && this.activeModal) {
        this.closeModal(this.activeModal);
      }
    });

    // Close modals when clicking outside
    document.addEventListener('click', (e) => {
      if (e.target.classList.contains('modal') ||
        e.target.classList.contains('popup-overlay') ||
        e.target.classList.contains('prescription-overlay') ||
        e.target.classList.contains('res-modal')) {
        this.closeModal(e.target);
      }
    });
  }

  openModal(modalId) {
    console.log('openModal called with:', modalId);
    const modal = document.getElementById(modalId);
    if (modal) {
      console.log('Modal found:', modal);
      modal.style.display = 'flex';
      modal.style.visibility = 'visible';
      modal.style.opacity = '1';
      // Rely on CSS for most positioning and dimensions
      modal.style.position = 'fixed';
      modal.style.zIndex = '9999';

      // Make sure the modal content has white background
      const modalContent = modal.querySelector('.prescription-modal');
      if (modalContent) {
        modalContent.style.background = 'white';
        modalContent.style.borderRadius = '10px';
        modalContent.style.boxShadow = '0 4px 15px rgba(0,0,0,0.2)';
      }
      this.activeModal = modal;
      document.body.style.overflow = 'hidden'; // Prevent background scrolling
      console.log('Modal display set to flex');
      console.log('Modal final style:', modal.style.cssText);
      console.log('Modal computed display:', window.getComputedStyle(modal).display);
      console.log('Modal computed visibility:', window.getComputedStyle(modal).visibility);
      console.log('Modal computed opacity:', window.getComputedStyle(modal).opacity);
    } else {
      console.warn('Modal not found:', modalId);
    }
  }

  closeModal(modal) {
    if (typeof modal === 'string') {
      modal = document.getElementById(modal);
    }

    if (modal) {
      modal.style.display = 'none';
      if (this.activeModal === modal) {
        this.activeModal = null;
      }
      document.body.style.overflow = 'auto'; // Restore scrolling
    }
  }

  // Prescription modal functions
  openPrescriptionModal(event, prescriptionId) {
    console.log('openPrescriptionModal called for ID:', prescriptionId);

    // Only open modal if the click is not on a button or link within the prescription item
    if (event.target.tagName === 'A' ||
      event.target.tagName === 'BUTTON' ||
      event.target.closest('a') ||
      event.target.closest('button')) {
      return;
    }

    if (!prescriptionId) {
      console.error('No prescription ID provided');
      return;
    }

    // Show loading state if you want, or just fetch
    fetch(window.location.origin + '/MVC/Pages/getPrescriptionJSON/' + prescriptionId)
      .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
      })
      .then(data => {
        this.populatePrescriptionModal(data);
        this.openModal('prescriptionPopup');
      })
      .catch(error => {
        console.error('Error fetching prescription:', error);
        alert('Failed to load prescription details. Please try again.');
      });
  }

  populatePrescriptionModal(data) {
    // Helper to set text or default
    const setT = (id, val, def = 'N/A') => {
      const el = document.getElementById(id);
      if (el) el.textContent = val || def;
    };

    setT('modal-doctor-name', 'Dr. ' + data.doctor_name);
    setT('modal-doctor-qualifications', data.doctor_qualifications || 'Medical Professional'); // Assuming qualifications might be added later or use generic
    setT('modal-doctor-slmc', 'SLMC: ' + (data.doctor_slmc || 'Verified'));
    setT('modal-doctor-contact', `Email: ${data.doctor_email}`);

    setT('modal-patient-name', data.patient_name);
    setT('modal-date', new Date(data.created_at).toLocaleDateString());
    setT('modal-ref-no', 'RX-' + data.id.toString().padStart(5, '0'));

    // Calculate Age
    let age = 'N/A';
    if (data.date_of_birth) {
      const birthDate = new Date(data.date_of_birth);
      const today = new Date();
      age = today.getFullYear() - birthDate.getFullYear();
      const m = today.getMonth() - birthDate.getMonth();
      if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        age--;
      }
    }

    // Gender - if available, otherwise skip
    const gender = data.gender || 'Not Specified';
    setT('modal-patient-meta', `${age} Years / ${gender}`);

    setT('modal-diagnosis', data.diagnosis);
    setT('modal-drug-name', data.drug_name);
    setT('modal-drug-meta', `${data.formulation || ''} | ${data.route || ''} | ${data.meal_relation || ''}`);

    setT('modal-dosage', data.dose_amount + ' ' + data.dose_unit);
    setT('modal-frequency', data.frequency + (data.time_of_day ? ` (${data.time_of_day})` : ''));

    let duration = data.duration_value ? `${data.duration_value} ${data.duration_type}` : 'Until stopped';
    setT('modal-duration', duration);

    setT('modal-special-instructions', data.special_instructions, 'None');
    setT('modal-pharmacy-note', data.pharmacy_note, 'No additional notes');

    setT('modal-valid-until', data.valid_until ? new Date(data.valid_until).toLocaleDateString() : 'Indefinite');
    setT('modal-doctor-footer-name', 'Dr. ' + data.doctor_name);

    // Handle deleted status in modal
    const deletedBadge = document.getElementById('modal-deleted-badge');
    const printBtn = document.getElementById('printPrescriptionBtn');
    if (data.is_deleted === 'deleted') {
      if (deletedBadge) deletedBadge.style.display = 'block';
      if (printBtn) printBtn.style.display = 'none';
      document.getElementById('printablePrescription').classList.add('is-deleted-modal');
    } else {
      if (deletedBadge) deletedBadge.style.display = 'none';
      if (printBtn) printBtn.style.display = 'inline-flex';
      document.getElementById('printablePrescription').classList.remove('is-deleted-modal');
    }
  }

  closePrescriptionModal() {
    this.closeModal('prescriptionPopup');
  }

  // Reschedule modal functions
  openResModal(btn) {
    const resApptId = btn.getAttribute('data-id');
    const cur = btn.getAttribute('data-current') || '';
    const form = document.getElementById('resForm');

    if (form) {
      form.action = window.location.origin + '/MVC/Appointments/reschedule/' + resApptId;

      const input = form.querySelector('input[name="new_datetime"]');
      if (input) {
        if (cur && cur.indexOf('T') === -1) {
          input.value = cur.replace(' ', 'T').slice(0, 16);
        } else {
          input.value = '';
        }
      }
    }

    this.openModal('resModal');
  }

  closeResModal() {
    this.closeModal('resModal');
  }

  // Delete confirmation modal
  openDeleteModal(prescriptionId) {
    const modal = document.getElementById('deletePrescriptionPopup');
    if (modal) {
      // Store the prescription ID for deletion
      const confirmBtn = document.getElementById('confirmDelete');
      if (confirmBtn) {
        confirmBtn.setAttribute('data-prescription-id', prescriptionId);
      }
      this.openModal('deletePrescriptionPopup');
    }
  }

  closeDeleteModal() {
    this.closeModal('deletePrescriptionPopup');
  }

  // Toggle all prescriptions
  toggleAllPrescriptions() {
    const list = document.getElementById('prescription-list') ||
      document.getElementById('doctor-prescription-list');
    const btn = document.getElementById('view-all-btn');

    if (list && btn) {
      list.classList.toggle('expanded');
      btn.textContent = list.classList.contains('expanded')
        ? 'Show Less'
        : 'View All Prescriptions';
    }
  }
}

// Initialize modal manager
document.addEventListener('DOMContentLoaded', function () {
  window.modalManager = new ModalManager();
  console.log('Modal manager initialized');

  // Test if modal exists
  const modal = document.getElementById('prescriptionPopup');
  console.log('Prescription modal found:', modal);
  if (modal) {
    console.log('Modal style:', modal.style.display);
    console.log('Modal computed style:', window.getComputedStyle(modal).display);
  }
});

// Global functions for backward compatibility
function openPrescriptionModal(event, prescriptionId) {
  console.log('Global openPrescriptionModal called');
  if (window.modalManager) {
    window.modalManager.openPrescriptionModal(event, prescriptionId);
  } else {
    console.error('Modal manager not initialized yet');
  }
}

function closePrescriptionModal() {
  if (window.modalManager) {
    window.modalManager.closePrescriptionModal();
  }
}

function openResModal(btn) {
  if (window.modalManager) {
    window.modalManager.openResModal(btn);
  }
}

function closeResModal() {
  if (window.modalManager) {
    window.modalManager.closeResModal();
  }
}

function toggleAllPrescriptions() {
  if (window.modalManager) {
    window.modalManager.toggleAllPrescriptions();
  }
}

function confirmDeletePrescription(event, prescriptionId) {
  event.stopPropagation();
  if (window.modalManager) {
    window.modalManager.openDeleteModal(prescriptionId);
  }
}

// Handle delete confirmation
document.addEventListener('DOMContentLoaded', function () {
  const cancelBtn = document.getElementById('cancelDelete');
  const confirmBtn = document.getElementById('confirmDelete');

  if (cancelBtn) {
    cancelBtn.addEventListener('click', function () {
      window.modalManager.closeDeleteModal();
    });
  }

  if (confirmBtn) {
    confirmBtn.addEventListener('click', function () {
      const prescriptionId = this.getAttribute('data-prescription-id');
      if (prescriptionId) {
        window.location.href = window.location.origin + '/MVC/Doctor/deletePrescription/' + prescriptionId;
      }
    });
  }
});
