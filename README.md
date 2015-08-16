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

- Run `bower install` to install all assets (Bootstrap css and jQuery)
- Copy `app/config/config.local.template.neon` to `app/config/config.local.neon` and edit connection to database.
- Import `sql/schema.sql`.
- From console (terminal) run `php www/index.php dwarfSearch:import` to import base scenarios data.
- From console (terminal) run `php www/index.php dwarfSearch:export` to export all scenarios to ElasticSearch.
- Profit :-)
