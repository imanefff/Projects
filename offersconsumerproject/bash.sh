#!/bin/bash

sudo git clone #link

cd offersconsumerproject
sudo composer install

sudo cp .env.example .env

sudo php artisan key:generate

sudo mkdir -p storage\app\public\offers\images\dgxS9qLNIUHql0jMHSE
sudo mkdir -p storage\app\public\offers\images\edbWspBfn1D27nGrgZm
sudo mkdir -p storage\app\public\offers\images\pim3uyLRGnw6cxutO3p
sudo mkdir -p storage\app\public\offers\images\wdmRVzLWPgEU1Xrydfu

sudo mkdir -p storage\app\public\offers\imageTemp\Creatives
sudo mkdir -p storage\app\public\offers\imageTemp\JCrop

sudo php artisan storage:link

sudo chmod -R 777 ./storage ./bootstrap

