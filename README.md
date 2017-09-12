**Application**

Run:

`docker-compose up` from the root directory

**Auditor**

In order to work with auditor you need to enter PHP-FPM container:

`docker exec -it "$(docker ps | grep -m 1 php-fpm | awk '{print $1}')" bash`

To run user audit:

`php /var/www/auditor/command.php auditor audit_user`

To send list of files via email:

`php /var/www/auditor/command.php auditor email_logs emailTo@emal.com emailFrom@email.com`

To run tests:

`/var/www/auditor/vendor/phpunit/phpunit/phpunit /var/www/auditor/AuditorTest.php`

Auditor can be accessed via browser:

localhost:8080