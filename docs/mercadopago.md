# Mercado Pago — Referência de Integração

## Credenciais no `.env`

```env
# Sandbox (testes) — prefixo TEST-
MERCADO_PAGO_PUBLIC_KEY=TEST-xxxx
MERCADO_PAGO_ACCESS_TOKEN=TEST-xxxx
MERCADO_PAGO_WEBHOOK_SECRET=     # gerado ao criar webhook no painel

# Produção — prefixo APP_USR-
MERCADO_PAGO_PUBLIC_KEY=APP_USR-xxxx
MERCADO_PAGO_ACCESS_TOKEN=APP_USR-xxxx
MERCADO_PAGO_WEBHOOK_SECRET=     # gerado ao criar webhook no painel
```

> Em desenvolvimento, deixe `MERCADO_PAGO_WEBHOOK_SECRET` vazio para pular a verificação de assinatura.

---

## O que precisa ser configurado no painel do MP

### 1. Webhook (obrigatório para processar pagamentos)

Painel: **Suas Integrações → Integrações → Webhooks**

| Campo | Valor |
|-------|-------|
| URL (produção) | `https://leticiaebrendon.com.br/api/mercadopago/webhook` |
| URL (local via tunnel) | `https://<tunnel>.trycloudflare.com/api/mercadopago/webhook` |
| Eventos | Pagamentos |

Após salvar, copie o **Webhook Secret** gerado e coloque em `MERCADO_PAGO_WEBHOOK_SECRET`.

> As back_urls (success/failure/pending) **não** precisam ser configuradas no painel — são passadas diretamente em cada preferência de pagamento.

---

## Testando localmente com webhook

Para que o MP consiga chamar seu servidor local, é necessário expô-lo via tunnel:

```bash
# Instalar cloudflared (Windows, uma vez só)
winget install Cloudflare.cloudflared

# Iniciar o servidor PHP (não usar Apache, pois exige Host: leticiaebrendon.test)
php artisan serve --host=0.0.0.0 --port=8082

# Iniciar tunnel (mantém aberto enquanto testa) — em outro terminal
cloudflared tunnel --url http://localhost:8082
# → Gera algo como: https://abc-def.trycloudflare.com
```

Com o tunnel ativo:
1. Atualize o webhook no painel MP com a URL do tunnel
2. Atualize `APP_URL` no `.env` — **apenas o domínio, SEM path**:
   ```env
   APP_URL=https://abc-def.trycloudflare.com
   ```
   > ⚠️ Nunca inclua o path do webhook no APP_URL (ex: errado: `APP_URL=https://abc.trycloudflare.com/api/mercadopago/webhook`). Isso quebra todas as rotas da aplicação.
3. Limpe o cache: `php artisan config:clear`
4. O `AppServiceProvider` detecta `*.trycloudflare.com` e força HTTPS automaticamente

> **Alternativa sem tunnel:** usar a simulação de webhook no painel MP em *Suas Integrações → Webhooks → Simular notificação*.

---

## Cartões de teste (Sandbox)

O comportamento é controlado pelo **nome do titular** do cartão:

| Titular | Resultado |
|---------|-----------|
| `APRO` | Aprovado |
| `OTHE` | Recusado (outros motivos) |
| `CONT` | Pendente / em processamento |
| `CALL` | Recusado (ligue para o banco) |

Cartões Mastercard válidos para testes:

| Número | CVV | Vencimento |
|--------|-----|------------|
| `5031 4332 1540 6351` | `123` | `11/30` |
| `5416 7526 0258 2580` | `123` | `11/30` |

> ⚠️ Use uma data de vencimento **futura** — o sandbox rejeita datas passadas como inválidas.

**CPF de teste:** `123.456.789-09` (qualquer CPF válido funciona no sandbox)

**PIX no sandbox:** após gerar o QR Code, o MP aprova automaticamente em ~5 min. Para aprovação imediata, use *Simular notificação* no painel.

---

## Verificação de assinatura HMAC

Cada webhook inclui o header `x-signature: ts=...,v1=...`

Template da string assinada:
```
id:{payment_id};request-id:{x-request-id};ts:{ts};
```

> PHP converte `?data.id=X` (query string do MP) para `$_GET['data_id']`. O controller já trata isso corretamente usando `$request->query('data_id')`.

---

## Fluxo completo

```
Usuário clica "Presentear"
  → POST /api/gifts/{id}/reserve
      DB: cria GiftOrder (status=P)
      DB: reserva o presente por 25 min (apenas presentes não cotáveis)
      MP: cria preferência → retorna init_point / sandbox_init_point
  → Frontend redireciona para a URL do checkout MP

Usuário paga no Mercado Pago
  → MP chama POST /api/mercadopago/webhook
      Valida assinatura HMAC (se MERCADO_PAGO_WEBHOOK_SECRET configurado)
      MP API: busca detalhes do pagamento por ID
      DB: atualiza GiftOrder (status=A/R/C, mp_status, paid_at)
      Se aprovado:
        – Presente não cotável → vendido=S, remove reserva
        – Presente cotável     → cotas_vendidas += quantity; se completo, vendido=S
        – Envia GiftPurchasedMail (se guest_email preenchido)
  → MP redireciona para back_url (success / failure / pending)
      GiftPaymentController exibe a página de resultado
```

---

## Mapeamento de status

| Status MP | Status interno |
|-----------|---------------|
| `approved` | `A` |
| `rejected` | `R` |
| `cancelled` / `refunded` | `C` |
| `pending` / `in_process` / `authorized` | `P` |

---

## Reserva e expiração

- Presentes não cotáveis: reservados por **25 minutos** durante o checkout. Expirado, qualquer outro usuário pode comprá-los.
- A preferência MP também é criada com `expires: true` e `expiration_date_to` igual ao `reserved_until` do pedido.
- O comando `php artisan release:expired-gifts` libera reservas vencidas. Em produção, adicionar ao cron:

```bash
* * * * * php /var/www/artisan release:expired-gifts >> /dev/null 2>&1
```

---

## E-mail de agradecimento

Disparado em `MercadoPagoWebhookController::handleApproval()` ao confirmar pagamento.

Configuração para **desenvolvimento** (emails ficam em `storage/logs/laravel.log`):
```env
MAIL_MAILER=log
```

Configuração para **produção**:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.seudominio.com.br
MAIL_PORT=587
MAIL_USERNAME=casamento@leticiaebrendon.com.br
MAIL_PASSWORD=sua-senha
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=casamento@leticiaebrendon.com.br
MAIL_FROM_NAME="Letícia e Brendon"
```

---

## Resultado do pagamento testado (sandbox)

Fluxo end-to-end validado localmente em 2026-06-11:
- Preferência criada com sucesso ✓
- Redirecionamento para checkout MP ✓
- Pagamento processado com cartão de teste ✓
- Webhook recebido e processado ✓ (presente marcado como `vendido=S`, pedido `status=A`)
- E-mail de agradecimento disparado ✓
- Página de resultado exibida ✓

---

## O que muda ao ir para produção

1. Trocar as chaves `TEST-` por `APP_USR-` no `.env`
2. Atualizar a URL do webhook no painel MP para o domínio real
3. Configurar `MAIL_MAILER` com credenciais SMTP reais
4. Verificar `APP_URL=https://www.leticiaebrendon.com.br` e `APP_ENV=production`
5. Agendar `release:expired-gifts` no cron do servidor
6. Rodar `php artisan prepare:deploy` e `php artisan cache:pages`
