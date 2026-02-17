# Instruções de Commits e Branches

Objetivo
- Padronizar mensagens de commit e nomes de branches seguindo o padrão Conventional Commits (v1.0.0-beta.4) para facilitar leitura, geração de changelogs e automações.

Referência
- Base: https://www.conventionalcommits.org/pt-br/v1.0.0-beta.4/

Regras de Commit (resumo)
- Formato do cabeçalho: `<type>[escopo opcional]: <descrição curta>`
  - `type`: use um dos tipos principais: `feat`, `fix`, `docs`, `style`, `refactor`, `perf`, `test`, `build`, `ci`, `chore`, `revert`.
  - `escopo` (opcional): módulo/área afetada, ex.: `auth`, `product`.
  - `descrição curta`: imperativa, minúscula, sem ponto final, até ~50 caracteres.
- Corpo (opcional): explique o *porquê* da mudança e detalhes de implementação; quebre linhas a ~72 caracteres.
- Footer (opcional): referencie issues ou breaking changes.
  - Para breaking changes use `BREAKING CHANGE: <descrição>` no footer OU adicione `!` após o type/scope no cabeçalho: `refactor!: remove X`.
  - Use `Closes #<n>` ou `Fixes #<n>` para vincular issues fechadas.
  - Os commits devem ser em inglês.

Exemplos válidos
- `feat(auth): adicionar endpoint de login`
- `fix(product): corrigir cálculo do preço com desconto`
- `docs: atualizar README com instruções de teste`
- `perf(db): reduzir número de queries em listagem`
- `refactor!: remover suporte ao endpoint legado (breaking)`

Boas práticas
- Commits pequenos e atômicos — preferir vários commits claros a um único commit gigante.
- Use o corpo para justificar decisões ou mostrar trechos de comandos de migração. Não repita o que já está no código.
- Assunto em inglês ou português deve ser consistente no repositório; escolha um e mantenha.

Branch naming (padrões)
- Formato geral: `<type>/<short-description>` onde `type` é um dos:
  - `feature` — novas funcionalidades
  - `fix` — correções de bugs
  - `chore` — tarefas de manutenção (dependências, scripts)
  - `docs` — documentação
  - `refactor` — refatorações sem mudança de comportamento
  - `hotfix` — correções urgentes para produção
  - `release` — branchs de release (ex.: `release/1.2.0`)
- Use `kebab-case` para a descrição (ex.: `feature/add-product-endpoint`, `fix/price-calculation`).
- Opcional: iniciar com número de issue: `feature/123-add-product-endpoint`.
  - As branches devem ser em inglês.

Exemplos de branch
- `feature/add-product-endpoint`
- `fix/price-calculation`
- `hotfix/1.2.1-critical-bug`

Regras de Pull Request / Título
- O título do PR deve seguir o mesmo padrão de commit header quando possível.
- Descreva no body do PR os testes realizados e qualquer passo de migração.
- O body do PR deve ser curto e focado em explicar o *porquê* da mudança, não o *o quê* (que deve ser claro no código e no título).

Automação e ganchos (recomendado)
- Recomenda-se configurar `commitlint` + `husky` para validar mensagens de commit localmente.
- No CI, validar que commits/PRs sigam o padrão para habilitar geração automática de changelogs.

Resumo rápido
- Commits: `<type>[scope]: description` — use tipos convencionais e FOOTER para `BREAKING CHANGE` e `Closes #n`.
- Branches: `<type>/<kebab-case-description>` — prefira `feature/`, `fix/`, `chore/`, `hotfix/`, `release/`.

