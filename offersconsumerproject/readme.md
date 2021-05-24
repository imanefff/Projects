# Environment 
### php
- PHP >= 7.1.3
- PDO PHP Extension
- CURL PHP Extension
- GD PHP Extension
### composer 
-composer v >= 1.6.5

# Clone project 
-sudo git clone #link


# After Clone project 

``````
cd offersconsumerproject
sudo composer install

sudo cp .env.example .env

sudo php artisan key:generate

sudo mkdir -p storage/app/public/offers/images/dgxS9qLNIUHql0jMHSE
sudo mkdir -p storage/app/public/offers/images/edbWspBfn1D27nGrgZm
sudo mkdir -p storage/app/public/offers/images/pim3uyLRGnw6cxutO3p
sudo mkdir -p storage/app/public/offers/images/wdmRVzLWPgEU1Xrydfu
sudo mkdir -p storage/app/public/offers/imageTemp/Creatives
sudo mkdir -p storage/app/public/offers/imageTemp/JCrop
sudo mkdir -p storage/app/public/offers/TempCreativesCake
sudo mkdir -p storage/app/public/offers/TempCreativesEverFlow


sudo php artisan storage:link

sudo chmod -R 777 ./storage ./bootstrap

sudo apt-get install php7.1-zip (php version )
sudo service apache2 restart

``````

# Configuration Database
- in .env File fill config connection database
- in .env File change name of application by changing this line :  ```APP_NAME=Laravel``` by  ```APP_NAME="Offers Consumer"```
- import Database Sql or migrate it use commend : ```php artisan migrate:fresh --seed```


## Linux setup
- create vertual host offerConstumer.conf :


``````

<VirtualHost *:83> //port 83 

        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/html/offersconsumerproject/public  //path of index file in project 

        <Directory /var/www/html/offersconsumerproject/public>  // htaccess -> path of index file in project 
            Options Indexes FollowSymLinks
            AllowOverride All
            Require all granted
        </Directory>

        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined

</VirtualHost> 

``````

- in /etc/apache2/ports.conf add line listen port number  : ```Listen 83```

- a2ensite virtual  : ```sudo  a2ensite offerConstumer.conf```
- reload apache : ```sudo service apache2 reload```


## script php to get cache_offers 
- file name :  ```insertCasheOffers.php```
- change server configs :
``````
//****************** CONFIGURATION **************************
$database_host     = "Server";
$database_user     = "User";
$database_password = "Password";
$database_name     = "Database";
//**********************************************************
``````

## If any line or any file added in composer.json 
- Execute this commend  : ``````composer dump-autoload``````

## License

[PIM license]
