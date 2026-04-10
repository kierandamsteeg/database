<?php
require_once 'config.php';
require_once 'functions.php';

// If form submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    addCustomer($db, $_POST);
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Nieuwe klant</title>
</head>
<body>
    <h1>Nieuwe klant toevoegen</h1>
    
    <form method="POST">
        <p>Voornaam: <input type="text" name="first_name" required></p>
        <p>Achternaam: <input type="text" name="last_name" required></p>
        <p>Adres: <input type="text" name="address" required></p>
        <p>Woonplaats: <input type="text" name="city" required></p>
        <p>Telefoon: <input type="text" name="phone"></p>
        <p>Email: <input type="email" name="email"></p>
        
        <button type="submit">Opslaan</button>
        <a href="index.php">Annuleren</a>
    </form>
</body>
</html>