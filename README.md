# Advanced Employee and Product Management System

## 1. Project Overview

The Advanced Employee and Product Management System is a web-based application developed using CodeIgniter 4 and MySQL.

The application provides role-based access for Admin and Employee users. It allows administrators to manage employees and products, while employees can manage their own products.

The system also provides REST API functionality and report exports in PDF, CSV, and Excel formats.

## 2. Technologies Used

- **Backend:** PHP, CodeIgniter 4
- **Database:** MySQL
- **Frontend:** HTML, CSS, Bootstrap 5.3.8
- **Scripting:** JavaScript, jQuery, AJAX
- **API Testing:** Postman
- **Local Server:** XAMPP
- **Version Control:** Git

## 3. Main Modules

### 3.1 Registration
- Register new employee accounts.
- Validate user details.
- Upload profile images.
- Prevent duplicate usernames and email addresses.
- Store passwords securely using password hashing.

### 3.2 Login and Authentication
- Authenticate users using their login credentials.
- Manage user sessions.
- Provide role-based access.

### 3.3 Dashboard
- Display dashboard information based on the logged-in user's role.

### 3.4 Employee Management
- Admin can view and manage employee records.
- Perform employee-related operations.

### 3.5 Product Management
- Admin can manage all products.
- Employees can manage only their own products.
- Create, view, update, and delete products.
- Upload product images.
- Filter products using dropdowns.
- Search product information.

### 3.6 REST API
- API authentication using Bearer tokens.
- Role-based API access.
- Product CRUD operations.
- Product search, filtering, sorting, and pagination.
- Product ownership validation.

### 3.7 Reports and Export
- Export Employee Listing.
- Export Admin Product Listing.
- Export Employee Product Listing.
- Supported formats:
  - PDF
  - CSV
  - XLSX

## 4. Software Requirements

Install the following software before running the project:

- XAMPP
- PHP 8.2 or above
- MySQL
- Composer
- Visual Studio Code
- Postman (for API testing)

## 5. Installation and Setup

### Step 1: Copy the Project

Copy the project folder into the XAMPP `htdocs` directory.

Example:

`C:\xampp\htdocs\Advanced Task`

### Step 2: Start XAMPP

Start the following services:

- Apache
- MySQL

### Step 3: Create the Database

1. Open phpMyAdmin.
2. Create a database named:

`advanced_managment`

3. Configure the database connection in the `.env` file.

### Step 4: Configure Environment

Open the `.env` file and configure the application environment and database connection.

Example:

```ini
CI_ENVIRONMENT = development

database.default.hostname = localhost
database.default.database = advanced_managment
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
```

Update the database username and password according to your local MySQL configuration.

### Step 5: Install Dependencies

Open the project folder in the terminal and run:

```bash
composer install
```

### Step 6: Run Database Migrations

Execute:

```bash
php spark migrate
```
<!-- if needed to create a new table using migration use this: php spark make:migration CreateEmployeesTable -->


### Step 7: Run Database Seeders

Execute the following command to run all configured seeders:

```bash
php spark db:seed DatabaseSeeder
```
<!-- if needed to create a new seeder use this: php spark make:seeder EmployeeSeeder (run this after the saving the file: php spark db:seed EmployeeSeeder) -->
The project includes:

- `CategorySeeder` — inserts Electronics and Clothing categories only if they do not already exist.
- `AdminSeeder` — checks whether an Admin account exists and skips insertion if one is already present.
- `DatabaseSeeder` — executes both seeders.

### Step 8: Run the Application

Execute:

```bash
php spark serve
```

Open the application in your browser:

http://localhost:8080

## 6. REST API

The application provides REST API endpoints for product management.

API authentication uses Bearer tokens.

To test the API:

1. Open Postman.
2. Select the required HTTP method.
3. Enter the API endpoint.
4. Add the Bearer token under Authorization.
5. Provide the required request data.
6. Send the request and verify the response.

## 7. User Roles

### Admin
- Access the dashboard.
- Manage employees.
- View and manage all products.
- Access reports and exports.
- Use authorized API operations.

### Employee
- Access the dashboard.
- Manage their own products.
- View their own product records.
- Export their product reports.
- Use authorized API operations.

## 8. Project Execution

Make sure Apache and MySQL are running in XAMPP.

Start the CodeIgniter development server:

```bash
php spark serve
```

Access the application:

http://localhost:8080

## 9. Developer Notes

- Configure the `.env` file before running the application.
- Ensure the MySQL database is available.
- Keep uploaded images in the configured uploads directory.
- Use Postman to test REST API endpoints.
- Do not commit sensitive credentials or private API tokens to Git.

---

**Project:** Advanced Employee and Product Management System

**Framework:** CodeIgniter 4