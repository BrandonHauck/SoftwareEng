<?php
try {
    // Establishing the database connection
    $db = new PDO('mysql:host=127.0.0.1;dbname=elevator', 'brandon', 'Cc7766488');
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Static queries for date and time
    $queryTime = "SELECT CURRENT_TIME()";
    $result = $db->query($queryTime);
    $curtime = $result->fetch()['CURRENT_TIME()'];
    var_dump($curtime);

    $queryDate = "SELECT CURRENT_DATE()";
    $result = $db->query($queryDate);
    $curdate = $result->fetch()['CURRENT_DATE()'];
    var_dump($curdate);

    // Prepared statement
    $query = "INSERT INTO elevatorNetwork (date, time, status, currentFloor, requestedFloor, otherInfo) VALUES (:date, :time, :status, :currentFloor, :requestedFloor, :otherInfo)";
    $statement = $db->prepare($query);

    $params = [
        ':date' => $curdate,
        ':time' => $curtime,
        ':status' => 1,
        ':currentFloor' => 1,
        ':requestedFloor' => 2,
        ':otherInfo' => 'na'
    ];

    $result = $statement->execute($params);
    var_dump($result);

    // Query to display the entire database
    $rows = $db->query('SELECT * FROM elevatorNetwork ORDER BY nodeID');
    foreach ($rows as $row) {
        var_dump($row);
        echo "<br/><br/>";
    }
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
