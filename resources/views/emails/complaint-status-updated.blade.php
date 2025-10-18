<!DOCTYPE html>
<html>
<head>
    <title>Complaint Status Updated</title>
</head>
<body>
    <h1>Complaint Status Update</h1>
    <p>Dear {{ $user->name }},</p>
    <p>The status of your complaint has been updated:</p>
    <ul>
        <li><strong>Complaint ID:</strong> {{ $complaint->id }}</li>
        <li><strong>Title:</strong> {{ $complaint->title }}</li>
        <li><strong>New Status:</strong> {{ $status }}</li>
        <li><strong>Updated At:</strong> {{ $updatedAt->format('Y-m-d H:i:s') }}</li>
    </ul>
    <p>Please log in to your account for more details.</p>
    <p>Thank you for using our complaint management system.</p>
    <p>Best regards,<br>Complaint Management Team</p>
</body>
</html>
