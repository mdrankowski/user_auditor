**Auditor**

You can access the auditor via browser:
localhost:8080

or command line: 

docker exec -it "$(docker ps | grep -m 1 php-fpm | awk '{print $1}')" bash

php /var/www/auditor/audit_user.php

Auditor app generates the logs within /var/www/auditor/logs directory
The list of logs is sent every 3 hours via email. App logs are logrotated.

Apache access logs are stored within /usr/local/apache2/logs/auditor_access.log files.
Logstash beaver streams them to redis, logstash picks all redis entries and saves
to database.

There is a known issue with logstash: https://github.com/elastic/logstash/issues/6824.
It requires further investigation. 

**Emailer**

You can trigger the emailer via browser:
http://localhost:8080/email_logs.php?mailTo=email@email.com

or command line: 

docker exec -it "$(docker ps | grep -m 1 php-fpm | awk '{print $1}')" bash

php /var/www/auditor/email_logs.php email@email.com

**MySQL**

host: localhost

port: 3333

username: tr

password: test

**FTP**

host: localhost

port 21

username: tourradar

password: test

FTP requires some further configuration

