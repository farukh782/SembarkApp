# 🚀 URL Shortener - Laravel Project

A role-based URL Shortener application built with **Laravel 10**.  
This project demonstrates **Authentication, Authorization, Company/User Management, Invitations, and URL Shortening** with restrictions based on user roles.  

---

## 📌 Features

- ✅ Authentication (Login / Logout)
- ✅ Role-based access control:
  - SuperAdmin, Admin, Member, Sales, Manager
- ✅ Company-based multi-user system
- ✅ SuperAdmin Seeder created using raw SQL
- ✅ Invitation system with role restrictions
- ✅ URL Shortening with restrictions:
  - **SuperAdmin, Admin, Member cannot create short URLs**
  - **Sales, Manager can create short URLs**
  - Admin can only see URLs not created in their company
  - Member can only see URLs not created by themselves
- ✅ Non-public URL resolving (redirect to original URL only inside the app)
- ✅ Tests for rules & restrictions

---

## 🛠️ Tech Stack

- **PHP**: 8.2+
- **Laravel**: 10.x
- **Database**: MySQL / SQLite
- **Auth**: Laravel Breeze / Sanctum
- **Frontend**: Blade Templates (Barebones HTML)

---

## ⚙️ Installation & Setup

###  Clone Repository
```bash
git clone https://github.com/farukh782/SembarkApp.git
cd url-shortener


 Install Dependencies
composer install
npm install && npm run dev

Setup Environment

Copy .env.example → .env

cp .env.example .env


Update DB credentials in .env:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=url_shortener
DB_USERNAME=root
DB_PASSWORD=Farukh@123

Generate App Key
php artisan key:generate

 Run Migrations & Seeders
php artisan migrate --seed


Seeder will create a SuperAdmin:

Email: superadmin@example.com

Password: password123

Start the Server
php artisan serve


Now visit 👉 http://localhost:8000

Acceptable AI Usage Policy

1). I used ChatGPT to help write and structure the README.md file.

2).I used ChatGPT to resolve issues related to Laravel packages during setup.

3).I used ChatGPT to assist with testing-related tasks.
