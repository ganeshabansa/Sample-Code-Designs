<?php
session_start();

// initializing variables
$email = "";
$password    = "";
$errors = array(); 

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'test');

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $username = mysqli_real_escape_string($db, $_POST['signupName']);
  $email = mysqli_real_escape_string($db, $_POST['signupEmail']);
  $password_1 = mysqli_real_escape_string($db, $_POST['signupPassword']);

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM user WHERE name='$username' OR email='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['name'] === $username) {
      array_push($errors, "Username already exists");
    }

    if ($user['email'] === $email) {
      array_push($errors, "email already exists");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	//$password = md5($password_1);//encrypt the password before saving in the database

  	$query = "INSERT INTO user (name, email, password) 
  			  VALUES('$username', '$email', '$password')";
  	mysqli_query($db, $query);
  	$_SESSION['name'] = $username;
  	$_SESSION['success'] = "You are now logged in";
  	header('location: home.php');
  }
}
// ... 

// LOGIN USER
if (isset($_POST['login_user'])) {
    $username = mysqli_real_escape_string($db, $_POST['loginEmail']);
    $password = mysqli_real_escape_string($db, $_POST['loginPassword']);
  
    if (count($errors) == 0) {
        // $password = md5($password);
        $query = "SELECT * FROM user WHERE name='$username' AND password='$password'";
        $results = mysqli_query($db, $query);
        if (mysqli_num_rows($results) == 1) {
          $_SESSION['name'] = $username;
          $_SESSION['success'] = "You are now logged in";
          header('location: home.php');
        }else {
            array_push($errors, "Wrong username/password combination");
        }
    }
  }
  
?>