🌾 AgroConnect – Farmer to Consumer Marketplace

A web-based marketplace designed to connect Indian farmers directly with consumers, eliminating middlemen and promoting fair trade, transparency, and digital empowerment in rural communities.

📌 Overview

AgroConnect is an e-commerce platform that enables farmers to list and sell agricultural products directly to customers. The system provides an easy-to-use interface for browsing products, managing orders, and handling user accounts with role-based access (Customer, Farmer, Admin).

🧩 Features
👨‍🌾 Farmer Features

Upload and manage product listings

Update prices, quantities, and descriptions

View order requests and notifications

🛍️ Customer Features

Browse available agricultural products

Add items to cart and place orders

View order status and history

🛠️ Admin Features

Monitor user activities

Manage disputes and product authenticity

Generate system reports

🔐 Authentication

Secure login & registration (PHP Sessions)

Role-based dashboard access

🏗️ How It Works

Users Register/Login (Customer or Farmer)

Farmers Upload Products (Name, image, price, stock)

Customers Browse Products and add items to the cart

Orders Are Placed and stored in the database

Farmers Receive Order Notifications

Admin Monitors Platform and handles issues

🖥️ Tech Stack
Frontend

HTML

CSS

JavaScript

Bootstrap

Backend

PHP

Database

MySQL

Environment

XAMPP / Apache Server

PHPMyAdmin

📁 Project Structure
/project-folder
│── index.php
│── login.php
│── register.php
│── cart.php
│── product.php
│── seller.php
│── order_history.php
│── profile.php
│── logout.php
│── /uploads (product images)
│── /css/styles.css
│── /js/script.js
│── /database/marketplace.sql

⚙️ Setup Instructions
1. Install XAMPP

Download from: https://www.apachefriends.org/

2. Move Project Files

Place the project folder inside:

xampp/htdocs/

3. Start Apache & MySQL

Open XAMPP → Start Apache and MySQL

4. Import Database

Go to: http://localhost/phpmyadmin

Create database: marketplace

Import marketplace.sql

5. Run the Project

Open browser →

http://localhost/project-folder/

🧪 Testing

Verified login functionality

Product upload/edit/delete tested

Cart and order placement tested

SQL injection and session security validated

🚀 Future Enhancements

Online payment integration (UPI/Card)

Delivery tracking system

Mobile app version

Multi-language support

Review and rating system for farmers

🤝 Contributing

Contributions are welcome!
Feel free to submit pull requests or report issues.

📜 License

This project is for educational and academic purposes.
