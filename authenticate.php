<?php
session_start();
// Change this to your connection info.
$DATABASE_HOST = 'sql112.byethost8.com';
$DATABASE_USER = 'b8_34979102';
$DATABASE_PASS = 'Edwin1234@';
$DATABASE_NAME = 'b8_34979102_bridgewater';
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if ( !isset($_POST['username'], $_POST['password']) ) {
	// Could not get the data that should have been sent.
	exit('Please fill both the username and password fields!');
}
// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	// Store the result so we can check if the account exists in the database.
	$stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch();
        // Account exists, now we verify the password.
        // Note: remember to use password_hash in your registration file to store the hashed passwords.
        if (password_verify($_POST['password'], $password)) {
            // Verification success! User has logged-in!
            // Retrieve the login history JSON from the database for the logged-in user
$loginHistory = ''; // Initialize the variable

if ($stmt = $con->prepare('SELECT loginHistory FROM accounts WHERE id = ?')) {
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($loginHistory);
    $stmt->fetch();
    $stmt->close();
}

         // Retrieve existing login history JSON
         $loginHistoryJson = json_decode($loginHistory, true);
    
    // Add the current login time to the history
         $loginHistoryJson[] = date('Y-m-d H:i:s');
    
    // Convert back to JSON and update the column
         $updatedLoginHistory = json_encode($loginHistoryJson);
    
    // Update the loginHistory column in the accounts table
    if ($stmt = $con->prepare('UPDATE accounts SET loginHistory = ? WHERE id = ?')) {
        $stmt->bind_param('si', $updatedLoginHistory, $id);
        $stmt->execute();
        $stmt->close();
    }

        sleep(3);
            // Continue with the login process
            // Create sessions, so we know the user is logged in; they basically act like cookies but remember the data on the server.
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = $id;
            header('Location: dashboard.php');
        } else {
            // Incorrect password
            echo 'Login failed. Please check your credentials.';
        }
        
    } else {
        // Incorrect username
        echo 'Login failed. Please check your credentials.';
    }
    

	$stmt->close();
}


?>