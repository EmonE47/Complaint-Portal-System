<!DOCTYPE html>
<html>
<head>
    <title>Case Assigned</title>
</head>
<body>
    <h1>New Case Assignment</h1>
    <p>Dear {{ $inspector->name }},</p>
    <p>A new case has been assigned to you with the following details:</p>
    <ul>
        <li><strong>Case ID:</strong> {{ $case->id }}</li>
        <li><strong>Title:</strong> {{ $case->title }}</li>
        <li><strong>Description:</strong> {{ $case->description }}</li>
        <li><strong>Assigned At:</strong> {{ $assignedAt->format('Y-m-d H:i:s') }}</li>
    </ul>
    <p>Please take the necessary actions promptly.</p>
    <p>Thank you for your dedication.</p>
    <p>Best regards,<br>Complaint Management Team</p>
</body>
</html>
