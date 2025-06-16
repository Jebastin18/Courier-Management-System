# 🚚 Courier Management System

A complete Courier Management System built with PHP, MySQL, Bootstrap, HTML, and CSS. This system allows users and admins to manage courier branches, parcels, tracking, and generate invoices and reports with an easy-to-use interface.

---

## 🧩 Features

### ✉️ Parcel Management
- Create and edit parcel details
- Assign parcel status (In Transit, Delivered, etc.)
- Generate parcel reports

### 🧑‍💼 User & Staff Management
- Create and manage users (admin, staff)
- Edit staff details
- Assign and track staff sales

### 🏢 Branch Management
- Create and update courier branches
- Generate branch-wise reports

### 🧾 Sales & Invoices
- Record sales and generate sales reports
- Create and view invoices

### 📦 Tracking System
- Track parcels using tracking number
- Email customers tracking updates (`send_tracking_link.php`)

### 🔐 Authentication
- Admin login and logout
- Session-controlled dashboard access

---

## ⚙️ Technologies Used

- **Frontend**: HTML5, CSS3, Bootstrap 5
- **Backend**: PHP
- **Database**: MySQL
- **Others**: JavaScript (AJAX), Email functionality (PHP mail)

---

## 🔐 Default Admin Credentials

- **Username**: `admin`
- **Password**: 123456

- ## 🔐 Default Admin Credentials

- **Username**: `vasanth`
- **Password**: 123456

---

## 🗂️ File Structure Overview

```bash
/courier-management-system
│
├── db.php                    # Database connection
├── login.php                 # Login logic
├── logout.php                # Logout session
├── home.php                  # Dashboard home
├── sidebar.php               # Navigation
├── topbar.php                # Header
├── maindash.php              # Main dashboard page
│
├── Branch Management
│   ├── create_branch.php
│   ├── branch_edit.php
│   ├── update_branch.php
│   ├── branch_report.php
│
├── Parcel Management
│   ├── parsel_create.php
│   ├── edit_parcel.php
│   ├── update_parcel.php
│   ├── parsel_report.php
│   ├── update_parcel_status.php
│   ├── update_status_history.php
│
├── Tracking System
│   ├── track.php
│   ├── track_parcel.php
│   ├── fetch_tracking.php
│   ├── send_tracking_link.php
│
├── Sales & Invoice
│   ├── sales.php
│   ├── sales_report.php
│   ├── generate_invoice.php
│   ├── staff_sales_report.php
│
├── User Management
│   ├── user_create.php
│   ├── user_edit.php
│   ├── update-users.php
│   ├── user_report.php
│
├── Fetch Files (AJAX)
│   ├── fetch_users.php
│   ├── fetch_branches.php
│   ├── fetch_staff.php
│   ├── fetch_parsel.php
│   ├── fetch_sales.php
│   ├── fet_branches.php
│   ├── fetch_status.php
│   ├── fetch_parcel_item.php
│   ├── fetch_staff_sales.php
│   ├── fetch_branch_update.php
│   ├── fetch_update.php
│   ├── fetch_sales_report.php
│
├── style.css                 # Styling
├── logo.png                  # System logo
└── courier_db.sql            # SQL dump file for MySQL
🛠️ Installation & Setup
Clone or download the project

bash
Copy
Edit
git clone https://github.com/yourusername/courier-management-system.git
Import the Database

Use phpMyAdmin to import courier_db.sql into a new MySQL database, e.g., courier_db.

Configure Database Connection

Open db.php and update with your DB credentials:

php
Copy
Edit
$conn = new mysqli("localhost", "root", "", "courier_db");
Run Locally

Place the project folder in htdocs (if using XAMPP).
http://localhost:8080/courier/login_form.php
📸 Screenshots (optional)
Add screenshots of the dashboard, parcel form, tracking page, and sales report here.

🧑 Author
Jebastin Raj
📧 Email: jebastinr817@gmail.com
🌐 GitHub: github.com/Jebastin18


Would you like a matching `database.sql` description or a visual dashboard design too? Let me know!
