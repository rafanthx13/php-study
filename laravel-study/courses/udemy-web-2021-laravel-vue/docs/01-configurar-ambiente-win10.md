INSTALAR PHP

1. 
2. acesse php.net, baixe a versão download.
3. baixe o zip
4. crie uma pasta `php-versão-....`, coloque-o lá e o extraia as coisas lá. Ponha na raiz em `C:`
5. execute  `php --version` na masta com o cmd
6. depois deve torná-lo global: usar as variáveis de ambiente
7. Coloque em `Path` nas variáveis de sistema e depois testes o mesmo comando em outra pasta pra vÊ se funinou

INSTALAR COMPOSE

getcomposer.org => baixe o instladro do windows => no instalador, selecione a pasta do php anterior => garatan que tenha o php.ini, se não tiver ele (o composer) vai criar.

Se nâo tiver , copie o `php.ini-development` e deixe como `php.ini` pois o `development` tem mais recursos

por fim o commando `composer --version` deve funcionarR



ERROS QUE O COMPOSER PODE DÁR

**1) Dica do Bruno Martins:** Ao iniciar novos projetos Laravel por meio do Composer, caso ocorra os erros:



- Erro: Composer diagnose

Checking git settings: OK Checking http connectivity to packagist: WARNING [Composer\Downloader\TransportException] The "http://packagist.org/packages.json" file could not be downloaded: failed to open stream: No connection could be made because the target machine actively refused it.



ou



- Erro: [Composer\Downloader\TransportException]                                          

curl error 60 while downloading https://repo.packagist.org/packages.json: SSL certificate problem: self signed certificate



**A dica** para resolver é executar os comandos abaixo na linha de comandos do SO da seguinte forma:



1) Execute o comando: `composer config -g repo.packagist composer https://repo.packagist.org`

2) Execute o comando: `composer self-update`

3) Execute o comando: `composer create-project --prefer-dist laravel/laravel projeto_lavavel_via_composer "7.0"`



**2) Dica do Pablo Batista:** Ao iniciar novos projetos Laravel por meio do Composer, caso ocorra o erro:



- Erro: [Composer\Exception\NoSslException]



**A dica** para resolver é acessar o arquivo `php.ini` e descomentar a linha com a instrução `extension=openssl`.



## cRIAR PROJETO LAVAREL

fazer alguns tetstes

vamos fazer duas configuraçôes de composers

1) Execute o comando: `composer config -g repo.packagist composer https://repo.packagist.org`. Definido uma configuraçâo global para dizer de onde buscar os pacotes

`composer config -g repo.packagist composer https://repo.packagist.org`

`composer config -g github protocols https ssh`



### Instalar laravel por composer

`composer create-project --prefer-dist laravel/laravel nome_projto`

para ser uma versão especifica

``composer create-project --prefer-dist laravel/laravel nome_projto "7.0"``

prefer-dist: da preferencia as dependendicas a propria distribuiçao, ou seja, do proprioo alravel. Vamos da preferenic apara aixar as dependencias extadas usadas pelo proprio laravel

laravel/larave: distribuidor/pacote, pois laravel poderia ter outros pacotes

NAO USE ESPAÇO E DE PREFEEREICNIA TRAÇO

VAI CRIAR UMA PASTA

`php -S localhost:8000` na pasta public

### Instalar projeto laravael com Laravel Installer

É uma forma maix elegante e mais rápida. Em contra partida, é necessário instalar o Laravel

1. `composer global require laravel/installer`
2. Configuarar a variave de ambiente   `%USERPROFILE%\AppData\Roaming\Composer\vendor\bin` na variáel Path
3. Por fim, deve conseguir executar `laravel --version`



### Usando laravel installer

`laravel new projeto_laravel_via_installer`