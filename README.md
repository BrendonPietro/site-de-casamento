# Site de casamento — Letícia & Brendon

Site completo do nosso casamento, feito do zero e usado de verdade pelos convidados: convite, informações do evento, confirmação de presença, lista de presentes com pagamento online pelo Mercado Pago e um painel administrativo para os noivos acompanharem tudo.

> Projeto pessoal publicado como portfólio. O evento já aconteceu e o site saiu do ar. Os dados de convidados neste repositório são **fictícios**.

## O que o site faz

- **Área do convidado:** cada convidado entra com o próprio login e vê o conteúdo personalizado.
- **Confirmação de presença com delegação:** um convidado pode confirmar por outros (ex.: um casal ou os pais confirmando pelos filhos).
- **Lista de presentes:** presentes inteiros ou divididos em cotas, reserva com prazo de 30 dias e liberação automática das reservas vencidas (`php artisan release:expired-gifts`).
- **Pagamento com Mercado Pago:** checkout, webhook com validação de assinatura, atualização do pedido e das cotas, e e-mail de confirmação do presente.
- **Painel dos noivos (Filament 3):** convidados e delegações, presentes (com upload de foto), quem presenteou e um resumo em números. Acesso restrito aos noivos; convidado comum é redirecionado para o site.
- **Conteúdo do evento:** contagem regressiva, linha do tempo do casal, cerimônia e recepção com mapa, dress code, manual do convidado e interesse no serviço de van.

## Destaques técnicos

- **Cache de página inteira com convidado logado:** o Spatie ResponseCache usa um perfil próprio (`GuestAwareCacheProfile`) que nunca cacheia nem serve cache para quem está logado. Isso corrigiu um bug em que um convidado recebia a página personalizada de outro.
- **Autenticação por um guard próprio (`guest`)**, reaproveitado pelo login do painel Filament.
- **CSS próprio (WPF3):** um sistema de classes utilitárias escrito em SCSS, sem Tailwind, com prefixos de breakpoint.
- **Testes automatizados** do checkout, do webhook do Mercado Pago, da confirmação de presença e do painel.

## Stack

PHP 8.2 · Laravel 10 · Filament 3 · Alpine.js · Vite · SCSS · MySQL · Mercado Pago SDK · Spatie ResponseCache

## Rodar local

```bash
composer install
npm install
cp .env.example .env            # ajuste o banco, a SENHA_NOIVOS e as chaves de teste do Mercado Pago
php artisan key:generate
php artisan migrate --seed      # cria os noivos, os convidados fictícios e a lista de presentes
npm run dev
php artisan serve
```

O seed lê `database/dados/guests.txt`, `delegacoes_presenca.txt` e `gifts.txt`. Senha de cada convidado: os 4 últimos dígitos do telefone (ou o primeiro nome, se não tiver telefone). O painel fica em `/painel`.

## Testes

```bash
php artisan test
```

## Documentação

- [docs/painel.md](docs/painel.md): painel administrativo
- [docs/mercadopago.md](docs/mercadopago.md): credenciais, webhook, cartões de teste e checklist de produção
- [CLAUDE.md](CLAUDE.md): arquitetura e deploy na Hostinger

---

Desenvolvido por [Brendon Pietro](https://github.com/BrendonPietro).
