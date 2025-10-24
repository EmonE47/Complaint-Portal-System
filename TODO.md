# Enable Chatting Option Between Users and Assigned SI

## Overview
Implement a messaging system that allows users to chat with their assigned Sub-Inspector (SI) for specific cases. This will update both User Dashboard and SI Dashboard with chat functionality.

## Tasks

### 1. Database & Models
- [x] Create Message model and migration
- [x] Define relationships (belongs to Complaint, belongs to Sender User, belongs to Receiver User)
- [x] Add message status (sent, delivered, read)

### 2. Controllers & Routes
- [x] Create MessageController with methods:
  - sendMessage
  - getMessages
  - markAsRead
- [x] Add routes for messaging (protected by auth middleware)
- [x] Update InspectorController to handle SI messaging

### 3. User Dashboard Updates
- [x] Add "Chat" button/link in complaints table for assigned cases
- [x] Create chat modal/interface in User dashboard
- [x] Show chat history and allow sending new messages
- [x] Real-time message updates (polling)

### 4. SI Dashboard Updates
- [x] Add "Chat" option in case actions dropdown
- [x] Create chat interface in SI dashboard
- [x] Show unread message indicators
- [x] Allow SI to respond to user messages

### 5. Frontend Implementation
- [x] Create chat modal component
- [x] Implement AJAX for sending/receiving messages
- [x] Add message notification badges
- [x] Style chat interface consistently with existing design

### 6. Security & Validation
- [x] Ensure users can only chat with their assigned SI
- [x] Ensure SI can only chat with users of their assigned cases
- [x] Validate message content and length
- [x] Rate limiting for message sending

### 7. Testing
- [ ] Test message sending between user and SI
- [ ] Test message history loading
- [ ] Test real-time updates
- [ ] Test security restrictions
