<?php
session_start();

$_SESSION = array();

session_destroy();

// Redirect to login page
echo "<script LANGUAGE='JavaScript'>
          window.alert('Logging out...');
          window.location.href='index.php';
       </script>";

exit();
