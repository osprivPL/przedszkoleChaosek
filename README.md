# 🧸 Przedszkole Chaosek

A full-featured **kindergarten management web app** (PHP + MySQL) with role-based dashboards for the principal, teachers and parents — including recruitment, lessons, homework, menus, news articles and messaging.

![PHP](https://img.shields.io/badge/PHP-8-777bb4?style=flat-square&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-innoDB-4479a1?style=flat-square&logo=mysql&logoColor=white)
![Composer](https://img.shields.io/badge/Composer-PHPMailer-565656?style=flat-square&logo=composer&logoColor=white)
![JS](https://img.shields.io/badge/Vanilla%20JS-CSS-f7df1e?style=flat-square)

## 📖 About

**Przedszkole Chaosek** is a school-project web application for running a kindergarten ("przedszkole"). Users log in and get a panel tailored to their role:

- 👩‍💼 **Dyrektor (principal)** — manages users and permissions, recruitment (`oczekujace`), lesson plans, articles, groups and communications for the whole kindergarten
- 👩‍🏫 **Nauczyciel (teacher)** — manages their group's children, lessons, homework, schedules and group announcements
- 👨‍👩‍👧 **Rodzic (parent)** — views their child's information: weekly menu (*jadłospis*), lessons, homework, messages and news

Email notifications are sent through PHPMailer; authentication uses PHP sessions.

## ✨ Features

- 🔐 Login with roles and granular permissions (`uprawnienia`)
- 🧒 Child & group management (`dzieci`, `grupy`)
- 📅 Lesson schedule and hourly slots (`lekcje`, `godzinylekcyjne`, `plan_lekcji`)
- 📚 Homework tracking (`pracedomowe`)
- 🍽️ Weekly meal menu per week (`jadlospis`)
- 📰 News/articles with image attachments (`artykuly`)
- 💬 Internal messaging between roles (`wiadomosci`)
- 📝 Recruitment flow with pending applications (`rekrutacja.php`, `oczekujace`)
- 📣 Kindergarten-wide and per-group announcements (`komunikaty`)
- ✉️ Email sending via PHPMailer

## 🗄️ Database

MySQL schema ships with the repo:

- `przedszkole.sql` — full dump / reference database
- `bazaDoPrezentacji.sql` — demo dataset for presentations

Core tables: `uzytkownicy`, `dzieci`, `grupy`, `lekcje`, `godzinylekcyjne`, `plan_lekcji`, `pracedomowe`, `jadlospis`, `artykuly`, `komunikaty`, `wiadomosci`, `oczekujace`, `uprawnienia`.

## 📁 Structure

```
przedszkoleChaosek/
├── index.php               # Login + main panels
├── rekrutacja.php          # Recruitment module
├── rekrutacjaCompleted.php
├── mailCode.php            # PHPMailer email helper
├── electronicDiary/        # Role inboxes
│   ├── inbox.php
│   ├── parents.php
│   ├── principle.php
│   └── teacher.php
├── models/                 # PHP classes (User)
├── scripts/
│   ├── php/                # Server-side helpers
│   └── js/                 # Client-side UI logic
├── styles/                 # CSS
├── assets/                 # Images/media
├── przedszkole.sql         # Database dump
└── bazaDoPrezentacji.sql   # Demo data
```

## 🚀 Getting Started

### Prerequisites

- PHP 8+ with `mysqli` and `json` extensions
- MySQL / MariaDB
- Composer (for PHPMailer)

### Setup

```bash
git clone https://github.com/osprivPL/przedszkoleChaosek.git
cd przedszkoleChaosek

composer install

# Import the database
mysql -u root -p -e "CREATE DATABASE przedszkole CHARACTER SET utf8mb4;"
mysql -u root -p przedszkole < przedszkole.sql

# Start a local dev server
php -S localhost:8000
```

Open http://localhost:8000 and log in with one of the demo accounts (see `uzytkownicy.txt`). Configure SMTP credentials in `mailCode.php` if you want real emails.

## ⚠️ Known Issues

This was a school project developed under deadline pressure — see `DoZrobienia.txt` (to-do) and `crashTest.txt` (test notes) for open items: CSS polish, animations, per-group announcements, edge cases with empty states. PRs welcome 🙂

## 📄 License

School project — all rights reserved.
