<?php 
session_start(); // Starts a session

// Retrieve form data
$username = $_POST['username'];
$password = $_POST['password'];
$authenticated = FALSE;

try {
    // Create a new PDO connection
    $db = new PDO('mysql:host=127.0.0.1;dbname=elevator', 'brandon', 'Cc7766488');
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Authenticate against the database
    $query = $db->prepare("SELECT * FROM authorizedUsers WHERE username = :username");
    $query->bindParam(':username', $username);
    $query->execute();

    $row = $query->fetch();

    if ($row && password_verify($password,$row['password'])) {
        $authenticated = TRUE;
        $_SESSION['authenticated'] = true;
        $_SESSION['username'] = $username;
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

if ($authenticated) {
    $_SESSION['username'] = $username;
    echo "<p>Congrats " . $username .", you are logged in with password: ". $password .  "!</p>";
    echo "<p>Click <a href='member.php'>here</a> to go to the members-only page</p>";
} else {
    $_SESSION['authenticated'] = false;    
    header('Location: ../login.html');
    exit();
}
?>
