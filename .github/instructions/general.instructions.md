---
applyTo: "**"
---

# Instruções gerais do repositório

Objetivo
- Orientar o GitHub Copilot a gerar sugestões de código consistentes com a arquitetura DDD, padrões PHP/Laravel e convenções de teste adotadas no repositório.

# Instruções gerais do repositório

Objetivo
- Orientar o GitHub Copilot a gerar sugestões de código consistentes com a arquitetura DDD, padrões PHP/Laravel e convenções de teste adotadas no repositório.

Escopo
- Aplica‑se a sugestões para arquivos em `Catalog/`, `app/`, `tests/` e `docs/`.

Prioridades
- Respeitar camadas: Controller → Application (Use Case / Service) → Domain → Infrastructure.
- Priorizar legibilidade humana: nomes descritivos, funções curtas e early return para reduzir complexidade.
- Preferir injeção de dependência (constructor injection) e tipagem explícita.

Regras rápidas
- Defina contratos (interfaces) em `Domain/Contracts` e registre bindings em `Providers`.
- Controllers devem ser finos: use `FormRequest` para validação e delegue a Services/UseCases.
- Não instanciar classes concretas do domínio diretamente; dependa de interfaces para facilitar testes e mudança de implementações.
- Evitar helpers globais no domínio; encapsule em serviços injetados quando necessário.


Regras de implementação (faça sempre)
- Use `FormRequest` para validação em controllers.
- Mova lógica para Services/UseCases; controllers apenas coordenam.
- Defina contratos (interfaces) em `Domain/Contracts` e registre bindings em `Providers`.
- Evite helpers globais no domínio; use serviços injetados.

Comentários e documentação
- Comentários devem explicar o *porquê*, não o *o quê*.
- Docblocks só quando agregam informação (contratos públicos, tipos complexos).

Exemplos rápidos
- Controller (early return + DI):

```php
public function __construct(ProductService $service)
{
	$this->service = $service;
}

public function store(ProductRequest $request)
{
	$data = $request->validated();
	$product = $this->service->create($data);
	if (! $product) {
		return response()->json(['error' => 'invalid'], 400);
	}
	return response()->json($product, 201);
}
```

- Teste de Service (Mockery::type):

```php
$repo = Mockery::mock(ProductRepositoryInterface::class);
$repo->expects('create')->with(Mockery::type(ProductEntity::class))->andReturn($expected);

$service = new ProductService($repo);
$result = $service->create($data);
```

Checklist rápido para sugestões
- Seguir camadas DDD.
- Usar DI e declarar tipos.
- Aplicar early return e funções curtas.
- Evitar helpers globais no domínio.
- Gerar testes usando Mockery e factories.

Se uma sugestão violar estas regras, apresente uma alternativa que siga o padrão.

