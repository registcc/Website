<?php
// Auth0 credentials
$clientId = 'your-client-id';
$clientSecret = 'your-client-secret';
$auth0Domain = 'your-auth0-domain.auth0.com';

// Redirect URL after login
$redirectUri = 'https://your-app.com/callback.php'; // Your callback URL

// Check if the user is already authenticated
if (!isset($_SESSION['access_token'])) {
    // User not authenticated, redirect to Auth0 login page
    header('Location: https://' . $auth0Domain . '/authorize' . '?client_id=' . $clientId . '&redirect_uri=' . urlencode($redirectUri) . '&response_type=code&scope=openid profile email&state=STATE');
    exit;
}

// User is authenticated, you can access user information using the access token
$accessToken = $_SESSION['access_token'];

// Make an API call to get user information
$userInfoUrl = 'https://' . $auth0Domain . '/userinfo';
$ch = curl_init($userInfoUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $accessToken,
]);
$userInfoResponse = curl_exec($ch);
curl_close($ch);

$userInfo = json_decode($userInfoResponse, true);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your App</title>
</head>
<body>
    <h1>Welcome, <?php echo $userInfo['name']; ?></h1>
    <p>Email: <?php echo $userInfo['email']; ?></p>
    <a href="logout.php">Logout</a> <!-- Create a logout page to clear the session -->
</body>
</html>
