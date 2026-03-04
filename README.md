# Corporate Travel

Um sistema de gerenciamento de viagens corporativas.

<img width="1410" height="876" alt="image" src="https://github.com/user-attachments/assets/6abd6525-35f4-413f-9abe-ad818a89241f" />

<img width="1410" height="727" alt="image" src="https://github.com/user-attachments/assets/52cfb77e-c21d-4125-829c-88643d8dd394" />

<img width="1410" height="597" alt="image" src="https://github.com/user-attachments/assets/b75105ca-3b64-44ff-96d7-70b4d1167de3" />


---

## 🚀 Como executar

### Back-end (Laravel)
1. Instale as dependências  
   Comando: `cd backend && composer install`

2. Configure o ambiente  
   Comando: `cp .env.example .env`  
   Comando: `php artisan key:generate`

3. Rode as migrations  
   Comando: `php artisan migrate`

4. Suba o servidor  
   Comando: `php artisan serve`

👉 Acesse a API em: http://localhost:8000/api

---

### Front-end (Vue.js)
1. Instale as dependências  
   Comando: `cd frontend && npm install`

2. Suba o front  
   Comando: `npm run dev`

👉 Acesse o front-end em: http://localhost:5173

---

## ⚙️ Configurações necessárias

### Variáveis de ambiente (.env)

**Rodando no Docker:**

DB_CONNECTION=pgsql

DB_HOST=db

DB_PORT=5432

DB_DATABASE=travel_orders

DB_USERNAME=laravel

DB_PASSWORD=subaru


**Rodando localmente:**

DB_CONNECTION=pgsql

DB_HOST=127.0.0.1

DB_PORT=3725

DB_DATABASE=travel_orders

DB_USERNAME=laravel

DB_PASSWORD=subaru


### Banco de dados
- PostgreSQL rodando no container `db` ou local  
- Usuário: `laravel`  
- Senha: `subaru`  
- Banco: `travel_orders`

---

## 🧪 Rodando testes

Comando: `php artisan test`

Os testes cobrem:
- Criação de pedidos  
- Regras de acesso (usuário comum vs admin)  
- Filtros de listagem (status, destino, intervalo de datas)  
- Cenários de erro (ex: ida no passado, destino inválido, token inválido)  

---

## 🔒 Autenticação

Implementada com **JWT**.

- Usuário comum: cria, visualiza e lista apenas suas próprias ordens  
- Admin: pode visualizar todas as ordens, aprovar ou cancelar pedidos (com regra: não cancelar após aprovação)  

---

## 📖 Documentação

A documentação da API é gerada automaticamente com **Scribe**.  
Acesse em: http://localhost:8000/docs  

Inclui exemplos de requisição e resposta, exporta coleção Postman e OpenAPI spec.

---

## 🧩 Estrutura do projeto

### Back-end (Laravel)
- `app/Models` → modelos (User, Order)  
- `app/Http/Controllers` → controllers (AuthController, OrderController)  
- `routes/api.php` → rotas da API  
- `tests/Feature` → testes de backend

### Front-end (Vue.js)
- `frontend/src/views` → páginas (Login.vue, Orders.vue, Admin.vue)  
- `frontend/src/components` → componentes reutilizáveis  
- `frontend/src/services/api.js` → cliente Axios para consumir a API  

---

## 🛠️ Regras de negócio específicas
- Não permitir ida no passado (`after_or_equal:today`)  
- Data de volta ≥ data de ida  
- Destinos limitados a lista pré-definida (São Paulo, Rio de Janeiro, Belo Horizonte, Curitiba)  


## 📖 Observações

A ideia inicialmente era subir a execução em docker, por isso os arquivos de configuração.
Porém tive problemas tanto com minha máquina para a execução dos containers quanto com a subida.
O docker para o banco e o back-end funcionaram mas no front estava com conflitos de pacotes.
Acabei optando por deixar a subida local mesmo ao invés de realizar toda a configuração para docker. 
