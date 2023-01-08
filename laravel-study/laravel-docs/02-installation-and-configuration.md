# 02 -  Installation and Configuration

## Instalar PHP no Windows

https://dev.to/marcelochia/instalando-o-php-8-no-windows-237m

OU

Link https://www.youtube.com/watch?v=KwEilZK5d04&ab_channel=TreinaWeb

ou 

https://blog.schoolofnet.com/como-instalar-o-php-no-windows-do-jeito-certo-e-usar-o-servidor-embutido/

+ 1 - Acesse https://windows.php.net/download/ e baixe o **ZIP** (Pode ser o Non Thread Safe)
+ 2 - Depois eu criou uma pasta em `C:/` chamada PHP_nome_da_versao
+ 3 - Coloque os arquitov nessa pasta
+ 4 - Adicione o caminho para ``php.exe`` no path de variáveis de ambiente em ``Path``. Exemplo: ``C:\PHP8``
+ Ficou por exemplo `C:\DevEnviroment\php-8.2.0`

## Verificar se foi instalado corretamente

No terminal  (ou Windows + R e em seguida ``cmd``/``powershell``)

````
> php -v 

ou

> php --version
````

Você tambem pode executar como

````
php -S 127.0.0.1:8050
````

ou

````
php -S localhost:8080
````

(Essa 2 opçao funcione se voce configurar em C:\Windows\System32\drivers\etc.)


e crie um arquivo com

````php
<?php

phpinfo();
````

phpinfo vai mostr os dados do nosso php.

Poderia ser também no termnal

````
php .\info.php
````

## Instalar Composer Windows

Baixe o executavel em:
https://getcomposer.org/download/

Nao marque develop mode

Elee devera já encontrar o php **QUE FOIN INSTALADO ANTERIORMENTE** instlaado (se nao encontrad moster eo caminho)

**Tetar composer**

no terminal

````
`> composer
````

ou

composer -V

## Instalar Laravel

Requer que seu PHP tenha algumas extenose haiblitadas.

Para habilitar vai em ``/php.ini``. Bsu ue por ``extension=bz2``. Ai vai etsar as extensones

Voce^devera habiltiar
````
curl
nbstring
openssl
pdo_mysql
````

Além disos habilite a seguinte linha. Pois vai dizer apra o windos ler as extensoes na pasta ext ed onde foi instalada o(no windows)

````
; On windows:
extension_dir = "ext"
````


Tem que ter o composer instalado e executar.

Estaremos instalando globalmente

````
composer global require laravel/installer
````

### Testar se laravel foi instalado

````
$ laravel
````

devera abrir um help no terminal onde havera 

````
Available commands:
  help  Display help for a command
  list  List commands
  new   Create a new Laravel application
````

**Ond eé instalado (pleo composer)**

Em:

````
 C:\Users\Mayara\AppData\Roaming\Composer\vendor\bin\/../laravel/installer/bin/laravel
 ````

 **laravel new novo_project**

## Testar versao do laravel

na pasta onde está o projeto laravel

````
php artisan --version
````

ou a criar um projeto

````
   _                               _
  | |                             | |
  | |     __ _ _ __ __ ___   _____| |
  | |    / _` | '__/ _` \ \ / / _ \ |
  | |___| (_| | | | (_| |\ V /  __/ |
  |______\__,_|_|  \__,_| \_/ \___|_|

    Creating a "laravel/laravel" project at "./crate-first-app"
    Info from https://repo.packagist.org: #StandWithUkraine
    Installing laravel/laravel (v9.4.1)
        Failed to download laravel/laravel from dist: The zip extension and unzip/7z commands are both missing, skipping.
The php.ini used by your command-line PHP is: C:\DevEnviroment\php-8.2.0\php.ini
    Now trying to download from source
  - Syncing laravel/laravel (v9.4.1) into cache
````

## Criando projetos laravel com uma versâo especifica

Dessa forma nao precisa instalar diretoamente o laravel global

 FUNCIONA````composer create-project laravel/laravel="5.1.*" myProject````


INSTALANDO LARA 6 (FUNCONA)
 > composer create-project laravel/laravel="6.*" marketplace_lara6


## php.ini

sendo que é ``;`` que habilita ou desabilita

````
;
;extension=bz2
extension=curl
extension=fileinfo
extension=gd2
;extension=gettext
;extension=gmp
extension=intl
extension=imap
;extension=interbase
;extension=ldap
extension=mbstring
extension=exif      ; Must be after mbstring as it depends on it

extension=mysqli
;extension=oci8_12c  ; Use with Oracle Database 12c Instant Client
;extension=odbc
extension=openssl
;extension=pdo_firebird

extension=pdo_mysql
;extension=pdo_oci
;extension=pdo_odbc
;extension=pdo_pgsql
;extension=pdo_sqlite
;extension=pgsql
;extension=shmop

; The MIBS data available in the PHP distribution must be installed.
; See http://www.php.net/manual/en/snmp.installation.php
;extension=snmp

;extension=soap
;extension=sockets
;extension=sodium
;extension=sqlite3
;extension=tidy
extension=xmlrpc
;extension=xsl

````

## atualizar composer

> composer self-update

## Outras feramenats

+ Debugbar: Feio na ultima seçÂo redis (banco em memoria = como um cache), serve para abrir uma barra no larvel e vermos as consusltas feitas no lravel

## instalar laravel

cria projeto alravel::

composer create-project laravel/laravel example-app

instala cli alravel
composer global require laravel/installer

## EXTENSOES do php.inio para laravel funcionar

Encontra nesse site

https://github.com/GastonHeim/Laravel-Requirement-Checker/blob/master/check.php

A do Laravel 9 (2023)

'9.0' => array(
        'php' => '8.0.0',
        'bcmath' => true,
        'ctype' => true,
        'curl' => true,
        'dom' => true,
        'fileinfo' => true,
        'json' => true,
        'mbstring' => true,
        'openssl' => true,
        'pcre' => true,
        'pdo' => true,
        'tokenizer' => true,
        'xml' => true
    ),

    habilitei a mais (2023 php 8 + laravel 9)
  extension=fileinfo
  extension=zip

  habilite isso no windos
  extension_dir = "ext"