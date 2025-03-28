mariadb
 
 CREATE TABLE users (
    ->    account_number INT AUTO_INCREMENT PRIMARY KEY,
    ->     first_name VARCHAR(50) NOT NULL,
    ->     last_name VARCHAR(50) NOT NULL,
    ->     username VARCHAR(50) NOT NULL UNIQUE,
    ->     password VARCHAR(32) NOT NULL,
    ->     user_type ENUM('Admin', 'Employee') NOT NULL,
    ->     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    -> );
