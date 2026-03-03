# 📝 Daily Task Tracker

![Status](https://img.shields.io/badge/Status-In%20Development-orange)
![Laravel](https://img.shields.io/badge/Laravel-12.x-red)
![Tailwind](https://img.shields.io/badge/Tailwind_CSS-v4-blue)
![License](https://img.shields.io/badge/license-MIT-green)

A modern, efficient task management application built with **Laravel 12** and **Tailwind CSS v4**. This application helps users organize their daily lives by managing tasks, categories, and complex recurring schedules.

> ⚠️ **Note:** This project is currently **under active development**. Features are subject to change, and the UI is being refined.

## ✨ Key Features

*   **🔐 Secure Authentication**
    *   User registration and login.
    *   Password reset and email verification flows.
    *   Profile management.
*   **📂 Category Management**
    *   Organize tasks into custom categories.
    *   Full CRUD operations for categories.
*   **✅ Task Management**
    *   Create, edit, and delete tasks.
    *   Set due dates and descriptions.
    *   Toggle completion status instantly.
    *   Filter tasks by status (Completed/Incomplete), Category, and Date Range.
*   **cw Recurring Tasks (Advanced)**
    *   Set up tasks that repeat automatically.
    *   **Frequencies supported:**
        *   Daily
        *   Weekdays (Mon-Fri)
        *   Weekly (Select specific days, e.g., Mon, Wed, Fri)
        *   Monthly (Select specific day of the month)
*   **🎨 Modern UI**
    *   Built with **Tailwind CSS v4**.
    *   Fully responsive design.
    *   **Dark Mode** support built-in.
    *   Interactive elements powered by **Alpine.js**.

## 🛠️ Tech Stack

*   **Backend:** Laravel 12, MySQL
*   **Frontend:** Blade Templates, Tailwind CSS v4, Alpine.js
*   **Build Tool:** Vite
*   **Database:** MySQL (with UUID support for models)

## 🚀 Getting Started

Follow these steps to set up the project locally:

### Prerequisites
*   PHP 8.2 or higher
*   Composer
*   Node.js & NPM
*   MySQL

### Installation

1.  **Clone the repository**
    ```bash
    git clone https://github.com/yourusername/daily-task-tracker.git
    cd daily-task-tracker
    ```

2.  **Install PHP dependencies**
    ```bash
    composer install
    ```

3.  **Install Frontend dependencies**
    ```bash
    npm install
    ```

4.  **Environment Setup**
    Copy the example environment file and configure your database credentials:
    ```bash
    cp .env.example .env
    ```
    *Update `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` in the `.env` file.*

    *For email testing, it is recommended to use [Mailpit](https://github.com/axllent/mailpit) and configure `MAIL_HOST=127.0.0.1` and `MAIL_PORT=1025`.*

5.  **Generate Application Key**
    ```bash
    php artisan key:generate
    ```

6.  **Run Migrations**
    ```bash
    php artisan migrate
    ```

7.  **Build Assets & Run Server**
    ```bash
    npm run dev
    # In a separate terminal:
    php artisan serve
    ```

## 🗺️ Roadmap

- [x] Basic Task CRUD
- [x] Category System
- [x] Recurring Task Logic
- [ ] Dashboard Statistics & Charts
- [ ] Email Notifications for due tasks
- [ ] Drag-and-drop task ordering
- [ ] API Implementation

## 🤝 Contributing

Contributions are welcome! Since this is a learning project/work in progress, please feel free to open issues or submit pull requests for improvements.

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
