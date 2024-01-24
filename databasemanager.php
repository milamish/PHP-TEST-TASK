<?php
// databaseManager.php
class DatabaseManager {
    private $conn;

    public function __construct($serverName, $connectionOptions) {
        $this->conn = sqlsrv_connect($serverName, $connectionOptions);

        if (!$this->conn) {
            throw new Exception("Connection error: " . print_r(sqlsrv_errors(), true));
        }
    }
    /**Assumptions:
    We have a Customers, Orders, Payments, and Deliveryinfo table
    CustomerID is a foreign key on Orders, Payments, and deliveryinfo tables
    */
    public function fetchData() {
        try {
            $sql = "
                SELECT 
                    customers.CustomerID,
                    customers.CustomerName,
                    SUM(orders.TotalAmount) as TotalSpent,
                    SUM(payments.payment_amount) as TotalPaid,
                    deliveryinfo.delivery_address
                FROM 
                    customers
                INNER JOIN 
                    orders ON customers.CustomerID = orders.CustomerID
                LEFT JOIN
                    payments ON customers.CustomerID = payments.CustomerID
                LEFT JOIN
                    deliveryinfo ON customers.CustomerID = deliveryinfo.CustomerID
                GROUP BY 
                    customers.CustomerID, customers.CustomerName, deliveryinfo.delivery_address
            ";

            $queryResult = sqlsrv_query($this->conn, $sql);

            if (!$queryResult) {
                throw new Exception("Error executing SQL query: " . print_r(sqlsrv_errors(), true));
            }

            $results = [];
            while ($row = sqlsrv_fetch_array($queryResult, SQLSRV_FETCH_ASSOC)) {
                $results[] = $row;
            }

            return $results;
        } catch (Exception $e) {
            throw new Exception("Error fetching data: " . $e->getMessage());
        }
    }

    public function closeConnection() {
        sqlsrv_close($this->conn);
    }
}

?>
