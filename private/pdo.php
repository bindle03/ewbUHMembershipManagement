<?php
  // this file is to type this handle request once. Use require_once whenever needed
  $pdo = new PDO('mysql:host=localhost;port=3306;dbname=ewb_members','ewb','EWBuh2024!');
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>
