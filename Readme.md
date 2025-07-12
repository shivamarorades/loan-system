# 💳 Loan Management System (CodeIgniter 3)

A robust web-based Loan Management System built with **CodeIgniter 3**, allowing users to apply for loans, view loan statuses, and repay loans. Includes a secured admin panel for managing user applications.
http://localhost/loan_system
---

## 🚀 Features

### 👤 User Panel
- ✅ User registration with OTP verification (Not by any api mailer)
- ✅ Secure login/logout 
- ✅ Apply for loans (amount, purpose, tenure)
- ✅ Track loan status (Pending / Approved / Rejected / No Loans)
- ✅ Repay approved loans

### 🛡 Admin Panel
- ✅ Admin login/logout
- ✅ View all user loan applications
- ✅ Approve, Reject
- ✅ Filter users applications by loan status

---

## 🛠 Tech Stack

| Layer     | Technology         |
|-----------|--------------------|
| Backend   | PHP (CodeIgniter 3)|
| Frontend  | HTML, Bootstrap, jQuery |
| Database  | MySQL              |
| Security  | Bcrypt, Session Auth |

---

## 📦 Installation

### ⚙️ Requirements

- PHP 8.x
- MySQL 
- Apache
- [XAMPP](https://www.apachefriends.org/)
- Git (optional for version control)

---

### 🧪 Step-by-Step (Localhost with XAMPP)

#### 1. 📁 Clone or Copy Project

If using Git:

```bash
cd /c/xampp/htdocs
git clone https://github.com/shivamarorades/loan-system.git

or
Make a folder name loan_system in htdocs folder
Manually Extract to C:/xampp/htdocs/loan_system


Admin username and password
username: admin123
password: Adminpeer134

Import loan_db in phpmyadmin before importing please make database name : loan_db

If you face any problem please replace httpd.conf


