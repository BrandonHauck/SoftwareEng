<?php
session_start(); // Start the session

// File containing usernames and passwords
$jsonFile = __DIR__ . '/../JSON/data.json';

// Function to load JSON data
function loadJSONData($file) 
{
    if (file_exists($file) && is_readable($file)) 
    {
        $contents = file_get_contents($file);
        $data = json_decode($contents, true);
        if ($data !== null) 
        {
            return $data;
        } 
        else 
        {
            throw new Exception("Error decoding JSON data.");
        }
    } 
    else 
    {
        throw new Exception("Error reading JSON file.");
    }
}

// Function to save JSON data
function saveJSONData($file, $data) 
{
    if (is_writable($file)) 
    {
        $jsonString = json_encode($data, JSON_PRETTY_PRINT);
        if (file_put_contents($file, $jsonString) === false) 
        {
            throw new Exception("Error writing to JSON file.");
        }
    } 
    else 
    {
        throw new Exception("JSON file is not writable.");
    }
}

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $username = $_POST['username'];
    $password = $_POST['password'];
    try 
    {
        // Load existing data
        $data = loadJSONData($jsonFile);

        // Check if username already exists
        foreach ($data['users'] as $user) 
        {
            if ($user['username'] === $username) 
            {
                throw new Exception("Username already exists. Please choose a different username.");
            }
        }

        // Add new user
        $newUser = 
        [
            "username" => $username,
            "password" => $password,
        ];
        $data['users'][] = $newUser;

        // Save updated data
        saveJSONData($jsonFile, $data);

        // Redirect to a success page or display a success message
        echo "<p>Registration successful! <a href='../login.html'>Go to login</a></p>";
    } 
    catch (Exception $e) 
    {
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
} 
else 
{
    header('Location: ../requestaccess.html');
    exit();
}
?>
