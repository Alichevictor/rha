<?php
session_start();
$POSTGRES_CONNECTION_STRING = "postgres://default:FoQMG0CR6IWE@ep-muddy-art-24176362.ap-southeast-1.postgres.vercel-storage.com:5432/verceldb";

// Attempt to connect to PostgreSQL using the connection string
$con = pg_connect($POSTGRES_CONNECTION_STRING);

if (!$con) {
    exit('Failed to connect to PostgreSQL: ' . pg_last_error());
}

$depositedAmount = $_POST['amount'];

// Handle file uploads for front and back images
$frontImageName = $_FILES['frontImage']['name'];
$backImageName = $_FILES['backImage']['name'];
$uploadsDirectory = 'uploads/';

// Check if the directory already exists
if (!is_dir($uploadsDirectory)) {
    // Attempt to create the directory
    if (!mkdir($uploadsDirectory, 0755)) {
        exit('Failed to create directory');
    }
}

if (isset($_FILES['frontImage']) && is_uploaded_file($_FILES['frontImage']['tmp_name'])) {
    $frontImageName = $_FILES['frontImage']['name'];
    $frontImagePath = $uploadsDirectory . $frontImageName;
    if (!move_uploaded_file($_FILES['frontImage']['tmp_name'], $frontImagePath)) {
        exit('Failed to move front image');
    }
} else {
    exit('Front image was not uploaded or not uploaded via HTTP POST');
}

if (isset($_FILES['backImage']) && is_uploaded_file($_FILES['backImage']['tmp_name'])) {
    $backImageName = $_FILES['backImage']['name'];
    $backImagePath = $uploadsDirectory . $backImageName;
    if (!move_uploaded_file($_FILES['backImage']['tmp_name'], $backImagePath)) {
        exit('Failed to move back image');
    }
} else {
    exit('Back image was not uploaded or not uploaded via HTTP POST');
}

$stmt = $con->prepare("SELECT balance FROM accounts WHERE id = ?");
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($balance);
$stmt->fetch();
$stmt->close();

$newBalance = $balance + $depositedAmount;

$updateStmt = $con->prepare("UPDATE accounts SET balance = ? WHERE id = ?");
$updateStmt->bind_param('di', $newBalance, $_SESSION['id']);
$updateStmt->execute();
$updateStmt->close();

// Insert transaction record
$transactionType = "Deposit";
$insertTransactionStmt = $con->prepare("INSERT INTO transactions (user_id, transaction_type, amount) VALUES (?, ?, ?)");
$insertTransactionStmt->bind_param('iss', $_SESSION['id'], $transactionType, $depositedAmount);
$insertTransactionStmt->execute();
$insertTransactionStmt->close();

header("Location: deposit.php");
exit();
?>
