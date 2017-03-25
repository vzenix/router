
# "vzenix/router" Library

## Introduction

This library makes interface to work with friendly routes when everything is
redirects to "index.php" page.

The idea is to modify it later to integrate it with frameworks like laravel 
so that the libraries that have to consult some aspect of the url are 
completely portables to any framework or CMS that has a handling 
of friendly URLs.

## Mode of use (with composer)

Before continuing, if you do not know the composer, open this link: https://getcomposer.org/
and learn about this.

Create the file "composer.json"

```
#!json
{"require": {"vzenix/configuration": "1.*"}}
```

Launch the composer install

```
#!shell
# If it's a new project
composer install 

# If you add the library in an existing project
composer update 
```

Ready, you can use the library now.

## Mode of use (without composer)

If you do not use composer (not recommended), you just have to download
and add the include, see sample:

```
#!php
<?php
require_once "PATH/TO/LIBRARY/custom_loader.php"
$_iMiRouter = new \VZenix\Router\Router::GetInstance();
```

## Web server configuration

Example configuration in different server applications to redirect 
content to your application.

Examples extracted from the laravel framework

```
#!Apache
# .htaccess
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews
    </IfModule>

    RewriteEngine On

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)/$ /$1 [L,R=301]

    # Handle Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
</IfModule>
```

```
#!IIS
# web.config
<configuration>
  <system.webServer>
    <rewrite>
      <rules>
        <rule name="Imported Rule 1" stopProcessing="true">
          <match url="^(.*)/$" ignoreCase="false" />
          <conditions>
            <add input="{REQUEST_FILENAME}" matchType="IsDirectory" ignoreCase="false" negate="true" />
          </conditions>
          <action type="Redirect" redirectType="Permanent" url="/{R:1}" />
        </rule>
        <rule name="Imported Rule 2" stopProcessing="true">
          <match url="^" ignoreCase="false" />
          <conditions>
            <add input="{REQUEST_FILENAME}" matchType="IsDirectory" ignoreCase="false" negate="true" />
            <add input="{REQUEST_FILENAME}" matchType="IsFile" ignoreCase="false" negate="true" />
          </conditions>
          <action type="Rewrite" url="index.php" />
        </rule>
      </rules>
    </rewrite>
  </system.webServer>
</configuration>
```

## Examples of use

Example 1: Reading URL

```
#!php
<?php
// test_router.php
$_iMiRouter = \VZenix\Router\Router::GetInstance();

// If the URL has the value "/agenda/calendario/2" return "2"
$_iMiRouter->getPosition(2);

// If the URL has the value "/agenda/calendario/2" return "agenda"
$_iMiRouter->getPosition(0);

// If the URL has the value "/agenda/calendario/2" return "calendario"
$_iMiRouter->setInitPosition(1);
$_iMiRouter->getPosition(0);
```

Example 2: http redirects

```
#!php
<?php
// test_redirection.php

// Redirection Example 301
\VZenix\Router\Router::Redirect(301, "http://website.com/someone/", true);
\VZenix\Router\Router::Redirect(\VZenix\Router\Router::REDIRECT_MOVED_PERMANENTLY, "http://website.com/someone/", true);

// Redirection Example 304
\VZenix\Router\Router::Redirect(304);
\VZenix\Router\Router::Redirect(\VZenix\Router\Router::REDIRECT_NOT_MODIFIED);
```

## Licency

GNU General Public License v3.
