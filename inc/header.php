<?php
    $filepath = realpath(dirname(__FILE__));
    include_once $filepath.'/../lib/Session.php';
    Session::init();
?>
<!DOCTYPE html>
<html>
	<head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Login and Registration</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">
         <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
        <script src="js/vendor/modernizr-2.8.3.min.js"></script>
    </head>
    <?php
        if (isset($_GET['action']) && $_GET['action']=="logout") {
           Session::destroy();
        }
    ?>
 	<body >
	<div class="container">
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<div class="navbar-header">
					<a class="navbar-brand" href="index.php">Login and Registration System</a>
 				</div>
				<ul class="nav navbar-nav navbar-right" {color:#06960E;}>
        <?php 
                    $id = Session::get("id");
                    $userlogin = Session::get("login");
                    if ($userlogin==true) {
        ?>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="profile.php ? id=<?php echo $id; ?>">Profile</a></li>
                    <li><a href="?action=logout">Logout</a></li>
         <?php }
                    else { ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
            <?php } ?>
                </ul>
			</div>
		</nav>