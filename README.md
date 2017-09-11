**Auditor**

In order to work with auditor you need to access PHP-FPM container:

docker exec -it "$(docker ps | grep -m 1 php-fpm | awk '{print $1}')" bash

To run user audit:

php /var/www/auditor/command.php auditor audit_user

To send list of files via email:

php /var/www/auditor/command.php auditor email_logs email@email.com

To run tests:

/var/www/auditor/vendor/phpunit/phpunit/phpunit /var/www/auditor/AuditorTest.php