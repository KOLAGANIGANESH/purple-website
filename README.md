eCommerce Website with Role-Based Access Control (RBAC)
This is a simple eCommerce website built using PHP and MySQL, featuring Role-Based Access Control (RBAC). It supports three user roles: Admin, Vendor, and Customer, each with specific access privileges. The project is hosted locally using XAMPP.

Features
1. User Roles
Admin:
Manage users and roles.
Oversee all products and orders.
Vendor:
Add, edit, and manage their products.
View orders related to their products.
Customer:
Browse products.
Place and track orders.
2. Functionalities
Authentication:
Secure login and registration.
Password encryption using password_hash().
Product Management:
Vendors can add, edit, and delete products.
Admin can view all products.
Order Management:
Customers can place orders and track their status.
Vendors can view orders related to their products.
Admin can manage all orders.
RBAC:
Role-specific dashboards and functionalities.
Unauthorized access prevention.
3. Technology Stack
Frontend: HTML, CSS, JavaScript
Backend: PHP
Database: MySQL
Server: XAMPP (Apache + MySQL)
Installation Guide
1. Prerequisites
XAMPP installed on your system.
Basic knowledge of PHP, MySQL, and HTML.
2. Setup Instructions
Clone this repository:
bash
Copy code
git remote add origin https://github.com/KOLAGANIGANESH/purple-website.git
Move the project to the XAMPP htdocs folder:
bash
Copy code
mv ecommerce-rbac C:/xampp/htdocs/
Start XAMPP and activate:
Apache
MySQL
Create a database:
Open phpMyAdmin (http://localhost/phpmyadmin).
Create a new database named ecommerce_db.
Import the SQL file from the database folder:
bash
Copy code
database/ecommerce_db.sql
Configure the database connection:
Edit includes/db.php:
php
Copy code
$host = "localhost";
$user = "root";
$password = "";
$database = "ecommerce_db";
Open the application in your browser:
arduino
Copy code
http://localhost/ecommerce-rbac
Project Structure
bash
Copy code

How to Use
Admin:
Login with the admin role to manage users and oversee the platform.
Vendor:
Add and manage products via the vendor dashboard.
Customer:
Register, browse products, and place orders.
Future Enhancements
Implement payment gateway integration.
Add a product review and rating system.
Enhance the UI using a frontend framework (e.g., Bootstrap).
Add an API for mobile integration.
