<?php
$submissionSuccess = false; // Set to false by default

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $domain = htmlspecialchars($_POST['domain'], ENT_QUOTES, 'UTF-8');
    $phishingUrl = htmlspecialchars($_POST['phishing-url'], ENT_QUOTES, 'UTF-8');
    $description = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');

    // You can add additional fields if needed

    function sendDiscordEmbed($email, $domain, $phishingUrl, $description) {
        $timestamp = date("c", strtotime("now"));
        
        $hookObject = [
            "embeds" => [
                [
                    "type" => "rich",
                    "timestamp" => $timestamp,
                    "color" => hexdec('5865F2'),
                    "fields" => [
                        [
                            "name" => "Email Address",
                            "value" => $email,
                            "inline" => false
                        ],
                        [
                            "name" => "Phishing Domain Name",
                            "value" => $domain,
                            "inline" => false
                        ],
                        [
                            "name" => "Description",
                            "value" => $description,
                            "inline" => false
                        ]
                    ]
                ]
            ]
        ];

        // Conditionally add "Phishing URL" field if it's provided
        if (!empty($phishingUrl)) {
            $hookObject['embeds'][0]['fields'][] = [
                "name" => "Phishing URL",
                "value" => $phishingUrl,
                "inline" => false
            ];
        }

        $hookObject = json_encode($hookObject, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

        $headers = ['Content-Type: application/json; charset=utf-8'];
        $url = "https://discord.com/YOURWEBHOOK";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $hookObject);
        
        $response = curl_exec($ch);
        
        if ($response !== false) {
            if ($response !== false) {
            // Set the flag to true if the submission is successful.
            global $submissionSuccess;
            $submissionSuccess = true;
        }
        } else {
            // Error handling here
            echo "Error submitting the report.";
        }
    }
    
    // Call the function to send Discord embed
    sendDiscordEmbed($email, $domain, $phishingUrl, $description);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Get free domains! regist.cc</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/halfmoon@2.0.1/css/halfmoon.min.css" rel="stylesheet" integrity="sha256-SsJizWSIG9JT9Qxbiy8xnYJfjCAkhEQ0hihxRn7jt2M=" crossorigin="anonymous">
    <!-- Halfmoon Modern Theme -->
    <link href="https://cdn.jsdelivr.net/npm/halfmoon@2.0.1/css/cores/halfmoon.modern.css" rel="stylesheet" integrity="sha256-DD6elX+jPmbFYPsGvzodUv2+9FHkxHlVtQi0/RJVULs=" crossorigin="anonymous">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
<?php include "header.php"; ?>

<!-- Header -->
<header>
    <div class="container">
        <div class="card bg-light mt-4">
            <div class="card-body text-center">
                <h1 class="display-4"><i class="bi bi-r-square"></i> regist.cc</h1>
                <p class="lead">Get a free domain in seconds!</p>
            </div>
        </div>
    </div>
</header>
<div class="container">
    <div class="card mt-4">
        <div class="card-header">
            <h2 class="card-title">Report Suspicious Activity</h2>
        </div>
        <div class="card-body">
             <?php if ($submissionSuccess): ?>
                <!-- Success alert -->
                <div class="alert alert-success text-center" role="alert">
                    Report submitted successfully!
                </div>
            <?php endif ?>
            <form method="POST" action="#">
                <div class="mb-3">
                    <label for="email" class="form-label">Your Email Address</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Your Email Address" required>
                </div>
                <div class="mb-3">
                    <label for="domain" class="form-label">Phishing Domain Name</label>
                    <input type="text" name="domain" id="domain" class="form-control" placeholder="Phishing Domain Name" required>
                </div>
                <div class="mb-3">
                    <label for="phishing-url" class="form-label">Phishing URL (if applicable)</label>
                    <input type="url" name="phishing-url" id="phishing-url" class="form-control" placeholder="Phishing URL" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description of Suspicious Activity</label>
                    <textarea name="description" id="description" class="form-control" rows="4" placeholder="Description of Suspicious Activity" required></textarea>
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Submit Report</button>
                </div>
            </form>
        </div>
    </div>
    <p> Design by <a href="https://superdev.one/">superdev.one</a> </p>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>

</body>
</html>