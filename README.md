requirements:  
PHP 8.2  
PHP Composer  
Node.js  

installation and running:

0. powershell  
   `git clone https://github.com/skff-err/feego2`  
   on any directory
1. within feego2/feegot, change .env.example to .env and adjust accordingy, make sure to generate APP_KEY
2. navigate into folder feegot within the earlier directory and run the following commands  
   `npm install`  
   `composer update`, important or else php artisan will not run  
3. run migrations with the following command  
   `php artisan migrate`
4. once done, run the following commands  
   `npm run dev` which initializes vite and the SCSS files  
   `php artisan serve` which runs the laravel project on 127.0.0.1  
