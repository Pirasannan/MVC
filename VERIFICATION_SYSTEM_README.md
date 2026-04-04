# Doctor Profile Verification System

A complete PHP + MySQL verification system for doctor profiles with secure file upload, validation, and status management.

## Features

- **Secure File Upload**: JPG/PNG validation with file header checking
- **Status Management**: Pending, Verified, Rejected status tracking
- **User Interface**: Complete web interface for doctors to manage verification
- **File Security**: Secure storage with proper permissions and .htaccess protection
- **MVC Architecture**: Clean separation following existing application patterns
- **AJAX Support**: Smooth user experience with asynchronous operations

## System Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- GD extension for image processing
- Fileinfo extension for MIME type detection
- JSON extension for API responses
- Web server with .htaccess support (Apache recommended)

## Installation

### 1. Database Setup

Run the database setup script:
```bash
php database/setup_verification_table.php
```

Or manually execute the SQL:
```sql
-- See database/doctor_verifications_schema.sql
```

### 2. Directory Setup

Run the system setup script:
```bash
php setup_verification_system.php
```

This will:
- Create upload directories with proper permissions
- Set up security .htaccess files
- Check system requirements
- Generate setup report

### 3. File Structure

The system creates the following structure:
```
public/uploads/verifications/
├── .htaccess (security rules)
├── {user_id}/
│   ├── .htaccess (user-specific security)
│   └── verification_{user_id}_{timestamp}_{random}.{ext}
```

## File Structure

### Models
- `app/models/M_Verification.php` - Database operations for verification records

### Controllers
- `app/controllers/Verification.php` - Main verification controller

### Views
- `app/views/verification/profile.php` - Main verification interface
- `app/views/verification/upload.php` - Upload form interface
- `app/views/verification/view.php` - Photo viewing interface

### Libraries
- `app/libraries/FileValidator.php` - File validation and security
- `app/libraries/FileManager.php` - File storage and management

### Database
- `database/doctor_verifications_schema.sql` - Database schema
- `database/setup_verification_table.php` - Setup script
- `database/verify_table_setup.php` - Verification script

## Usage

### For Doctors

1. **Access Verification**: Navigate to `/Verification/index`
2. **Upload Photo**: Click "Upload Verification Photo" button
3. **View Status**: Check verification status (Pending/Verified/Rejected)
4. **Update Photo**: Replace existing photo if needed
5. **Delete Verification**: Remove verification record completely

### URL Routes

- `GET /Verification/index` - Main verification page
- `POST /Verification/upload` - Upload new verification photo
- `GET /Verification/view` - View uploaded photo
- `POST /Verification/update` - Update existing photo
- `POST /Verification/delete` - Delete verification record
- `GET /Verification/getStatus` - AJAX status check

## API Responses

All AJAX endpoints return JSON responses:

```json
{
    "success": true|false,
    "message": "Response message",
    "data": {} // Optional additional data
}
```

## Security Features

### File Validation
- File type validation (JPG/PNG only)
- File size limits (5MB maximum)
- MIME type checking
- File header validation (magic numbers)
- Filename sanitization

### Access Control
- User authentication required
- Doctor role verification
- User can only access own verification records
- CSRF protection on forms

### File Storage Security
- Files stored outside web root when possible
- Unique filename generation
- .htaccess protection against direct access
- Directory traversal prevention

## Database Schema

```sql
CREATE TABLE doctor_verifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    email VARCHAR(255) NOT NULL,
    photo_path VARCHAR(500) NOT NULL,
    verification_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    verified_at DATETIME NULL,
    rejection_reason TEXT NULL,
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_status (verification_status)
);
```

## Configuration

### PHP Settings
Recommended PHP configuration:
```ini
upload_max_filesize = 5M
post_max_size = 6M
memory_limit = 128M
max_execution_time = 300
```

### File Permissions
- Upload directories: 755
- Uploaded files: 644
- .htaccess files: 644

## Error Handling

The system handles various error scenarios:

### File Upload Errors
- Invalid file type
- File size exceeding limits
- Upload directory permissions
- Disk space limitations
- Corrupted files

### Database Errors
- Foreign key constraint violations
- Connection failures
- Transaction rollbacks

### Security Errors
- Unauthorized access attempts
- File path traversal attempts
- Malicious file uploads

## Maintenance

### Cleanup Orphaned Files
```php
// Get all valid file paths from database
$validPaths = $verificationModel->getAllFilePaths();

// Clean up orphaned files
$result = FileManager::cleanupOrphanedFiles($validPaths);
```

### Monitor Directory Size
```php
// Check user directory size
$size = FileManager::getUserDirectorySize($userId);
```

## Troubleshooting

### Common Issues

1. **Upload fails with "Permission denied"**
   - Check directory permissions (755 for directories)
   - Ensure web server can write to upload directory

2. **File validation fails**
   - Verify GD extension is installed
   - Check file is actually a valid image

3. **Database connection errors**
   - Verify database credentials in config
   - Ensure Users table exists with proper structure

4. **AJAX requests fail**
   - Check browser console for JavaScript errors
   - Verify URLROOT constant is set correctly

### Debug Mode

Enable debug mode by adding to your config:
```php
define('DEBUG_MODE', true);
```

This will show detailed error messages and validation results.

## Integration with Existing System

The verification system integrates with your existing MVC application:

1. **User Authentication**: Uses existing session management
2. **Database**: Extends existing database with foreign key to Users table
3. **Styling**: Uses existing Bootstrap classes and styling
4. **Navigation**: Can be integrated into existing doctor dashboard

### Adding to Doctor Dashboard

Add verification link to doctor navigation:
```php
<a href="<?php echo URLROOT; ?>/Verification/index" class="nav-link">
    <i class="fas fa-shield-alt"></i> Profile Verification
</a>
```

## Support

For issues or questions:
1. Check the setup report generated during installation
2. Review error logs in your web server
3. Verify all system requirements are met
4. Test with the provided verification script

## License

This verification system is part of your existing PHP application and follows the same licensing terms.