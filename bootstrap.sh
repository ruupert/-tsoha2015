#!/bin/bash

source config/environment.sh

echo "Luodaan projektikansio..."

# Luodaan projektin kansio
ssh $USERNAME@lakka.kapsi.fi "
cd htdocs
touch favicon.ico
mkdir $PROJECT_FOLDER
cd $PROJECT_FOLDER
touch .htaccess
echo 'RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [QSA,L]' > .htaccess
exit"

echo "Valmis!"

echo "Siirretään tiedostot users-palvelimelle..."

# Siirretään tiedostot palvelimelle
scp -r app config lib vendor sql assets index.php composer.json $USERNAME@lakka.kapsi.fi:htdocs/$PROJECT_FOLDER

echo "Valmis!"

echo "Asetetaan käyttöoikeudet ja asennetaan Composer..."

# Asetetaan oikeudet ja asennetaan Composer
ssh $USERNAME@lakka.kapsi.fi "
chmod -R a+rX htdocs
cd htdocs/$PROJECT_FOLDER
curl -sS https://getcomposer.org/installer | php
php composer.phar install
exit"

echo "Valmis! Sovelluksesi on nyt valmiina osoitteessa $USERNAME.kapsi.fi/$PROJECT_FOLDER"
