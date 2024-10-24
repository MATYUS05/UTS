CREATE DATABASE Event;

USE Event;

CREATE TABLE users (
    ->     id INT AUTO_INCREMENT PRIMARY KEY,
    ->     username VARCHAR(50) NOT NULL UNIQUE,
    ->     password VARCHAR(255) NOT NULL,
    ->     email VARCHAR(100) NOT NULL UNIQUE,
    ->     role ENUM('admin', 'user') DEFAULT 'user'
    -> );


CREATE TABLE registrations (
    ->     id INT AUTO_INCREMENT PRIMARY KEY,
    ->     user_id INT,
    ->     event_id INT,
    ->     registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ->     FOREIGN KEY (user_id) REFERENCES users(id),
    ->     FOREIGN KEY (event_id) REFERENCES events(id)
    -> );

 CREATE TABLE events (
    ->     id INT PRIMARY KEY AUTO_INCREMENT,
    ->     event_name VARCHAR(255),
    ->     event_date DATE,
    ->     event_time TIME,
    ->     event_location VARCHAR(255),
    ->     description TEXT,
    ->     max_participants INT,
    ->     image_url VARCHAR(255),
    ->     status ENUM('open', 'closed', 'canceled')
    -> );