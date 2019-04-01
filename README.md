### About
Track long-living Redmine Issues for lazy developers.

### Install
```bash
composer install
export REDMINE_API_ACCESS_KEY='redmine-api-key'
export REDMINE_URL='https://myredmine.com'
```
Of course you can add upper vars in .basrc etc.

### Track
```bash
php bin/console.php redmine:track -u 111 -i 222 -m 'hardworking week'
```
This will log 8 hours for all working days in the week before today.