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

// Initialize the application
document.addEventListener("DOMContentLoaded", () => {
  window.authManager = new AuthManager()
  window.pageManager = new PageManager()
})