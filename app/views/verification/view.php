<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4><i class="fas fa-eye"></i> Verification Photo</h4>
                    <a href="<?php echo URLROOT; ?>/Verification/index" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to Profile
                    </a>
                </div>
                <div class="card-body">
                    
                    <?php if (isset($data['error'])): ?>
                        <!-- Error message -->
                        <div class="alert alert-danger">
                            <h5><i class="fas fa-exclamation-triangle"></i> Error</h5>
                            <p><?php echo htmlspecialchars($data['error']); ?></p>
                        </div>
                        
                        <div class="text-center">
                            <a href="<?php echo URLROOT; ?>/Verification/index" class="btn btn-primary">
                                <i class="fas fa-arrow-left"></i> Return to Profile
                            </a>
                        </div>
                        
                    <?php else: ?>
                        <!-- Verification details -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5>Verification Details</h5>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            <?php
                                            $status = $data['verification']->verification_status;
                                            $statusClass = '';
                                            $statusIcon = '';
                                            
                                            switch($status) {
                                                case 'pending':
                                                    $statusClass = 'warning';
                                                    $statusIcon = 'clock';
                                                    break;
                                                case 'verified':
                                                    $statusClass = 'success';
                                                    $statusIcon = 'check-circle';
                                                    break;
                                                case 'rejected':
                                                    $statusClass = 'danger';
                                                    $statusIcon = 'times-circle';
                                                    break;
                                            }
                                            ?>
                                            <span class="badge bg-<?php echo $statusClass; ?>">
                                                <i class="fas fa-<?php echo $statusIcon; ?>"></i> 
                                                <?php echo ucfirst($status); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Uploaded:</strong></td>
                                        <td><?php echo date('M d, Y H:i', strtotime($data['verification']->uploaded_at)); ?></td>
                                    </tr>
                                    <?php if ($data['verification']->updated_at !== $data['verification']->uploaded_at): ?>
                                    <tr>
                                        <td><strong>Last Updated:</strong></td>
                                        <td><?php echo date('M d, Y H:i', strtotime($data['verification']->updated_at)); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if ($data['verification']->verified_at): ?>
                                    <tr>
                                        <td><strong>Verified On:</strong></td>
                                        <td><?php echo date('M d, Y H:i', strtotime($data['verification']->verified_at)); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if ($data['file_size']): ?>
                                    <tr>
                                        <td><strong>File Size:</strong></td>
                                        <td><?php echo formatFileSize($data['file_size']); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                                
                                <?php if ($data['verification']->rejection_reason): ?>
                                <div class="alert alert-danger">
                                    <h6><i class="fas fa-times-circle"></i> Rejection Reason</h6>
                                    <p class="mb-0"><?php echo htmlspecialchars($data['verification']->rejection_reason); ?></p>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-6">
                                <h5>Quick Actions</h5>
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-info" onclick="downloadImage()">
                                        <i class="fas fa-download"></i> Download Photo
                                    </button>
                                    
                                    <?php if ($status === 'rejected' || $status === 'pending'): ?>
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateModal">
                                        <i class="fas fa-edit"></i> Update Photo
                                    </button>
                                    <?php endif; ?>
                                    
                                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                        <i class="fas fa-trash"></i> Delete Verification
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Photo display -->
                        <div class="text-center">
                            <h5>Verification Photo</h5>
                            <div class="verification-photo-container">
                                <img src="<?php echo $data['file_url']; ?>" 
                                     alt="Verification Photo" 
                                     class="img-fluid rounded border shadow verification-photo"
                                     id="verificationImage"
                                     onclick="toggleFullscreen()">
                                <p class="text-muted mt-2">
                                    <small>
                                        <i class="fas fa-search-plus"></i> Click image to toggle fullscreen view
                                    </small>
                                </p>
                            </div>
                        </div>
                        
                        <!-- Zoom controls -->
                        <div class="text-center mt-3">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="zoomOut()">
                                    <i class="fas fa-search-minus"></i> Zoom Out
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetZoom()">
                                    <i class="fas fa-expand-arrows-alt"></i> Reset
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="zoomIn()">
                                    <i class="fas fa-search-plus"></i> Zoom In
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update Verification Photo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Updating your verification photo will reset your status to "Pending" and require re-review.
                    </div>
                    
                    <div class="mb-3">
                        <label for="update_verification_photo" class="form-label">Select New Verification Document</label>
                        <input type="file" class="form-control" id="update_verification_photo" name="verification_photo" 
                               accept=".jpg,.jpeg,.png" required>
                        <div class="form-text">
                            Accepted formats: JPG, PNG. Maximum size: 5MB
                        </div>
                    </div>
                    
                    <div id="updatePreview" class="text-center" style="display: none;">
                        <img id="updatePreviewImage" src="" alt="Preview" class="img-fluid rounded border" style="max-height: 200px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning" id="updateBtn">
                        <i class="fas fa-edit"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
let currentZoom = 1;
let isFullscreen = false;

document.addEventListener('DOMContentLoaded', function() {
    // Update form preview
    document.getElementById('update_verification_photo').addEventListener('change', function(e) {
        previewFile(e.target, 'updatePreviewImage', 'updatePreview');
    });
    
    // Update form submission
    document.getElementById('updateForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const updateBtn = document.getElementById('updateBtn');
        
        updateBtn.disabled = true;
        updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
        
        fetch('<?php echo URLROOT; ?>/Verification/update', {
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
            updateBtn.disabled = false;
            updateBtn.innerHTML = '<i class="fas fa-edit"></i> Update';
            bootstrap.Modal.getInstance(document.getElementById('updateModal')).hide();
        });
    });
});

// File preview functionality
function previewFile(input, imageId, containerId) {
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(imageId).src = e.target.result;
            document.getElementById(containerId).style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById(containerId).style.display = 'none';
    }
}

// Toggle fullscreen view
function toggleFullscreen() {
    const image = document.getElementById('verificationImage');
    const body = document.body;
    
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

// Zoom functions
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
    if (!isFullscreen) {
        image.style.transform = `scale(${currentZoom})`;
    }
}

// Download image
function downloadImage() {
    const link = document.createElement('a');
    link.href = '<?php echo $data['file_url']; ?>';
    link.download = 'verification_photo.<?php echo pathinfo($data['verification']->photo_path, PATHINFO_EXTENSION); ?>';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Confirm delete
function confirmDelete() {
    if (confirm('Are you sure you want to delete your verification record? This action cannot be undone.')) {
        deleteVerification();
    }
}

// Delete verification
function deleteVerification() {
    fetch('<?php echo URLROOT; ?>/Verification/delete', {
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
                window.location.href = '<?php echo URLROOT; ?>/Verification/index';
            }, 1500);
        } else {
            showAlert('danger', data.message);
        }
    })
    .catch(error => {
        showAlert('danger', 'Delete failed. Please try again.');
    });
}

// Show alert messages
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.container');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto dismiss after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Keyboard shortcuts for fullscreen mode
document.addEventListener('keydown', function(e) {
    if (isFullscreen) {
        switch(e.key) {
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
</script>

<?php
// Helper function to format file size
function formatFileSize($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}
?>

<?php require APPROOT . '/views/inc/footer.php'; ?>