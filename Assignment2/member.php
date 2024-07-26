<?php
session_start();  // Required for every page where you call or declare a session

// Include the functions file
require_once 'db.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Authorized Users Database</title>
</head>
</html>

<?php 
// Make sure users that are not logged in do not have access to this page
if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) 
{
    // Database credentials
    $dbPath = 'mysql:host=127.0.0.1;dbname=elevator';
    $dbUser = 'brandon';
    $dbPassword = 'Cc7766488';

    // Connect to the database
    $db = connectDatabase($dbPath, $dbUser, $dbPassword);

    // Add 'members only' content here
    echo "<p>Greetings, " . $_SESSION['username'] . "! You've successfully infiltrated our secret database.</p>";
    echo "<p>As a trusted member, you now have access to our top-secret plans for world domination. Shh... don't tell anyone!</p>";
    echo "<p>Remember, with great power comes great responsibility. Use your database privileges wisely.</p>";
    echo "<p>Click to <a href='logout.php'>Logout</a> and disappear without a trace when your mission is complete.</p>";

    // Handle new user insertion
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['insert'])) 
    {
        $newUsername = $_POST['newUsername'];
        $newPassword = $_POST['newPassword'];

        try 
        {
            insertUser($db, $newUsername, $newPassword);
            echo "<p>New user inserted successfully!</p>";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } 
        catch (Exception $e) 
        {
            echo '<p>Failed to insert user: ' . $e->getMessage() . '</p>';
        }
    }

    // Handle user password update
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editUser'])) 
    {
        $username = $_POST['username'];
        echo '
            <h2>Update User Password</h2>
            <form method="post">
                <input type="hidden" name="username" value="' . htmlspecialchars($username) . '">
                <label for="newPassword">New Password:</label>
                <input type="password" name="newPassword" id="newPassword" required><br><br>
                <input type="submit" name="update" value="Update">
            </form>';
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) 
    {
        $username = $_POST['username'];
        $newPassword = $_POST['newPassword'];

        try 
        {
            updateUserPassword($db, $username, $newPassword);
            echo "<p>User password updated successfully!</p>";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } 
        catch (Exception $e) 
        {
            echo '<p>Failed to update user password: ' . $e->getMessage() . '</p>';
        }
    }

    // Handle user deletion
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteUser'])) 
    {
        $username = $_POST['username'];

        try 
        {
            deleteUser($db, $username);
            echo "<p>User deleted successfully!</p>";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } 
        catch (Exception $e) 
        {
            echo '<p>Failed to delete user: ' . $e->getMessage() . '</p>';
        }
    }

    // Display the content from authorizedUsers
    showContent($db);

    // Form to insert new user
    echo '
        <h2>Insert New User</h2>
        <form method="post">
            <label for="newUsername">New Username:</label>
            <input type="text" name="newUsername" id="newUsername" required><br><br>
            
            <label for="newPassword">New Password:</label>
            <input type="password" name="newPassword" id="newPassword" required><br><br>
            
            <input type="submit" name="insert" value="Insert">
        </form>';

} 
else 
{
    echo "<p>You must be logged in to access this classified information!</p>";
    echo "<p>Confirm your credentials <a href=\"../login.html\">Here</a></p>";
    echo "<p>Or else! >:(</p>";     
}
?>
