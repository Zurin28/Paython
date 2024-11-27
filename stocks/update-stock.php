<?php

require_once('../tools/functions.php');
require_once('../classes/stocks.class.php');

$id = $_GET['id'] ?? null;
$quantity = $status = $reason = '';
$quantityErr = $statusErr = $reasonErr = '';

$stocksObj = new Stocks();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = clean_input($_POST['quantity']);
    $status = clean_input($_POST['stock_action']);
    $reason = isset($_POST['reason']) ? clean_input($_POST['reason']) : '';

    // Validate quantity
    if (empty($quantity)) {
        $quantityErr = 'Quantity is required.';
    } elseif (!is_numeric($quantity)) {
        $quantityErr = 'Quantity should be a number.';
    } elseif ($quantity <= 0) {
        $quantityErr = 'Quantity must be greater than 0.';
    } elseif ($status === 'out' && $quantity > $stocksObj->getAvailableStocks($id)) {
        $available = $stocksObj->getAvailableStocks($id) ?: 0;
        $quantityErr = "Quantity exceeds available stock: $available.";
    }

    // Validate status
    if (empty($status)) {
        $statusErr = 'Stock action is required.';
    }

    // Validate reason for stock out
    if ($status === 'out' && empty($reason)) {
        $reasonErr = 'Reason is required for stock out.';
    }

    // If there are validation errors, return them as JSON
    if (!empty($quantityErr) || !empty($statusErr) || !empty($reasonErr)) {
        echo json_encode([
            'status' => 'error',
            'quantityErr' => $quantityErr,
            'statusErr' => $statusErr,
            'reasonErr' => $reasonErr,
        ]);
        exit;
    }

    // Proceed with stock update
    $stocksObj->product_id = $id;
    $stocksObj->quantity = $quantity;
    $stocksObj->status = $status;
    $stocksObj->reason = $reason;

    if ($stocksObj->add()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update stock.']);
    }
}
?>
