<?php
    class onlineStore{
        private $DBConnect = NULL;
        private $DBName = "";
        private $storeID = "";
        private $inventory = array();
        private $shoppingCart = array();

        function __construct(){
            include("inc_onlinestoresDB.php");
            $this->DBConnect = $DBConnect;
            $this->DBName = $DBName;
        }

        function __destruct(){
            // echo "<p>Closing Database" . "<em>$this->DBName</em>.</p>\n";
            echo "<p>Closing Database <em>$this->DBName</em>.</p>\n";
            $this->DBConnect->close();
        }

        function __wakeup(){
            include("inc_onlinestoresDB.php");
            $this->DBConnect = $DBConnect;
            $this->DBName = $DBName;
        }

        public function setStoreID($storeID){
            if($this->storeID != $storeID){
                $this->storeID = $storeID;
                $TableName = "inventory";
                $SQLString = "SELECT * FROM $TableName" . " WHERE storeid='" . $this->storeID . "'";
                $QueryResult = $this->DBConnect->query($SQLString);
                if (!$QueryResult) {
                    $errorMsgs[] = "<p>Unable to execute the query.<br>" . "Error Code" . 
                    $this->DBConnect->errno . ": " . 
                    $this->DBConnect->error . "</p>\n";
                    $this->storeID = "";
                } else {
                    $inventory = array();
                    $shoppingCart = array();
                    while (($row = $QueryResult->fetch_assoc()) != NULL) {
                        $this->inventory[$row['productID']] = array();
                        $this->inventory[$row['productID']]['name'] = $row['name'];
                        $this->inventory[$row['productID']]['description'] = $row['description'];
                        $this->inventory[$row['productID']]['price'] = $row['price'];
                        $this->shoppingCart[$row['productID']]['name'] = 0;
                    }
                    echo "<pre>\n";
                    print_r($this->inventory);
                    print_r($this->shoppingCart);
                    echo "</pre>\n"; 
                }
            }
        }
    }
?>