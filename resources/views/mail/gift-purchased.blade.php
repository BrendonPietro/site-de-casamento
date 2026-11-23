<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Obrigado pelo presente!</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Georgia, 'Times New Roman', serif;
            background-color: #f5f2ee;
            color: #3a3a2e;
            line-height: 1.7;
        }
        .wrapper {
            max-width: 580px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        }
        .header {
            background-color: #6a7d55;
            padding: 40px 40px 32px;
            text-align: center;
        }
        .header__names {
            font-family: Georgia, serif;
            font-size: 13px;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.75);
            margin-bottom: 6px;
        }
        .header__title {
            font-size: 26px;
            color: #ffffff;
            font-weight: normal;
            letter-spacing: 0.04em;
        }
        .body {
            padding: 36px 40px;
        }
        .greeting {
            font-size: 17px;
            color: #3a3a2e;
            margin-bottom: 16px;
        }
        .message {
            font-size: 15px;
            color: #5a5a4a;
            margin-bottom: 16px;
        }
        .gift-card {
            background: #f8f6f2;
            border-left: 3px solid #6a7d55;
            border-radius: 4px;
            padding: 20px 24px;
            margin: 24px 0 28px;
        }
        .gift-card__label {
            font-size: 11px;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #8a8a7a;
            margin-bottom: 6px;
        }
        .gift-card__name {
            font-size: 17px;
            color: #3a3a2e;
            margin-bottom: 4px;
        }
        .gift-card__value {
            font-size: 15px;
            color: #6a7d55;
            font-style: italic;
        }
        .closing {
            font-size: 15px;
            color: #5a5a4a;
            margin-bottom: 8px;
        }
        .signature {
            font-size: 17px;
            color: #6a7d55;
        }
        .footer {
            padding: 20px 40px;
            background: #f8f6f2;
            border-top: 1px solid #ece8e0;
            text-align: center;
            font-size: 12px;
            color: #9a9a8a;
        }
        .footer a {
            color: #6a7d55;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <p class="header__names">Letícia &amp; Brendon</p>
            <h1 class="header__title">Obrigado pelo presente! 💚</h1>
        </div>

        <div class="body">
            <p class="greeting">Oi, {{ explode(' ', trim($order->guest_name))[0] }}!</p>

            <p class="message">
                Recebemos seu presente e gostaríamos de agradecer por fazer parte desse momento tão importante para nós.
            </p>

            <p class="message">
                Estamos vivendo uma fase muito especial e saber que temos pessoas tão queridas ao nosso lado torna tudo
                ainda mais significativo. Sua lembrança fará parte da construção do nosso lar e nos sentimos honrados
                por ter um pedacinho de você no início dessa nova história.
            </p>

            <p class="message">
                Muito obrigada por contribuir para a realização dos nossos sonhos e por celebrar este momento conosco.
            </p>

            <div class="gift-card">
                <p class="gift-card__label">Presente confirmado</p>
                <p class="gift-card__name">{{ $order->gift->nome }}</p>
                <p class="gift-card__value">
                    @if ($order->quantity)
                        {{ $order->quantity }} {{ $order->quantity == 1 ? 'cota' : 'cotas' }}
                        &mdash;
                    @endif
                    R$ {{ number_format($order->total_value, 2, ',', '.') }}
                </p>
                @if ($order->message)
                    <p style="margin-top:12px;padding-top:12px;border-top:1px solid #e4e0d8;font-size:14px;color:#6a6a5a;font-style:italic;">
                        "{{ $order->message }}"
                    </p>
                @endif
            </div>

            <p class="closing">Com carinho,</p>
            <p class="signature">Lê e Brendon</p>
        </div>

        <div class="footer">
            <p>
                <a href="{{ config('app.url') }}">leticiaebrendon.com</a>
                &nbsp;&middot;&nbsp;
                14 de novembro de 2026
            </p>
        </div>
    </div>
</body>
</html>
