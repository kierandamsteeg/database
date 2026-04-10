<?php
/**
 * Helper functions for NAW system
 */

// Clean user input (security)
function clean($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Get all customers from database
function getAllCustomers($db) {
    $stmt = $db->query("SELECT * FROM customers ORDER BY last_name");
    return $stmt->fetchAll();
}

// SEARCH customers by name or city
function searchCustomers($db, $searchWord) {
    $word = "%$searchWord%"; // % = wildcard (zoekt overal in tekst)
    
    $sql = "SELECT * FROM customers 
            WHERE first_name LIKE ? 
            OR last_name LIKE ? 
            OR city LIKE ? 
            ORDER BY last_name";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([$word, $word, $word]);
    return $stmt->fetchAll();
}

// Get one customer by ID
function getCustomer($db, $id) {
    $stmt = $db->prepare("SELECT * FROM customers WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Add new customer
function addCustomer($db, $data) {
    $sql = "INSERT INTO customers (first_name, last_name, address, city, phone, email) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    return $stmt->execute([
        clean($data['first_name']),
        clean($data['last_name']),
        clean($data['address']),
        clean($data['city']),
        clean($data['phone']),
        clean($data['email'])
    ]);
}

// Update customer
function updateCustomer($db, $id, $data) {
    $sql = "UPDATE customers SET 
            first_name = ?, last_name = ?, address = ?, 
            city = ?, phone = ?, email = ? 
            WHERE id = ?";
    $stmt = $db->prepare($sql);
    return $stmt->execute([
        clean($data['first_name']),
        clean($data['last_name']),
        clean($data['address']),
        clean($data['city']),
        clean($data['phone']),
        clean($data['email']),
        $id
    ]);
}

// Delete customer
function deleteCustomer($db, $id) {
    $stmt = $db->prepare("DELETE FROM customers WHERE id = ?");
    return $stmt->execute([$id]);
}
?>