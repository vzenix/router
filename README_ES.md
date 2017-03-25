
# Librería "vzenix/router"

## Introducción

Esta librería hace de interfaz para trabajar con rutas amigables cuando todo se 
redirecciona a "index.php" en la página.

La idea es modificarla mas adelante para integrarla con frameworks como laravel
de modo que las librerías que tengan que consultar algún aspecto de la url sean
complemtanete portables a cualquier framework o CMS que tenga un manejo de URLs 
amigables.

## Modo de uso (con composer)

Antes de continuar, si no conoces composer, abre este link: https://getcomposer.org/
e infórmate.

Crea el archivo "composer.json" con la siguiente estructura

`
#!json
{"require": {"vzenix/router": "0.1.*"}}
`

A contunuación ejecuta composer con las instrucciones de instalación

`
#!shell
# si estás creando el proyecto
composer install 

# si ya tenías el proyecto creado y estás añadiendo la librería
composer update 
`

Listo, ya puedes usar la librería en tu proyecto.

## Modo de uso (sin composer)

Si no usas composer (algo que desaconsejamos), Solo tienes que descargar 
el código y añadirlo de la siguiente forma:

`
#!php
<?php
require_once "PATH/TO/LIBRARY/custom_loader.php"
$_iMiRouter = new \VZenix\Router\Router::GetInstance();
`

## Configuración del servidor web

Ejemplo de configuración en diferentes aplicaciones de servidor
para redireccionar contenido a tu aplicación.

Ejemplos extraidos del framework laravel

`
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
`

`
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
`

## Ejemplos de uso

Ejemplo 1: Lectura de la URL

`
#!php
<?php
// test_router.php
$_iMiRouter = \VZenix\Router\Router::GetInstance();

// Si la URL tiene el valor "/agenda/calendario/2" retorna "2"
$_iMiRouter->getPosition(2);

// Si la URL tiene el valor "/agenda/calendario/2" retorna "agenda"
$_iMiRouter->getPosition(0);

// Si la URL tiene el valor "/agenda/calendario/2" retorna "calendario"
$_iMiRouter->setInitPosition(1);
$_iMiRouter->getPosition(0);
`

Ejemplo 2: Redirecciones http

`
#!php
<?php
// test_redirection.php

// Ejemplo de redirección 301
\VZenix\Router\Router::Redirect(301, "http://website.com/someone/", true);
\VZenix\Router\Router::Redirect(\VZenix\Router\Router::REDIRECT_MOVED_PERMANENTLY, "http://website.com/someone/", true);

// Ejemplo de redirección 304
\VZenix\Router\Router::Redirect(304);
\VZenix\Router\Router::Redirect(\VZenix\Router\Router::REDIRECT_NOT_MODIFIED);
`

## Licencia

GNU General Public License v3.
