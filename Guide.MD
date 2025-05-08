# EasyEV-Charging Web Application Assignment

This project involves building a dynamic website for managing electric vehicle (EV) charging stations, using Object-Oriented PHP and MySQLi. The project should be divided into two parts: Part 1 involves the development of a web application for EV charging management, and Part 2 involves creating a WordPress website with custom PHP plugins.

## Project Overview

The EasyEV-Charging web application allows users to manage EV charging stations at various locations. There are two types of users: Administrators and Users. The web app should allow users to register, log in, check-in and check-out at charging locations, and track their charging history. Administrators can manage charging locations, user details, and monitor the system.

The goal of the assignment is to create a functional, dynamic website using PHP, MySQLi, and WordPress, with user-friendly interfaces and smooth navigation between different features.

## Part 1: EasyEV-Charging Web Application (25 Marks)

### Key Features

**User Management:**
- Admin and User roles with registration, login, and logout functionalities.
- Users can check in to charge their EVs and check out once charging is complete.
- Admins can manage users and view details about their charging activity.

**Charging Location Management:**
- Users can view all available charging locations.
- Admins can add, edit, or delete charging locations.
- Admins can list charging locations based on availability (full/available).

**Charging Process:**
- Users can check in to start charging and check out to finish charging, with cost details displayed.
- Admins can monitor and manage the charging stations.

### Database Structure

- **Users table**: user_id, name, phone, email, type (admin/user).
- **Charging Locations table**: location_id, description, number_of_stations, cost_per_hour.

### File Structure

```
/assets                 # Contains CSS, JS, images, and other assets
/classes                # Contains PHP classes
    /User.php            # User class for registration and login functionality
    /Location.php        # Location class for managing locations
    /ChargingSession.php # Charging session class for check-in and check-out
/config                 # Database configuration
    /config.php          # MySQLi connection configuration
/controllers            # Contains PHP files to handle user requests
    /UserController.php # Handles user registration, login, and logout
    /LocationController.php # Handles location management (add/edit/delete)
    /ChargingController.php # Handles check-in/check-out process
/views                  # Contains HTML and PHP files for user interface
    /index.php           # Homepage
    /register.php        # User registration page
    /login.php           # User login page
    /dashboard.php       # User dashboard showing locations and status
    /admin.php           # Admin dashboard to manage locations and users
/database               # Contains SQL scripts for database setup
    /init.sql            # SQL script to create tables and initial data
```

### Database Script

Use the following script to set up your database tables.

```sql
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
```

### PHP Class and Script Guidelines

**User Class:**
- Implements registration, login, and logout functionality.
- Should validate user input, check for existing users, and hash passwords using PHP's password_hash function.

**Location Class:**
- Handles operations for adding, editing, and deleting charging locations.
- Should include methods for listing locations based on availability (stations available or full).

**ChargingSession Class:**
- Handles check-in and check-out functionality.
- Calculates the total cost based on the number of hours the user charges.

**Controllers:**
- UserController: Manages user registration, login, and logout actions.
- LocationController: Manages location creation, updates, and listing.
- ChargingController: Manages check-in, check-out, and cost calculation.

## Part 2: WordPress Website (5 Marks)

### Key Features

**Company Website:**
- Create a simple company website with Home, About Us, and Contact Us pages.
- The design and theme should be customized to match your company's branding.

**WordPress Plugin:**
- Write a custom PHP plugin that adds functionality to your website.
- This plugin can modify or extend a theme feature, add a custom shortcode, or modify a default WordPress behavior.

**Video Explanation:**
- Record a 2-3 minute video explaining how you customized the WordPress theme and the PHP code written for the plugin.

### Plugin Guidelines

The custom plugin can:
- Add a new widget or shortcode.
- Modify how posts are displayed or add new features to the backend.

### Guidelines for Copilot:

- Use MySQLi for all database interactions. Avoid PDO.
- Focus on Object-Oriented PHP: Use classes and methods to structure your application.
- Ensure proper validation: Validate all user inputs both client-side (JavaScript) and server-side (PHP).
- Handle sessions securely: Use PHP sessions for user login and authentication.

## Submission Requirements

**Part 1:**
- A ZIP file containing all PHP files, database scripts, and the README file.
- A PDF report including your name, student number, email, a description of each file/class, and the database schema.

**Part 2:**
- A short video explaining your WordPress customizations, theme changes, and PHP plugin functionality.