<?php
require_once 'config.php';
require_once 'functions.php';

// Check: is er een zoekwoord?
$search = isset($_GET['search']) ? $_GET['search'] : '';

if (!empty($search)) {
    // JA: zoeken in database
    $customers = searchCustomers($db, $search);
} else {
    // NEE: toon alle klanten
    $customers = getAllCustomers($db);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>NAW Systeem</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        a { text-decoration: none; padding: 5px 10px; }
        .add { background: green; color: white; }
        .edit { background: orange; color: white; }
        .delete { background: red; color: white; }
        .search { padding: 5px; width: 300px; }
        .btn { background: #4CAF50; color: white; border: none; padding: 5px 15px; }
    </style>
</head>
<body>
    <h1>Klantenoverzicht</h1>
    
    <!-- ZOEKFORMULIER -->
    <form method="GET">
        <input type="text" name="search" class="search" 
               placeholder="Zoek op naam of woonplaats..." 
               value="<?php echo $search; ?>">
        <button type="submit" class="btn">Zoeken</button>
        
        <?php if ($search): ?>
            <a href="index.php">Wis zoeken</a>
        <?php endif; ?>
    </form>
    
    <br>
    <a href="create.php" class="add">+ Nieuwe klant</a>
    <br><br>
    
    <!-- RESULTAAT TELLER -->
    <p>Gevonden: <?php echo count($customers); ?> klant(en)</p>
    
    <table>
        <tr>
            <th>Naam</th>
            <th>Adres</th>
            <th>Woonplaats</th>
            <th>Telefoon</th>
            <th>Acties</th>
        </tr>
        
        <?php foreach ($customers as $c): ?>
        <tr>
            <td><?php echo $c['first_name'] . ' ' . $c['last_name']; ?></td>
            <td><?php echo $c['address']; ?></td>
            <td><?php echo $c['city']; ?></td>
            <td><?php echo $c['phone']; ?></td>
            <td>
                <a href="edit.php?id=<?php echo $c['id']; ?>" class="edit">Wijzig</a>
                <a href="delete.php?id=<?php echo $c['id']; ?>" class="delete" onclick="return confirm('Zeker?')">Verwijder</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>