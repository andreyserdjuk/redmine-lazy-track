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
php bin/console.php redmine:track \
-u 111 \                # user with id 111  
-i 222 \                # issue with id 222
-m 'hardworking week' \ # message text
-h 8 \                  # 8 hours per day
-a 9                    # activity with id 9 (Development in most cases) 
```
Or if you need to copy-paste:
```bash
php bin/console.php redmine:track -u 111 -i 222 -m 'hardworking week' -h 8 -a 9
```
This will track all working days in the week up to today starting from the last active date.
