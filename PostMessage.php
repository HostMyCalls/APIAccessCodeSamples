<?php 
require __DIR__ . '/vendor/autoload.php';
use Jumbojett\OpenIDConnectClient;

$user = 'userName';
$password = 'password';
$from = '+17708002476';
$to = '+10000000000';
$message = 'Test message from PHP page';

$oidc = new OpenIDConnectClient('https://hmcopenidserver.azurewebsites.net', $user,$password );

$oidc->providerConfigParam(array('token_endpoint'=>'https://hmcopenidserver.azurewebsites.net/connect/token')); 
$oidc->addScope('api1'); // name of api -> change it according to the API accessed

$clientCredentialsToken = $oidc->requestClientCredentialsToken()->access_token;

$url = 'http://localhost:5001/Messages';
$authorization = "Authorization: Bearer "; 
$authorization .= $clientCredentialsToken;
$ch = curl_init($url);

$jsonData = array( 'From' => $from, 'To' => $to, 'Message' => $message  );
$jsonDataEncoded = json_encode($jsonData);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',$authorization));

$result = curl_exec($ch);
?>