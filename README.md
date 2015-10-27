# KPars

### Requires:
* Vagrant
* Symfony 2 Framework 
* PHPStorm (with plugins: Symfony, Twig )

Commands:
* app/console kp:create:cache:structure
* app/console kp:scrap:page
* app/console kp:scrap:sub:page
* app/console kp:complex

Examples:
* app/console kp:scrap:page film
* app/console kp:scrap:page name --limit=10
* app/console kp:scrap:sub:page film cast --limit=10

See page settings in `tables.yml`
