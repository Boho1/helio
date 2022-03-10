<?php
session_start();

$name = "";
$password = "";
$password_confirm = "";
$errors = array(); 

$db = mysqli_connect('localhost', 'root', '', 'helio');

if (isset($_POST['reg_user'])) {
  $name = mysqli_real_escape_string($db, $_POST['name']);
  $password = mysqli_real_escape_string($db, $_POST['password']);
  $password_confirm = mysqli_real_escape_string($db, $_POST['password_confirm']);

  if (empty($name)) { array_push($errors, "name is required"); }
  if (empty($password)) { array_push($errors, "password is required"); }

  $user_check_query = "SELECT * FROM registration WHERE name='$name' OR password='$password' OR password_confirm='$password_confirm' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['name'] === $name) {
      array_push($errors, "Név létezik");
    }

    if ($user['password'] === $password) {
      array_push($errors, "Jelszó létezik");
    }
    if ($password !== $password_confirm) {
      array_push($errors, "Nem egyeznek");
    }
  }

  if (count($errors) == 0) {
  	$password = md5($password);
    $password_confirm = md5($password_confirm);
  	$query = "INSERT INTO registration (name, password, password_confirm) 
  			  VALUES('$name', '$password', '$password_confirm')";
  	mysqli_query($db, $query);
  	$_SESSION['name'] = $name;
  	$_SESSION['success'] = "You are now logged in";
  	header('location: belepes.php');
  }
}