# Point of Sale App

The Point of Sale (POS) App is designed to simplify small business operations. Built with Laravel and Axios, it ensures smooth transitions and dynamic updates without page reloads. The application provides essential POS features, including product, customer, and invoice management, while integrating secure JWT-based authentication for user management.

## Features

-   **Single-Page Application**: Axios integration ensures seamless transitions and dynamic updates without full page reloads.
-   **JWT Authentication**: Secure authentication for managing users.
-   **Product Management**: Add, update, and delete products with ease.
-   **Customer Management**: Maintain customer records with CRUD operations.
-   **Invoice Management**: Generate, view, and delete invoices efficiently.
-   **Dashboard Insights**: Visual summaries of key business data, including products, customers, sales, and invoices.
-   **Dynamic Tables**: Real-time updates for listings of products, customers, and invoices.

## Getting Started

Follow these instructions to set up the project.

### Installation

1. **Clone the repository:**

    ```shell
    git clone git@github.com:Fabdoc27/POS-System.git
    ```

2. **Navigate to the project directory:**

    ```shell
    cd "POS-System"
    ```

3. **Install PHP dependencies:**

    ```shell
    composer install
    ```

4. **Create the environment file:**

    ```shell
    cp .env.example .env
    ```

5. **Set up your `.env` file:**

    ```env
    JWT_KEY=your_jwt_secret
    ```

6. **Generate the application key:**

    ```shell
    php artisan key:generate
    ```

7. **Run database migrations:**

    ```shell
    php artisan migrate
    ```

8. **Start the local development server:**

    ```shell
    php artisan serve
    ```
