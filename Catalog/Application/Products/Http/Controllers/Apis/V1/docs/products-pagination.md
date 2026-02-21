---
openapi: 3.0.3
info:
  title: Catalog - Products API
  version: 1.0.0
servers:
  - url: /api
paths:
  /v1/catalog/products:
    get:
      summary: Listar produtos com paginação (10 por página)
      tags:
        - Catalog
        - Products
      parameters:
        - in: query
          name: page
          schema:
            type: integer
            minimum: 1
            default: 1
          description: Número da página (opcional). Tamanho por página fixo em 10.
      responses:
        '200':
          description: Lista paginada de produtos
          content:
            application/json:
              schema:
                type: object
                properties:
                  data:
                    type: array
                    items:
                      type: object
                      properties:
                        id:
                          type: string
                        name:
                          type: string
                        description:
                          type: string
                        price:
                          type: number
                          format: float
                        created_at:
                          type: string
                        updated_at:
                          type: string
                  meta:
                    type: object
                    properties:
                      current_page:
                        type: integer
                      per_page:
                        type: integer
                      total:
                        type: integer
                      last_page:
                        type: integer
              examples:
                success:
                  value:
                    data:
                      - id: "id-1"
                        name: "Produto A"
                        description: "Descrição A"
                        price: 10.5
                        created_at: "2026-02-17 12:00:00"
                        updated_at: "2026-02-17 12:00:00"
                      - id: "id-2"
                        name: "Produto B"
                        description: "Descrição B"
                        price: 7.0
                        created_at: "2026-02-18 08:00:00"
                        updated_at: "2026-02-18 08:00:00"
                    meta:
                      current_page: 1
                      per_page: 10
                      total: 42
                      last_page: 5
        '400':
          description: Requisição inválida
        '422':
          description: Parâmetros de validação inválidos

---

# GET /v1/catalog/products

Descrição:

- Retorna todos os produtos cadastrados, paginados com 10 itens por página.
- A paginação é controlada pelo parâmetro de query `page` (padrão = 1).

Parâmetros:

- `page` (query, integer, opcional): número da página a ser retornada. Valor mínimo 1.

Exemplo de requisição (curl):

```bash
curl -s "http://localhost/api/v1/catalog/products?page=1"
```

Exemplo de response (200):

```json
{
  "data": [
    {
      "id": "id-1",
      "name": "Produto A",
      "description": "Descrição A",
      "price": 10.5,
      "created_at": "2026-02-17 12:00:00",
      "updated_at": "2026-02-17 12:00:00"
    }
  ],
  "meta": {
    "current_page": 1,
    "per_page": 10,
    "total": 42,
    "last_page": 5
  }
}
```

Status esperados:

- `200 OK` — sucesso com lista paginada
- `400 Bad Request` — requisição inválida
- `422 Unprocessable Entity` — parâmetros de paginação inválidos

Observações:

- O tamanho por página é fixo em 10 e não pode ser modificado via query string.
- O controller usa um `ProductResource` para normalizar cada item retornado.
