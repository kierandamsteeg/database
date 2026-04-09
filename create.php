<?php
/**
 * Create customer page
 * Form to add new customer to database
 */

require_once 'config.php';
require_once 'functions.php';

session_start();

// Initialize variables
$errors = [];
$success = false;

// Form processing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $firstName = sanitizeInput($_POST['first_name'] ?? '');
    $lastName = sanitizeInput($_POST['last_name'] ?? '');
    $address = sanitizeInput($_POST['address'] ?? '');
    $postalCode = sanitizeInput($_POST['postal_code'] ?? '');
    $city = sanitizeInput($_POST['city'] ?? '');
    $phone = sanitizeInput($_POST['phone'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    
    // Validation
    if (empty($firstName)) {
        $errors[] = "Voornaam is verplicht";
    }
    if (empty($lastName)) {
        $errors[] = "Achternaam is verplicht";
    }
    if (empty($address)) {
        $errors[] = "Adres is verplicht";
    }
    if (empty($postalCode)) {
        $errors[] = "Postcode is verplicht";
    } elseif (!validatePostalCode($postalCode)) {
        $errors[] = "Postcode formaat is ongeldig (gebruik: 1234 AB)";
    }
    if (empty($city)) {
        $errors[] = "Woonplaats is verplicht";
    }
    if (!empty($email) && !validateEmail($email)) {
        $errors[] = "Email adres is ongeldig";
    }
    
    // If no errors, insert into database
    if (empty($errors)) {
        $db = getDBConnection();
        if ($db) {
            // Use prepared statement to prevent SQL injection
            $sql = "INSERT INTO customers (first_name, last_name, address, postal_code, city, phone, email) 
                    VALUES (:first_name, :last_name, :address, :postal_code, :city, :phone, :email)";
            
            $stmt = $db->prepare($sql);
            
            // Bind parameters
            $stmt->bindParam(':first_name', $firstName);
            $stmt->bindParam(':last_name', $lastName);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':postal_code', $postalCode);
            $stmt->bindParam(':city', $city);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':email', $email);
            
            if ($stmt->execute()) {
                redirect('index.php', 'Klant succesvol toegevoegd!', 'success');
            } else {
                $errors[] = "Er is een fout opgetreden bij het opslaan";
            }
        } else {
            $errors[] = "Database verbinding mislukt";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nieuwe klant - NAW Beheersysteem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h1>Nieuwe klant toevoegen</h1>
                
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <strong>Corrigeer de volgende fouten:</strong>
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="" novalidate>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">Voornaam *</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" 
                                           value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Achternaam *</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" 
                                           value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="address" class="form-label">Adres *</label>
                                <input type="text" class="form-control" id="address" name="address" 
                                       value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="postal_code" class="form-label">Postcode *</label>
                                    <input type="text" class="form-control" id="postal_code" name="postal_code" 
                                           placeholder="1234 AB"
                                           value="<?php echo isset($_POST['postal_code']) ? htmlspecialchars($_POST['postal_code']) : ''; ?>" required>
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label for="city" class="form-label">Woonplaats *</label>
                                    <input type="text" class="form-control" id="city" name="city" 
                                           value="<?php echo isset($_POST['city']) ? htmlspecialchars($_POST['city']) : ''; ?>" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Telefoon</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="index.php" class="btn btn-secondary">Annuleren</a>
                                <button type="submit" class="btn btn-primary">Klant opslaan</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="mt-3">
                    <small class="text-muted">* Verplichte velden</small>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>