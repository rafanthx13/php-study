# 02 -  Installation and Configuration

## Instalar PHP no Windows

Link https://www.youtube.com/watch?v=KwEilZK5d04&ab_channel=TreinaWeb

ou 

https://blog.schoolofnet.com/como-instalar-o-php-no-windows-do-jeito-certo-e-usar-o-servidor-embutido/

+ 1 - Acesse https://windows.php.net/download/ e baixe o ZIP (Pode ser o Non Thread Safe)
+ 2 - Depois eu criou uma pasta em `C:/` chamada PHP_nome_da_versao
+ 3 - Coloque os arquitov nessa pasta
+ 4 - Adicione o caminho para ``php.exe`` no path de variáveis de ambiente em ``Path``. Exemplo: ``C:\PHP8``

## Verificar se foi instalado corretamente

No terminal 

````
> php -v 

ou

> php --version
````

Você tambem pode executar como

````
php -S 127.0.0.1:8050

ou

php -S localhost:8080
(Essa 2 opçao funcione se voce configurar em C:\Windows\System32\drivers\etc.)
````

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

Elee devera já encontrar o php ja instlaado (se nao encontrad moster eo caminho)

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