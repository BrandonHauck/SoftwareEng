<?php 
session_start(); // Starts a session and creates a session variable

// File containing usernames and passwords
$jsonFile = __DIR__ . '/../JSON/data.json';

// Function to authenticate user
function authenticateUser($username, $password, $usersArray) 
{
    foreach ($usersArray['users'] as $user) 
    {
        if ($user['username'] === $username && $user['password'] === $password) {
            return true;
        }
    }
    return false;
}

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $username = $_POST['username'];  // Retrieves the username from the POST request
    $password = $_POST['password'];  // Retrieves the password from the POST request

    // Check if the JSON file exists and is readable
    if (file_exists($jsonFile) && is_readable($jsonFile)) 
    {
        // Read the contents of the JSON file
        $contents = file_get_contents($jsonFile);
        // Decode the JSON data into a PHP array
        $usersArray = json_decode($contents, true);

        // Check if decoding was successful and authenticate user
        if ($usersArray !== null && isset($usersArray['users']) && authenticateUser($username, $password, $usersArray)) 
        {
            // Stores the username in a session variable
            $_SESSION['username'] = $username;
            // Stores the password in a session variable (Note: storing passwords in plain text is not secure, **DEAL WITH THIS LATER**)
            $_SESSION['password'] = $password;

            // Redirect to the members-only page
            header('Location: member.php');
            exit();
        } 
        else 
        {
            // Outputs an error message if username or password is incorrect
            echo '<p>Failed to confirm credentials, invalid username or password. <a href="../login.html">Try again</a></p>';
        }
    } 
    else 
    {
        echo '<p>Unable to read user data. Please try again later.</p>';
    }
} 
// Check if the user is already logged in
elseif (isset($_SESSION['username']) && isset($_SESSION['password'])) 
{
    // Check if the JSON file exists and is readable
    if (file_exists($jsonFile) && is_readable($jsonFile)) 
    {
        // Read the contents of the JSON file
        $contents = file_get_contents($jsonFile);
        // Decode the JSON data into a PHP array
        $usersArray = json_decode($contents, true);

        // Check if decoding was successful and authenticate user
        if ($usersArray !== null && isset($usersArray['users']) && authenticateUser($_SESSION['username'], $_SESSION['password'], $usersArray)) 
        {
            // Outputs an access message
            echo '<p>You\'re already logged into the Elevator Project!.</p>';
            // Provides a link to the members-only page
            echo '<p>Click <a href="member.php">here</a> to be taken to our super secret members only page</p>';
        } 
        else 
        {
            header('Location: ../login.html');
            exit();
        }
    } 
    else 
    {
        echo '<p>Unable to read user data. Please try again later.</p>';
    }
}
// Redirect to login page if accessed directly
else {
    header('Location: ../login.html');
    exit();
}
?>
