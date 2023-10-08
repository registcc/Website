

<!DOCTYPE html>
<html>
<head>
    <title>Get free domains! regist.cc</title>
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
                <h1 class="display-4"><i class="bi bi-r-square"></i> regist.cc</h1>
                <p class="lead">Get a free domain in seconds! Inspired by <a href="https://freenom.com">Freenom</a>.</p>
            </div>
        </div>
    </div>
</header>

<div class="container">
    <div class="card mt-4">
        <div class="card-body">

<?php
// Cloudflare API Credentials
$apiKey = 'replace with your api Key';
$zoneId = 'replace with your zone id';

// Function to create a DNS record in Cloudflare
function createDNSRecord($subdomain, $type, $content)
{
    global $apiKey, $zoneId;

    $url = "https://api.cloudflare.com/client/v4/zones/{$zoneId}/dns_records";

    $data = [
        'type' => $type,
        'name' => $subdomain,
        'content' => $content,
        'ttl' => 1, // Set the TTL (time to live) as desired
        'proxied' => false, // Change to true if you want Cloudflare proxying
    ];

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey,
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
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
    $subdomain = $_POST['subdomain'];
    $type = $_POST['type'];
    $content = $_POST['content'];

    // Input validation
    if (strpos($subdomain, '@') !== false ||
        strpos($subdomain, '%') !== false ||
        strpos($subdomain, '.') !== false ||
        strpos($subdomain, '_') !== false ||
        strpos($subdomain, '*') !== false) {
        echo 'Error: Subdomain cannot contain "@", "%", ".", "_" or "*".';
    } elseif (strtolower(substr($subdomain, -9)) === 'regist.cc') {
        echo 'Error: Subdomain cannot end with "regist.cc".';
    } else {
        // Generate a unique hash
        $uniqueHash = uniqid();

        // Create DNS record
        $result = createDNSRecord($subdomain, $type, $content);

        if ($result['success']) {
            // Save the subdomain record to the database with the unique hash
            $sql = "INSERT INTO subdomain_records (hash, subdomain, type, content) VALUES ('$uniqueHash', '$subdomain', '$type', '$content')";
            if ($conn->query($sql) === TRUE) {
$subdomainLink = 'http://' . htmlspecialchars($subdomain) . '.regist.cc';

echo '<h2 class="card-title">Your free domain "'. $subdomainLink . '" is generated!</h2>';

echo '<div class="mb-3">
        <a href="' . $subdomainLink . '" class="btn btn-primary">Go to Domain</a>
      </div>';

echo '<div class="mb-3">
        <a href="modifydomain.php?hash=' . $uniqueHash . '" class="btn btn-secondary">Change Domain</a>
      </div>';

            } else {
                echo 'Error saving to the database: ' . $conn->error;
            }
        } else {
            echo 'Error: ' . $result['errors'][0]['message'];
        }
    }
} else {
    echo '<form method="POST">
        <h2 class="card-title">Register a Subdomain</h2>
        <div class="mb-3">
            <label for="subdomain" class="form-label">Choose a Subdomain:</label>
            <div class="input-group input-group-lg">
                <input type="text" name="subdomain" id="subdomain" class="form-control" placeholder="e.g., mysubdomain" required>
                <span class="input-group-text">.regist.cc</span>
            </div>
        </div>
        <div class="mb-3 input-group-lg">
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
            <label for="content" class="form-label">Enter Destination:</label>
            <input type="text" name="content" id="content" class="form-control" placeholder="e.g., 192.168.0.1 or example.com" required>
        </div>

        <div class="mb-3 input-group-lg">
            <button type="submit" class="btn btn-primary">Create Subdomain</button>
        </div>
    </form>';
}

// Close the database connection
$conn->close();
?>



        </div>
    </div>
    <p> Design by <a href="https://superdev.one/">superdev.one</a> </p>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>

</body>
</html>
