# POST /v1/catalog/products — Fluxo de criação de produto

Resumo
- Endpoint: `POST /v1/catalog/products`
- Objetivo: criar um novo produto no catálogo seguindo a arquitetura DDD (Controller → Application → Domain → Infrastructure).

Formato da requisição
- Content-Type: `application/json`

Exemplo de payload
```
{
  "name": "Nome do produto",
  "sku": "SKU123",
  "price": 199.90,
  "description": "Descrição opcional",
  "stock": 10
}
```

Validação
- Onde: camada de Request / `FormRequest` (validação de campos obrigatórios, tipos, ranges, unicidade de SKU).
- Em caso de falha: resposta HTTP `422 Unprocessable Entity` com detalhes dos erros.

Fluxo de execução (sequência detalhada)

1. Rota
   - A requisição chega em `POST /v1/catalog/products` e é roteada conforme [Catalog/Application/Routes/Products/api.php](Catalog/Application/Routes/Products/api.php).

2. Controller
   - O controller recebe o `FormRequest` já validado.
   - Injeta o UseCase/Service da camada de Application responsável por criar produtos.
   - Converte os dados validados em um DTO ou array e delega ao UseCase.

3. Application (UseCase / Service)
   - Orquestra a criação do `Product`: monta a entidade de domínio, aplica normalizações e regras de aplicação (ex.: cálculos, padrões de dados).
   - Faz validações de negócio básicas (ex.: `price >= 0`).
   - Chama o repositório (interface) definido em Domain/Contracts para persistir o agregado.

4. Domain
   - A entidade/aggregate `Product` encapsula invariantes (ex.: SKU obrigatório, price não-negativo).
   - Qualquer violação das invariantes resulta em exceção de domínio que deve ser mapeada para um código HTTP apropriado.

5. Infrastructure (Repository / Persistence)
   - Implementação concreta do repositório persiste a entidade (por exemplo, via Eloquent/`app/Models/Product.php`).
   - Retorna a entidade persistida ou lança erro de infra (ex.: falha no DB).

6. Pós-persistência
   - O UseCase pode disparar eventos (ex.: `ProductCreated`) para processamento assíncrono.
   - O UseCase mapeia a entidade para um Resource/array para resposta.

7. Resposta
   - Controller retorna `201 Created` com o recurso do produto em JSON.

Códigos de resposta esperados
- `201 Created`: produto criado com sucesso — body: recurso do produto (`id`, `name`, `sku`, `price`, `stock`, `created_at`, `updated_at`).
- `422 Unprocessable Entity`: validação falhou — body: erros de validação.
- `409 Conflict`: conflito de negócio (ex.: SKU duplicado), quando aplicável.
- `500 Internal Server Error`: falha de infra.

Tratamento de erros
- Validação: `FormRequest` → 422.
- Erros de domínio: mapear para 4xx (ex.: 400/409) com mensagens claras.
- Erros de infra: 500 e logging para diagnóstico.

Observabilidade e eventos
- Logs: registrar início da operação, payload (sem dados sensíveis), resultado e erros.
- Events: disparar `ProductCreated` para responsabilidades assíncronas (indexação, notificações, etc.).
- Métricas/Tracing: instrumentar tempo de execução do UseCase e chamadas ao banco.

Testes recomendados
- Teste de integração: POST com payload válido → `201` e registro no DB.
- Testes de validação: campos ausentes/invalidos → `422`.
- Teste de regra de negócio: preço negativo / SKU duplicado → `4xx` esperado.
- Testes unitários do UseCase: mock do repositório para isolar lógica de aplicação.

Exemplo curl
```
curl -X POST https://api.example.com/v1/catalog/products \
  -H "Content-Type: application/json" \
  -d '{"name":"Produto X","sku":"PX-001","price":99.9,"stock":5}'
```

Arquivos de referência
- Rotas: [Catalog/Application/Routes/Products/api.php](Catalog/Application/Routes/Products/api.php)
- Camada Application: [Catalog/Application/Products](Catalog/Application/Products)
- Entidade/Domínio: [Domain/Products](Domain/Products)
- Infra (repositório/Model): [Infrastructure/Products](Infrastructure/Products) e [app/Models](app/Models)

Notas de implementação (boas práticas)
- Controllers finos: delegar validação para `FormRequest` e orquestração para UseCases.
- Definir contratos em `Domain/Contracts` e usar DI para implementar repositórios.
- Manter regras de negócio no domínio e lógica de orquestração na Application.

---
Documento gerado automaticamente por GitHub Copilot — revisão recomendada antes do merge.
