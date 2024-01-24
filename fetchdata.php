<?php

/**
15. Develop a PHP application that connects to an MS SQL Server database, retrieves data from
multiple tables, performs a complex SQL query to join and aggregate data, and then returns
the results as JSON. Demonstrate proper error handling and security measures in your code.
*/

// Database configuration
$serverName = "test-server";
$connectionOptions = array(
    "Database" => "test",
    "Uid" => "test",
    "PWD" => "pswd",
    "Encrypt" => true,  // Use encryption
    "TrustServerCertificate" => false,  // Do not trust the server certificate blindly
);

// Establishes the connection
$conn = sqlsrv_connect($serverName, $connectionOptions);

// Check the connection
if (!$conn) {
    // Handle connection error
    die(print_r(sqlsrv_errors(), true));
}

try {
    /**
    Assumptions:
    Table: Customers (with customer details e.g., id, name, email, phone)
    Table: Orders (customer order -> customer_id, order name, quantity)
    Table: Payments (payment details -> customer_id, payment_amount)
    Table: DeliveryInfo (delivery details -> customer_id, delivery_address)   
    foreign_key: Customer_ID
    */
    
    // Perform a complex SQL query to join and aggregate data
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
    
    $queryResult = sqlsrv_query($conn, $sql);

    if (!$queryResult) {
        throw new Exception("Error executing SQL query: " . print_r(sqlsrv_errors(), true));
    }

    // results fetched into an associative array
    $results = [];
    while ($row = sqlsrv_fetch_array($queryResult, SQLSRV_FETCH_ASSOC)) {
        $results[] = $row;
    }

    // results  returned as JSON
    header('Content-Type: application/json');
    echo json_encode($results);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} finally {
    // Close database connection
    sqlsrv_close($conn);
}

