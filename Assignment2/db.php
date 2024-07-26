<?php
// Database connection function
function connectDatabase($path, $user, $password) 
{
    try 
    {
        $db = new PDO($path, $user, $password);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db; 
    } 
    catch (PDOException $e) 
    {
        throw new Exception('Failed to connect to database: ' . $e->getMessage());
    }
}

// Function to display content from authorizedUsers
function showContent($db) 
{
    try 
    {
        $query = "SELECT * FROM authorizedUsers";
        $rows = $db->query($query);
        echo "<h2>Authorized Users</h2>";
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Username</th><th>Password</th><th>Actions</th></tr>";
        foreach ($rows as $row) 
        {
            echo "<tr>";
            foreach ($row as $key => $column) 
            {
                echo "<td>" . htmlspecialchars($column) . "</td>";
            }
            echo "<td>
                    <form method='post' style='display:inline;'>
                        <input type='hidden' name='username' value='" . htmlspecialchars($row['username']) . "'>
                        <input type='hidden' name='action' value='edit'>
                        <input type='submit' name='editUser' value='Edit'>
                    </form>
                    <form method='post' style='display:inline;'>
                        <input type='hidden' name='username' value='" . htmlspecialchars($row['username']) . "'>
                        <input type='hidden' name='action' value='delete'>
                        <input type='submit' name='deleteUser' value='Delete'>
                    </form>
                </td>";
            echo "</tr>";
        }
        echo "</table>";
    } 
    catch (Exception $e) 
    {
        echo 'Failed to display content: ' . $e->getMessage();
    }
}

// Function to insert new user into authorizedUsers
function insertUser($db, $username, $password) 
{
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    try 
    {
        $query = 'INSERT INTO authorizedUsers (username, password) VALUES (:username, :password)';
        $statement = $db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':password', $hashedPassword);
        $statement->execute();
    } 
    catch (Exception $e) 
    {
        throw new Exception('Failed to insert user: ' . $e->getMessage());
    }
}

// Function to update user password in authorizedUsers
function updateUserPassword($db, $username, $newPassword) 
{
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    try 
    {
        $query = 'UPDATE authorizedUsers SET password = :password WHERE username = :username';
        $statement = $db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':password', $hashedPassword);
        $statement->execute();
    } 
    catch (Exception $e) 
    {
        throw new Exception('Failed to update user password: ' . $e->getMessage());
    }
}

// Function to delete user from authorizedUsers
function deleteUser($db, $username) 
{
    try 
    {
        $query = 'DELETE FROM authorizedUsers WHERE username = :username';
        $statement = $db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->execute();
    } 
    catch (Exception $e) 
    {
        throw new Exception('Failed to delete user: ' . $e->getMessage());
    }
}
?>