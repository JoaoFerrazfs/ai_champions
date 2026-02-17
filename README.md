```markdown
# ai_champions

Projeto configurado para desenvolvimento Laravel (Docker + SQLite).

Como usar (resumo):

- Criar o projeto Laravel (uma vez):

```bash
# Use o serviço composer para criar o scaffold do Laravel na raiz
docker compose run --rm composer create-project laravel/laravel . "10.*"
```

- Criar o banco SQLite e instalar dependências:

```bash
./bin/setup
```

- Subir a stack (nginx + php-fpm):

```bash
docker compose up --build -d
```

- Gerar chave da aplicação e rodar migrations (se ainda não tiver sido feito):

```bash
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
```

Abra http://localhost:8080 no seu navegador.

```
# ai_champions
