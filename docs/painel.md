# Painel Administrativo — Referência

Painel restrito aos **noivos** para gerenciar convidados, delegações de presença, presentes e ver quem presenteou. Construído com **Filament v3** sobre o guard `guest` que já existe no site.

- **URL:** `/painel`
- **Quem acessa:** apenas convidados com `tipo` ∈ `noivo` / `noiva`
- **Login:** por `nome_completo` + senha (o mesmo do site)

---

## Acesso e permissões

O controle é por **tipo de convidado**, não por ID — sobrevive a re-seed/reordenação.

| Camada | Arquivo | Papel |
|--------|---------|-------|
| `Guest::isDono()` | `app/Models/Guest.php` | `true` se `tipo` ∈ `Guest::TIPOS_PAINEL` (`noivo`/`noiva`) |
| `Guest::canAccessPanel()` | `app/Models/Guest.php` | Contrato `FilamentUser`; retorna `isDono()` |
| `EnsurePanelOwner` | `app/Http/Middleware/EnsurePanelOwner.php` | Convidado comum logado → **redirect** pro site (em vez do 403 padrão) |
| Link "Painel" | `resources/views/components/site/content/header.blade.php` | Só renderiza para `auth('guest')->user()?->isDono()` (desktop + mobile) |

### Por que o `EnsurePanelOwner` está no `$middlewarePriority`

O Filament barra não-autorizados com **403** via `canAccessPanel()`. Como o requisito é **redirecionar** (não mostrar erro), o `EnsurePanelOwner` precisa rodar **antes** do `Authenticate` do Filament. O Laravel reordena middlewares pela lista de prioridade do Kernel, então o `EnsurePanelOwner` foi adicionado ao `$middlewarePriority` (em `app/Http/Kernel.php`) logo antes de `AuthenticatesRequests`/`Authenticate`. Sem isso, o `Authenticate` rodaria primeiro e dispararia o 403.

Comportamento resultante:

| Quem | Em `/painel` |
|------|--------------|
| Não logado | Redirect para `/painel/login` (tela do Filament) |
| Convidado comum (logado) | Redirect para a home do site |
| Noivo / noiva | Entra no painel |

### Login por nome (não e-mail)

`app/Filament/Pages/Auth/Login.php` estende o login do Filament e troca o campo `email` por `nome_completo`, reaproveitando `Guest::getAuthPassword()` (coluna `senha`). É o mesmo par de credenciais do `GuestAuthController` do site.

### Como dar acesso a uma pessoa

Basta o convidado ter `tipo = noivo` ou `noiva` e uma `senha` definida. Pelo próprio painel: **Convidados → editar → Tipo** e, se necessário, preencher a seção **Senha de acesso** (só grava quando preenchida; é hasheada com `Hash::make`).

> ⚠️ Qualquer convidado marcado como `noivo`/`noiva` ganha acesso total ao painel. Use esses tipos apenas para o casal.

---

## Estrutura de arquivos

```
app/
├── Filament/
│   ├── Concerns/HandlesSnBooleans.php        # trait: Toggle <-> 'S'/'N'
│   ├── Pages/Auth/Login.php                   # login por nome_completo
│   ├── Resources/
│   │   ├── GuestResource.php                  # Convidados
│   │   │   └── RelationManagers/DependentesRelationManager.php  # delegação
│   │   ├── GiftResource.php                   # Presentes
│   │   └── GiftOrderResource.php              # Presenteados (somente leitura)
│   └── Widgets/StatsOverview.php              # números do dashboard
├── Http/Middleware/EnsurePanelOwner.php       # gate + redirect
└── Providers/Filament/AdminPanelProvider.php  # config do painel
```

---

## Páginas do painel

### Convidados (`GuestResource`)

- **Lista:** nome, tipo (badge — noivos destacados), presença (✓/✗), van, calçado; colunas opcionais de telefone e último acesso.
- **Busca:** nome e telefone. **Filtros:** tipo, presença (confirmada/pendente), interesse na van.
- **Formulário:** dados pessoais, toggles de presença/van, e seção de senha de acesso.
- **Delegação de presença** (aba na edição — `DependentesRelationManager`): lista quem aquele convidado **pode confirmar**. Usa a relação `dependentes` (`belongsToMany` via tabela `delegacoes_presenca`). Botão **Adicionar pessoa** anexa um convidado existente; **Remover** desfaz. Não cria convidados novos por ali.

### Presentes (`GiftResource`)

- **Formulário:** nome, descrição, valor (R$), **categoria** (campo com `datalist` — sugere as categorias já cadastradas, mas aceita digitar uma nova), **foto** (upload em `storage/gifts`, disco `public`); seção de cotas (campos de cota aparecem só quando **cotável** está ligado); seção de exibição (ativo/favorito).
- **Lista:** foto, nome, valor, categoria, cotas (`vendidas/total`), ativo, vendido. **Filtros:** categoria, ativo, cotável.

### Presenteados (`GiftOrderResource`) — somente leitura

Os pedidos são gerados pelo fluxo do Mercado Pago — o painel só consulta (`canCreate()` = `false`, sem editar/excluir).

- **Lista:** data, quem presenteou, presente, valor, forma de pagamento (badge), status (badge colorido). **Filtros:** status e forma de pagamento.
- **Ver (modal/infolist):** dados do presente, quem presenteou + **mensagem**, e bloco de pagamento (status, método, ID do pagamento MP, referência, datas).

### Dashboard (`StatsOverview`)

Três números: **presenças confirmadas** (com pendentes na descrição), **total arrecadado** (soma dos pedidos aprovados) e **total de convidados**.

---

## Tema visual

Definido em `app/Providers/Filament/AdminPanelProvider.php`:

| Item | Valor |
|------|-------|
| Cor primária | `#414B33` (verde "Main" do site) |
| Fonte | `Lora` |
| Marca | `Letícia & Brendon` |
| Path / guard | `painel` / `guest` |

> A cor primária é aplicada em runtime (variáveis CSS do Filament) — **não precisa** de `npm run build` para o verde valer. Só os assets base do Filament (publicados em `public/`) precisam existir.

---

## Cache de resposta

O painel inclui `DoNotCacheResponse` no próprio `->middleware()` do provider, então **não** precisa do alias `doNotCacheResponse` por rota nem aparece no Spatie ResponseCache. Conteúdo do painel é sempre dinâmico.

---

## Convenções de dados

- **Colunas booleanas são `char(1)` `'S'`/`'N'`** (`presenca_confirmada`, `interesse_van`, `cotavel`, `vendido`, `reservado`, `favorito`, `ativo`). O trait `HandlesSnBooleans::snToggle()` faz a ponte Toggle ↔ `'S'`/`'N'`.
- **`tipo`** é enum: `convidado, madrinha, padrinho, mae, pai, avo, ava, familiar, noivo, noiva` (rótulos amigáveis em `GuestResource::tipoOptions()`).
- **`gift_orders.status`**: `P` pendente, `A` aprovado, `R` recusado, `E` expirado, `C` cancelado (`GiftOrderResource::statusOptions()`).
- **`gift_orders.payment_method`**: valores do MP (`pix`, `credit_card`, `debit_card`, `account_money`, `ticket`…), traduzidos em `GiftOrderResource::paymentLabel()`.

---

## Comandos úteis

```bash
# Gerar novo resource / relation manager / widget
php artisan make:filament-resource NomeDoModel
php artisan make:filament-relation-manager XResource relacao tituloAttr
php artisan make:filament-widget NomeWidget --stats-overview

# (Re)publicar os assets do Filament após atualizar o pacote
php artisan filament:assets

# Atualizar o Filament
php artisan filament:upgrade

# Conferir as rotas do painel
php artisan route:list --path=painel
```

---

## Testes

`tests/Feature/PanelSmokeTest.php` cobre: redirect de não-logado, redirect de convidado comum, render de todas as páginas como noivo (lista/criar/editar + delegação) e o infolist de um pedido.

```bash
php artisan test --filter=PanelSmokeTest
```

> ⚠️ **APP_URL nos testes:** o `.env` usa `APP_URL=leticiaebrendon.test` (sem `http://`). Sem o esquema, o `parse_url` trata tudo como *path* e **toda rota dá 404 em teste/console** (no navegador funciona porque a request real carrega o host). Por isso o `phpunit.xml` define `APP_URL=http://localhost`. O ideal é o `.env` usar `http://leticiaebrendon.test` (local) e `https://leticiaebrendon.com` (produção). Ver também a regra geral de APP_URL.

---

## Deploy (Hostinger)

O Filament adiciona **dependências** e **assets** que precisam chegar à produção:

1. **Dependências** (`vendor/`): o pacote `filament/filament` e suas libs (livewire, etc.).
2. **Assets** publicados: `public/css/{filament,forms,support}` e `public/js/filament`.
3. **Symlink de storage** (para as fotos dos presentes): `php artisan storage:link`.

Caminhos de deploy:

- **FTP (fluxo atual do projeto):** subir o `vendor/` atualizado **e** as pastas de assets em `public/`. Depois, no SSH: `php artisan responsecache:clear` (e `config/route/view:cache` se aplicável).
- **Composer no servidor:** rodar `composer install` no Linux da Hostinger.
  > ⚠️ O `composer.json` tem `config.cafile` apontando para um caminho **Windows** (`C:/laragon/...`). Esse caminho não existe no servidor e quebraria o `composer install` lá — remova/ajuste o `cafile` se for compor no servidor.

> `composer.json` tem `config.platform.php = 8.2` para o Composer resolver pacotes compatíveis com a Hostinger (foi o que rebaixou o `openspout` para uma versão de PHP 8.2). Mantenha esse lock.

---

## Troubleshooting

| Sintoma | Causa provável | Solução |
|---------|----------------|---------|
| Convidado comum vê **403** em vez de redirect | `EnsurePanelOwner` rodando depois do `Authenticate` | Garantir que ele está no `$middlewarePriority` do Kernel, antes de `AuthenticatesRequests` |
| **404** em todas as rotas nos testes | `APP_URL` sem `http://` | `phpunit.xml` define `APP_URL=http://localhost` (ver seção Testes) |
| Painel sem estilo / ícones quebrados | Assets do Filament não foram para produção | `php artisan filament:assets` e subir `public/css|js/{filament,forms,support}` |
| Foto do presente não aparece | Falta o symlink de storage | `php artisan storage:link` |
| Link "Painel" não aparece no header | Convidado não é `noivo`/`noiva` | Ajustar o `tipo` do convidado |

---

## Checklist ao ir para produção

1. Subir `vendor/` (com o Filament) e os assets `public/css|js/{filament,forms,support}`
2. `php artisan storage:link` no servidor (fotos dos presentes)
3. Confirmar `APP_URL=https://leticiaebrendon.com` (com esquema, sem path) e `APP_ENV=production`
4. `php artisan config:cache` / `route:cache` / `view:cache`
5. `php artisan responsecache:clear`
6. Conferir login no painel com a conta de noivo/noiva
