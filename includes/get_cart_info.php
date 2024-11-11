<?php
session_start();
include 'db_conn.inc';

$response = [
    'totalItems' => 0,
    'totalAmount' => 0,
];

if (isset($_SESSION['customer_id'])) {
    $customerId = $_SESSION['customer_id'];
    $sql = "SELECT SUM(ci.quantity) AS total_items, SUM(ci.quantity * ci.price) AS total_amount
            FROM cartitems ci
            JOIN carts c ON ci.cart_id = c.cart_id
            WHERE c.customer_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customerId);
    $stmt->execute();
    $result = $stmt->get_result();
    $cartData = $result->fetch_assoc();

    if ($cartData) {
        $response['totalItems'] = $cartData['total_items'] ?: 0;
        $response['totalAmount'] = $cartData['total_amount'] ?: 0;
    }
}

header('Content-Type: application/json');
echo json_encode($response);
