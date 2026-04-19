<?php
require APPROOT . '/views/inc/header.php';
$current_page = 'doctorProfile';
?>

<div class="dashboard-container doctor">
    <!-- Sidebar Navigation -->
    <?php require APPROOT . '/views/inc/components/doctorSidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div class="header-left">
                <h1 class="page-title">Profile Verification</h1>
            </div>
            <div class="header-right">
                <div class="user-info">
                    <div class="user-avatar">
                        <span class="avatar-icon">👤</span>
                    </div>
                    <div class="user-details">
                        <span class="user-name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                        <span class="user-id">ID: <?php echo $_SESSION['user_id']; ?></span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Verification Content -->
        <div class="dashboard-content">
            <?php if (!$data['verification']): ?>
                <!-- No verification record -->
                <div class="verification-section">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="fas fa-shield-alt"></i> Start Profile Verification
                        </h2>
                    </div>
                    <div class="section-content">
                        <div class="verification-status no-verification">
                            <div class="status-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="status-content">
                                <h4>Profile Not Verified</h4>
                                <p>To complete your doctor profile, please upload a verification document (such as your NIC or medical license).</p>
                                <div class="verification-requirements">
                                    <h6>Requirements:</h6>
                                    <ul>
                                        <li>Clear photo of your NIC or medical license</li>
                                        <li>File format: JPG or PNG only</li>
                                        <li>Maximum file size: 5MB</li>
                                        <li>All text must be clearly readable</li>
                                    </ul>
                                </div>
                                <div class="verification-actions">
                                    <button type="button" class="action-button primary" onclick="showUploadForm()">
                                        <i class="fas fa-upload"></i> Upload Verification Document
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Upload Form (Initially Hidden) -->
                        <div id="uploadFormSection" class="upload-form-section" style="display: none;">
                            <form id="uploadForm" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="verification_photo" class="form-label">Select Verification Document</label>
                                    <input type="file" class="form-control" id="verification_photo" name="verification_photo" 
                                           accept=".jpg,.jpeg,.png" required>
                                    <div class="form-text">
                                        Accepted formats: JPG, PNG. Maximum size: 5MB
                                    </div>
                                </div>
                                
                                <div id="uploadPreview" class="preview-section" style="display: none;">
                                    <h6>Preview:</h6>
                                    <img id="previewImage" src="" alt="Preview" class="verification-photo">
                                </div>
                                
                                <div id="uploadProgress" class="progress-section" style="display: none;">
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                    </div>
                                </div>
                                
                                <div class="form-actions">
                                    <button type="submit" class="action-button primary" id="uploadBtn">
                                        <i class="fas fa-upload"></i> Upload Document
                                    </button>
                                    <button type="button" class="action-button secondary" onclick="hideUploadForm()">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
            <?php else: ?>
                <!-- Existing verification record -->
                <div class="verification-section">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="fas fa-shield-alt"></i> Verification Status
                        </h2>
                        <a href="<?php echo URLROOT; ?>/Pages/doctorProfile" class="action-button secondary">
                            <i class="fas fa-arrow-left"></i> Back to Profile
                        </a>
                    </div>
                    <div class="section-content">
                        <?php
                        $status = $data['verification']->verification_status;
                        $statusClass = '';
                        $statusIcon = '';
                        $statusText = '';
                        $statusMessage = '';
                        
                        switch($status) {
                            case 'pending':
                                $statusClass = 'pending';
                                $statusIcon = 'clock';
                                $statusText = 'Verification Pending';
                                $statusMessage = 'Your verification is under review. You will be notified once it\'s processed.';
                                break;
                            case 'verified':
                                $statusClass = 'verified';
                                $statusIcon = 'check-circle';
                                $statusText = 'Profile Verified';
                                $statusMessage = 'Your profile has been successfully verified!';
                                break;
                            case 'rejected':
                                $statusClass = 'rejected';
                                $statusIcon = 'times-circle';
                                $statusText = 'Verification Rejected';
                                $statusMessage = 'Your verification was rejected. Please upload a new document.';
                                break;
                        }
                        ?>
                        
                        <div class="verification-status <?php echo $statusClass; ?>">
                            <div class="status-icon">
                                <i class="fas fa-<?php echo $statusIcon; ?>"></i>
                            </div>
                            <div class="status-content">
                                <h4><?php echo $statusText; ?></h4>
                                <p><?php echo $statusMessage; ?></p>
                                
                                <div class="verification-details">
                                    <div class="detail-item">
                                        <span class="detail-label">Uploaded:</span>
                                        <span class="detail-value"><?php echo date('M d, Y H:i', strtotime($data['verification']->uploaded_at)); ?></span>
                                    </div>
                                    
                                    <?php if ($data['verification']->verified_at): ?>
                                    <div class="detail-item">
                                        <span class="detail-label">Verified:</span>
                                        <span class="detail-value"><?php echo date('M d, Y H:i', strtotime($data['verification']->verified_at)); ?></span>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($data['verification']->rejection_reason): ?>
                                    <div class="detail-item rejection-reason">
                                        <span class="detail-label">Reason:</span>
                                        <span class="detail-value"><?php echo htmlspecialchars($data['verification']->rejection_reason); ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Current Photo Display -->
                        <div class="current-photo-section">
                            <h5>Current Verification Document</h5>
                            <div class="verification-photo-container">
                                <?php $fileUrl = URLROOT . '/' . $data['verification']->photo_path; ?>
                                <img src="<?php echo $fileUrl; ?>" 
                                     alt="Verification Photo" 
                                     class="verification-photo"
                                     onclick="viewFullImage('<?php echo $fileUrl; ?>')">
                                <p class="photo-hint">Click image to view full size</p>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="verification-actions">
                            <?php if ($status === 'rejected' || $status === 'pending'): ?>
                                <button type="button" class="action-button primary" onclick="showUpdateForm()">
                                    <i class="fas fa-edit"></i> Replace Document
                                </button>
                            <?php endif; ?>
                            
                            <button type="button" class="action-button danger" onclick="confirmDelete()">
                                <i class="fas fa-trash"></i> Delete Verification
                            </button>
                        </div>
                        
                        <!-- Update Form (Initially Hidden) -->
                        <?php if ($status === 'rejected' || $status === 'pending'): ?>
                        <div id="updateFormSection" class="update-form-section" style="display: none;">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                Replacing your verification document will reset your status to "Pending" and require re-review.
                            </div>
                            
                            <form id="updateForm" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="update_verification_photo" class="form-label">Select New Verification Document</label>
                                    <input type="file" class="form-control" id="update_verification_photo" name="verification_photo" 
                                           accept=".jpg,.jpeg,.png" required>
                                    <div class="form-text">
                                        Accepted formats: JPG, PNG. Maximum size: 5MB
                                    </div>
                                </div>
                                
                                <div id="updatePreview" class="preview-section" style="display: none;">
                                    <h6>Preview:</h6>
                                    <img id="updatePreviewImage" src="" alt="Preview" class="verification-photo">
                                </div>
                                
                                <div class="form-actions">
                                    <button type="submit" class="action-button primary" id="updateBtn">
                                        <i class="fas fa-edit"></i> Replace Document
                                    </button>
                                    <button type="button" class="action-button secondary" onclick="hideUpdateForm()">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<!-- Full Image Modal -->
<div class="modal fade" id="fullImageModal" tabindex="-1" aria-labelledby="fullImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fullImageModalLabel">Verification Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="fullImage" src="" alt="Full Size Verification Photo" class="img-fluid">
            </div>
        </div>
    </div>
</div>



<!-- Full Image Modal -->
<div class="modal fade" id="fullImageModal" tabindex="-1" aria-labelledby="fullImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fullImageModalLabel">Verification Photo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="fullImage" src="" alt="Full Size Verification Photo" class="img-fluid">
            </div>
        </div>
    </div>
</div>



<script src="<?php echo URLROOT; ?>/js/verification.js"></script>
<script>
// Page-specific functions
function showUploadForm() {
    document.getElementById('uploadFormSection').style.display = 'block';
    document.querySelector('.verification-status').style.display = 'none';
}

function hideUploadForm() {
    document.getElementById('uploadFormSection').style.display = 'none';
    document.querySelector('.verification-status').style.display = 'block';
}

function showUpdateForm() {
    document.getElementById('updateFormSection').style.display = 'block';
}

function hideUpdateForm() {
    document.getElementById('updateFormSection').style.display = 'none';
}

// View full image
function viewFullImage(imageUrl) {
    document.getElementById('fullImage').src = imageUrl;
    new bootstrap.Modal(document.getElementById('fullImageModal')).show();
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    // File preview for upload
    const uploadInput = document.getElementById('verification_photo');
    if (uploadInput) {
        uploadInput.addEventListener('change', function(e) {
            previewFile(e.target, 'previewImage', 'uploadPreview');
        });
    }

    // File preview for update
    const updateInput = document.getElementById('update_verification_photo');
    if (updateInput) {
        updateInput.addEventListener('change', function(e) {
            previewFile(e.target, 'updatePreviewImage', 'updatePreview');
        });
    }

    // Upload form submission
    const uploadForm = document.getElementById('uploadForm');
    if (uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const uploadBtn = document.getElementById('uploadBtn');
            const progressContainer = document.getElementById('uploadProgress');
            
            setButtonLoading(uploadBtn, true, 'Uploading...');
            progressContainer.style.display = 'block';
            
            fetch('<?php echo URLROOT; ?>/Verification/upload', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', data.message);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert('danger', data.message);
                }
            })
            .catch(error => {
                showAlert('danger', 'Upload failed. Please try again.');
            })
            .finally(() => {
                setButtonLoading(uploadBtn, false, 'Upload Document');
                progressContainer.style.display = 'none';
            });
        });
    }

    // Update form submission
    const updateForm = document.getElementById('updateForm');
    if (updateForm) {
        updateForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const updateBtn = document.getElementById('updateBtn');
            
            setButtonLoading(updateBtn, true, 'Replacing...');
            
            fetch('<?php echo URLROOT; ?>/Verification/update', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', data.message);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert('danger', data.message);
                }
            })
            .catch(error => {
                showAlert('danger', 'Update failed. Please try again.');
            })
            .finally(() => {
                setButtonLoading(updateBtn, false, 'Replace Document');
            });
        });
    }
});

function previewFile(input, imageId, containerId) {
    const file = input.files[0];
    if (file) {
        const validation = validateFileClient(file);
        if (!validation.valid) {
            showAlert('danger', validation.errors.join(', '));
            document.getElementById(containerId).style.display = 'none';
            return;
        }

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

function setButtonLoading(button, loading, text) {
    if (!button) return;
    
    if (loading) {
        button.disabled = true;
        button.innerHTML = `<i class="fas fa-spinner fa-spin"></i> ${text}`;
    } else {
        button.disabled = false;
        button.innerHTML = `<i class="fas fa-upload"></i> ${text}`;
    }
}
</script>

<!-- Fix Large Verification Photo Issue -->
<style>
/* Verification Photo Size Fix - Override any existing styles */
.verification-photo-container {
    text-align: center !important;
    margin: 16px 0 !important;
    max-width: 100% !important;
}

.verification-photo {
    max-width: 350px !important;
    max-height: 250px !important;
    width: auto !important;
    height: auto !important;
    border-radius: 8px !important;
    border: 1px solid #e5e7eb !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
    cursor: pointer !important;
    transition: transform 0.2s ease !important;
    object-fit: contain !important;
}

.verification-photo:hover {
    transform: scale(1.02) !important;
}

.photo-hint {
    margin-top: 8px !important;
    color: #6b7280 !important;
    font-size: 14px !important;
    text-align: center !important;
}

/* Preview images in forms */
#previewImage, #updatePreviewImage {
    max-width: 300px !important;
    max-height: 200px !important;
    width: auto !important;
    height: auto !important;
    border-radius: 8px !important;
    border: 2px dashed #d1d5db !important;
    padding: 8px !important;
    object-fit: contain !important;
}

/* Current photo section */
.current-photo-section {
    margin: 24px 0 !important;
    padding: 20px !important;
    background: #f9fafb !important;
    border-radius: 8px !important;
    border: 1px solid #e5e7eb !important;
}

.current-photo-section h5 {
    margin-bottom: 16px !important;
    color: #1f2937 !important;
    font-weight: 600 !important;
    text-align: center !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .verification-photo {
        max-width: 280px !important;
        max-height: 200px !important;
    }
    
    #previewImage, #updatePreviewImage {
        max-width: 250px !important;
        max-height: 180px !important;
    }
}

@media (max-width: 480px) {
    .verification-photo {
        max-width: 240px !important;
        max-height: 180px !important;
    }
    
    #previewImage, #updatePreviewImage {
        max-width: 200px !important;
        max-height: 150px !important;
    }
}
</style>

<?php require APPROOT . '/views/inc/footer.php'; ?>