CREATE DATABASE easy_ev_charging;

USE easy_ev_charging;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    phone VARCHAR(15),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    type ENUM('admin', 'user') NOT NULL
);

CREATE TABLE locations (
    location_id INT AUTO_INCREMENT PRIMARY KEY,
    description VARCHAR(255),
    number_of_stations INT,
    cost_per_hour DECIMAL(10, 2)
);

CREATE TABLE charging_sessions (
    session_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    location_id INT,
    check_in_time DATETIME,
    check_out_time DATETIME,
    total_cost DECIMAL(10, 2),
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (location_id) REFERENCES locations(location_id)
);