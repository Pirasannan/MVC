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
    this.suspendBtn = document.getElementById('suspendBtn')
    this.deactivateBtn = document.getElementById('deactivateBtn')
    this.reactivateBtn = document.getElementById('reactivateBtn')
    this.doctorItems = document.querySelectorAll('.appointment-item')
    this.currentDoctorId = null
    this.currentDoctorStatus = 'active'
    
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
      if (!item.dataset.doctorId) {
        return
      }

      console.log('Adding click listener to item', index)
      item.addEventListener('click', (e) => {
        console.log('Doctor item clicked:', item.dataset.doctorName)
        this.currentDoctorId = item.dataset.doctorId
        this.currentDoctorStatus = item.dataset.doctorStatus || 'active'
        document.getElementById('modal-doctor-name').textContent = item.dataset.doctorName
        document.getElementById('modal-doctor-email').textContent = item.dataset.doctorEmail
        document.getElementById('modal-doctor-created').textContent = item.dataset.doctorCreated

        const statusElement = document.getElementById('modal-doctor-status')
        if (statusElement) {
          statusElement.textContent = this.currentDoctorStatus === 'inactive' ? 'Inactive' : 'Suspended'
          statusElement.className = 'status-badge ' + (this.currentDoctorStatus === 'inactive' ? 'confirmed' : 'rejected')
        }

        if (this.approveBtn) {
          this.approveBtn.style.display = this.currentDoctorStatus === 'suspended' ? 'inline-flex' : 'none'
        }

        if (this.reactivateBtn) {
          this.reactivateBtn.style.display = this.currentDoctorStatus === 'inactive' ? 'inline-flex' : 'none'
        }

        if (this.suspendBtn) {
          this.suspendBtn.style.display = this.currentDoctorStatus === 'inactive' ? 'none' : 'inline-flex'
        }

        if (this.deactivateBtn) {
          this.deactivateBtn.style.display = this.currentDoctorStatus === 'inactive' ? 'none' : 'inline-flex'
        }

        const documentLink = document.getElementById('modal-doctor-document-link')
        if (documentLink) {
          if (item.dataset.doctorDocument) {
            documentLink.href = window.location.origin + '/MVC/' + item.dataset.doctorDocument
            documentLink.style.pointerEvents = 'auto'
            documentLink.style.opacity = '1'
            documentLink.textContent = 'View Uploaded Document'
          } else {
            documentLink.href = '#'
            documentLink.style.pointerEvents = 'none'
            documentLink.style.opacity = '0.6'
            documentLink.textContent = 'Document not available'
          }
        }

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

    if (this.suspendBtn) {
      this.suspendBtn.addEventListener('click', () => {
        this.suspendDoctor()
      })
    }

    if (this.deactivateBtn) {
      this.deactivateBtn.addEventListener('click', () => {
        this.deactivateDoctor()
      })
    }

    if (this.reactivateBtn) {
      this.reactivateBtn.addEventListener('click', () => {
        this.reactivateDoctor()
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

  suspendDoctor() {
    if (!this.currentDoctorId) {
      return
    }

    fetch(window.location.origin + '/MVC/Pages/suspendDoctor', {
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
        alert('Doctor suspended successfully!')
        location.reload()
      } else {
        alert('Error: ' + (data.message || 'Failed to suspend doctor'))
      }
    })
    .catch(error => {
      console.error('Error:', error)
      alert('Error suspending doctor')
    })
  }

  deactivateDoctor() {
    if (!this.currentDoctorId) {
      return
    }

    fetch(window.location.origin + '/MVC/Pages/deactivateDoctor', {
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
        alert('Doctor deactivated successfully!')
        location.reload()
      } else {
        alert('Error: ' + (data.message || 'Failed to deactivate doctor'))
      }
    })
    .catch(error => {
      console.error('Error:', error)
      alert('Error deactivating doctor')
    })
  }

  reactivateDoctor() {
    if (!this.currentDoctorId) {
      return
    }

    fetch(window.location.origin + '/MVC/Pages/reactivateDoctor', {
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
        alert('Doctor reactivated successfully!')
        location.reload()
      } else {
        alert('Error: ' + (data.message || 'Failed to reactivate doctor'))
      }
    })
    .catch(error => {
      console.error('Error:', error)
      alert('Error reactivating doctor')
    })
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
    this.reactivateBtn = document.getElementById('reactivateBtn')
    this.suspendBtn = document.getElementById('suspendBtn')
    this.deactivateBtn = document.getElementById('deactivateBtn')
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
        const currentStatus = item.dataset.patientStatus || 'inactive'
        const statusElement = document.getElementById('modal-patient-status')
        if (statusElement) {
          statusElement.textContent = currentStatus
        }

        if (this.suspendBtn) {
          this.suspendBtn.style.display = currentStatus === 'suspended' ? 'none' : 'inline-block'
        }

        if (this.deactivateBtn) {
          this.deactivateBtn.style.display = currentStatus === 'suspended' ? 'inline-block' : 'none'
        }

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

    // Reactivate patient from inactive/suspended account view.
    if (this.reactivateBtn) {
      this.reactivateBtn.addEventListener('click', () => {
        this.reactivatePatient()
      })
    }

    // Suspend patient from inactive/suspended account view.
    if (this.suspendBtn) {
      this.suspendBtn.addEventListener('click', () => {
        this.suspendPatient()
      })
    }

    if (this.deactivateBtn) {
      this.deactivateBtn.addEventListener('click', () => {
        this.deactivatePatient()
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

  reactivatePatient() {
    if (!this.currentPatientId) {
      return
    }

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
        alert('Patient reactivated successfully!')
        location.reload()
      } else {
        alert('Error: ' + (data.message || 'Failed to reactivate patient'))
      }
    })
    .catch(error => {
      console.error('Error:', error)
      alert('Error reactivating patient')
    })
  }

  suspendPatient() {
    if (!this.currentPatientId) {
      return
    }

    fetch(window.location.origin + '/MVC/Pages/rejectPatient', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        patient_id: this.currentPatientId,
        reason: 'Suspended by admin from inactive/suspended accounts'
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert('Patient suspended successfully!')
        location.reload()
      } else {
        alert('Error: ' + (data.message || 'Failed to suspend patient'))
      }
    })
    .catch(error => {
      console.error('Error:', error)
      alert('Error suspending patient')
    })
  }

  deactivatePatient() {
    if (!this.currentPatientId) {
      return
    }

    fetch(window.location.origin + '/MVC/Pages/deactivatePatient', {
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
        alert('Patient deactivated successfully!')
        location.reload()
      } else {
        alert('Error: ' + (data.message || 'Failed to deactivate patient'))
      }
    })
    .catch(error => {
      console.error('Error:', error)
      alert('Error deactivating patient')
    })
  }
}

// Prescription Manager - now handled by modal-manager.js

// Initialize the application
document.addEventListener("DOMContentLoaded", () => {
  window.authManager = new AuthManager()
  window.pageManager = new PageManager()
  window.doctorVerificationManager = new DoctorVerificationManager()
  window.patientVerificationManager = new PatientVerificationManager()
  window.notificationManager = new NotificationManager()
  // Prescription manager is now handled by modal-manager.js
})