<?php
/**
14. Write a PHP script that integrates with a REST API protected by OAuth 2.0 authentication.
Implement the OAuth 2.0 authorization code flow to obtain an access token and use that
token to make authenticated requests to the API. Provide a code example that demonstrates
the complete authentication and data retrieval process.*/

require_once 'vendor/autoload.php';

use GuzzleHttp\Client;
use League\OAuth2\Client\Provider\GenericProvider;

/**
Assumption is that we are intergrating to github
*/

// GitHub OAuth App credentials and API details
$client_id = 'github_client_id';
$client_secret = 'github_client_secret';
$redirect_uri = 'redirect_uri';
$api_url = 'https://api.github.com';

// Guzzle HTTP client
$httpClient = new Client();

// OAuth 2.0 provider
$provider = new GenericProvider([
    'clientId'                => $client_id,
    'clientSecret'            => $client_secret,
    'redirectUri'             => $redirect_uri,
    'urlAuthorize'            => 'https://github.com/login/oauth/authorize',
    'urlAccessToken'          => 'https://github.com/login/oauth/access_token',
    'urlResourceOwnerDetails' => 'https://api.github.com/user',
]);

// Step 1: Redirect to the GitHub authorization endpoint to obtain an authorization code
if (!isset($_GET['code'])) {
    $authorizationUrl = $provider->getAuthorizationUrl();
    header('Location: ' . $authorizationUrl);
    exit;
}

// Step 2: Exchange authorization code for an access token
$accessToken = $provider->getAccessToken('authorization_code', [
    'code' => $_GET['code'],
]);

// Step 3: Use the access token to make authenticated requests to the GitHub API
$apiEndpoint = $api_url . '/user';
try {
    // Make authenticated request to GitHub API using Guzzle
    $response = $httpClient->get($apiEndpoint, [
        'headers' => [
            'Authorization' => 'Bearer ' . $accessToken->getToken(),
            'User-Agent' => 'YourAppName',
        ],
    ]);

    $data = json_decode($response->getBody(), true);

    // response from the GitHub API
    print_r($data);
    } 
catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}

?>
