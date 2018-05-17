
PLATAFORMA DE FOLHETOS DIGITAIS.
---
A aplicação foi modelada em 4 partes:

1. API (PHP/MySql/Yii2)
2. Painel administrativo. (Angular)
3. Painel do vendedor. (Angular)
4. Aplicação mobile para distribuição dos folhetos. (React)


Estrutura dos diretórios
-------------------

      modules/apiv1/controllers             Recursos da API
      modules/apiv1/models                  Acesso a base de dados
      web/painel                            Painel do vendedor
      web/admin                             Painel do administrador
      web/app                               Aplicação do cliente


# Requerimento

O mínimo requerido para rodar este projeto no web service seria PHP 5.4.0, Apache 2 e MySql 5

---
# Instalação

## Inicilização da aplicação **(SIMPLIFICADA)**

1.Inicializa o ambiente

```
$ docker-compose up -d
```

2.Executa o composer do php

```
$ docker-compose exec php after_docker-compose-up.sh
```

## Inicilização da aplicação **(COMPLICADA)**

1. Inicializa o ambiente

```
$ docker-compose up -d
```
2. Executa o composer do php

```
$ docker-compose exec php composer install

```

3. Executa o migrate do rbca

```
$ docker-compose exec php yii migrate --migrationPath=vendor/yiisoft/yii2/rbac/migrations/  --interactive=0
```

4. Executa o migrate da aplicacao

```
$ docker-compose exec php yii migrate  --interactive=0
```

5. Lista os contianers disponiveis

```
$ docker container list
```

6. Acessar o bash do container

**(WINDOWS)**

```
winpty docker exec -it NUMERODOCONTAINNER_API bash
```

**(LINUX)**

```
$ docker exec -it NUMERODOCONTAINNER_API bash
```

7. Rodar as rotinas de teste da API

```
docker-compose exec php codecept run api
```

8. Finalizar o ambiente 

```
$ docker-compose down
```

9. Mysql

```
mysql -uysk_dbu -pysk_pass
```

---
# Configuração


---
# Testando

## JMETER LOAD TESTER

```
$ ../../apache-jmeter-4.0/bin/jmeter -n -t main.jmx -l jmeter-results.jtl
```

# FORCE RESTART

## no linux:

```
$ sudo docker-compose down; sudo docker-compose up -d --force-recreate; sleep 15; sudo docker-compose exec php after_docker-compose-up.sh
```

## no windows:

```
docker-compose down; docker-compose up -d --force-recreate; sleep 60; winpty docker-compose exec php after_docker-compose-up.sh
```


# CURL


Login Admin:
```
curl -i -H "Accept:application/json"  -H "Content-Type:application/json" -XPOST "http://localhost:8000/apiv1/users/login" -d '{"username":"admin","password":"123456"}'
```

Login Vendedor:
```
curl -i -H "Accept:application/json"  -H "Content-Type:application/json" -XPOST "http://localhost:8000/apiv1/users/login" -d '{"username":"vendedor","password":"123456"}'
```

curl -i -H "Accept:application/json"  -H "Content-Type:application/json" -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJqdGkiOiJWdkErWjAzNmxiZGxWSThjNHF1SU5ZZ3poYVVUOWRraUhUYzE1Z2JZM2pjPSIsImlhdCI6MTUyNTEzNzA4NCwiaXNzIjoibG9jYWxob3N0IiwiYXVkIjoibG9jYWxob3N0IiwibmJmIjoxNTI1MTM3MDg0LCJleHAiOjE1Mjc3MjkwODQsImRhdGEiOiIrdll5UWVaWHB5TW5nTXBGRDJWUEJVTTdNQ3MwU0RKYldtTnd6T25KTEZwZ1V0dWFkWWRjYnh0bHREVjFjazRiIn0.-DvjoF-Fo9qX_iXG2T80ukM5z_E73e3BUmr4HClX4__nocMB0l5DJlIL-PIz90sdj8L4890lKfhP8hxqARn2RA" "http://localhost:8000/apiv1/users/me" 

Usuários:
```
curl -i -H "Accept:application/json"  -H "Content-Type:application/json" -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJqdGkiOiJZR3RyMzVwVzZ0NUZTMkE4c0pEQmxFMFByRG5pcGRMOUJ3STFFQlBkVm1zPSIsImlhdCI6MTUyMjgwNDUzMiwiaXNzIjoibG9jYWxob3N0IiwiYXVkIjoibG9jYWxob3N0IiwibmJmIjoxNTIyODA0NTMyLCJleHAiOjE1MjUzOTY1MzIsImRhdGEiOiJTXC8xOVl1dmtNak44d0oxNHhFb01qeWZIRUFyRWtOVUVGd2dUMGpmUkNqbz0ifQ.PEtBFHxsqWdfh1gjLFEWxOocbx7KaFYBnyl_Iij-yLMsnqJkJ7b7UJhHTYAkFZgmxlGIAStHHFkTGZ619LDWMg" "http://localhost:8000/apiv1/users"
```

Usuários somente com email
```
curl -i -H "Accept:applicatioarer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJqdGkiOiJZR3RyMzVwVzZ0NUZTMkE4c0pEQmxFMFByRG5pcGRMOUJ3STFFQlBkVm1zPSIsImlhdCI6MTUyMjgwNDUzMiwiaXNzIjoibG9jYWxob3N0IiwiYXVkIjoibG9jYWxob3N0IiwibmJmIjoxNTIyODA0NTMyLCJleHAiOjE1MjUzOTY1MzIsImRhdGEiOiJTXC8xOVl1dmtNak44d0oxNHhFb01qeWZIRUFyRWtOVUVGd2dUMGpmUkNqbz0ifQ.PEtBFHxsqWdfh1gjLFEWxOocbx7KaFYBnyl_Iij-yLMsnqJkJ7b7UJhHTYAkFZgmxlGIAStHHFkTGZ619LDWMg" "http://localhost:8000/apiv1/users?fields=email,id&sort=email"
```

Material com ddd grupos e sheets

```
curson" -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJqdGkiOiJSVytzZVR4S0NiVmtQUEVkNERMdnhOY3FyVnNRUlgyZEtoS2hlSTVUcXlnPSIsImlhdCI6MTUyNTEzNzg5NywiaXNzIjoibG9jYWxob3N0IiwiYXVkIjoibG9jYWxob3N0IiwibmJmIjoxNTI1MTM3ODk3LCJleHAiOjE1Mjc3Mjk4OTcsImRhdGEiOiJTXC8xOVl1dmtNak44d0oxNHhFb01qeWZIRUFyRWtOVUVGd2dUMGpmUkNqbz0ifQ.voQB5_YiUyMr2ZZjLrH6Sd0H0WJwUhcJ8PwO-KGqef21LY4wKYNChldSx6L9SWPATiaU9HO5nTspd69qGMHujA" "http://localhost:8000/apiv1/handouts/2?expand=handoutDddGroups,handoutSheets"
```