<?php
// Change this to your connection info.
$POSTGRES_CONNECTION_STRING = "postgres://default:FoQMG0CR6IWE@ep-muddy-art-24176362.ap-southeast-1.postgres.vercel-storage.com:5432/verceldb";

// Attempt to connect to PostgreSQL using the connection string
$con = pg_connect($POSTGRES_CONNECTION_STRING);

if (!$con) {
    exit('Failed to connect to PostgreSQL: ' . pg_last_error());
}

$requiredFields = [
    'firstName',
    'lastName',
    'otherName',
    'email',
    'phoneNumber',
    'dateOfBirth',
    'residentialAddress',
    'statesecurityNumber',
    'nextofkinName',
    'username',
    'password'
];

foreach ($requiredFields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        echo 'Please complete the registration form';
        break;
    }
}

// Validate image fields (required)
if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    echo  'Please upload a valid image';
}

// Validate image type and size
$allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
$maxImageSize = 5 * 1024 * 1024; // 5 MB

if (
    !in_array($_FILES['image']['type'], $allowedImageTypes) ||
    $_FILES['image']['size'] > $maxImageSize
) {
    echo  'Please upload a valid image (JPEG, PNG, GIF) within 5 MB.';
}

// Move the uploaded image to a designated upload directory
$uploadDir = 'uploads/';
$uploadedFilePath = $uploadDir . $_FILES['image']['name'];

if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadedFilePath)) {
    echo 'Failed to move uploaded image to the directory.';
}

$imagePath = $uploadedFilePath;

if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    echo  'Email is not valid!';
}
if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0) {
    echo  'Username is not valid!';
}
if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
    echo  'Password must be between 5 and 20 characters long!';
}

// We need to check if the account with that username exists.
if ($stmt = $con->prepare('SELECT id FROM accounts WHERE username = ?')) {
    // Bind parameters (s = string), bind the username.
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        // Username already exists
        echo  'Username exists, please choose another!';
    } else {
        // Username doesn't exist, insert a new account
        if ($stmt = $con->prepare(
            'INSERT INTO accounts (
                firstName,
                lastName,
                otherName,
                email,
                phoneNumber,
                dateOfBirth,
                residentialAddress,
                statesecurityNumber,
                nextofkinName,
                username,
                password,
                imagePath,     /* New column for storing image path */
                balance
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        )) {
            // Hash the password and use password_hash when storing passwords.
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $balance = 0.00; // Initial balance
            
            $stmt->bind_param(
                'ssssssssssssd', // Adjust the data types accordingly
                $_POST['firstName'],
                $_POST['lastName'],
                $_POST['otherName'],
                $_POST['email'],
                $_POST['phoneNumber'],
                $_POST['dateOfBirth'],
                $_POST['residentialAddress'],
                $_POST['statesecurityNumber'],
                $_POST['nextofkinName'],
                $_POST['username'],
                $password,
                $imagePath, // Store the image path in the database
                $balance
            );
            
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo   'You have successfully registered! You can now <a href="sign-in.html">login</a>.';
                    header("refresh:3;url=sign-in.html");
                } else {
                    echo  'Registration failed. Please try again.';
                }
            } else {
                echo  'Registration failed. Please try again.';
            }
            
        } else {
            echo   'Could not prepare the statement!';
        }
    }
    $stmt->close();
} else {
    echo   'Could not prepare the statement!';
}

$con->close();
?>