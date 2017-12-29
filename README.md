## Chronolabs Cooperative presents

# Favorite Icons REST API v1.1.2

## icons + Favorite Icons - http://icons.snails.email

### Author: Simon Antony Roberts <simon@snails.email>

The following REST API allows for images over nearly every format to be stored as an original in the database compressed and service icons with URL for support.

# Setting Up the environment in Ubuntu/Debian

There is a couple of extensions you will require for this API to run you need to execute the following at your terminal bash shell to have the modules installed before installation.

    $ sudo apt-get install imagemagick* -y
    

# Apache Module - URL Rewrite

The following script goes in your API_ROOT_PATH/.htaccess file

    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d


To Turn on the module rewrite with apache run the following:

    $ sudo a2enmod rewrite
    $ sudo service apache2 restart

# Cron Jobs - Scheduled Tasks

There is a couple of cron jobs that need to run on the system in order for the system to run completely within versioning specifications to get to the cron scheduler in ubuntu/debian run the following

    $ sudo crontab -e
    
once in the cron scheduler put these lines in making sure the paths resolution is correct as well as any load balancing you have to do

    

