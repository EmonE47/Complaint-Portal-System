<!DOCTYPE html>
<html>
<head>
    <title>Complaint Created</title>
</head>
<body>
    <h1>New Complaint Submitted</h1>
    <p>Dear {{ $user->name }},</p>
    <p>A new complaint has been submitted with the following details:</p>
    <ul>
        <li><strong>Complaint ID:</strong> {{ $complaint->complaint_number }}</li>
        <li><strong>Type:</strong> {{ $complaint->complaint_type_text }}</li>
        <li><strong>Description:</strong> {{ $complaint->description }}</li>
        <li><strong>Status:</strong> {{ $complaint->status_text }}</li>
        <li><strong>Police Station:</strong> {{ $complaint->police_station_text }}</li>
        <li><strong>Submitted At:</strong> {{ $complaint->created_at->format('Y-m-d H:i:s') }}</li>
    </ul>
    <p>You will be notified when there are updates to this complaint.</p>
    <p>Thank you for using our complaint management system.</p>
    <p>Best regards,<br>Complaint Management Team</p>
</body>
</html>
