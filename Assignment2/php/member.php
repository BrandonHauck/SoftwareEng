<?php
// member.php
// this page is protected to members only even when accessed directly
session_start();  // Required for every page where you call or declare a session

// File containing usernames and passwords
$jsonFile = __DIR__ . '/../JSON/data.json';

//************************ PHP Function Declaration Begins ******************************
// authenticateUser();
// connectDatabase();
// insertContent();
// updateContent();
// deleteContent();
// showContent();
//***************************************************************************************

// Function to authenticate user
function authenticateUser($username, $password, $usersArray) 
{
    foreach ($usersArray['users'] as $user) 
    {
        if ($user['username'] === $username && $user['password'] === $password) 
        {
            return true;
        }
    }
    return false;
}

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

// Function to insert new content into elevatorNetwork
function insertContent($db, $status, $currentFloor, $requestedFloor, $otherInfo) 
{
    try 
    {   //Utilize Transactions to avoid incomplete data transfer and potential curruption 
        $db->beginTransaction();
        
        // query for current time
        $queryTime = "SELECT CURRENT_TIME()";
        $result = $db->query($queryTime);
        $curtime = $result->fetch()['CURRENT_TIME()'];

        // query for current date
        $queryDate = "SELECT CURRENT_DATE()";
        $result = $db->query($queryDate);
        $curdate = $result->fetch()['CURRENT_DATE()'];

        // Prepared statement with additional date and time fields
        $query = 'INSERT INTO elevatorNetwork (status, currentFloor, requestedFloor, otherInfo, date, time) VALUES (:status, :currentFloor, :requestedFloor, :otherInfo, :curdate, :curtime)';
        $statement = $db->prepare($query);
        // Bind the values for each respective field
        $statement->bindValue(':status', $status);
        $statement->bindValue(':currentFloor', $currentFloor);
        $statement->bindValue(':requestedFloor', $requestedFloor);
        $statement->bindValue(':otherInfo', $otherInfo);
        $statement->bindValue(':curdate', $curdate);
        $statement->bindValue(':curtime', $curtime);
        $statement->execute();

        // Commit the transaction
        $db->commit();
    } 
    catch (Exception $e) 
    {
        // Rollback the transaction if something failed
        $db->rollBack();
        throw new Exception('Transaction failed: ' . $e->getMessage());
    }
}

// Function to update content in elevatorNetwork
function updateContent($db, $nodeID, $status, $currentFloor, $requestedFloor, $otherInfo) 
{
    try 
    {   //Utilize Transactions to avoid incomplete data transfer and potential curruption 
        $db->beginTransaction();
        
        // query for current time
        $queryTime = "SELECT CURRENT_TIME()";
        $result = $db->query($queryTime);
        $curtime = $result->fetch()['CURRENT_TIME()'];

        // query for current date
        $queryDate = "SELECT CURRENT_DATE()";
        $result = $db->query($queryDate);
        $curdate = $result->fetch()['CURRENT_DATE()'];

        // Prepared statement with additional date and time fields
        $query = 'UPDATE elevatorNetwork SET status = :status, currentFloor = :currentFloor, requestedFloor = :requestedFloor, otherInfo = :otherInfo, date = :curdate, time = :curtime WHERE nodeID = :nodeID';
        $statement = $db->prepare($query);
        // Bind the values for each respective field
        $statement->bindValue(':status', $status);
        $statement->bindValue(':currentFloor', $currentFloor);
        $statement->bindValue(':requestedFloor', $requestedFloor);
        $statement->bindValue(':otherInfo', $otherInfo);
        $statement->bindValue(':curdate', $curdate);
        $statement->bindValue(':curtime', $curtime);
        $statement->bindValue(':nodeID', $nodeID);
        $statement->execute();

        // Commit the transaction
        $db->commit();
    } 
    catch (Exception $e) 
    {
        // Rollback the transaction if something failed
        $db->rollBack();
        throw new Exception('Transaction failed: ' . $e->getMessage());
    }
}

// Function to delete content from elevatorNetwork
function deleteContent($db, $nodeID) 
{
    try 
    {   //Utilize Transactions to avoid incomplete data transfer and potential curruption 
        $db->beginTransaction();

        // Prepared statement for deletion
        $query = 'DELETE FROM elevatorNetwork WHERE nodeID = :nodeID';
        $statement = $db->prepare($query);
        // Delete based off selected nodeID
        $statement->bindValue(':nodeID', $nodeID);
        $statement->execute();

        // Commit the transaction
        $db->commit();
    } 
    catch (Exception $e) 
    {
        // Rollback the transaction if something failed
        $db->rollBack();
        throw new Exception('Transaction failed: ' . $e->getMessage());
    }
}

// Function to display content from elevatorNetwork (Reformats Date and Time for display)
function showContent($db) 
{
    try 
    {
        $query = "SELECT * FROM elevatorNetwork";
        $rows = $db->query($query);
        echo "<table border='1'>";
        echo "<tr><th>Date</th><th>Time</th><th>NodeID</th><th>Status</th><th>Current Floor</th><th>Requested Floor</th><th>Other Info</th><th>Actions</th></tr>";
        foreach ($rows as $row) 
        {
            echo "<tr>";
            foreach ($row as $key => $column) 
            {
                if ($key == 'date') 
                {
                    $dateTime = new DateTime($column);
                    $formattedDate = $dateTime->format('M-d-Y'); // Alphanumeric date format
                    echo "<td>" . htmlspecialchars($formattedDate) . "</td>";
                } 
                elseif ($key == 'time') 
                {
                    $dateTime = new DateTime($column);
                    $formattedTime = $dateTime->format('h:i:s A'); // 12-hour time format
                    echo "<td>" . htmlspecialchars($formattedTime) . "</td>";
                } 
                else 
                {
                    echo "<td>" . htmlspecialchars($column) . "</td>";
                }
            }
            echo "<td>
                    <form method='post' style='display:inline;'>
                        <input type='hidden' name='nodeID' value='" . $row['nodeID'] . "'>
                        <input type='submit' name='edit' value='Edit'>
                    </form>
                    <form method='post' style='display:inline;'>
                        <input type='hidden' name='nodeID' value='" . $row['nodeID'] . "'>
                        <input type='submit' name='delete' value='Delete'>
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

//************************ PHP Code Begins ******************************

// Make sure users that are not logged in do not have access to this page
if (isset($_SESSION['username']) && isset($_SESSION['password'])) 
{
    // Check if the JSON file exists and is readable
    if (file_exists($jsonFile) && is_readable($jsonFile)) 
    {
        // Read the contents of the JSON file
        $contents = file_get_contents($jsonFile);
        // Decode the JSON data into a PHP array
        $usersArray = json_decode($contents, true);

        // Check if json decoding was successful and authenticate user
        if ($usersArray !== null && isset($usersArray['users']) && authenticateUser($_SESSION['username'], $_SESSION['password'], $usersArray)) 
        {
            // Add 'members only' content here
            echo "<p>Greetings, " . $_SESSION['username'] . "! You've successfully infiltrated our secret lair.</p>";
            echo "<p>As a trusted member, you now have access to our top-secret plans for world domination. Shh... don't tell anyone!</p>";
            echo "<p>Remember, with great power comes great responsibility. Use your database privileges wisely.</p>";
            echo "<p>Click to <a href='logout.php'>Logout</a> and disappear without a trace when your mission is complete.</p>";

            // Database credentials
            $dbPath = 'mysql:host=127.0.0.1;dbname=elevator';
            $dbUser = 'brandon';
            $dbPassword = 'Cc7766488';
            $dbName = 'elevator';

            // Connect to the database
            $db = connectDatabase($dbPath, $dbUser, $dbPassword);

            // If the user submits an insert table handle new content insertion
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['insert'])) 
            {
                $status = $_POST['status'];
                $currentFloor = $_POST['currentFloor'];
                $requestedFloor = $_POST['requestedFloor'];
                $otherInfo = $_POST['otherInfo'];

                try 
                {
                    insertContent($db, $status, $currentFloor, $requestedFloor, $otherInfo);
                    echo "<p>New content inserted successfully!</p>";
                } 
                catch (Exception $e) 
                {
                    echo '<p>Failed to insert content: ' . $e->getMessage() . '</p>';
                }
            }

            // If the user submits a delete request handle content deletion
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) 
            {
                $nodeID = $_POST['nodeID'];

                try 
                {
                    deleteContent($db, $nodeID);
                    echo "<p>Content deleted successfully!</p>";
                } 
                catch (Exception $e) 
                {
                    echo '<p>Failed to delete content: ' . $e->getMessage() . '</p>';
                }
            }

            // Display the content from elevatorNetwork
            echo "<h2>Elevator Network Data</h2>";
            showContent($db);

            // Form to insert new content
            echo '
                <h2>Insert New Content</h2>
                <form method="post">
                    <label for="status">Status:</label>
                    <input type="number" name="status" id="status" min="1" max="3" required><br><br>
                    
                    <label for="currentFloor">Current Floor:</label>
                    <input type="number" name="currentFloor" id="currentFloor" min="1" max="3" required><br><br>
                    
                    <label for="requestedFloor">Requested Floor:</label>
                    <input type="number" name="requestedFloor" id="requestedFloor" min="1" max="3" required><br><br>
                    
                    <label for="otherInfo">Other Info:</label>
                    <input type="text" name="otherInfo" id="otherInfo" required><br><br>
                    
                    <input type="submit" name="insert" value="Insert">
                </form>';

            // If the user selects the "edit" option select which row to edit based off nodeID
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) 
            {
                $nodeID = $_POST['nodeID'];

                // Fetch the existing nodeID record to pre-fill the form
                $query = 'SELECT * FROM elevatorNetwork WHERE nodeID = :nodeID';
                $statement = $db->prepare($query);
                //Pre-fill by nodeID data
                $statement->bindValue(':nodeID', $nodeID);
                $statement->execute();
                //Fetch a record of the nodeID data and store it into an array
                $record = $statement->fetch();

                //If the entry exists display a form to edit its contents (pre-filled)
                if ($record) 
                {
                    echo '
                        <h2>Edit Content</h2>
                        <form method="post">
                            <input type="hidden" name="nodeID" value="' . $record['nodeID'] . '">
                            <label for="status">Status:</label>
                            <input type="number" name="status" id="status" min="1" max="3" value="' . htmlspecialchars($record['status']) . '" required><br><br>
                            
                            <label for="currentFloor">Current Floor:</label>
                            <input type="number" name="currentFloor" id="currentFloor" min="1" max="3" value="' . htmlspecialchars($record['currentFloor']) . '" required><br><br>
                            
                            <label for="requestedFloor">Requested Floor:</label>
                            <input type="number" name="requestedFloor" id="requestedFloor" min="1" max="3" value="' . htmlspecialchars($record['requestedFloor']) . '" required><br><br>
                            
                            <label for="otherInfo">Other Info:</label>
                            <input type="text" name="otherInfo" id="otherInfo" value="' . htmlspecialchars($record['otherInfo']) . '" required><br><br>
                            
                            <input type="submit" name="update" value="Update">
                        </form>';
                } 
                else //No data retrieved from provided nodeID unable to pre-fill
                {
                    echo '<p>Record not found, no data exists within database for provided nodeID. It is recommended to add your desired entry.</p>';
                }
            }
            
            // If the user chooses to update the edit table handle content update
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) 
            {
                $nodeID = $_POST['nodeID'];
                $status = $_POST['status'];
                $currentFloor = $_POST['currentFloor'];
                $requestedFloor = $_POST['requestedFloor'];
                $otherInfo = $_POST['otherInfo'];

                try 
                {
                    updateContent($db, $nodeID, $status, $currentFloor, $requestedFloor, $otherInfo);
                    echo "<p>Content updated successfully!</p>";
                } 
                catch (Exception $e) 
                {
                    echo '<p>Failed to update content: ' . $e->getMessage() . '</p>';
                }
            }
        } 
        else // JSON decoding was unsuccessful, credentials check failed. 
        {
            echo "<p>Failed to decode user credentials! Please try again.</p>";
            echo "<p>Confirm your credentials <a href=\"../login.html\">Here</a></p>"; 
        }
    } 
    else // Unable to access JSON File 
    {
        echo '<p>Unable access user data. Please try again later.</p>';
    }
} 
else // Someone tried accessing member.php through the back door
{
    echo "<p>You must be logged in to access this classified information!</p>";
    echo "<p>Confirm your credentials <a href=\"../login.html\">Here</a></p>";
    echo "<p>Or else! >:(</p>";     
}
?>
