<?php
  // 1. Create a database connection
  define("DB_SERVER", "localhost");
  define("DB_USER", "coderedempuser");
  define("DB_PASS", "SwC2!zmIDyk48S%hEbT^&33u");
  define("DB_NAME", "coderedemp");

  // Database=inclinedb;Data Source=eu-cdbr-azure-north-e.cloudapp.net;User Id=b3754291c17e85;Password=930b1315

  $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);



  // Test if connection succeeded
  if(mysqli_connect_errno()) {
    die("Database connection failed: " .
         mysqli_connect_error() .
         " (" . mysqli_connect_errno() . ")"
    );
  }
?>
