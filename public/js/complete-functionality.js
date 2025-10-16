// Enhanced copy functionality with notifications
window.copyCode = (codeId) => {
  const codeElement = document.getElementById(codeId)
  if (codeElement) {
    const text = codeElement.textContent
    navigator.clipboard
      .writeText(text)
      .then(() => {
        // Show notification
        if (window.docsManager) {
          window.docsManager.showNotification("Code copied to clipboard!", "success", 3000)
        }

        // Show button feedback
        const button = event.target
        const originalText = button.textContent
        button.textContent = "Copied!"
        setTimeout(() => {
          button.textContent = originalText
        }, 2000)
      })
      .catch((err) => {
        console.error("Failed to copy code:", err)
        if (window.docsManager) {
          window.docsManager.showNotification("Failed to copy code", "error", 3000)
        }
      })
  }
}

// Simple analytics tracking (mock)
window.trackEvent = (eventName, properties = {}) => {
  console.log('Analytics Event:', eventName, properties)
  // In a real app, this would send data to analytics service
}

// Track page load
document.addEventListener('DOMContentLoaded', () => {
  window.trackEvent('page_loaded', {
    page: document.title,
    url: window.location.href
  })
})

// Track button clicks
document.addEventListener('click', (e) => {
  if (e.target.matches('button, .btn')) {
    window.trackEvent('button_clicked', {
      button_text: e.target.textContent,
      button_id: e.target.id
    })
  }
})