#!/bin/bash
/usr/local/php8.0/bin/php /home/fykwlxn/www/preprod/backOffice/artisan schedule:run >> /home/fykwlxn/www/preprod/backOffice/storage/logs/cron.log 2>&1
