# Conceitos do Laravel

## Laravel Retornos em `routes/api.php`

Tudo que retomar como array associativo é convertido para JSON em `api.php`. Para isso, os header devem ser  `Accept: application/json`

````php
return ['errors' => 'vira_json'];
````

## PHP Lembretes

**Diferença entre -> e ::**

Usamos `'->'` para chamar um **método comuns**

```
$name = $foo->getName();
```

Usamos `'::'` para executar um **método estáticos**

```
$name = $foo::getName();
```

**function dd()**

Use ela ao invés de `var_dump()`  para ver o conteúdo e ocupar todo a a tela. Assim fica mais fácil de ver a variável

## Update Views

Se você trocar a view, muitas vezes nãovai atualizar na hora. Então, escere alguma coisa nos arquivos novos que asism vai forçar a reler. nãotem nada haver com cahce e nãosei porque isso acontece.

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