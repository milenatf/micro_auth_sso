# Microserviço de autenticação SSO com laravel socialite e keycloak

## Requisitos
- Docker
- Docker Compose
- Mysql 8.0
- PHP 8.2
- Laravel 11

## Links úteis
- [Scialite Providers](https://socialiteproviders.com/about/)
- [Scialite Providers](https://socialiteproviders.com/Keycloak/) - Keycloak

### Configurações iniciais
Criar o arquivo de configuração .env
```sh
cd diretorio/raiz/da/aplicacao/micro_auth
cp .env-example .env
```

Atribuir os valores às variáveis de ambiente no .env

> APP_NAME="Micro Auth"
>
> APP_URL=http://localhost:8083
>
> DB_CONNECTION=mysql
>
> DB_HOST=db_micro_auth
>
> DB_PORT=3306
>
> DB_DATABASE=db_micro_auth
>
> DB_USERNAME=admin
>
> DB_PASSWORD=123

Para ambiente windows
Alterer o /etc/hosts ou C:\Windows\System32\drivers\etc\hosts
```
127.0.0.1 host.docker.internal
```
No .env
> KEYCLOAK_BASE_URL="http://host.docker.internal:8082"

### Executar a aplicação
```sh
docker compose up -d --build
```

Se, no log do container do mysql, for exibida a mensagem abaixo, quer dizer que o banco de dados está executando corretamente

> db_micro_auth     | 2025-01-08T17:25:24.343789Z 0 [System] [MY-011323] [Server] X Plugin ready for connections. Bind-address: '::' port: 33060, socket: /var/run/mysqld/mysqlx.sock
>
> db_micro_auth     | 2025-01-08T17:25:24.343867Z 0 [System] [MY-010931] [Server] /usr/sbin/mysqld: ready for connections. Version: '8.0.40'  socket: '/var/run/mysqld/mysqld.sock'  port: 3306  MySQL Community Server - GPL.

## Acessar o container docker do micro_auth e executar os comandos:
Acessar o container do micro auth
```sh
docker compose exec micro_auth bash
```

Instalar as dependências
```sh
composer install
```

Gerar a chave do laravel
```sh
php artisan key:generate
```

Executar a migration
```sh
php artisan migrate
```

Acessar a aplicação
> http://localhost:8083

## Configurações iniciais no keycloak

⚠️ Estas configurações devem ser aplicadas de acordo com a realidade do projeto

- Acessar a aplicação do keycloak
- Criar o reml

Em Clients:
- Definir o client Id: confidential-client (ou qualquer outro nome)
- Name: confidential-client (ou qualquer outro nome)
- Root URL: A URL da aplicação de autenticação, neste caso é: http://localhost:8083
- Valid redirect URL: http://localhost:8083/auth/keycloak/callback
- Web origins: http://localhost:8083
- Admin URL: http://localhost:8083


