CREATE DATABASE IF NOT EXISTS kayaking CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE kayaking;

CREATE TABLE IF NOT EXISTS inquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    trip_date DATE NOT NULL,
    time_start VARCHAR(20) NOT NULL,
    time_end VARCHAR(20) NOT NULL,
    message TEXT,
    experience_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

INSERT INTO users (username, password) VALUES ('pxkayaking', '1234')
    ON DUPLICATE KEY UPDATE password='1234';