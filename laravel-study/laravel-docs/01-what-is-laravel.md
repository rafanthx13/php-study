# 01 - O que é Laravel

Framework Web PHP

## Libs e Frameworks do Ecossitema Laravel

+ ELoquent ORM
+   - É o modo de tornar os Models em query SQL, como o Prisma do Nest]

+ Laragon
+  - Resumo: alternativa ao xamp e melhor com alravel
+  - https://laragon.org/download/index.html
+  - É como se fosse um XAMP especifico para o Laravel otimizado
+  O Laragon é o ambiente de desenvolvimento web mais completo e simplista, recomendado a substituir para quem utiliza o XAMPP no Windows

+ Socilaite
 - RESUMO: Autentcar com conta como gooel e etc
 - Laravel Socialite provides an expressive, fluent interface to OAuth authentication with Facebook, Twitter, Google, LinkedIn, GitHub, GitLab and Bitbucket. It handles almost all of the boilerplate social authentication code you are dreading writing.

+ Cashier
   - RSEUMO:? API para conectar com o STRIP: Mecanismo de pagamento
   - Laravel Cashier Stripe provides an expressive, fluent interface to Stripe's subscription billing services. It handles almost all of the boilerplate subscription billing code you are dreading writing. In addition to basic subscription management, Cashier can handle coupons, swapping subscription, subscription "quantities", cancellation grace periods, and even generate invoice PDFs.

+ Scout
  + RESUMO: É um ElasticSeaacrh como serviço para integrar ao Laravel
  + O Scout é um package para Laravel que adiciona aos models do Eloquent a possibilidade de usar mecanismos de busca de forma fácil.
  + Realizar buscas em textos é algo que a maioria dos bancos de dados não fazem muito bem (com eficiência). Isso fica ainda pior quando temos que buscar em vários campos com muitas informações. O problema é que esse tipo de situação é cada vez mais comum nos sistemas, devido a diminuição no valor do armazenamento, maior presença das pessoas online e outros fatores. Baseado nisso, existem algumas soluções que ajudam a resolver essa demanda. Basicamente elas consistem em carregar esses dados para índices e usar algoritmos específicos para esse tipo de busca. Existem alguns softwares que podem ser instalados diretamente no seu servidor como o Apache Solr, Elasticsearch etc, também existem algumas aplicações SaaS que fazem esse “trabalho sujo” e que disponibiliza uma API para se comunicar com o seu software, como é o caso do Algolia e searchly.

## Nomelcatura dos metodos dos controlles

show = getbyID
store = POST