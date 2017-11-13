apt install bower node composer
bower install
composer install
npm install
mv node_modules/jquery-datetimepicker vendor
mkdir cache
chmod 755 cache
chown www-data cache
touch /var/security.log
chown www-data /var/security.log
chmod 755 /var/security.log
echo "Don't forget to edit config.default.php and the files in the config directory"