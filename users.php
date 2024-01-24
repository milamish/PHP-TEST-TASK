<?php
/**
10. Using PHP, make a GET request to a sample REST API (e.g., JSONPlaceholder) to retrieve a list
of users. Parse the JSON response and display the user's name and email address.
*/

// API endpoint for users
$Endpoint = 'https://jsonplaceholder.typicode.com/users';

// Get request to the API
$response = file_get_contents($Endpoint);

// Check if request is succesfull
if ($response === false) {
    die('Error: Unable to fetch data from the API.');
}

// JSON response
$users = json_decode($response, true);

// Check if JSON decoding is successful
if ($users === null) {
    die('Error: Unable to decode JSON response.');
}

// Display user information
echo "List of Users:\n";
foreach ($users as $user) {
    echo "User ID: {$user['id']}\n";
    echo "Name: {$user['name']}\n";
    echo "Email: {$user['email']}\n";
    echo "------------------------\n";
}

?>

