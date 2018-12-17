<?php
    require_once("inc_onlinestoresDB.php");
    require_once("class_onlineStore.php");
    if (class_exists("OnlineStore")) {
        $Store = new OnlineStore();
    } else {
        $errorMessage[] = "The <em>OnlineStore</em> class isn't available";
        $Store = NULL;
    }
    
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Gourmet Coffee</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1>Gourmet Coffee</h1>
    <h2>Description goes here</h2>
    <p>Welcome message goes here</p>
    <?php
    $tableName = "inventory";
    if (count($errorMessage) == 0) {
        $SQLString = "SELECT * FROM $tableName " . "WHERE storeID='COFFEE'";
        $QueryResults = $DBConnect->query($SQLString);
        if (!$QueryResults) {
            $errorMessage[] = "<p>Unable to execute the query.<br>" . "Error Code " . $DBConnect->errno . ": " . $DBConnect->error . "</p><br>\n";
        } else{
            echo "<table width='100%'>\n";
            echo "<tr>\n";
            echo "<th>Product</th>";
            echo "<th>Description</th>";
            echo "<th>Price Each</th>";
            echo "</tr>\n";
            while (($row = $QueryResults->fetch_assoc()) != NULL) {
                echo "<tr><td>" . htmlentities($row['name']) . "</td>\n";
                echo "<td>" . htmlentities($row['description']) . "</td>\n";
                printf("<td>$%.2f</td></tr>\n", $row['price']);
            }
            echo "</table>\n";

        }
    }
        if (count($errorMessage) > 0) {
            foreach ($errorMessage as $message) {
                echo "<p>" . $message . "</p><br>\n";
            }
        }
    ?>
</body>
</html>
<?php
    if (!$DBConnect->connect_error) {
        echo "<p>Closing Database <em>$DBName</em>.</p><br>\n";
        $DBConnect->close();
    }
?>