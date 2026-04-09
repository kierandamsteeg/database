<?php
/**
 * Delete customer script
 * Removes customer from database after confirmation
 */

require_once 'config.php';
require_once 'functions.php';

session_start();

// Get and validate ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id === 0) {
    redirect('index.php', 'Ongeldige klant ID', 'danger');
}

$db = getDBConnection();
if (!$db) {
    redirect('index.php', 'Database fout', 'danger');
}

// Verify customer exists
$checkStmt = $db->prepare("SELECT id FROM customers WHERE id = :id");
$checkStmt->bindParam(':id', $id, PDO::PARAM_INT);
$checkStmt->execute();

if ($checkStmt->rowCount() === 0) {
    redirect('index.php', 'Klant niet gevonden', 'danger');
}

// Delete customer
$deleteStmt = $db->prepare("DELETE FROM customers WHERE id = :id");
$deleteStmt->bindParam(':id', $id, PDO::PARAM_INT);

if ($deleteStmt->execute()) {
    redirect('index.php', 'Klant succesvol verwijderd', 'success');
} else {
    redirect('index.php', 'Fout bij het verwijderen van klant', 'danger');
}
?>