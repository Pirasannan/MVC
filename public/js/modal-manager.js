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
      modal.style.position = 'fixed';
      modal.style.top = '0';
      modal.style.left = '0';
      modal.style.width = '100vw';
      modal.style.height = '100vh';
      modal.style.background = 'rgba(0, 0, 0, 0.5)';
      modal.style.zIndex = '9999';
      modal.style.justifyContent = 'center';
      modal.style.alignItems = 'center';
      
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
  openPrescriptionModal(event, data) {
    console.log('openPrescriptionModal called', data);
    
    // Only open modal if the click is not on a button or link within the prescription item
    // Allow clicks on the prescription item itself, but block clicks on action buttons
    if (event.target.tagName === 'A' || 
        event.target.tagName === 'BUTTON' || 
        event.target.closest('a') || 
        event.target.closest('button')) {
      console.log('Click blocked - button/link detected');
      return;
    }

    if (data) {
        document.getElementById('vp-doctor-name').textContent = 'Dr. ' + (data.doctor_name || 'Name Unknown');
        document.getElementById('vp-patient-name').textContent = data.patient_name || 'Patient #' + data.patient_id;
        document.getElementById('vp-date').textContent = data.created_at ? data.created_at.split(' ')[0] : '';
        document.getElementById('vp-prescription-id').textContent = 'RX-' + data.id;
        document.getElementById('vp-diagnosis').textContent = data.diagnosis || '-';
        document.getElementById('vp-drug').textContent = data.drug_name || '-';
        document.getElementById('vp-formulation').textContent = data.formulation || '-';
        document.getElementById('vp-route').textContent = data.route || '-';
        document.getElementById('vp-dosage').textContent = ((data.dose_amount || '') + ' ' + (data.dose_unit || '')).trim() || '-';
        document.getElementById('vp-frequency').textContent = data.frequency || '-';
        document.getElementById('vp-duration').textContent = data.duration_type === 'Until stopped' ? 'Until stopped' : (data.duration_value ? data.duration_value + ' ' + data.duration_type : '-');
        document.getElementById('vp-special-instructions').textContent = data.special_instructions || 'None';
        document.getElementById('vp-pharmacy-note').textContent = data.pharmacy_note || 'None';
        document.getElementById('vp-valid-until').textContent = data.valid_until || 'Not specified';
        document.getElementById('vp-doctor-sign').textContent = 'Dr. ' + (data.doctor_name || '');
    }
    
    console.log('Opening prescription modal');
    this.openModal('prescriptionPopup');
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
document.addEventListener('DOMContentLoaded', function() {
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
function openPrescriptionModal(event, data) {
  console.log('Global openPrescriptionModal called');
  if (window.modalManager) {
    window.modalManager.openPrescriptionModal(event, data);
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
document.addEventListener('DOMContentLoaded', function() {
  const cancelBtn = document.getElementById('cancelDelete');
  const confirmBtn = document.getElementById('confirmDelete');
  
  if (cancelBtn) {
    cancelBtn.addEventListener('click', function() {
      window.modalManager.closeDeleteModal();
    });
  }
  
  if (confirmBtn) {
    confirmBtn.addEventListener('click', function() {
      const prescriptionId = this.getAttribute('data-prescription-id');
      if (prescriptionId) {
        window.location.href = window.location.origin + '/MVC/Doctor/deletePrescription/' + prescriptionId;
      }
    });
  }
});
