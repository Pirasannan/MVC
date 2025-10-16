// Documentation Page Functionality
class DocsManager {
  constructor() {
    this.currentSection = "introduction"
    this.init()
  }

  init() {
    this.bindNavigation()
    this.bindSearch()
    this.showSection(this.currentSection)
  }

  bindNavigation() {
    const docLinks = document.querySelectorAll(".doc-link")
    docLinks.forEach((link) => {
      link.addEventListener("click", (e) => {
        e.preventDefault()
        const sectionId = link.getAttribute("href").substring(1)
        this.showSection(sectionId)
        this.setActiveLink(link)
      })
    })
  }

  showSection(sectionId) {
    // Hide all sections
    const sections = document.querySelectorAll(".doc-section-content")
    sections.forEach((section) => section.classList.add("hidden"))

    // Show selected section
    const targetSection = document.getElementById(sectionId)
    if (targetSection) {
      targetSection.classList.remove("hidden")
      this.currentSection = sectionId
    }
  }

  setActiveLink(activeLink) {
    // Remove active class from all links
    const docLinks = document.querySelectorAll(".doc-link")
    docLinks.forEach((link) => link.classList.remove("active"))

    // Add active class to clicked link
    activeLink.classList.add("active")
  }

  bindSearch() {
    const searchInput = document.getElementById("doc-search")
    if (searchInput) {
      searchInput.addEventListener("input", (e) => {
        const query = e.target.value.toLowerCase()
        this.filterContent(query)
      })
    }
  }

  filterContent(query) {
    // Filter navigation links
    const links = document.querySelectorAll(".doc-link")
    links.forEach((link) => {
      const text = link.textContent.toLowerCase()
      if (text.includes(query)) {
        link.style.display = "block"
      } else {
        link.style.display = "none"
      }
    })
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

// Initialize docs functionality
document.addEventListener("DOMContentLoaded", () => {
  window.docsManager = new DocsManager()
})