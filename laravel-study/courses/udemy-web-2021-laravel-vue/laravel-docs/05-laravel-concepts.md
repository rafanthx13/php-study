# Conceitos do Laravel

## Update Views

Se vocÊ trocar a view, muitas vezes nâo vai atualizar na hora. Entâo, escere alguma coisa nos arquivos novos que asism vai forçar a reler. Nâo tem nada haver com cahce e nâo sei porque isso acontece.

````
https://stackoverflow.com/questions/37503627/blade-view-not-reflecting-changes

In order to avoid the parsing of Blade files on each reload, Laravel caches the views after Blade is processed. I've experienced some situations where the source (view file) is updated but the cache file is not "reloaded". In these cases, all you need to do is to delete the cached views and reload the page.

The cached view files are stored in storage/framework/views.
````

## Rotas

**O que deve ficar em Routes**: Lida com métodos HTTP, URL e Middlewares. Não deve ficar nenhuma lógica, esta vai para os controllers

As Rotas estão organizadas em 4 arquivos.

Elas são lidas e entendidas pelo laravel de forma diferente

+ `api.php`
  + Registrar rotas de API. Não tem cookies nem sessões. Mais moderar, para fazer uma app que é exclusivamente backend fornecendo acesso a front-end Desktop e Mobile
+ `channels.php`
  + Utilizado para comunicação em tempo real como WebSockets. Serve para o backend notificar o front quando algum evento ocorrer
+ `console.php`
  + Comandos personalizados para serrem executados pelo artisan
+ `web.php`
  + É o velho backend php que conhecemos: prover páginas html, e permitem cookies e sessões

## Controllers

É a implementação da lógica. Cada Rota é atrelada a um controlador que faz uma série de ações.

O ideal é sempre manter Controller e rotas separados,m esmo podendo colocar toda a lógica dentro da callback do `Router`.

Os controllers são classes que sempre terminam em `Controller` usando `CamelCase`.

Utilizamos o `artisan make:controller NomeController`.

Eles ficam entro de `app/HTTP/Controllers/`

## Views

Seria a página html a ser mandada