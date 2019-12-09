# PrintServer
Print server compatible with DOMjudge. 

Worked as changing the POST requests to the individual print server.

<u>**It is not tested in any contest!**</u>

<u>**It is not tested in any contest!**</u>

<u>**It is not tested in any contest!**</u>

## Requirements

**DOMjudge server need to be set to combine IP address with each user.**

Dump the `user` table (or just `username` and `ip_address` columns) and insert into the `print` database.

```mysql
create table if not exists `job` (
    `id` int(4) unsigned not null auto_increment,
    `username` varchar(255) NOT NULL,
    `ip_address` varchar(255),
    `file` varchar(255) NOT NULL,
    `origname` varchar(255) NOT NULL,
    `language` varchar(255),
    `status` tinyint(1) NOT NULL DEFAULT '0',
    `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

Set `AllowOverride All` in `httpd.conf` so that `.htaccess` should work.

