## Installation

1. Clone the github repo:

    ```bash
    git clone .......
    ```

2. Go the project directory:

    ```bash
    cd ......
    ```

3. Install the project dependencies:
    ```bash
    composer install or composer update
    ```
4. Copy the .env.example to .env or simly rename it:
   </br>If linux:
    ```bash
    cp .env.example .env
    ```
    If Windows:
    ```bash
    copy .env.example .env
    ```
5. Run XAMPP and create an empty Database
   </br>Create tables into database using Laravel migration and seeder:
    ```bash
    php artisan migrate OR php artisan migrate:fresh --seed
    ```
6. Create the application key:
    ```bash
    php artisan key:generate
    ```
7. To create the symbolic link:
    ```bash
    php artisan storage:link
    ```
8. Start the laravel server:
    ```bash
    php artisan serve
    ```
