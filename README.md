# PHP_Laravel12_Update_User_Profile

---
A beginner-friendly Laravel 12 project to update user profile including avatar, phone, city, and password. Users can view and update their information securely with authentication.

Project Overview
---
This project demonstrates:

User registration and login using Laravel authentication.

Update user profile including:
---
Name

Email

Avatar upload

Phone number

City

Password change (optional)

Display current profile information in the form.

Show success and error messages on update.

Profile link in navbar for logged-in users.

# Step‑by‑Step Installation

---
Step 1 — Create New Laravel Project

Open terminal and run:
```
composer create-project laravel/laravel PHP_Laravel12_Update_User_Profile "12.*"
cd PHP_Laravel12_Update_User_Profile
```
This creates a fresh Laravel 12 project.


Step:2 Setup Database

Open .env file and update your MySQL credentials:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE= laravel12_update_user_profile
DB_USERNAME=root
DB_PASSWORD=
```
Save file. Then run:
```
php artisan migrate
```
This creates users, password_resets, failed_jobs, personal_access_tokens tables.


Step:3 Install Auth (UI Scaffold)

Install UI scaffolding for login/register pages:

```
composer require laravel/ui
php artisan ui bootstrap --auth
npm install
npm run build
```
This generates:

Login, register, dashboard views.

Auth routes in routes/web.php.



Step:4 Add Profile Fields in Users Table

Create migration:
Use Control + Shift + m to toggle the tab key moving focus. Alternatively, use esc then tab to move to the next interactive element on the page.
No file chosen
Attach files by dragging & dropping, selecting or pasting them.
