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
                }
            }
        }
        public function getStoreInformation() {
            $retval = false;
            if ($this->storeID != "") {
                $TableName = "storeinfo";
                $SQLString = "SELECT * FROM $TableName " . "WHERE storeID='" . $this->storeID . "'";
                $QueryResult = $this->DBConnect->query($SQLString);
                if ($QueryResult) {
                    $retval = $QueryResult->fetch_assoc();
                }
            }
            return $retval;
        }
        public function getProductList(){
            $retval = false;
            $subTotal = 0;
            if (count($this->inventory) > 0) {
                echo "<table width='100%'>\n";
                echo "<tr>\n";
                echo "<th>Product</th>\n";
                echo "<th>Description</th>\n";
                echo "<th>Price Each</th>\n";
                echo "<th>Total Price</th>\n";
                echo "<th>&nbsp</th>\n";
                echo "</tr>\n";
                foreach($this->inventory as $ID =>$info) {
                    echo "<tr><td>" . htmlentities($info['name']) . "</td>\n";
                    echo "<td>" . htmlentities($info['description']) . "</td>\n";
                    printf("<td class='currency'>$%.2f</td></tr>\n", $info['price']);
                    echo "<td class='currency'>" . $this->shoppingCart[$ID] . "</td>";
                    printf("<td class='currency'>$%.2f</td>\n", $info['price'] * $this->shoppingCart[$ID]);
                    echo "<td><a href='" . $_SERVER['SCRIPT_NAME'] . "?PHPSESS=" . session_id() . "&ItemToAdd=$ID'>Add Items</a></td>";
                    $subTotal += ($info['price'] * $this->shoppingCart['$ID']);

                    echo "<tr><td colspan'4'>SubTotal</td>";
                    printf("<td class='currency'>$%.2f</td>", $subTotal);
                    echo "<td>&nbsp;</td></tr>";
                    echo "</table>\n";
                    $retval =  true;
                }
                echo "</table>\n";
            }
            return($retval);
        }
    }
?>