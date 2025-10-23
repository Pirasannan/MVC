/**
 * Verification System JavaScript
 * Handles file uploads, previews, and verification management
 */

// Global variables
let currentZoom = 1;
let isFullscreen = false;

// Initialize verification functionality
document.addEventListener('DOMContentLoaded', function () {
    initializeFilePreview();
    initializeFormSubmissions();
    initializeImageViewer();
});

/**
 * Initialize file preview functionality
 */
function initializeFilePreview() {
    // Upload form preview
    const uploadInput = document.getElementById('verification_photo');
    if (uploadInput) {
        uploadInput.addEventListener('change', function (e) {
            previewFile(e.target, 'previewImage', 'uploadPreview');
        });
    }

    // Update form preview
    const updateInput = document.getElementById('update_verification_photo');
    if (updateInput) {
        updateInput.addEventListener('change', function (e) {
            previewFile(e.target, 'updatePreviewImage', 'updatePreview');
        });
    }
}

/**
 * Initialize form submissions
 */
function initializeFormSubmissions() {
    // Upload form
    const uploadForm = document.getElementById('uploadForm');
    if (uploadForm) {
        uploadForm.addEventListener('submit', handleUploadSubmission);
    }

    // Update form
    const updateForm = document.getElementById('updateForm');
    if (updateForm) {
        updateForm.addEventListener('submit', handleUpdateSubmission);
    }
}

/**
 * Initialize image viewer functionality
 */
function initializeImageViewer() {
    // Keyboard shortcuts for fullscreen mode
    document.addEventListener('keydown', function (e) {
        if (isFullscreen) {
            switch (e.key) {
                case 'Escape':
                    toggleFullscreen();
                    break;
                case '+':
                case '=':
                    e.preventDefault();
                    zoomIn();
                    break;
                case '-':
                    e.preventDefault();
                    zoomOut();
                    break;
                case '0':
                    e.preventDefault();
                    resetZoom();
                    break;
            }
        }
    });
}

/**
 * Preview selected file
 */
function previewFile(input, imageId, containerId) {
    const file = input.files[0];
    if (file) {
        // Validate file before preview
        const validation = validateFileClient(file);
        if (!validation.valid) {
            showAlert('danger', validation.errors.join(', '));
            document.getElementById(containerId).style.display = 'none';
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById(imageId).src = e.target.result;
            document.getElementById(containerId).style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById(containerId).style.display = 'none';
    }
}

/**
 * Client-side file validation
 */
function validateFileClient(file) {
    const result = {
        valid: true,
        errors: []
    };

    // Check file type
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    if (!allowedTypes.includes(file.type)) {
        result.valid = false;
        result.errors.push('Invalid file type. Only JPG and PNG files are allowed.');
    }

    // Check file size (5MB = 5242880 bytes)
    const maxSize = 5242880;
    if (file.size > maxSize) {
        result.valid = false;
        result.errors.push('File is too large. Maximum size is 5MB.');
    }

    // Check minimum size (1KB)
    const minSize = 1024;
    if (file.size < minSize) {
        result.valid = false;
        result.errors.push('File is too small. Minimum size is 1KB.');
    }

    return result;
}

/**
 * Handle upload form submission
 */
function handleUploadSubmission(e) {
    e.preventDefault();

    const formData = new FormData(e.target);
    const uploadBtn = document.getElementById('uploadBtn');
    const progressContainer = document.getElementById('uploadProgress');
    const progressBar = progressContainer ? progressContainer.querySelector('.progress-bar') : null;

    // Disable button and show loading state
    setButtonLoading(uploadBtn, true, 'Uploading...');

    if (progressContainer) {
        progressContainer.style.display = 'block';
        if (progressBar) progressBar.style.width = '0%';
    }

    // Create XMLHttpRequest for progress tracking
    const xhr = new XMLHttpRequest();

    // Upload progress handler
    if (progressBar) {
        xhr.upload.addEventListener('progress', function (e) {
            if (e.lengthComputable) {
                const percentComplete = (e.loaded / e.total) * 100;
                progressBar.style.width = percentComplete + '%';
                progressBar.textContent = Math.round(percentComplete) + '%';
            }
        });
    }

    // Response handler
    xhr.addEventListener('load', function () {
        try {
            const response = JSON.parse(xhr.responseText);

            if (response.success) {
                showAlert('success', response.message);
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showAlert('danger', response.message);
            }
        } catch (error) {
            showAlert('danger', 'Upload failed. Please try again.');
        }
    });

    // Error handler
    xhr.addEventListener('error', function () {
        showAlert('danger', 'Upload failed. Please check your connection and try again.');
    });

    // Complete handler
    xhr.addEventListener('loadend', function () {
        setButtonLoading(uploadBtn, false, 'Upload');
        if (progressContainer) {
            progressContainer.style.display = 'none';
        }

        // Close modal if it exists
        const modal = bootstrap.Modal.getInstance(document.getElementById('uploadModal'));
        if (modal) modal.hide();
    });

    // Send request
    xhr.open('POST', getUrlRoot() + '/Verification/upload');
    xhr.send(formData);
}

/**
 * Handle update form submission
 */
function handleUpdateSubmission(e) {
    e.preventDefault();

    const formData = new FormData(e.target);
    const updateBtn = document.getElementById('updateBtn');

    setButtonLoading(updateBtn, true, 'Updating...');

    fetch(getUrlRoot() + '/Verification/update', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showAlert('danger', data.message);
            }
        })
        .catch(error => {
            showAlert('danger', 'Update failed. Please try again.');
        })
        .finally(() => {
            setButtonLoading(updateBtn, false, 'Update');

            // Close modal if it exists
            const modal = bootstrap.Modal.getInstance(document.getElementById('updateModal'));
            if (modal) modal.hide();
        });
}

/**
 * Set button loading state
 */
function setButtonLoading(button, loading, text) {
    if (!button) return;

    if (loading) {
        button.disabled = true;
        button.classList.add('btn-loading');
        button.innerHTML = `<i class="fas fa-spinner fa-spin"></i> ${text}`;
    } else {
        button.disabled = false;
        button.classList.remove('btn-loading');
        button.innerHTML = `<i class="fas fa-upload"></i> ${text}`;
    }
}

/**
 * View verification photo in new tab
 */
function viewVerificationPhoto() {
    window.open(getUrlRoot() + '/Verification/viewPhoto', '_blank');
}

/**
 * View full image in modal
 */
function viewFullImage(imageUrl) {
    const fullImageModal = document.getElementById('fullImageModal');
    const fullImage = document.getElementById('fullImage');

    if (fullImage && fullImageModal) {
        fullImage.src = imageUrl;
        new bootstrap.Modal(fullImageModal).show();
    }
}

/**
 * Toggle fullscreen view
 */
function toggleFullscreen() {
    const image = document.getElementById('verificationImage');
    const body = document.body;

    if (!image) return;

    if (!isFullscreen) {
        image.classList.add('fullscreen');
        body.classList.add('fullscreen-active');
        isFullscreen = true;
    } else {
        image.classList.remove('fullscreen');
        body.classList.remove('fullscreen-active');
        isFullscreen = false;
        resetZoom();
    }
}

/**
 * Zoom functions
 */
function zoomIn() {
    currentZoom += 0.2;
    applyZoom();
}

function zoomOut() {
    currentZoom = Math.max(0.2, currentZoom - 0.2);
    applyZoom();
}

function resetZoom() {
    currentZoom = 1;
    applyZoom();
}

function applyZoom() {
    const image = document.getElementById('verificationImage');
    if (image && !isFullscreen) {
        image.style.transform = `scale(${currentZoom})`;
    }
}

/**
 * Download image
 */
function downloadImage(imageUrl, filename) {
    const link = document.createElement('a');
    link.href = imageUrl;
    link.download = filename || 'verification_photo.jpg';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

/**
 * Confirm delete action
 */
function confirmDelete() {
    if (confirm('Are you sure you want to delete your verification record? This action cannot be undone.')) {
        deleteVerification();
    }
}

/**
 * Delete verification
 */
function deleteVerification() {
    fetch(getUrlRoot() + '/Verification/delete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                setTimeout(() => {
                    if (window.location.pathname.includes('/Verification/')) {
                        window.location.href = getUrlRoot() + '/Verification/index';
                    } else {
                        location.reload();
                    }
                }, 1500);
            } else {
                showAlert('danger', data.message);
            }
        })
        .catch(error => {
            showAlert('danger', 'Delete failed. Please try again.');
        });
}

/**
 * Show alert messages
 */
function showAlert(type, message) {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.alert-auto');
    existingAlerts.forEach(alert => alert.remove());

    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show alert-auto`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    const container = document.querySelector('.container') || document.querySelector('.dashboard-content') || document.body;
    container.insertBefore(alertDiv, container.firstChild);

    // Auto dismiss after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

/**
 * Get URL root from global variable or construct it
 */
function getUrlRoot() {
    return window.URLROOT || 'http://localhost/MVC';
}

/**
 * Format file size
 */
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';

    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}