HTTPDUSER=`ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`;
sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var/cache var/logs;
sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var/cache var/logs;
sudo cp conf/socialroom.conf /etc/apache2/sites-available;
sudo a2ensite socialroom;
sudo service apache2 reload;
composer install;
php bin/console doctrine:database:create;
php bin/console doctrine:schema:update --force;
