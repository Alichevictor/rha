<?php
session_start();
$POSTGRES_CONNECTION_STRING = "postgres://default:FoQMG0CR6IWE@ep-muddy-art-24176362.ap-southeast-1.postgres.vercel-storage.com:5432/verceldb";

// Attempt to connect to PostgreSQL using the connection string
$con = pg_connect($POSTGRES_CONNECTION_STRING);

if (!$con) {
    exit('Failed to connect to PostgreSQL: ' . pg_last_error());
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

// Redirect back to the dashboard3.php page after the transfer
header("Location: dashboard3.php");
exit();
?>
