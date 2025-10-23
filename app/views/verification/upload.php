<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-upload"></i> Upload Verification Photo</h4>
                </div>
                <div class="card-body">
                    
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Upload Guidelines</h6>
                        <ul class="mb-0">
                            <li>Upload a clear photo of your NIC or medical license</li>
                            <li>Accepted formats: JPG, PNG</li>
                            <li>Maximum file size: 5MB</li>
                            <li>Ensure all text is clearly readable</li>
                        </ul>
                    </div>
                    
                    <form id="uploadForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="verification_photo" class="form-label">Select Verification Document *</label>
                            <input type="file" class="form-control" id="verification_photo" name="verification_photo" 
                                   accept=".jpg,.jpeg,.png" required>
                            <div class="form-text">
                                Choose a clear, high-quality image of your verification document
                            </div>
                        </div>
                        
                        <!-- File preview -->
                        <div id="filePreview" class="mb-3" style="display: none;">
                            <label class="form-label">Preview:</label>
                            <div class="text-center">
                                <img id="previewImage" src="" alt="Preview" class="img-fluid rounded border" style="max-height: 300px;">
                            </div>
                        </div>
                        
                        <!-- File info -->
                        <div id="fileInfo" class="mb-3" style="display: none;">
                            <div class="row">
                                <div class="col-sm-6">
                                    <strong>File Name:</strong> <span id="fileName"></span>
                                </div>
                                <div class="col-sm-6">
                                    <strong>File Size:</strong> <span id="fileSize"></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Upload progress -->
                        <div id="uploadProgress" class="mb-3" style="display: none;">
                            <label class="form-label">Upload Progress:</label>
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                     role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>
                        
                        <!-- Error messages -->
                        <div id="errorMessages" class="alert alert-danger" style="display: none;">
                            <ul id="errorList" class="mb-0"></ul>
                        </div>
                        
                        <!-- Success message -->
                        <div id="successMessage" class="alert alert-success" style="display: none;"></div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg" id="uploadBtn">
                                <i class="fas fa-upload"></i> Upload Verification Photo
                            </button>
                            <a href="<?php echo URLROOT; ?>/Verification/index" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Profile
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('verification_photo');
    const uploadForm = document.getElementById('uploadForm');
    const uploadBtn = document.getElementById('uploadBtn');
    const progressBar = document.querySelector('#uploadProgress .progress-bar');
    
    // File input change handler
    fileInput.addEventListener('change', function(e) {
        handleFileSelect(e.target.files[0]);
    });
    
    // Form submission handler
    uploadForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const file = fileInput.files[0];
        if (!file) {
            showError(['Please select a file to upload']);
            return;
        }
        
        // Client-side validation
        const validation = validateFile(file);
        if (!validation.valid) {
            showError(validation.errors);
            return;
        }
        
        uploadFile(file);
    });
    
    // Handle file selection
    function handleFileSelect(file) {
        if (!file) {
            hidePreview();
            return;
        }
        
        // Validate file
        const validation = validateFile(file);
        if (!validation.valid) {
            showError(validation.errors);
            hidePreview();
            return;
        }
        
        // Show file info
        showFileInfo(file);
        
        // Show preview
        showPreview(file);
        
        // Clear any previous errors
        hideError();
    }
    
    // Client-side file validation
    function validateFile(file) {
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
    
    // Show file preview
    function showPreview(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImage').src = e.target.result;
            document.getElementById('filePreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
    
    // Hide file preview
    function hidePreview() {
        document.getElementById('filePreview').style.display = 'none';
        document.getElementById('fileInfo').style.display = 'none';
    }
    
    // Show file information
    function showFileInfo(file) {
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileSize').textContent = formatFileSize(file.size);
        document.getElementById('fileInfo').style.display = 'block';
    }
    
    // Upload file
    function uploadFile(file) {
        const formData = new FormData();
        formData.append('verification_photo', file);
        
        // Disable upload button
        uploadBtn.disabled = true;
        uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
        
        // Show progress bar
        document.getElementById('uploadProgress').style.display = 'block';
        progressBar.style.width = '0%';
        
        // Create XMLHttpRequest for progress tracking
        const xhr = new XMLHttpRequest();
        
        // Upload progress handler
        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                const percentComplete = (e.loaded / e.total) * 100;
                progressBar.style.width = percentComplete + '%';
                progressBar.textContent = Math.round(percentComplete) + '%';
            }
        });
        
        // Response handler
        xhr.addEventListener('load', function() {
            try {
                const response = JSON.parse(xhr.responseText);
                
                if (response.success) {
                    showSuccess(response.message);
                    
                    // Redirect after 2 seconds
                    setTimeout(() => {
                        window.location.href = '<?php echo URLROOT; ?>/Verification/index';
                    }, 2000);
                } else {
                    showError([response.message]);
                }
            } catch (error) {
                showError(['Upload failed. Please try again.']);
            }
        });
        
        // Error handler
        xhr.addEventListener('error', function() {
            showError(['Upload failed. Please check your connection and try again.']);
        });
        
        // Complete handler
        xhr.addEventListener('loadend', function() {
            uploadBtn.disabled = false;
            uploadBtn.innerHTML = '<i class="fas fa-upload"></i> Upload Verification Photo';
            document.getElementById('uploadProgress').style.display = 'none';
        });
        
        // Send request
        xhr.open('POST', '<?php echo URLROOT; ?>/Verification/upload');
        xhr.send(formData);
    }
    
    // Show error messages
    function showError(errors) {
        const errorList = document.getElementById('errorList');
        errorList.innerHTML = '';
        
        errors.forEach(error => {
            const li = document.createElement('li');
            li.textContent = error;
            errorList.appendChild(li);
        });
        
        document.getElementById('errorMessages').style.display = 'block';
        document.getElementById('successMessage').style.display = 'none';
    }
    
    // Hide error messages
    function hideError() {
        document.getElementById('errorMessages').style.display = 'none';
    }
    
    // Show success message
    function showSuccess(message) {
        document.getElementById('successMessage').textContent = message;
        document.getElementById('successMessage').style.display = 'block';
        document.getElementById('errorMessages').style.display = 'none';
    }
    
    // Format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
});
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>