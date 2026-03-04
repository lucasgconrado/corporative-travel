# Corporate Travel

API e front-end para gerenciamento de viagens corporativas.

---

## 🚀 Como executar

### Back-end (Laravel)
1. Configuração:
   # Instale as dependências
   cd backend composer install

2. Rode as migrations:

   # Configurações das tabelas e key
  ```php artisan key:generate ```
  ```php artisan migrate ```
  ```php artisan serve ```


3. Acesse a API em:

  ```http://localhost:8000/api ```

  
### Front-end (Vue.js)
1. Configuração:
  # Instale as dependências
  cd frontend npm install

2. Suba o front:

  ```cd frontend```
  ```npm run dev```


2. Acesse o front-end em:

  ```http://localhost:5173 ```

## ⚙️ Configurações necessárias
# Variáveis de ambiente (.env)
Para rodar dentro do Docker:

DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=travel_orders
DB_USERNAME=laravel
DB_PASSWORD=subaru

Se rodar Laravel local:

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=3725
DB_DATABASE=travel_orders
DB_USERNAME=laravel
DB_PASSWORD=subaru


### Banco de dados
- PostgreSQL rodando no container db.

- Usuário: laravel

- Senha: subaru

- Banco: travel_orders

## 🧪 Rodando testes
Execute:

``` php artisan test ```

Os testes cobrem:

- Criação de pedidos

- Regras de acesso (usuário comum vs admin)

- Filtros de listagem (status, destino, intervalo de datas)

- Cenários de erro (ex: ida no passado, destino inválido, token inválido)

## 🔒 Autenticação
Implementada com JWT.

- Usuário comum:

Cria, visualiza e lista apenas suas próprias ordens.

- Admin:

Pode visualizar todas as ordens.

Pode aprovar ou cancelar pedidos (com regra: não cancelar após aprovação).

## 📖 Documentação
A documentação da API é gerada automaticamente com Scribe.

Acesse em:

``` http://localhost:8000/docs ``` 

Inclui exemplos de requisição e resposta.

Exporta coleção Postman e OpenAPI spec.

## 🧩 Estrutura do projeto
Back-end (Laravel)
- app/Models → modelos (User, Order)

- app/Http/Controllers → controllers (AuthController, OrderController)

- routes/api.php → rotas da API

- tests/Feature → testes de integração

- tests/Unit → testes unitários

Front-end (Vue.js)
- frontend/src/views → páginas (Login.vue, Orders.vue, Admin.vue)

- frontend/src/components → componentes reutilizáveis

- frontend/src/services/api.js → cliente Axios para consumir a API

## 🛠️ Regras de negócio específicas
- Não permitir ida no passado (after_or_equal:today).

- Data de volta ≥ data de ida.

- Destinos limitados a lista pré-definida (São Paulo, Rio de Janeiro, Belo Horizonte, Curitiba).
