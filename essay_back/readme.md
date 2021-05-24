#WebSite domaine :  www.winlitdeals.com

## Note

Please note that this project is build entirely with the help of `Laravel Homestead`.
Laravel homestead is an official, pre-packaged Vagrant box that provides you a wonderful development environment without requiring you to install PHP, a web server, and any other server software on your local machine 
####Included Software :
``````
Ubuntu 18.04
Git
PHP 7.3
PHP 7.2
PHP 7.1
Nginx
MySQL
lmm for MySQL or MariaDB database snapshots
Sqlite3
PostgreSQL
Composer
Node (With Yarn, Bower, Grunt, and Gulp)
Redis
Memcached
Beanstalkd
Mailhog
avahi
ngrok
Xdebug
XHProf / Tideways / XHGui
wp-cli
Minio
``````


## Installation

1. Clone the repo and `cd` into it
1. `composer install`
1. composer require tcg/voyager
1. php artisan voyager:install
1. to install the hooks system `php artisan storage:link`
1. Rename or copy `.env.example` file to `.env`
1. `php artisan key:generate`
1. Set your database credentials in your `.env` file
1. Set your `APP_URL` in your `.env` file. This is needed for Voyager to correctly resolve asset URLs.
1. Set this facebook and google credentials in your `.env` file. This is needed for Login with social media :
``````
FB_APP_ID=295555801386787
FB_APP_SECRET=f6b08f81bc703f49d22ec8bd9fa1ca16
FB_CALLBACK_URL='https://winlitdeals.com/callback'

GOOGLE_CLIENT_ID=906221525321-h2lh6mjlevrs6r8j2kp2086up9mifudq.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=9tTvywbDV3WNAB5QmlZQaXtH
GOOGLE_URL=https://winlitdeals.com/callbackGoogle
``````

1. Set the SESSION_DRIVER from `file`  to `database` , and SESSION_LIFETIME to 60 in your `.env` file
1. The credentials for the Nocaptcha are : 
``````
NOCAPTCHA_SITEKEY=6LeIiqYUAAAAAFhySYZQj7gh91vEZGpjKCCx-3LF
NOCAPTCHA_SECRET=6LeIiqYUAAAAALE_Dtd0Ez9RTB-4WWu6qQUdvv0
``````
1. The admin account is : email : win.lit.deals@gmail.com & password : win12345@
You can also access the facebook and google developper account with this logs.

## Important

1. To see the analytics , please connect the google analytics account in the Voyager Dashboard with the google account given above.
1. Roles : Admin = 1 , normal user = 2, technician = 3. 
Edit the role_id in the database with one of the number above in the users tables to change their roles/permission to access the admin dashboard.
1. This Voyager tables should not be deleted or be emptied : Roles, settings..
1. Do not change or edit the columns in Sessions table, specialy ID and last_activity.
Sessions is a Laravel (preset/pr�d�fini) table that track users sessions, any change on this table must be overwritten in the App/Session -> DatabaseSessionHandler.






