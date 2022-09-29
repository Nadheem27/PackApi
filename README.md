# PackApi

Steps to Run this Project

Step 1 : git clone https://github.com/Nadheem27/PacktApi.git.
Step 2 : composer install.
Step 3 : create .env file & connect to the database created.
Step 4 : php artisan migrate:fresh --seed (Admin Login Username : admin@packt.com, password : password).
Step 5 : php artisan passport:install.
Step 6 : php artisan passport:keys.
Step 7 : php artisan serve.
Step 8 : http://127.0.0.1:8000/api/v1/books/insertData - call this url to insert dummy datas.

Backend setup is completed