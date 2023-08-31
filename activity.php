<?php
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: sign-in.html');
	exit;
}
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'bridgewater';
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
if (isset($_SESSION['id'])) {
  $userId = $_SESSION['id'];

  $stmt = $con->prepare('SELECT imagePath, firstName, lastName, otherName, email, phoneNumber, dateOfBirth, residentialAddress, statesecurityNumber, nextofkinName, username, password, balance FROM accounts WHERE id = ?');

  if ($stmt) {
      $stmt->bind_param('i', $userId);
      $stmt->execute();
      $stmt->bind_result($imagePath, $firstName, $lastName, $otherName, $email, $phoneNumber, $dateOfBirth, $residentialAddress, $statesecurityNumber, $nextofkinName, $username, $password, $balance);
      $stmt->fetch();
      $stmt->close();
  } else {
      // Handle the case when the prepared statement couldn't be created
      echo 'Could not prepare statement!';
  }
} else {
  // Handle the case when the user is not logged in
  header('Location: login.php');
  exit;
}
if ($stmt = $con->prepare('SELECT loginHistory FROM accounts WHERE id = ?')) {
  $stmt->bind_param('i', $userId);
  $stmt->execute();
  $stmt->bind_result($loginHistory);
  $stmt->fetch();
  $stmt->close();
}

       // Retrieve existing login history JSON
      $loginHistoryJson = json_decode($loginHistory, true);
if (is_array($loginHistoryJson)) {
    $loginHistoryJson = array_reverse($loginHistoryJson);
}

?>
<!DOCTYPE html>
<html>
    <head>
  <!--TITLE-->
  <title>Dashboard</title>
  <style>
    body{
      font-family: "Inter";
      font-size: 14px;
    }
  
  </style>
    <!--ICON-->
    <link rel="shortcut icon" href="images/logo.jpg">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="Responsive.css">
    <link href="https://fonts.googleapis.com/icon?family=Inter" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
   <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
      </head>
      <body>

<div id="dashbod1" >
<div style="height: 70px;border-bottom: 3px solid #780b54de;;">
<img src="images/logo.jpg" alt="logo" height="50px" style="padding: 10px;border-radius: 50px;">
</div>
<div style="padding-left: 40px;">
<P>AVAILABLE BALANCE</P>
<div id="balance" style="height: 60px;width: 250px; background-color: aqua;padding-top:1px;padding-left:4px">
<h1>$<?=$balance?></h1>
</div>
<div id="union">
  <div>
    <p>Income</p>
    <p>Debits</p>
  <br>
  <button id="btn1"><i class="fa fa-money"></i> <a href="dashboard3.php" style="color: white;text-decoration: none;"> TRANSFER</a></button>
 </div>
  <div style="text-align: right;">
    <p style="color: rgb(13, 88, 63);">66.12%</p>
    <p style="color: red;">24.12%</p>
  <br>
  <button id="btn2"> <i class="fa fa-credit-card"></i> <a href="deposit.php" style="color: white;text-decoration: none;"> Deposit</a></button>
  
  </div>
</div>
<p>MENU</p>
<ul>
  <li ><a href="dashboard.php"><i class="fa fa-tasks" ></i></i> Dashboard</a></li>
  <li><a href="dashboard2.php"><i class="fa fa-address-card-o"></i> Account summary</a></li>
  <li><a href="dashboard3.php"><i class="fa fa-share-square-o"></i> Transfer</a></li>
  <li><a href="dashboard4.php"><i class="fa fa-exchange"></i> Cross-border Transfer</a></li>
</ul>


</div>
</div>

<div id="dashbod2">
  <div style="height: 70px;background-color: #f5eef3de;;border-bottom: 1px solid #dbcdd6de;" >
  <div id="toggleButton" style="height: 50px;width: 150px;text-align: right;
    background-color: #780b54de;;float: right;margin: 10px;text-align: center;display: block;color:white;border-radius:8px"><p >Welcome <?=$_SESSION['name']?> <br> click me!</p></div>
    <div id="upperbase" style="display: none;">
    <div style="height: 100px;background-color: rgb(194, 202, 202);margin-top:-6%;color:purple;padding:5px;">
         <h2>Balance:$<?=$balance?></h2>
         <h4><?=$firstName?> <?=$lastName?> </h4><br>
        
 </div>
 <ul id="upperli">
  <li><a href="dashboard.php">Dashboard</a></li>
  <li><a href="profile.php">View Profile</a></li>
  <li><a href="setting.php">Account Settings</a></li>
  <li><a href="activity.php">Login Activity</a></li>
  <hr>
  <li><a href="logout.php">Log Out</a></li>
  </ul>
</div>  
 </div>


 
<!--  -->
<div id="underdash" >
<div id="one" class="standby" style="height: 750px;">
<h2 style="color: #780b54de;">Recent Login Activity</h2>
<p style="color: #3a0528de;">Information about recent login activity on your account.</p>
<div style="height: 500px;border: 1px solid #ea6abfde;padding:20px;overflow: auto" id="loginHistory">


<script>
    // Assuming you have retrieved the $loginHistory array from PHP
    const loginHistory = <?php echo json_encode($loginHistoryJson); ?>;
    
    // Get the loginHistory div element
    const loginHistoryDiv = document.getElementById('loginHistory');
    
    // Loop through the login history and display it
  
    loginHistory.forEach(loginTime => {
        const formattedTime = new Date(loginTime).toLocaleString();
        loginHistoryDiv.innerHTML += `<p>${formattedTime} <br> <hr></p>`;
    });
</script>

</div>
<div style="width: 70%;padding-left:20px;color: #780b54de;">
<h2 style="color: #470531de;"> We’re here to help you!</h2>
 <p>Ask a question or file a support ticket, manage request, report an issues. Our team support team will get back to you by email.
</p> 
<button id="support" ><b>Contact support</b></button>
</div> 
</div>
<div style="border-bottom: 1px solid #ec7ac6de;;margin-top: 10px;"></div>
<div style="height: 100px;color: #1f0316de;">
<p>© 2023 Bridgewater Financial Union - All rights reserved.</p>
</div>

</div>
</div>
</div>



<script src="dashboard.js"></script>
      </body>
      </html>