<?php
require_once 'config.php';
require_once 'functions.php';

deleteCustomer($db, $_GET['id']);

header("Location: index.php");
exit();
?>