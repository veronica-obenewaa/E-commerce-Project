<?php
$client_id = 'engGtFqRmiwcSxN64azvA';
$client_secret = 'dna3AQRTc2nqVzb6CtF20uwkkwqV08hF';
$account_id = 'ETka8FtcS8KsScZN3EOHJw';

$auth = base64_encode("$client_id:$client_secret");

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => "https://zoom.us/oauth/token?grant_type=account_credentials&account_id=$account_id",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Basic $auth",
        "Content-Type: application/x-www-form-urlencoded"
    ],
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => ""
]);

$response = curl_exec($ch);
curl_close($ch);

echo $response;
