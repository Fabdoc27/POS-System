# Point of Sale Using JWT Authentication

This project works with Laravel 10.x, PHP 8.1 or higher, and a MySQL database.

## Getting Started

Follow these steps to set up the project:

1. **Clone the repository and navigate to the directory:**

    ```shell
    git clone git@github.com:Fabdoc27/POS-System.git
    cd POS-System
    ```

2. **Install the dependencies:**

    ```shell
    composer install
    ```

3. **Create the environment file:**

    ```shell
    cp .env.example .env
    ```

4. **Generate the application key:**

    ```shell
    php artisan key:generate
    ```

5. **Run the database migrations:**

    ```shell
    php artisan migrate
    ```

6. **Start the development server:**

    ```shell
    php artisan serve
    ```
