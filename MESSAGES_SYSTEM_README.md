# MediLink Messages Dashboard

## Overview
The Messages Dashboard allows doctors to communicate with their patients through a secure messaging system integrated into the MediLink platform.

## Features
- **Unread Messages Counter**: Shows total unread messages at a glance
- **Recent Conversations**: Displays recent message threads with patients
- **Send New Messages**: Compose and send messages to any patient
- **Mark as Read**: Mark conversations as read to manage message status
- **Responsive Design**: Works on desktop, tablet, and mobile devices
- **Real-time Updates**: Messages update dynamically without page refresh

## Installation

### 1. Database Setup
Run the setup script to create the messages table:
```bash
php setup_messages_system.php
```

### 2. File Structure
The following files have been created/updated:
```
database/
├── messages_schema.sql          # Database schema for messages

app/
├── models/M_Messages.php        # Messages model with database operations
├── controllers/Pages.php       # Updated with doctorMessages method
└── views/pages/v_doctor_messages.php  # Messages dashboard view

public/css/components/messages/
└── doctor_messages.css          # Styling for messages dashboard
```

### 3. Navigation
The Messages link is already included in the doctor sidebar navigation and will be highlighted when active.

## Usage

### For Doctors:
1. **View Messages**: Click "Messages" in the sidebar to access the dashboard
2. **Check Unread**: See unread message count in the stats card
3. **Send Message**: Click "New Message" button to compose a message
4. **Mark as Read**: Click "Mark Read" on conversations with unread messages
5. **View Conversations**: Click "View" to see detailed conversation (feature expandable)

### Database Schema
The messages table includes:
- `id`: Primary key
- `sender_id`: ID of the message sender
- `receiver_id`: ID of the message receiver  
- `sender_type`: 'doctor' or 'patient'
- `receiver_type`: 'doctor' or 'patient'
- `subject`: Message subject line
- `message_text`: Message content
- `is_read`: Read status (boolean)
- `created_at`: Timestamp when message was sent
- `updated_at`: Timestamp when message was last updated

## Customization

### Adding Features:
1. **Attachments**: Extend the messages table to include file attachments
2. **Message Threading**: Group related messages by conversation ID
3. **Push Notifications**: Add real-time notifications for new messages
4. **Message Search**: Add search functionality to find specific messages
5. **Message Templates**: Create pre-written message templates for common scenarios

### Styling:
The CSS follows the existing MediLink design patterns and can be customized by modifying:
- `public/css/components/messages/doctor_messages.css`

## Security Notes
- All user inputs are sanitized using `htmlspecialchars()`
- Database queries use prepared statements to prevent SQL injection
- Session-based authentication ensures only authorized doctors can access messages
- Messages are only visible to the sender and receiver

## Future Enhancements
- Patient-side messaging interface
- Message encryption for enhanced security
- Bulk message operations
- Message scheduling
- Read receipts and delivery confirmations
- Integration with appointment system for context-aware messaging