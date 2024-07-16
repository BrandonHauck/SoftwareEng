<?php
function requestFloor(int $node_ID, int $new_floor = 1): int {
    $db1 = new PDO('mysql:host=192.168.1.200:3306;dbname=elevator', 'ese', 'ese');   
    //$db1 = new PDO('mysql:host=127.0.0.1;dbname=elevator', 'brandon', 'Cc7766488');
    $query = 'UPDATE elevatorNetwork 
            SET requestedFloor = :floor
            WHERE nodeID = :id';
    $statement = $db1->prepare($query);
    $statement->bindValue('floor', $new_floor);
    $statement->bindValue('id', $node_ID);
    $statement->execute();  
    
    return $new_floor;
}
?>
<?php 
function get_currentFloor(): int {
    try { 
        $db = new PDO('mysql:host=192.168.1.200:3306;dbname=elevator', 'ese', 'ese');       
        //$db = new PDO('mysql:host=127.0.0.1;dbname=elevator', 'brandon', 'Cc7766488');
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

    // Query the database to display current floor
    $query = 'SELECT currentFloor FROM elevatorNetwork WHERE nodeID = 1';
    $statement = $db->query($query);
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    return $result['currentFloor'];

}
function get_requestedFloor(): int {
    try { 
        $db = new PDO('mysql:host=192.168.1.200:3306;dbname=elevator', 'ese', 'ese');       
        //$db = new PDO('mysql:host=127.0.0.1;dbname=elevator', 'brandon', 'Cc7766488');
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

    // Query the database to display requested floor
    $query = 'SELECT requestedFloor FROM elevatorNetwork WHERE nodeID = 1';
    $statement = $db->query($query);
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    return $result['requestedFloor'];
}

?>

<html>
    <h1>ESE Project VI Elevator Button Implementation</h1> 
    
    <?php 
        if(isset($_POST['newfloor'])) {
            $newFlr = requestFloor(1, $_POST['newfloor']); 
            header('Refresh:0; url=Buttons.php');    
        } 

        $curFlr = get_currentFloor();
        $reqFlr = get_requestedFloor();

        echo "<h2>Current floor # $curFlr </h2>";
        echo "<h2>Requested floor # $reqFlr </h2>";
        
        if ($curFlr != $reqFlr) {
            header('Refresh:1; url=Buttons.php');
        }           
    ?>        
    
    <h2>   
        <form action="Buttons.php" method="POST">
            <button type="submit" name="newfloor" value="1" style="width:50px; height:40px">1</button>
            <button type="submit" name="newfloor" value="2" style="width:50px; height:40px">2</button>
            <button type="submit" name="newfloor" value="3" style="width:50px; height:40px">3</button>
        </form>
    </h2>
</html>
