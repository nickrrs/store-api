## Documentação de Projeto
O projeto consiste em uma aplicação RESTFul em Laravel, rodando em uma ambiente de containers em Docker, e utiliza o banco de dados relacional para armazenamento de informações. A arquitetura da aplicação foi baseada em DDD (Domain-Driven-Design) mas de forma customizada pessoal.

> Documentação/Collection das Endpoints: **/docs/collection/** dentro do projeto. 

### Para configurar e executar o projeto é só seguir os simples passos:

Antes de iniciar, é importante que você crie um arquivo **.env** caso não o tenha. No arquivo **.env.example** há um modelo do arquivo que possa ser criado, com variáveis e valores ideais para o seu ambiente.

1 - Ao clonar o projeto, na raiz, execute o seguinte comando para monstar as imagens de container e executá-los em segundo plano: 
    
```docker
docker-compose up -d --build 
```

**OBS: caso o script de "change owner" do Dockerfile não funcione corretamente, pode tentar executar o seguinte comando no terminal:** 
```docker  
docker-compose exec app chmod -R 777 /var/www/storage 
```

Rodar esse comando, dá a permissão geral para qualquer usuário dentro da pasta storage, algo importante, já que nessa aplicação usamos algumas escritas em Log. Entretanto, essa permissão em específico não é recomendável de se utilizar fora de ambientes de desenvolvimento/teste.

2 - Uma vez que o seu ambiente docker estiver buildado e rodando certinho, acessa o bash, utilizando o comando: 

> ```docker exec -it store_api_app bash``` (ou, no lugar do "store_api_app", o nome do container da sua aplicação definido do docker-compose)


3 - Uma vez dentro do console do bash, exexcute o seguinte comando para instalar as dependências do projeto: 

> ```composer install```

4 - Uma vez que as depedências do projeto estiverem instaladas, e .env configurado, execute o seguinte comando no terminal para rodar as migrations e popular o banco de dados: 

>```php artisan migrate```

Execute também o seguinte comando, se preciso:

> ```php artisan key:generate```

5 - Prontinho, seu ambiente de desenvolvimento está configurado, caso queira parar a execução dos containers, execute o seguinte comando:

```docker-compose stop```

### Como funciona a API ?

A API consiste em um ambiente simples para simulação de vendas, onde podemos adicionar produtos a uma venda que será processada e concluída posteriormente.

Para fins de testes da aplicação, se for do seu interesse, pode rodar o seguinte comando para executar todos os testes já montados para o sistema, lembre-se de executar o comando estando no console bash do docker: 

> ```php artisan test```

Mas caso queira testar o fluxo da funcionalidade de uma forma manual, primeiro popule o banco de dados, utilizando o seguinte comando:

>```php artisan db:seed```

Uma vez com o banco populado, pode pegar dados de exemplo para utilizar nas endpoints do sistema.

## Roadmap e Possíveis Melhorias
- [ ] Melhoria de arquitetura de Domain para agregar melhor os recursos das aplicações por entidades.
    * Exemplos: 
        - Agregar a camada de Models aos Domains da aplicação.
        - Adição de UseCases para a camada de Application do Domain.
        - Melhor abstração de DTO's para a camada de Application do Domain para retorno de consultas / especificar campos e retirar campos sensíveis, como ID do Sale, Product, valores de vendas, etc.
        - Desacoplar a camada HTTP do MVC padrão do Laravel para uma camada de "Presentation" no Domain
            - E nesta camada, poderíamos dividir da seguinte forma:
                * API: Sub-camada para a lidar com requisições externas à nossa API
                * CLI: Sub-camada para gerenciamento de comandos
                * HTTP: Sub-camada para desaclopar a camada de Controllers e Routes.

- [ ] Implementar fluxo de Autenticação e Autorização, para segurança, gerenciamento de sessão e controle das rotas. Isso inclui também verificar e validar permissões de acesso para diferentes recursos da API.

- [ ] Injeção automatica de dependências das classes utilizando o AppServiceProvider,
bindando as classes utilizadas.

- [ ] Implementar jobs em filas, queueable para o Listeners, para enviar e-mails de confirmaçao de compra/venda dos produtos.

- [ ] Implementação através de um servidor remoto para realizar implementação de um Cron, ou talvez um serviço em Lambda, para envio dos Jobs/Listeners em fila, para os e-mails citado anteriormente.

- [ ] Implementação de um gateway de pagamento e confirmação de compras através de webhooks.

- [ ] Para a rota de listagem, retornar apenas as vendas que tiveram pagamentos concluídos e status alterados para "completed". 