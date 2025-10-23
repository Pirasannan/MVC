// Simple Page Manager
class PageManager {
  constructor() {
    this.init()
  }

  init() {
    this.bindEvents()
  }

  bindEvents() {
    // Handle navigation clicks
    const navLinks = document.querySelectorAll('.header-navigation a')
    navLinks.forEach(link => {
      link.addEventListener('click', (e) => {
        e.preventDefault()
        const href = link.getAttribute('href')
        this.handleNavigation(href)
      })
    })

    // Handle CTA button clicks
    const ctaButtons = document.querySelectorAll('#hero-get-started, #hero-view-docs, #cta-start-building')
    ctaButtons.forEach(button => {
      button.addEventListener('click', (e) => {
        e.preventDefault()
        this.handleCTAClick(button.id)
      })
    })
  }

  handleNavigation(href) {
    switch(href) {
      case '#docs':
        window.location.href = 'docs.html'
        break
      case '#features':
        this.scrollToSection('features')
        break
      case '#pricing':
        this.scrollToSection('pricing')
        break
      case '#support':
        this.showNotification('Support page coming soon!', 'info')
        break
      default:
        console.log('Navigation to:', href)
    }
  }

  handleCTAClick(buttonId) {
    switch(buttonId) {
      case 'hero-get-started':
      case 'cta-start-building':
        this.showNotification('Get Started - Registration coming soon!', 'info')
        break
      case 'hero-view-docs':
        window.location.href = 'docs.html'
        break
      default:
        console.log('CTA clicked:', buttonId)
    }
  }

  scrollToSection(sectionId) {
    const section = document.getElementById(sectionId)
    if (section) {
      section.scrollIntoView({ behavior: 'smooth' })
    }
  }

  showNotification(message, type = "info") {
    // Simple notification system
    const notification = document.createElement("div")
    notification.className = `notification notification-${type}`
    notification.textContent = message
    notification.style.cssText = `
      position: fixed;
      top: 20px;
      right: 20px;
      padding: 12px 20px;
      border-radius: 6px;
      color: white;
      font-weight: 500;
      z-index: 1000;
      animation: slideIn 0.3s ease;
    `
    
    if (type === "success") {
      notification.style.backgroundColor = "#10b981"
    } else if (type === "error") {
      notification.style.backgroundColor = "#ef4444"
    } else {
      notification.style.backgroundColor = "#3b82f6"
    }

    document.body.appendChild(notification)

    setTimeout(() => {
      notification.remove()
    }, 3000)
  }
}

// Simple Auth Manager
class AuthManager {
  constructor() {
    this.isLoggedIn = false
    this.init()
  }

  init() {
    this.bindEvents()
    this.checkAuthStatus()
  }

  bindEvents() {
    const loginBtn = document.getElementById('login-btn')
    const registerBtn = document.getElementById('register-btn')

    if (loginBtn) {
      loginBtn.addEventListener('click', () => this.handleLogin())
    }

    if (registerBtn) {
      registerBtn.addEventListener('click', () => this.handleRegister())
    }
  }

  handleLogin() {
    // Mock login functionality
    this.isLoggedIn = true
    this.updateAuthButtons()
    this.showNotification('Login successful!', 'success')
  }

  handleRegister() {
    // Mock registration functionality
    this.showNotification('Registration page coming soon!', 'info')
  }

  checkAuthStatus() {
    // Check if user is logged in (mock)
    this.isLoggedIn = localStorage.getItem('isLoggedIn') === 'true'
    this.updateAuthButtons()
  }

  updateAuthButtons() {
    const loginBtn = document.getElementById('login-btn')
    const registerBtn = document.getElementById('register-btn')

    if (this.isLoggedIn) {
      if (loginBtn) {
        loginBtn.textContent = 'Dashboard'
        loginBtn.onclick = () => this.showNotification('Dashboard coming soon!', 'info')
      }
      if (registerBtn) {
        registerBtn.textContent = 'Logout'
        registerBtn.onclick = () => this.logout()
      }
    }
  }

  logout() {
    this.isLoggedIn = false
    localStorage.removeItem('isLoggedIn')
    this.updateAuthButtons()
    this.showNotification('Logged out successfully!', 'success')
  }

  showNotification(message, type = "info") {
    // Simple notification system
    const notification = document.createElement("div")
    notification.className = `notification notification-${type}`
    notification.textContent = message
    notification.style.cssText = `
      position: fixed;
      top: 20px;
      right: 20px;
      padding: 12px 20px;
      border-radius: 6px;
      color: white;
      font-weight: 500;
      z-index: 1000;
      animation: slideIn 0.3s ease;
    `
    
    if (type === "success") {
      notification.style.backgroundColor = "#10b981"
    } else if (type === "error") {
      notification.style.backgroundColor = "#ef4444"
    } else {
      notification.style.backgroundColor = "#3b82f6"
    }

    document.body.appendChild(notification)

    setTimeout(() => {
      notification.remove()
    }, 3000)
  }
}

// Doctor Verification Modal Manager
class DoctorVerificationManager {
  constructor() {
    this.modal = document.getElementById('doctorModal')
    this.closeBtn = document.querySelector('.close')
    this.approveBtn = document.getElementById('approveBtn')
    this.rejectBtn = document.getElementById('rejectBtn')
    this.doctorItems = document.querySelectorAll('.appointment-item')
    this.currentDoctorId = null
    
    console.log('DoctorVerificationManager initialized')
    console.log('Modal found:', !!this.modal)
    console.log('Doctor items found:', this.doctorItems.length)
    
    if (this.modal) {
      this.init()
    }
  }

  init() {
    this.bindEvents()
  }

  bindEvents() {
    console.log('Binding events to', this.doctorItems.length, 'doctor items')
    
    // Open modal when doctor item is clicked
    this.doctorItems.forEach((item, index) => {
      console.log('Adding click listener to item', index)
      item.addEventListener('click', (e) => {
        console.log('Doctor item clicked:', item.dataset.doctorName)
        this.currentDoctorId = item.dataset.doctorId
        document.getElementById('modal-doctor-name').textContent = item.dataset.doctorName
        document.getElementById('modal-doctor-email').textContent = item.dataset.doctorEmail
        document.getElementById('modal-doctor-created').textContent = item.dataset.doctorCreated
        this.modal.style.display = 'block'
      })
    })

    // Close modal
    if (this.closeBtn) {
      this.closeBtn.addEventListener('click', () => {
        this.modal.style.display = 'none'
      })
    }

    // Close modal when clicking outside
    window.addEventListener('click', (event) => {
      if (event.target === this.modal) {
        this.modal.style.display = 'none'
      }
    })

    // Approve doctor
    if (this.approveBtn) {
      this.approveBtn.addEventListener('click', () => {
        this.approveDoctor()
      })
    }

    // Reject doctor
    if (this.rejectBtn) {
      this.rejectBtn.addEventListener('click', () => {
        this.rejectDoctor()
      })
    }
  }

  approveDoctor() {
    if (this.currentDoctorId) {
      fetch(window.location.origin + '/MVC/Pages/approveDoctor', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          doctor_id: this.currentDoctorId
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Doctor approved successfully!')
          location.reload()
        } else {
          alert('Error: ' + (data.message || 'Failed to approve doctor'))
        }
      })
      .catch(error => {
        console.error('Error:', error)
        alert('Error approving doctor')
      })
    }
  }

  rejectDoctor() {
    const reason = prompt('Please enter rejection reason:')
    if (reason && this.currentDoctorId) {
      fetch(window.location.origin + '/MVC/Pages/rejectDoctor', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          doctor_id: this.currentDoctorId,
          reason: reason
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Doctor rejected successfully!')
          location.reload()
        } else {
          alert('Error: ' + (data.message || 'Failed to reject doctor'))
        }
      })
      .catch(error => {
        console.error('Error:', error)
        alert('Error rejecting doctor')
      })
    }
  }
}

// Notification Manager
class NotificationManager {
  constructor() {
    this.sendBtn = document.getElementById('sendNotificationBtn')
    this.recipientType = document.getElementById('recipientType')
    this.title = document.getElementById('notificationTitle')
    this.message = document.getElementById('notificationMessage')
    
    if (this.sendBtn) {
      this.init()
    }
  }

  init() {
    this.bindEvents()
  }

  bindEvents() {
    this.sendBtn.addEventListener('click', () => {
      this.sendNotification()
    })
  }

  sendNotification() {
    const data = {
      recipient_type: this.recipientType.value,
      title: this.title.value,
      message: this.message.value,
      notification_type: 'info'
    }

    // Validate
    if (!data.title.trim() || !data.message.trim()) {
      alert('Please fill in both title and message')
      return
    }

    // Send request
    fetch(window.location.origin + '/MVC/Pages/sendNotification', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert('Notification sent successfully!')
        // Clear form
        this.title.value = ''
        this.message.value = ''
        // Reload page to show new notification
        location.reload()
      } else {
        alert('Error: ' + (data.message || 'Failed to send notification'))
      }
    })
    .catch(error => {
      console.error('Error:', error)
      alert('Error sending notification')
    })
  }
}

// Patient Verification Modal Manager
class PatientVerificationManager {
  constructor() {
    this.modal = document.getElementById('patientModal')
    this.closeBtn = document.querySelector('.close')
    this.approveBtn = document.getElementById('approveBtn')
    this.rejectBtn = document.getElementById('rejectBtn')
    this.patientItems = document.querySelectorAll('.appointment-item')
    this.currentPatientId = null
    
    console.log('PatientVerificationManager initialized')
    console.log('Modal found:', !!this.modal)
    console.log('Patient items found:', this.patientItems.length)
    
    if (this.modal) {
      this.init()
    }
  }

  init() {
    this.bindEvents()
  }

  bindEvents() {
    console.log('Binding events to', this.patientItems.length, 'patient items')
    
    // Open modal when patient item is clicked
    this.patientItems.forEach((item, index) => {
      console.log('Adding click listener to item', index)
      item.addEventListener('click', (e) => {
        console.log('Patient item clicked:', item.dataset.patientName)
        this.currentPatientId = item.dataset.patientId
        document.getElementById('modal-patient-name').textContent = item.dataset.patientName
        document.getElementById('modal-patient-email').textContent = item.dataset.patientEmail
        document.getElementById('modal-patient-created').textContent = item.dataset.patientCreated
        this.modal.style.display = 'block'
      })
    })

    // Close modal
    if (this.closeBtn) {
      this.closeBtn.addEventListener('click', () => {
        this.modal.style.display = 'none'
      })
    }

    // Close modal when clicking outside
    window.addEventListener('click', (event) => {
      if (event.target === this.modal) {
        this.modal.style.display = 'none'
      }
    })

    // Approve patient
    if (this.approveBtn) {
      this.approveBtn.addEventListener('click', () => {
        this.approvePatient()
      })
    }

    // Reject patient
    if (this.rejectBtn) {
      this.rejectBtn.addEventListener('click', () => {
        this.rejectPatient()
      })
    }
  }

  approvePatient() {
    if (this.currentPatientId) {
      fetch(window.location.origin + '/MVC/Pages/approvePatient', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          patient_id: this.currentPatientId
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Patient approved successfully!')
          location.reload()
        } else {
          alert('Error: ' + (data.message || 'Failed to approve patient'))
        }
      })
      .catch(error => {
        console.error('Error:', error)
        alert('Error approving patient')
      })
    }
  }

  rejectPatient() {
    const reason = prompt('Please enter rejection reason:')
    if (reason && this.currentPatientId) {
      fetch(window.location.origin + '/MVC/Pages/rejectPatient', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          patient_id: this.currentPatientId,
          reason: reason
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Patient rejected successfully!')
          location.reload()
        } else {
          alert('Error: ' + (data.message || 'Failed to reject patient'))
        }
      })
      .catch(error => {
        console.error('Error:', error)
        alert('Error rejecting patient')
      })
    }
  }
}

// Prescription Manager
class PrescriptionManager {
  constructor() {
    this.modal = document.getElementById('prescriptionPopup')
    this.deleteModal = document.getElementById('deletePrescriptionPopup')
    this.closeBtn = document.querySelector('.close')
    this.prescriptionItems = document.querySelectorAll('.medication-item')
    this.viewAllBtn = document.getElementById('view-all-btn')
    this.prescriptionList = document.getElementById('prescription-list') || document.getElementById('doctor-prescription-list')
    this.currentPrescriptionId = null
    
    console.log('PrescriptionManager initialized')
    console.log('Modal found:', !!this.modal)
    console.log('Delete modal found:', !!this.deleteModal)
    console.log('Prescription items found:', this.prescriptionItems.length)
    
    if (this.prescriptionItems.length > 0) {
      this.init()
    }
  }

  init() {
    this.bindEvents()
  }

  bindEvents() {
    console.log('Binding events to', this.prescriptionItems.length, 'prescription items')
    
    // Open modal when prescription item is clicked
    this.prescriptionItems.forEach((item, index) => {
      console.log('Adding click listener to item', index)
      item.addEventListener('click', (e) => {
        console.log('Prescription item clicked')
        this.openPrescriptionModal(e)
      })
    })

    // Handle view all button
    if (this.viewAllBtn) {
      this.viewAllBtn.addEventListener('click', () => {
        this.toggleAllPrescriptions()
      })
    }

    // Handle delete confirmation
    this.bindDeleteEvents()
  }

  bindDeleteEvents() {
    const cancelBtn = document.getElementById('cancelDelete')
    const confirmBtn = document.getElementById('confirmDelete')
    
    if (cancelBtn) {
      cancelBtn.addEventListener('click', () => {
        if (this.deleteModal) {
          this.deleteModal.style.display = 'none'
        }
      })
    }

    if (confirmBtn) {
      confirmBtn.addEventListener('click', () => {
        if (this.currentPrescriptionId) {
          window.location.href = window.location.origin + '/MVC/Doctor/deletePrescription/' + this.currentPrescriptionId
        }
      })
    }
  }

  openPrescriptionModal(event) {
    // Only open modal if the click is not on a button or link
    if (event.target.tagName === 'A' || event.target.tagName === 'BUTTON' || event.target.closest('a') || event.target.closest('button')) {
      return
    }
    
    if (this.modal) {
      this.modal.style.display = 'flex'
    }
  }

  toggleAllPrescriptions() {
    if (this.prescriptionList && this.viewAllBtn) {
      this.prescriptionList.classList.toggle('expanded')
      this.viewAllBtn.textContent = this.prescriptionList.classList.contains('expanded') 
        ? 'Show Less' 
        : 'View All Prescriptions'
    }
  }

  confirmDeletePrescription(event, prescriptionId) {
    event.stopPropagation()
    this.currentPrescriptionId = prescriptionId
    if (this.deleteModal) {
      this.deleteModal.style.display = 'flex'
    }
  }
}

// Initialize the application
document.addEventListener("DOMContentLoaded", () => {
  window.authManager = new AuthManager()
  window.pageManager = new PageManager()
  window.doctorVerificationManager = new DoctorVerificationManager()
  window.patientVerificationManager = new PatientVerificationManager()
  window.notificationManager = new NotificationManager()
  window.prescriptionManager = new PrescriptionManager()
})