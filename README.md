# EasyEV-Charging Web Application

## Overview
The EasyEV-Charging project is a dynamic web application designed for managing electric vehicle (EV) charging stations. It allows users to register, log in, check in and out at charging locations, and track their charging history. Administrators can manage users and charging locations, ensuring a smooth operation of the charging stations.

## Project Structure
The project is organized into the following directories and files:

- **assets/**: Contains all static assets for the web application.
  - **css/**: CSS files for styling the application.
  - **js/**: JavaScript files for client-side functionality.
  - **images/**: Image files used in the application.

- **classes/**: Contains PHP classes that encapsulate the core functionality.
  - **User.php**: Class for user registration, login, and logout functionalities.
  - **Location.php**: Class for managing charging locations.
  - **ChargingSession.php**: Class for handling the check-in and check-out process.

- **config/**: Contains configuration files.
  - **config.php**: Database configuration settings.

- **controllers/**: Contains controller files that handle user requests.
  - **UserController.php**: Manages user-related actions.
  - **LocationController.php**: Manages actions related to charging locations.
  - **ChargingController.php**: Manages the check-in and check-out process.

- **views/**: Contains the user interface files.
  - **index.php**: Homepage of the web application.
  - **register.php**: User registration form.
  - **login.php**: User login form.
  - **dashboard.php**: User dashboard displaying available charging locations.
  - **admin.php**: Admin dashboard for managing users and locations.

- **database/**: Contains SQL scripts for database setup.
  - **init.sql**: SQL script to create tables and initial data.

## Setup Instructions
1. Clone the repository to your local machine.
2. Navigate to the project directory.
3. Import the `init.sql` file into your MySQL database to set up the necessary tables.
4. Update the `config.php` file with your database connection settings.
5. Open the `index.php` file in your web browser to access the application.

## Usage Guidelines
- Users can register and log in to access their dashboard.
- Users can check in to start charging and check out to finish charging.
- Administrators can manage users and charging locations through the admin dashboard.

## Contributing
Contributions to the EasyEV-Charging project are welcome. Please submit a pull request or open an issue for any enhancements or bug fixes.

## License
This project is licensed under the MIT License.