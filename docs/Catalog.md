# Catalog

## Visão Geral

O módulo Catalog organiza toda a lógica relacionada a produtos da aplicação seguindo um estilo inspirado em Domain-Driven Design. Ele separa responsabilidades em camadas claras para facilitar manutenção, testes e extensibilidade.

## Arquitetura e pastas principais

- **Camada de Aplicação:** [Catalog/Application](Catalog/Application) — casos de uso, orquestração e validação de entrada.
- **Domínio:** [Domain/Products](Domain/Products) — entidades, value objects e lógica de domínio.
- **Infraestrutura:** [Infrastructure/Products](Infrastructure/Products) — implementações de repositórios, integração com banco e adaptadores externos.
- **Provedores:** [Providers](Providers) — registro de serviços relacionados ao Catalog.

Outros pontos relevantes:
- Migrations de produtos: [database/migrations](database/migrations) (ex.: 2026_02_17_142812_create_products_table.php)
- Modelos e Eloquent: `app/Models` (verifique se existe um model `Product` ou equivalente)
- Rotas e controllers: rotas API/HTTP em [routes](routes) e controllers em [app/Http/Controllers](app/Http/Controllers)

## Como adicionar um novo sub contexto (fluxo rápido)

1. Criar/atualizar entidade de domínio em [Domain/Products](Domain/Products).
2. Adicionar caso de uso em [Catalog/Application](Catalog/Application).
3. Implementar repositório na infraestrutura em [Infrastructure/Products](Infrastructure/Products).
4. Registrar binding no provider apropriado em [Providers](Providers) (ex.: RepositoriesProvider.php).
5. Criar endpoint no controller e rota em `routes/api.php`.
6. Escrever testes em `tests/Catalog` (existem testes de exemplo em `tests/Catalog/...`).

## Boas práticas

- Mantenha a lógica de negócios no domínio, evitando dependências de infra.
- Use DTOs/Requests para validação na borda (controllers).
- Injete repositórios por interfaces e registre implementações em providers.
- Escreva testes unitários para casos de uso e testes de integração para repositórios.

## Contratos e bindings entre domínios

- Classes ou serviços que são usados por múltiplos domínios não devem ser instanciadas diretamente dentro do código consumidor. Em vez disso:
	- Defina um contrato (interface) no `Domain` que descreva o comportamento esperado.
	- Implemente a interface na camada `Infrastructure`.
	- Registre o binding (interface -> implementação) no `Provider` correspondente (ex.: `RepositoriesProvider`).

	Isso mantém baixo acoplamento entre domínios, facilita testes (mock das interfaces) e permite trocar implementações sem alterar consumidores.

	Exemplo rápido:

	- `Catalog/Domain/Products/Contracts/ProductRepositoryInterface.php` (contrato)
	- `Catalog/Infrastructure/Products/Repositories/ProductRepository.php` (implementação)
	- `Catalog/Providers/RepositoriesProvider.php` (binding `app->bind(ProductRepositoryInterface::class, ProductRepository::class)`)

	Seguir esse padrão evita dependências diretas a implementações e preserva a independência dos domínios.
