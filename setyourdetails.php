<!DOCTYPE html>
<html lang="en">

<head>
    <title>Get free domains! regist.cc</title>
    <!-- Include Halfmoon CSS -->
    <link href="https://cdn.jsdelivr.net/npm/halfmoon@2.0.0/css/halfmoon.min.css" rel="stylesheet"
        integrity="sha256-pfT/Otf/lK1xFNInb5QQR1uRF9cOP/8zDICH+QQ6o2c" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Gabarito:wght@400;500;700;900&display=swap" rel="stylesheet">
    <style>
        /* Add custom styles here */
        body {
            background-color: #f8f9fa;
        }

        header {
            background-color: #343a40;
            color: #ffffff;
            padding: 2rem 0;
            opacity: 0;
            animation: fadeIn 1s forwards;
        }

        .banner {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
        }

        .form-card {
            margin-top: 2rem;
        }

        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease-in-out;
        }

        .card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .btn-primary:hover {
            transform: scale(1.1);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
    <style>
        body {
            background: #262626;
            color: white;
        }

        h1,
        p,
        h2,
        h3,
        body,
        .navbar-brand,
        .navbar-nav a {
            font-family: 'Gabarito', sans-serif;
        }
    </style>
</head>

<body>
    <?php include "header.php"; ?>
    <!-- Redesigned Header -->
    <header style="background-color: #343a40; color: #ffffff; padding: 2rem 0;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <h1 class="display-4 mb-3">regist.cc</h1>
                    <p class="lead">Get a free domain in seconds!</p>
                    <p>Get a free subdomain for your website!</p>
                </div>
            </div>
        </div>
    </header>
<!-- Your actual content -->
<div id="content">
    <div class="container">
        <div class="card mt-4">
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Set Your Details</h2>
                <p class="card-text text-center">Set your nameserver, IP, etc for your domain name.</p>

               <?php
                $apiKey = 'v1.0-fca7a7a447aaf562c0c79289-959904375afd931c6f5ec8dba860b34c81f080357aa1a7517e2e5af5e7b6df458ad7e36c47de966e3b2c2e3266977a148670337fa0896fa43c0a954fdcdd68bdf6189b0c2340270e36';
                $zoneId = '4b16fd630b65102537ebde875f4be31d';

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
                $username = "oujhymth_regist"; // Change this to your MySQL username
                $password = "pQVpmkhTor"; // Change this to your MySQL password
                $dbname = "oujhymth_regist";
            
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
        <div class="mb-3">
                <input type="hidden" name="subdomain" id="subdomain" class="form-control" placeholder="e.g., mysubdomain" required value="'. $_GET["domainname"] .'">
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
        <p class="text-center">Design by <a href="https://superdev.one/">superdev.one</a></p>
    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
</body>

</html>

<?php
function generateDomainCard($extension, $description, $domainName)
{
    if (!$domainName) {
        return "Domain Name Not Provided";
    }

    $availability = isDomainAvailable($domainName, $extension);

    $textOrButton = $availability ? "<a href='#' class='btn btn-success w-100'>Get it now!</a>" : "<b class='text-danger-emphasis'>Not Available</b>";

    return <<<HTML
<h5 class="card-title">
    $domainName.$extension
</h5>
<p class="card-text fs-5">
    <strong class="text-success-emphasis">FREE</strong>
</p>
<p class="card-text">$description</p>
$textOrButton
HTML;
}

// Function to check if a subdomain is available
function isDomainAvailable($subdomain, $extension) {
    // Database connection settings
    $servername = "localhost"; // Change this to your MySQL server hostname
    $username = "oujhymth_regist"; // Change this to your MySQL username
    $password = "pQVpmkhTor"; // Change this to your MySQL password
    $dbname = "oujhymth_regist";

    // Create a database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Combine the subdomain and extension to form the full domain name
    $fullDomain = $subdomain . '.' . $extension;

    // Prepare a SQL query to check if the full domain name exists in the database
    $query = "SELECT COUNT(*) FROM subdomain_records WHERE subdomain = ? AND domain = ?";

    // Use prepared statements to avoid SQL injection
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $subdomain, $extension);

    // Execute the query
    $stmt->execute();
    $stmt->bind_result($count);

    // Fetch the result
    $stmt->fetch();

    // Close the statement
    $stmt->close();

    // Close the database connection
    $conn->close();

    // If the count is 0, the domain is available; otherwise, it's not
    return ($count === 0);
}
?>
