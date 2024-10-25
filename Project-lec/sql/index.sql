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


    CREATE TABLE registrants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

ALTER TABLE events ADD COLUMN location VARCHAR(255);

ALTER TABLE events ADD COLUMN image VARCHAR(255);

ALTER TABLE registrations DROP FOREIGN KEY registrations_ibfk_2;

ALTER TABLE registrations
ADD CONSTRAINT registrations_ibfk_2 FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE;

SELECT
    ->     r.user_id,
    ->     r.event_id,
    ->     r.registration_date,
    ->     u.username as user_name,
    ->     u.email
    -> FROM registrations r
    -> INNER JOIN users u ON r.user_id = u.id;

CREATE TABLE export_registrants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),  -- Mengacu ke tabel users
    FOREIGN KEY (event_id) REFERENCES events(id)  -- Mengacu ke tabel events
);
