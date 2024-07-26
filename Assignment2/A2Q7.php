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
        echo 'Failed to connect to elevatorNetwork: ' . $e->getMessage();
        exit;
    }
}

// This function updates the selected fields in the elevatorNetwork by the provided nodeID given the user input on our webpage
function updateField(string $path, string $user, string $password, string $tablename, string $field, string $new_value, string $node_ID) : void 
{   
    //Connect to the database
    $db = connect($path, $user, $password);
    
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

// HTML Form
echo '
    <title>A2Q7 Update Fields</title>
    <h1>This page utilizes the following form to test the functionality of updating any field within our database</h1>
    <form method="post">
    <label for="field">Field to Update:</label>
    <select name="field" id="field">
        <option value="status">Status</option>
        <option value="currentFloor">Current Floor</option>
        <option value="requestedFloor">Requested Floor</option>
        <option value="otherInfo">Other Info</option>
    </select><br><br>
    
    <label for="new_value">New Value:</label>
    <input type="text" name="new_value" id="new_value" required><br><br>
    
    <label for="node_ID">Node ID:</label>
    <input type="text" name="node_ID" id="node_ID" required><br><br>
    
    <input type="submit" name="submit" value="Update">
</form>';

// Handle form submission using a POST array
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $field = $_POST['field'];
    $new_value = $_POST['new_value'];
    $node_ID= $_POST['node_ID'];
    
    try     //attempt to update the selected fields given the inputs retrieved from the POST array
    {
        updateField($path, $user, $password, $tablename, $field, $new_value, $node_ID);
        echo "Update successful!<br><br>";
    } 
    catch (PDOException $e) 
    {
        echo 'Update failed: ' . $e->getMessage();
    }
}

// Display the updated table
showTable($path, $user, $password, $tablename); 
?>
