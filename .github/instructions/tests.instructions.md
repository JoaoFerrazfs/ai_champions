---
applyTo: "tests/**"
---

# Instruções gerais para testes

Objetivo
- Garantir que as sugestões de testes geradas pelo Copilot sigam as convenções do repositório: organização, Mockery e legibilidade.

Padrões principais
- Estrutura: os testes devem espelhar a árvore de código. Ex.: `Catalog/Application/Products/...` → `tests/Catalog/Application/Products/...`.
- Organização: usar comentários de seção `// Set // Expectations // Actions // Assertions` em cada teste.

Mocking e expectativas
- Use `Mockery` para dependências externas (repositórios, clients). Prefira `->expects('method')->with(Mockery::type(...))->andReturn(...)` quando possível.
- Não mocar `FormRequest` em testes de controller; instancie a request real e use `merge()` para popular dados.
- Para Eloquent models em testes unitários, use `Mockery::mock(Model::class)->makePartial()` e stub `save()` quando necessário.

Estilo de teste
- Nomes de métodos em camelCase, descritivos (ex.: `testCreateReturnsEntityOnSaveSuccess`).
- Evitar asserções frágeis sobre detalhes internos do Eloquent — foque em comportamento observável.

Execução
- Com docker (serviço `app`):
````instructions
docker compose exec app ./vendor/bin/phpunit tests/Catalog --colors=never
```

# Instruções gerais para testes

Objetivo
- Garantir que as sugestões de testes geradas pelo Copilot sigam as convenções do repositório: organização, Mockery e legibilidade.

Padrões principais
- Estrutura: os testes devem espelhar a árvore de código. Ex.: `Catalog/Application/Products/...` → `tests/Catalog/Application/Products/...`.
- Organização: usar comentários de seção `// Set // Expectations // Actions // Assertions` em cada teste.

Mocking e expectativas
- Use `Mockery` para dependências externas (repositórios, clients). Prefira `->expects('method')->with(Mockery::type(...))->andReturn(...)` quando possível.
- Para repositórios: mock do container (`$this->app->instance(...)`) ou injeção via constructor.
- Não mocar `FormRequest` em testes de controller; instancie a request real e use `merge()` para popular dados.
- Para Eloquent models em testes unitários, use `Mockery::mock(Model::class)->makePartial()` e stub `save()` quando necessário.

Estilo de teste
- Nomes de métodos em camelCase, descritivos (ex.: `testCreateReturnsEntityOnSaveSuccess`).
- Evitar asserções frágeis sobre detalhes internos do Eloquent — foque em comportamento observável.

Execução
- Com docker (serviço `app`):
```bash
docker compose exec app ./vendor/bin/phpunit tests/Catalog --colors=never
```

Exemplos rápidos
- Teste de controller (usar request real e `merge()`):

```php
$request = new \Illuminate\Http\Request();
$request->merge([/* dados */]);

$controller = new ProductController($serviceMock);
$response = $controller->store($request);
```

- Teste de Service (Mockery::type):

```php
$repo = Mockery::mock(ProductRepositoryInterface::class);
$repo->expects('create')->with(Mockery::type(ProductEntity::class))->andReturn($expected);

$service = new ProductService($repo);
$result = $service->create($data);
```

Checklist rápido para sugestões de testes
- Gerar testes que sejam independentes e determinísticos.
- Mockar portas/contratos, não entidades de domínio.
- Usar `Mockery::type()` sempre que apenas o tipo precisa ser validado.
- Criar instâncias reais de `Request` para controllers e usar `merge()`.

````
