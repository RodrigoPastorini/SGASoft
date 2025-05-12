
# SGASoft

**SGASoft** é um sistema de gestão empresarial desenvolvido com **Laravel** no backend e **Vue.js** no frontend. O sistema tem como objetivo facilitar o controle de pedidos, fornecedores, e outros recursos essenciais para a organização de empresas.

## 🛠 Tecnologias Utilizadas

- **Laravel 10+** – Framework PHP para construção de APIs e lógica de negócios
- **Vue.js 3 + Inertia.js** – Framework JavaScript moderno com navegação SPA
- **MySQL** – Banco de dados relacional
- **Redis** – Cache e filas
- **Docker** – Ambientes de desenvolvimento padronizados

## 📦 Funcionalidades

- Autenticação e autorização de usuários
- Cadastro, listagem, edição e exclusão de **fornecedores**
- Criação e gestão de **pedidos** por fornecedor
- API RESTful protegida por token
- Cache com Redis
- Separação por tipo de usuário (admin, vendedor, etc.)

## ⚙️ Como Rodar o Projeto

### 1. Clonar o Repositório

```bash
git clone https://github.com/RodrigoPastorini/SGASoft.git
cd SGASoft
```

### 2. Copiar o arquivo `.env`

```bash
cp .env.example .env
```

### 3. Subir o ambiente com Docker

```bash
docker-compose up -d
```

### 4. Instalar as dependências

```bash
docker exec app composer install
docker exec app npm install
docker exec app npm run build
```

### 5. Gerar a chave da aplicação

```bash
docker exec app php artisan key:generate
```

### 6. Rodar as migrations

```bash
docker exec app php artisan migrate
```

### 7. (Opcional) Popular banco com dados fake

```bash
docker exec app php artisan db:seed
```

### 8. Acessar o sistema

Abra no navegador: [http://localhost:8000](http://localhost:8000)


## 🧠 Sobre o Projeto

Este projeto foi desenvolvido como parte de um desafio técnico para vaga de Programador Full Stack. O foco foi desenvolver um mini ERP com funcionalidades reais, utilizando boas práticas, versionamento, e organização de código.

## 📝 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

---

ROTAS PARA A API

Login :
curl --request POST \
  --url http://localhost:8000/api/v1/autenticacao \
  --header 'Content-Type: application/json' \
  --header 'User-Agent: insomnia/11.0.2' \
  --data '{
  "email": "admin@teste.com",
  "password": "12345678"
}
'



POST Pedidos:
curl --request POST \
  --url http://localhost:8000/api/v1/pedidos \
  --header 'Accept: application/json' \
  --header 'Authorization: Bearer 2|yrvGVWyt7TbHSZWmP7lFSf1YtNCTfJfY45HaNlyOb00e5192' \
  --header 'Content-Type: application/json' \
  --header 'User-Agent: insomnia/11.0.2' \
  --data '{
  "fornecedor_id": 1,
  "data": "2025-05-08",
  "observacao": "Pedido urgente",
	"produto_id": 1,
  "quantidade": 5
}'


GET Pedidos:
curl --request GET \
  --url http://localhost:8000/api/v1/fornecedor/1234567800019/pedidos \
  --header 'Accept: application/json' \
  --header 'Authorization: Bearer 2|yrvGVWyt7TbHSZWmP7lFSf1YtNCTfJfY45HaNlyOb00e5192' \
  --header 'User-Agent: insomnia/11.0.2'

PUT Pedidos:
curl --request PUT \
  --url http://localhost:8000/api/v1/pedidos \
  --header 'Accept: application/json' \
  --header 'Authorization: Bearer 1|anZI5oF33hyVXtNTidsp4BKIaTZxvt2wFFuLtyr1fcbe9b72' \
  --header 'Content-Type: application/json' \
  --header 'User-Agent: insomnia/11.0.2' \
  --data '{
	"id": 1,
	"observacao": "Super legal",
	"status": "pendente"
}'

DEL Pedidos:
curl --request DELETE \
  --url http://localhost:8000/api/v1/pedidos \
  --header 'Accept: application/json' \
  --header 'Authorization: Bearer 1|anZI5oF33hyVXtNTidsp4BKIaTZxvt2wFFuLtyr1fcbe9b72' \
  --header 'Content-Type: application/json' \
  --header 'User-Agent: insomnia/11.0.2' \
  --data '{
	"id": 3
}'

Get Fornecedores Pedidos
curl --request GET \
  --url http://localhost:8000/api/v1/fornecedor/1234567800019/pedidos \
  --header 'Accept: application/json' \
  --header 'Authorization: Bearer 2|yrvGVWyt7TbHSZWmP7lFSf1YtNCTfJfY45HaNlyOb00e5192' \
  --header 'User-Agent: insomnia/11.0.2'



Desenvolvido por **Rodrigo Pastorini** 🚀  
[GitHub](https://github.com/RodrigoPastorini)
