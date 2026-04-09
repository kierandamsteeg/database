<?php
/**
 * Edit customer page
 * Form to modify existing customer data
 */

require_once 'config.php';
require_once 'functions.php';

session_start();

// Get customer ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id === 0) {
    redirect('index.php', 'Ongeldige klant ID', 'danger');
}

$db = getDBConnection();
if (!$db) {
    die("Database connection failed");
}

// Fetch existing customer data
$stmt = $db->prepare("SELECT * FROM customers WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$customer = $stmt->fetch();

if (!$customer) {
    redirect('index.php', 'Klant niet gevonden', 'danger');
}

$errors = [];

// Form processing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Same validation as create.php
    $firstName = sanitizeInput($_POST['first_name'] ?? '');
    $lastName = sanitizeInput($_POST['last_name'] ?? '');
    $address = sanitizeInput($_POST['address'] ?? '');
    $postalCode = sanitizeInput($_POST['postal_code'] ?? '');
    $city = sanitizeInput($_POST['city'] ?? '');
    $phone = sanitizeInput($_POST['phone'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    
    // Validation (same as create.php)
    if (empty($firstName)) $errors[] = "Voornaam is verplicht";
    if (empty($lastName)) $errors[] = "Achternaam is verplicht";
    if (empty($address)) $errors[] = "Adres is verplicht";
    if (empty($postalCode)) {
        $errors[] = "Postcode is verplicht";
    } elseif (!validatePostalCode($postalCode)) {
        $errors[] = "Postcode formaat is ongeldig";
    }
    if (empty($city)) $errors[] = "Woonplaats is verplicht";
    if (!empty($email) && !validateEmail($email)) {
        $errors[] = "Email adres is ongeldig";
    }
    
    if (empty($errors)) {
        // Update query with prepared statement
        $sql = "UPDATE customers 
                SET first_name = :first_name, 
                    last_name = :last_name, 
                    address = :address, 
                    postal_code = :postal_code, 
                    city = :city, 
                    phone = :phone, 
                    email = :email 
                WHERE id = :id";
        
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':postal_code', $postalCode);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            redirect('index.php', 'Klantgegevens succesvol bijgewerkt!', 'success');
        } else {
            $errors[] = "Fout bij het bijwerken van gegevens";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Klant wijzigen - NAW Beheersysteem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h1>Klantgegevens wijzigen</h1>
                <p class="text-muted">Klant: <?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?></p>
                
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="">
                            <!-- Same form fields as create.php but with $customer values -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Voornaam *</label>
                                    <input type="text" class="form-control" name="first_name" 
                                           value="<?php echo htmlspecialchars($customer['first_name']); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Achternaam *</label>
                                    <input type="text" class="form-control" name="last_name" 
                                           value="<?php echo htmlspecialchars($customer['last_name']); ?>" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Adres *</label>
                                <input type="text" class="form-control" name="address" 
                                       value="<?php echo htmlspecialchars($customer['address']); ?>" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Postcode *</label>
                                    <input type="text" class="form-control" name="postal_code" 
                                           value="<?php echo htmlspecialchars($customer['postal_code']); ?>" required>
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label class="form-label">Woonplaats *</label>
                                    <input type="text" class="form-control" name="city" 
                                           value="<?php echo htmlspecialchars($customer['city']); ?>" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Telefoon</label>
                                    <input type="tel" class="form-control" name="phone" 
                                           value="<?php echo htmlspecialchars($customer['phone']); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">E-mail</label>
                                    <input type="email" class="form-control" name="email" 
                                           value="<?php echo htmlspecialchars($customer['email']); ?>">
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="index.php" class="btn btn-secondary">Annuleren</a>
                                <button type="submit" class="btn btn-warning">Wijzigingen opslaan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>