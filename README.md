# Flah-R Maintenance Management System

A comprehensive web application built with Laravel to manage vehicle maintenance contracts, client requests, representative assignments, and reporting.

![Screenshot of the Admin Dashboard](./screenshots/admin-dashboard.png)  <!-- Add a screenshot later -->

---

## ✨ Features

*   **Role-Based Access Control:** Separate dashboards and permissions for Admins, Clients, and Service Representatives.
*   **Contract Management:** Admins can create, manage, and import contracts, each with its own specific list of allowed products/services.
*   **Client Portal:** Clients can log in, view their contract details, and submit new maintenance requests using a dynamic product selection form.
*   **Request Workflow:** Admins can review new requests, assign them to available representatives, and track their status from "Pending" to "Completed".
*   **Representative Portal:** Representatives can view their assigned tasks, upload proof-of-work documents (receipts), and mark tasks as complete.
*   **Automated Notifications:** Automatic email and WhatsApp notifications to alert representatives and clients of new assignments and status changes.
*   **Data Import/Export:** Admins can bulk-import contracts and products via Excel sheets, with automatic price calculations.
*   **Reporting:** A dynamic reporting center for visualizing maintenance statistics and representative performance.

---

## 🛠️ Technology Stack

*   **Backend:** PHP 8.2, Laravel 12
*   **Frontend:** Blade Templates, CSS, JavaScript (jQuery, Select2)
*   **Database:** MySQL
*   **External APIs:**
    *   Mailgun for transactional emails.
    *   Twilio for WhatsApp notifications.
*   **Key Packages:**
    *   `barryvdh/laravel-dompdf` for PDF generation.
    *   `maatwebsite/excel` for Excel imports.
    *   `twilio/sdk` for Twilio API integration.

---

## 🚀 Local Setup and Installation

Follow these steps to set up the project on your local machine.

### Prerequisites

*   PHP >= 8.2
*   Composer
*   Node.js & NPM
*   A local web server environment (e.g., XAMPP, WAMP, Laragon)
*   MySQL Database

### Installation Steps

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/YourUsername/flah-r-maintenance-system.git
    cd flah-r-maintenance-system
    ```

2.  **Install PHP dependencies:**
    ```bash
    composer install
    ```

3.  **Install JavaScript dependencies:**
    ```bash
    npm install
    npm run dev
    ```

4.  **Create your environment file:**
    ```bash
    cp .env.example .env
    ```

5.  **Generate a new application key:**
    ```bash
    php artisan key:generate
    ```

6.  **Configure your `.env` file:**
    Open the `.env` file and set up your database connection (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) and add your API keys for Mailgun and Twilio.

7.  **Run database migrations:**
    This will create all the necessary tables.
    ```bash
    php artisan migrate
    ```

8.  **Create the storage link:**
    This makes uploaded files publicly accessible.
    ```bash
    php artisan storage:link
    ```

9.  **Serve the application:**
    You can use a local server like XAMPP or run the built-in Laravel server:
    ```bash
    php artisan serve
    ```
    Your application will now be running at `http://localhost:8000`.

---

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a pull request or open an issue for any bugs or feature suggestions.
