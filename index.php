<?php
/**
 * Main page - Customer list view
 * Displays all customers with search and sort functionality
 */

require_once 'config.php';
require_once 'functions.php';

// Start session for messages
session_start();

// Get database connection
$db = getDBConnection();
if (!$db) {
    die("Database connection failed");
}

// Handle search functionality
$search = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';
$sort = isset($_GET['sort']) ? sanitizeInput($_GET['sort']) : 'last_name';
$order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'DESC' : 'ASC';

// Allowed sort columns to prevent SQL injection
$allowedSortColumns = ['first_name', 'last_name', 'city', 'created_at'];
if (!in_array($sort, $allowedSortColumns)) {
    $sort = 'last_name';
}

// Build query with optional search
if (!empty($search)) {
    // Use LIKE for partial matching
    $sql = "SELECT * FROM customers 
            WHERE first_name LIKE :search 
            OR last_name LIKE :search 
            OR city LIKE :search 
            OR email LIKE :search
            ORDER BY {$sort} {$order}";
    $stmt = $db->prepare($sql);
    $searchParam = "%{$search}%";
    $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
} else {
    $sql = "SELECT * FROM customers ORDER BY {$sort} {$order} LIMIT 100";
    $stmt = $db->prepare($sql);
}

$stmt->execute();
$customers = $stmt->fetchAll();

// Get message from session if exists
$message = '';
if (isset($_SESSION['message'])) {
    $message = showAlert($_SESSION['message'], $_SESSION['message_type']);
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NAW Beheersysteem - Klantenoverzicht</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/custom.css">
</head>
<body>
    <div class="container mt-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-8">
                <h1>NAW Beheersysteem</h1>
                <p class="text-muted">Beheer klantgegevens op een veilige manier</p>
            </div>
            <div class="col-md-4 text-end">
                <a href="create.php" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle"></i> Nieuwe klant
                </a>
            </div>
        </div>

        <!-- Messages -->
        <?php echo $message; ?>

        <!-- Search bar -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="" class="row g-3">
                    <div class="col-md-8">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Zoek op naam, woonplaats of email..." 
                               value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-outline-primary">Zoeken</button>
                        <?php if (!empty($search)): ?>
                            <a href="index.php" class="btn btn-outline-secondary">Wis filter</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Customers table -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Klantenlijst (<?php echo count($customers); ?> resultaten)</h5>
            </div>
            <div class="card-body">
                <?php if (count($customers) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>
                                        <a href="?sort=last_name&order=<?php echo $order === 'ASC' ? 'desc' : 'asc'; ?>&search=<?php echo urlencode($search); ?>" class="text-white text-decoration-none">
                                            Naam <?php echo $sort === 'last_name' ? ($order === 'ASC' ? '↑' : '↓') : ''; ?>
                                        </a>
                                    </th>
                                    <th>Adres</th>
                                    <th>
                                        <a href="?sort=city&order=<?php echo $order === 'ASC' ? 'desc' : 'asc'; ?>&search=<?php echo urlencode($search); ?>" class="text-white text-decoration-none">
                                            Woonplaats <?php echo $sort === 'city' ? ($order === 'ASC' ? '↑' : '↓') : ''; ?>
                                        </a>
                                    </th>
                                    <th>Contact</th>
                                    <th>Acties</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($customers as $customer): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($customer['last_name']); ?></strong>, 
                                            <?php echo htmlspecialchars($customer['first_name']); ?>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($customer['address']); ?><br>
                                            <small class="text-muted"><?php echo htmlspecialchars($customer['postal_code']); ?></small>
                                        </td>
                                        <td><?php echo htmlspecialchars($customer['city']); ?></td>
                                        <td>
                                            <?php if ($customer['phone']): ?>
                                                <small>📞 <?php echo htmlspecialchars($customer['phone']); ?></small><br>
                                            <?php endif; ?>
                                            <?php if ($customer['email']): ?>
                                                <small>✉️ <?php echo htmlspecialchars($customer['email']); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="edit.php?id=<?php echo $customer['id']; ?>" class="btn btn-sm btn-warning">Wijzig</a>
                                            <button onclick="confirmDelete(<?php echo $customer['id']; ?>, '<?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?>')" class="btn btn-sm btn-danger">Verwijder</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        Geen klanten gevonden. <a href="create.php">Voeg een nieuwe klant toe</a>.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Delete confirmation modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Bevestig verwijderen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Weet je zeker dat je <strong id="deleteName"></strong> wilt verwijderen?
                    <br><span class="text-danger">Dit kan niet ongedaan worden gemaakt!</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                    <a href="#" id="deleteLink" class="btn btn-danger">Verwijderen</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        function confirmDelete(id, name) {
            document.getElementById('deleteName').textContent = name;
            document.getElementById('deleteLink').href = 'delete.php?id=' + id;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
    </script>
</body>
</html>