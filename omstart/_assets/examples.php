<?php require_once("intialize.php");

//CREATING A NEW USER.
$new_user = new User();
$new_user->username = "WhatUp645";
$new_user->password = User::password_encrypt("secret");
$new_user->email = "Aha@ahaha.com";
$new_user->set_id(27);
$new_user->save();








?>