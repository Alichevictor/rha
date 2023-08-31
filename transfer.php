<?php
session_start();
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'bridgewater';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

$transferredAmount = $_POST['amount'];


// Retrieve current user's balance from the database
$stmt = $con->prepare("SELECT balance FROM accounts WHERE id = ?");
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($balance);
$stmt->fetch();
$stmt->close();

// Calculate new balance
$newBalance = $balance - $transferredAmount;

// Update user's balance in the database
$updateStmt = $con->prepare("UPDATE accounts SET balance = ? WHERE id = ?");
$updateStmt->bind_param('di', $newBalance, $_SESSION['id']);
$updateStmt->execute();
$updateStmt->close();

// Insert transfer transaction record
$transactionType = "Transfer";
$accountNumber = $_POST['acc_number'];
$AccountName = $_POST['AccountName'];

$insertTransactionStmt = $con->prepare("INSERT INTO transactions (user_id, transaction_type, account_number, account_name, amount) VALUES (?, ?, ?, ?, ?)");
$insertTransactionStmt->bind_param('isssd', $_SESSION['id'], $transactionType, $accountNumber, $AccountName, $transferredAmount);
$insertTransactionStmt->execute();
$insertTransactionStmt->close();

header("Location: dashboard3.php");
exit();
?>
