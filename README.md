DwarfSearch source code
=======================

Installation
-----------

The best way to install **DwarfSearch** is using  [Composer](http://getcomposer.org/):

```sh
$ composer create-project legendik/dwarf-search
```

Configuration
---------------------

- Run `composer install` to install all dependencies
- Run `bower install` to install all assets (Bootstrap css and jQuery)
- Run `cp app/config/config.local.template.neon app/config/config.local.neon` and edit connection to database.
- Import `sql/schema.sql`.
- Run `php www/index.php dwarfSearch:import cs` to import base scenarios data.
- Run `php www/index.php dwarfSearch:export cs` to export all scenarios to ElasticSearch.
- Profit :-)
