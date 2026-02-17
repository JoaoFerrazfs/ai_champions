# TESTING — Padrões e Convenções

Este documento descreve os padrões de testes adotados no projeto, com foco no contexto `Catalog` e nas convenções que usamos (Mockery, estrutura de arquivos, estilo e comandos).

## TL;DR
- Testes unitários: `phpunit` + `Mockery`.
- Convenção de nomes: métodos de teste em camelCase (ex.: `testCreateReturnsEntityOnSaveSuccess`).
- Estrutura de testes: espelhar `Catalog/` dentro de `tests/Catalog/`.
- Padrão de cada teste: // Set // Expectations // Actions // Assertions
- Não mocar `Request` — usar instâncias reais e `merge()` para inserir dados.

## Estrutura de diretórios de teste

- Código: `Catalog/Application/...`, `Catalog/Domain/...`, `Catalog/Infrastructure/...`
- Testes: `tests/Catalog/Application/...`, `tests/Catalog/Domain/...`, `tests/Catalog/Infrastructure/...`

Exemplo:
 - `Catalog/Application/Products/Service/ProductService.php`
 - `tests/Catalog/Application/Products/Service/ProductServiceTest.php`

## Convenção de escrita de testes

1. Comentários de blocos para cada fase do teste:

```php
// Set
// Expectations
// Actions
// Assertions
```

2. Métodos de teste em camelCase e descritivos.

3. Preferir retornos explícitos nos mocks (evitar `andReturnUsing` quando possível).

## Mocking com Mockery — regras práticas

- Criar mocks para dependências externas ao unit under test (repositórios, gateways, serviços externos).
- Usar `Mockery::type()` em vez de closures `Mockery::on()` quando apenas o tipo deve ser verificado:

```php
$repo->expects('create')
    ->with(Mockery::type(ProductEntity::class))
    ->andReturn($expected);
```

- Use `expects()`/`shouldReceive()` conforme necessidade. Chamadas comuns:
  - `->expects('method')->andReturn($value)`
  - `->shouldReceive('method')->once()->andReturn($value)`

- Para mocks de Eloquent `Model` que precisam aceitar `setAttribute`/atributos: usar `makePartial()` e mockar apenas `save()`:

```php
$model = Mockery::mock(ProductModel::class)->makePartial();
$model->expects('save')->andReturn(true);
$model->setAttribute('created_at', '2026-02-17 12:00:00');
```

- Ao finalizar cada teste, garantir `Mockery::close()` (normalmente feito no `tearDown` do `TestCase`).

## Requests nos testes de controller

- Não mocar `ProductRequest`/`Request` com Mockery. Em vez disso, instancie e injete os dados esperados:

```php
$request = new \Catalog\Application\Products\Http\Request\ProductRequest();
$request->merge(['name' => 'X', 'price' => 10.0]);
$response = $controller->create($request);
```

Isso garante que validações e o fluxo do FormRequest sejam preservados.

## Boas práticas adicionais

- Teste o `Domain` primeiro (fonte da verdade). Em seguida, `Application`, e por último `Infrastructure`.
- Para `Infrastructure` (repositórios) prefira testes de integração contra `sqlite::memory` ou um DB isolado.
- Evite fixtures pesadas em testes de unidade — use objetos simples ou factories leves.

## Comandos úteis

- Rodar todos os testes:

```bash
./vendor/bin/phpunit
```

- Rodar apenas os testes do contexto Catalog:

```bash
docker compose exec app ./vendor/bin/phpunit tests/Catalog --colors=never
```

- Rodar arquivo específico:

```bash
docker compose exec app ./vendor/bin/phpunit tests/Catalog/Application/Products/Service/ProductServiceTest.php
```

## Exemplos rápidos

- Teste de Service (padrão):

```php
// Set
$data = [...];
$repo = Mockery::mock(ProductRepositoryInterface::class);
$service = new ProductService($repo);

// Expectations
$expected = new ProductEntity('X','Y',1.0);
$repo->expects('create')->with(Mockery::type(ProductEntity::class))->andReturn($expected);

// Actions
$result = $service->create($data);

// Assertions
$this->assertInstanceOf(ProductEntity::class, $result);
```

- Teste de Repository (save true/false):

```php
// Expectations
$model = Mockery::mock(ProductModel::class)->makePartial();
$model->expects('save')->andReturn(true);

$container = Mockery::mock(Container::class);
$container->expects('make')->with(ProductModel::class)->andReturn($model);

// Actions
$repo = new ProductRepository($container);
$result = $repo->create($entity);

// Assertions
$this->assertInstanceOf(ProductEntity::class, $result);
```

## Próximos passos recomendados

- Padronizar um `tests/CONTRIBUTING.md` com convenções de testes e exemplos de uso do Mockery.
- Adotar factories leves para fixtures em `tests`.
- Criar testes de integração para `ProductRepository` com `sqlite` in-memory.

---

Se quiser, eu genero README atualizado apontando para `docs/`.
