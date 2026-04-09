-- Database: naw_database
-- Tabel: customers

CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    address VARCHAR(100) NOT NULL,
    postal_code VARCHAR(10) NOT NULL,
    city VARCHAR(50) NOT NULL,
    phone VARCHAR(15),
    email VARCHAR(100)
);

INSERT INTO customers (first_name, last_name, address, postal_code, city, phone, email) VALUES 
('Jan', 'Jansen', 'Dorpsstraat 12', '1234 AB', 'Amsterdam', '0612345678', 'jan@test.nl'),
('Maria', 'Pietersen', 'Kerkweg 45', '5678 CD', 'Rotterdam', '0687654321', 'maria@test.nl');