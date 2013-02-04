DNS slave tools for MESC
========================

Installation
------------

Download a ZIP-file of this package an extract it somewhere on your system or clone the repository using git:

```
git clone git://github.com/driehle/mesc-dns-slave.git
```

You should install the dependencies of this package with composer. If you don't have composer on your system, you need to get composer first, see https://getcomposer.org/download/ for details.

Simply install the dependencies by running this command inside the package directory:

```
composer.phar install
```

Copy the configuration file and edit it, to reflect your needs:

```
cp config/config.ini.dist config/config.ini
vi config/config.ini
```

Run the following command to update the DNS zones of your slave server:

```
php bin/update-named.php
```

You may want to execute this periodically via cron.


Security considerations
-----------------------

In order for ```mesc-dns-slave``` tools to update the zones of your Bind DNS server, it needs to restart or reload Bind, which usually only root is allowed to do.

One possibilty is running the tools as root. However, *it is strongly recommended not to run these scripts under root!*

Instead, it is recommended to use ```sudo``` to give a certain non-priviledged user the priviledge to restart or reload Bind. The following example configuration assumes that ```dnsslave``` is the username under which the scripts should be executed.

Run ```visudo``` and add a line like the following to  ```/etc/sudoers```:

```
# User privilege specification
dnsslave ALL=NOPASSWD:/etc/init.d/bind9 reload
```

Update ```config/config.ini``` like this:

```
; Command to restart Bind
bind_restart_cmd = "sudo /etc/init.d/bind9 reload"
```

Grant write access to the partial Bind configuration file:

```
touch /etc/bind/named.slave.conf
chgrp dnsuser /etc/bind/named.slave.conf
chmod 0664 /etc/bind/named.slave.conf
```

You're now ready to securely run mesc-dns-slave with an unpriviledged user.