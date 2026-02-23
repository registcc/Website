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
                <h2 class="card-title text-center mb-4">Choose Your Free Domain</h2>
                <p class="card-text text-center">Select from our free domain extensions and get started with
                    your online presence today. <b> Note that .rgst.site registrations are coming soon.</b></p>

                <div class="row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <div class="card bg-light text-dark mb-4">
                            <div class="card-body">
                                <?php
                                $domainName = (isset($_GET['domainname'])) ? htmlspecialchars($_GET['domainname']) : null;
                                echo generateDomainCard("regist.cc", "Ideal for personal websites, blogs, or small businesses.", $domainName);
                                ?>
                            </div>
                        </div>
                    </div>
                    <!--<div class="col-sm-6 mb-3 mb-sm-0">
                        <div class="card bg-light text-dark mb-4">
                            <div class="card-body">
                                <?php
                                $domainName = (isset($_GET['domainname'])) ? htmlspecialchars($_GET['domainname']) : null;
                                echo generateDomainCard("rgst.site (coming soon)", "Perfect for your projects, creative endeavors, or experimental websites.", $domainName);
                                ?>
                            </div>
                        </div>
                    </div>-->
                </div>
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

    $textOrButton = $availability ? "<a href='/setyourdetails?domainname=" . $_GET['domainname'] . "' class='btn btn-success w-100'>Get it now!</a>" : "<b class='text-danger-emphasis'>Not Available</b>";

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
