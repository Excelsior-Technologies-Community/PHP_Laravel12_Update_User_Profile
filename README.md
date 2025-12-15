Skip to content
Navigation Menu
Excelsior-Technologies-Community
PHP_Laravel12_Update_User_Profile

Type / to search
Code
Issues
Pull requests
Actions
Projects
Wiki
Security
Insights
Settings
PHP_Laravel12_Update_User_Profile
/
README.md
in
main

Edit

Preview
Indent mode

Spaces
Indent size

4
Line wrap mode

Soft wrap
Editing README.md file contents
Selection deleted
1
2
3
4
5
6
7
8
9
10
11
12
13
14
15
16
17
18
19
20
21
22
23
24
25
26
27
28
29
30
31
32
33
34
35
36
37
38
39
40
41
42
43
44
45
46
47
48
49
50
51
52
53
54
55
56
57
58
59
60
61
62
63
64
65
66
67
68
69
70
71
72
73
74
75
76
77
78
79
80
81
82
83
84
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
