### About
Track long-living Redmine Issues for lazy developers.  
If you have to track same Issue every day, this solution will save your time.

### Install
##### Get your API KEY:
Enter redmine page -> my account -> API access key (on the right sidebar).
##### Setup environment variables: 
```bash
composer install
export REDMINE_API_ACCESS_KEY='redmine-api-key'
export REDMINE_URL='https://myredmine.com'
```
You can add upper vars in .basrc, .bash_profile or .profile etc depends on what your console terminal is loading.

### Track
```bash
php bin/console.php redmine:track \
-user 111 \                    # user with id 111  
-issue 222 \                   # issue with id 222
-message 'hardworking week' \  # message text
-hours 8 \                     # 8 hours per day
-activity 9                    # activity with id 9 (Development in the most of cases) 
```
Or simply copy-paste:
```bash
php bin/console.php redmine:track -u 111 -i 222 -m 'hardworking week' -ho 8 -a 9
```
This will track all working days in the week up to today starting from the last active date.
