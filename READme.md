# CyberSecure Inc. – Client Intelligence Dashboard

Welcome to CyberSecure Inc. Client Intelligence Dashboard, a modern web interface for monitoring and managing clients, projects, contracts, and invoices of a global cybersecurity firm. This dashboard is designed to handle sensitive and high-profile clients, including multinational corporations and government agencies.

## Project Overview

This project allows real-time client monitoring, data management, and operational transparency. Features include:

View active clients and their associated projects.

Search clients by name or ID with instant AJAX-powered results.

View client details in a dynamic modal popup.

Delete client records safely from the database.

Pagination for handling large datasets efficiently.

Loader and spinner animation to indicate processing and AJAX activity.

The design combines modern UI aesthetics using Google Fonts (Orbitron and Inter) and Bootstrap 5, paired with a cyber-themed color palette for a sleek, futuristic feel.

##  Database Structure

The database, cybersecure_inc, consists of five main tables:

Clients – Stores client information.

Contacts – Stores client contact persons.

Projects – Projects associated with each client.

Contracts – Contracts linked to projects.

Invoices – Tracks invoices and payment status.

Note: All child tables have foreign key constraints, so records must be inserted in the order: clients → projects → contracts → invoices.

Sample SQL to create tables and insert initial data is provided in db_seed.sql.

##  Technologies Used

Frontend: HTML5, CSS3, Bootstrap 5, Google Fonts (Orbitron & Inter)

Backend: PHP 8, MySQL

AJAX: jQuery for asynchronous requests

Database: MySQL with foreign key relationships

##  Features
Client Management

Display a table of clients with:

Client ID

Name

Industry

Country

Registration date

Action buttons (View / Delete)

Responsive design for all screen sizes.

Custom loader overlay for asynchronous data requests.

CRUD Functionality

View Client Details: Fetch client info from database without page reload.

Delete Client Records: Remove clients safely from all related tables (projects, contracts, invoices).

UX Enhancements

Dark cyber-themed UI with neon accent colors.

Hover effects on cards and table rows.

Spinner animation with glowing text during data processing.

##  Setup Instructions

Clone the repository to your local machine:

git clone https://github.com/Dorcastunmise/CyberSecure-Dashboard.git


Import the database:

Open phpMyAdmin or MySQL CLI.

Create database cybersecure_inc.

Import db_seed.sql to populate tables.

Configure PHP files:

Ensure database credentials (host, user, password, dbname) in PHP scripts match your local setup.

Open cyber.php in a browser via local server (XAMPP, WAMP, or similar).

Test search, view, delete, and pagination features.

##  File Structure
/Ajax/                   # PHP scripts for AJAX requests
   extract_clients.php
   view_client.php
   delete_client.php
/cyber.php               # Main dashboard interface
/cyber.css               # Styles for dashboard UI
/db_seed.sql             # Database schema + sample data

##  Notes

Foreign Key Awareness: Always insert parent table records before children to avoid errors (#1452 foreign key).

Security: Use prepared statements in PHP to prevent SQL injection.

Scalability: AJAX + pagination ensures smooth performance for thousands of clients.

##  Future Enhancements

User authentication and role-based access.

Export client or project data to CSV/PDF.

Real-time notifications for pending invoices.

Analytics dashboard for client/project performance.

## Developed By

Oluwatunmise Alimi
Fullstack Software Engineer
Portfolio
 | GitHub
 | LinkedIn

Built with precision, performance, and futuristic aesthetics in mind.