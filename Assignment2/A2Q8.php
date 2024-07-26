<?php 
$host = '127.0.0.1'; 
$database = 'elevator'; 
$tablename = 'elevatorNetwork'; 
$path = 'mysql:host=' . $host . ';dbname=' . $database; 
$user = 'brandon'; 
$password = 'Cc7766488';

// This function connects the webpage to the elevatorNetwork database using the appropriate db credentials
function connect(string $path, string $user, string $password) 
{
    try     //Create a PHP Data Objects instance with the appropriate Data Source Name, username, and password to connect to the database
    {
        $db = new PDO($path, $user, $password);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db; 
    } 
    catch (PDOException $e) 
    {
        throw new Exception('Failed to connect to elevatorNetwork: ' . $e->getMessage());
    }
}

// This function updates the selected fields in the elevatorNetwork by the provided nodeID given the user input on our webpage
function updateField(string $path, string $user, string $password, string $tablename, string $field, string $new_value, string $node_ID) : void 
{   
    //Connect to the database
    $db = connect($path, $user, $password);
    
    try
    {
    // Begin the transaction
    $db->beginTransaction();

    //query for current time
    $queryTime = "SELECT CURRENT_TIME()";
    $result = $db->query($queryTime);
    $curtime = $result->fetch()['CURRENT_TIME()'];

    //query for current date
    $queryDate = "SELECT CURRENT_DATE()";
    $result = $db->query($queryDate);
    $curdate = $result->fetch()['CURRENT_DATE()'];

    // Update fields statement with timestamps: UPDATE elevatorNetwork SET *field* = *new value* , date = today, time = rn WHERE nodeID = *int*;
    $query = 'UPDATE ' . $tablename . ' SET ' . $field . ' = :new_value, date = :curdate, time = :curtime WHERE nodeID = :node_ID';
    $statement = $db->prepare($query);                  //prepare the update query
    $statement->bindValue(':new_value', $new_value);
    $statement->bindValue(':curdate', $curdate);
    $statement->bindValue(':curtime', $curtime);
    $statement->bindValue(':node_ID', $node_ID);
    $statement->execute();                              //execute the query given the binded values

    // Commit the transaction   (Send all the updated fields at once)
    $db->commit();
    }
    catch (Exception $e) 
    {
        // Rollback the transaction if something failed (Revert the database back to its prior state)
        $db->rollBack();
        throw new Exception('Transaction failed: ' . $e->getMessage());
    }    
}

//This function displays the most recent entries on the elevatorNetwork table to the webpage 
function showTable(string $path, string $user, string $password, string $tablename) 
{
    $db = connect($path, $user, $password); 
    $query = "SELECT * FROM $tablename"; 
    $rows = $db->query($query); 
    foreach ($rows as $row) 
    {
        var_dump($row);
        echo "<br><br>";
    }
}

// HTML Form with numeric min and max values implemented for NodeID and new values (text for other info disabled)
echo '
    <title>A2Q8 Update Fields</title>
    <h1>This page utilizes the following form to test the functionality of updating any field within our database using transactions</h1>
    <form method="post">
    <label for="field">Field to Update:</label>
    <select name="field" id="field">
        <option value="status">Status</option>
        <option value="currentFloor">Current Floor</option>
        <option value="requestedFloor">Requested Floor</option>
        <option value="otherInfo">Other Info</option>
    </select><br><br>
    
    <label for="new_value">New Value:</label>
    <input type="number" name="new_value" id="new_value" max="3" min="1" required><br><br>
    
    <label for="node_ID">Node ID:</label>
    <input type="number" name="node_ID" id="node_ID" max="3" min="1" required><br><br>
    
    <input type="submit" name="submit" value="Update">
</form>';

// Handle form submission using a POST array
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $field = $_POST['field'];
    $new_value = $_POST['new_value'];
    $node_ID = $_POST['node_ID'];
    
    try //attempt to update the selected fields given the inputs retrieved from the POST array
    {
        //ensure all fields hold value
        if (empty($field) || empty($new_value) || empty($node_ID)) 
        {
            throw new Exception("All fields are required.");
        }
        updateField($path, $user, $password, $tablename, $field, $new_value, $node_ID);
        echo "Update successful!<br><br>";
    } 
    catch (Exception $e) 
    {
        echo 'Update failed: ' . $e->getMessage();
    }
}

// Display the updated table
try 
{
    showTable($path, $user, $password, $tablename); 
} 
catch (Exception $e) 
{
    echo 'Failed to display table: ' . $e->getMessage();
}
?>