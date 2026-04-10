<?php
require_once 'config.php';
require_once 'functions.php';

$id = $_GET['id'];
$customer = getCustomer($db, $id);

// If form submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    updateCustomer($db, $id, $_POST);
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Klant wijzigen</title>
</head>
<body>
    <h1>Klant wijzigen</h1>
    
    <form method="POST">
        <p>Voornaam: <input type="text" name="first_name" value="<?php echo $customer['first_name']; ?>"></p>
        <p>Achternaam: <input type="text" name="last_name" value="<?php echo $customer['last_name']; ?>"></p>
        <p>Adres: <input type="text" name="address" value="<?php echo $customer['address']; ?>"></p>
        <p>Woonplaats: <input type="text" name="city" value="<?php echo $customer['city']; ?>"></p>
        <p>Telefoon: <input type="text" name="phone" value="<?php echo $customer['phone']; ?>"></p>
        <p>Email: <input type="email" name="email" value="<?php echo $customer['email']; ?>"></p>
        
        <button type="submit">Opslaan</button>
        <a href="index.php">Annuleren</a>
    </form>
</body>
</html>