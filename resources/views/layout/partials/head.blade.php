
<!DOCTYPE html>
<html>
<head>

<?php
ini_set('session.cookie_httponly',1);
ini_set('session.use_only_cookies',1);
session_start(['cookie_lifetime' => 43200,'cookie_secure' => true,'cookie_httponly' => true]);
header( "Set-Cookie: name=value; httpOnly" );
header("X-XSS-Protection: 1; mode=block");
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Content-Type: text/html; charset=UTF-8');
//header("Content-Security-Policy: default-src 'self'; script-src 'self'");
?>
 <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ session()->get('establishfullname') }}</title>
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="css/bootstrap.min.css">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="css/_all-skins.min.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="css/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="css/jquery-jvectormap.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="css/bootstrap-datepicker.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="css/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="css/bootstrap3-wysihtml5.min.css">
<link rel="stylesheet" href="css/blue.css">
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/sweetalert.css">
<link rel="stylesheet" type="text/css" href="css/sweetalert.min.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
 <!--  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic"> -->
</head>
<style type="text/css">

.parsley-errors-list
{
  color: red;
    margin-left: -38px;
        padding-top: 2px;
    list-style-type: none;
}
/* css for corousel*/

.mandatory{
  color:red;
}
.swal-modal {

  border: 3px solid white;
      font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
}
.swal-button {
  padding: 7px 19px;
  border-radius: 2px;
  background-color: #3c8dbc;
  font-size: 12px;
  border: 1px solid #3c8dbc;
  text-shadow: 0px -1px 0px rgba(0, 0, 0, 0.3);
}
</style>
