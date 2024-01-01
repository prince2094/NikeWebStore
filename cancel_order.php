<?php
session_start();
require_once("db_connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $orderId = $_POST["order_id"];

    // Delete the order and associated payment data
    $deleteOrderSql = "DELETE FROM orders WHERE id = $user_id";
    $deletePaymentDataSql = "DELETE FROM payment_data WHERE order_id = $phone";

    if ($conn->query($deleteOrderSql) && $conn->query($deletePaymentDataSql)) {
        echo "Order canceled successfully!";
    } else {
        echo "Error canceling the order: " . $conn->error;
    }
}

$conn->close();
?>
