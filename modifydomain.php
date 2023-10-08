<!DOCTYPE html>
<html>
<head>
    <title>Modify Domain - regist.cc</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/halfmoon@2.0.0/css/halfmoon.min.css" rel="stylesheet" integrity="sha256-pfT/Otf/lK1xFNInb5QQR1uRF9cOP/8zDICH+QQ6o2c=" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
<?php include "header.php"; ?>

<!-- Header -->
<header>
    <div class="container">
        <div class="card bg-light mt-4">
            <div class="card-body text-center">
                <h1 class="display-4"><i class="bi bi-r-square"></i> Modify Domain</h1>
                <p class="lead">Update or delete your subdomain information!</p>
            </div>
        </div>
    </div>
</header>
<div class="container">
    <div class="card mt-4">
        <div class="card-body">
        <b> Make sure to keep the URL in a safe place, like in a USB flash drive. </b>
<?php
// Cloudflare API Credentials
$apiKey = 'replace with API key';
$zoneId = 'replace with zone';

// Function to update a DNS record in Cloudflare
function updateDNSRecord($zoneId, $recordId, $type, $content, $domain)
{
    global $apiKey;

    $url = "https://api.cloudflare.com/client/v4/zones/{$zoneId}/dns_records/{$recordId}";

    $data = [
        'content' => $content,
        'name' => $domain, // Replace with the appropriate domain name
        'proxied' => false,
        'type' => $type,
        'comment' => 'Domain verification record',
        'tags' => ['owner:dns-team'],
        'ttl' => 3600,
    ];

    $headers = [
        'Content-Type: application/json',
        "Authorization: Bearer {$apiKey}",
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    curl_close($ch);

    //return json_decode($response, true);
    return $response;
}


// Function to delete a DNS record in Cloudflare
function deleteDNSRecord($zoneId, $recordId)
{
    global $apiKey;

    $url = "https://api.cloudflare.com/client/v4/zones/{$zoneId}/dns_records/{$recordId}";

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey,
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Database connection settings
$servername = "localhost"; // Change this to your MySQL server hostname
$username = "user"; // Change this to your MySQL username
$password = "pass"; // Change this to your MySQL password
$dbname = "db";

// Create a new database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if a request is detected
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $uniqueHash = $_POST['hash']; // Unique hash received from the form
           $domain = $row['domain_name'];

    if ($action === 'update') {
        $type = $_POST['type'];
        $content = $_POST['content'];

        // Retrieve the record from the database using the unique hash
        $sql = "SELECT * FROM subdomain_records WHERE hash = '$uniqueHash'";
        $result = $conn->query($sql);

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $recordId = $row['record_id']; // Get the Cloudflare record ID from the database
            // Update DNS record
            $result = updateDNSRecord($zoneId, $recordId, $type, $content);

            if ($result) {
                echo $result;
            } else {
                echo 'Error updating DNS record: ' . $result['errors'][0]['message'];
            }
        } else {
            echo 'Error: Invalid or expired hash.';
        }
    } elseif ($action === 'delete') {
        // Retrieve the record from the database using the unique hash
        $sql = "SELECT * FROM subdomain_records WHERE hash = '$uniqueHash'";
        $result = $conn->query($sql);

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $recordId = $row['record_id']; // Get the Cloudflare record ID from the database
            $domain = $row['domain_name'];
            // Delete DNS record
            $result = deleteDNSRecord($zoneId, $recordId);

            if ($result['success']) {
                // Delete the record from the database
                $deleteSql = "DELETE FROM subdomain_records WHERE hash = '$uniqueHash'";
                if ($conn->query($deleteSql) === TRUE) {
                    echo '<h2 class="card-title">Domain deleted successfully!</h2>';
                } else {
                    echo 'Error deleting from the database: ' . $conn->error;
                }
            } else {
                echo 'Error deleting DNS record: ' . $result['errors'][0]['message'];
            }
        } else {
            echo 'Error: Invalid or expired hash.';
        }
    }
} else {
    $hash = $_GET['hash'];

    echo '<form method="POST">
        <h2 class="card-title">Update or Delete Domain</h2>
        <input type="hidden" name="hash" value="' . $hash . '">
        <div class="mb-3">
            <label for="type" class="form-label">Select Record Type:</label>
            <select name="type" id="type" class="form-select" required>
                <option value="A">A (IPv4 Address)</option>
                <option value="AAAA">AAAA (IPv6 Address)</option>
                <option value="CNAME">CNAME (Alias)</option>
                <option value="NS">NS (Nameservers)</option>
                <option value="TXT">TXT (Text Record)</option>
            </select>
        </div>
        <div class="mb-3 input-group-lg">
            <label for="content" class="form-label">Enter New Destination:</label>
            <input type="text" name="content" id="content" class="form-control" placeholder="e.g., 192.168.0.1 or example.com" required>
        </div>

        <div class="mb-3 input-group-lg">
            <button type="submit" name="action" value="update" class="btn btn-primary">Update Domain</button>
            <button type="submit" name="action" value="delete" class="btn btn-danger">Delete Domain</button>
        </div>
    </form>';
}

// Close the database connection
$conn->close();
?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>

</body>
</html>
