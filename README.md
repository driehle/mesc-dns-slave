DNS slave tools for MESC
========================

Installation
------------

Install package with composer (see http://getcomposer.org):

```
composer.phar install
```

Copy the configuration file:

```
cp config/config.ini.dist config/config.ini
```

Go ahead and edit the ```config.ini``` to reflect your needs,
then run the following command do update the DNS zones for 
your slave server:

```
php bin/update-named.php
```

You may want to execute this periodically via cron.