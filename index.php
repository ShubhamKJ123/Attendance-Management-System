<?php

session_start();

if (isset($_POST['login'])) {
    try {
        if (empty($_POST['username']) || empty($_POST['password'])) {
            throw new Exception("Username and password are required!");
        }

        include('connect.php');

        $stmt = $pdo->prepare("SELECT * FROM admininfo WHERE username = :username AND password = :password AND type = :type");
        $stmt->bindParam(':username', $_POST['username']);
        $stmt->bindParam(':password', $_POST['password']); // Note: This is vulnerable to SQL injection, use password_hash()
        $stmt->bindParam(':type', $_POST['type']);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && $_POST["type"] == 'teacher') {
            $_SESSION['name'] = "oasis";
            header('location: teacher/index.php');
            exit();
        } elseif ($row && $_POST["type"] == 'student') {
            $_SESSION['name'] = "oasis";
            header('location: student/index.php');
            exit();
        } elseif ($row && $_POST["type"] == 'admin') {
            $_SESSION['name'] = "oasis";
            header('location: admin/index.php');
            exit();
        } else {
            throw new Exception("Username, password, or role is wrong. Try again!");
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        // You may want to redirect to the login page here instead of echoing an error message.
    }
}

// Add the rest of your script here

?>


<!DOCTYPE html>
<html>
<head>

	<title>Online Attendance Management System</title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
	 
	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" >
	 
	<link rel="stylesheet" href="styles.css" >
	 
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</head>

<body>
	<center>

<header>

  <h1>Online Attendance Management System 1.0</h1>

</header>

<h1>Login</h1>

<?php
//printing error message
if(isset($error_msg))
{
	echo $error_msg;
}
?>

<!-- Old Version -->
<!-- 
<form action="" method="post">
	
	<table>
		<tr>
			<td>Username </td>
			<td><input type="text" name="username"></input></td>
		</tr>
		<tr>
			<td>Password</td>
			<td><input type="password" name="password"></input></td>
		</tr>
		<tr>
			<td>Role</td>
			<td>
			<select name="type">
				<option name="teacher" value="teacher">Teacher</option>
				<option name="student" value="student">Student</option>
				<option name="admin" value="admin">Admin</option>
			</select>
			</td>
		</tr>
		<tr><td><br></td></tr>
		<tr>
			<td><button><input type="submit" name="login" value="Login"></input></button></td>
			<td><button><input type="reset" name="reset" value="Reset"></button></td>
		</tr>
	</table>
</form>
-->

<div class="content">
	<div class="row">

		<form method="post" class="form-horizontal col-md-6 col-md-offset-3">
			<div class="form-group">
			    <label for="input1" class="col-sm-3 control-label">Username</label>
			    <div class="col-sm-7">
			      <input type="text" name="username"  class="form-control" id="input1" placeholder="your username" />
			    </div>
			</div>

			<div class="form-group">
			    <label for="input1" class="col-sm-3 control-label">Password</label>
			    <div class="col-sm-7">
			      <input type="password" name="password"  class="form-control" id="input1" placeholder="your password" />
			    </div>
			</div>


			<div class="form-group" class="radio">
			<label for="input1" class="col-sm-3 control-label">Role</label>
			<div class="col-sm-7">
			  <label>
			    <input type="radio" name="type" id="optionsRadios1" value="student" checked> Student
			  </label>
			  	  <label>
			    <input type="radio" name="type" id="optionsRadios1" value="teacher"> Teacher
			  </label>
			  <label>
			    <input type="radio" name="type" id="optionsRadios1" value="admin"> Admin
			  </label>
			</div>
			</div>


			<input type="submit" class="btn btn-primary col-md-3 col-md-offset-7" value="Login" name="login" />
		</form>
	</div>
</div>



<br><br>
<p><strong>Have forgot your password? <a href="reset.php">Reset here.</a></strong></p>
<p><strong>If you don't have any account, <a href="signup.php">Signup</a> here</strong></p>

</center>
</body>
</html>