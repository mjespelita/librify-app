

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Form Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #0066cc;
            color: #ffffff;
            padding: 15px;
            border-radius: 8px 8px 0 0;
            text-align: center;
            font-size: 20px;
        }
        .content {
            padding: 20px;
        }
        .content p {
            margin: 10px 0;
            line-height: 1.5;
        }
        .label {
            font-weight: bold;
            color: #0066cc;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            text-align: center;
            color: #999999;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            New Message from ISP Website Contact Form
        </div>
        <div class="content">
            <p><span class="label">Name:</span> {{ $name }}</p>
            <p><span class="label">Email:</span> {{ $email }}</p>
            <p><span class="label">Subject:</span> {{ $subjectLine }}</p>
            <p><span class="label">Message:</span><br>{{ $messageContent }}</p>
        </div>
    </div>
</body>
</html>