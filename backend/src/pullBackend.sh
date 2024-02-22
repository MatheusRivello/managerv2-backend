#!/bin/bash

cd /var/www/afv2backend.sig2000.com.br/html

git pull origin develop

# Validando se existe o link simb—lico

linkSimbolico="/var/www/afv2backend.sig2000.com.br/html/public/"

if [ ! -d $linkSimbolico ]
then
	ln -s /var/www/afv2backend.sig2000.com.br/arquivos/ /var/www/afv2backend.sig2000.com.br/html/public/
else
	echo " "
fi

composer install --no-dev -o

echo "Deploy finalizado"    
