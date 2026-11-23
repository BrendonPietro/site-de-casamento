<x-site.app :current_page="'gift-list'" :forced-scrolled="true">
    <section class="gift-payment-result">
        <div class="gift-payment-result__card">

            @if ($status === 'success')
                <div class="gift-payment-result__icon">
                    @svg('site.nav.present')
                </div>
                <h1 class="gift-payment-result__title">Muito obrigado!</h1>
                <p class="gift-payment-result__text">
                    Seu pagamento foi aprovado. Faz parte da nossa história e jamais será esquecido.
                </p>

            @elseif ($status === 'pending')
                <div class="gift-payment-result__icon">
                    @svg('site.nav.present')
                </div>
                <h1 class="gift-payment-result__title">Pagamento pendente</h1>
                <p class="gift-payment-result__text">
                    Seu pagamento está sendo processado. Assim que confirmado, você receberá um e-mail.
                </p>

            @else
                <div class="gift-payment-result__icon">
                    @svg('site.nav.sad')
                </div>
                <h1 class="gift-payment-result__title">Pagamento não concluído</h1>
                <p class="gift-payment-result__text">
                    Não foi possível processar o pagamento. Você pode tentar novamente na lista de presentes.
                </p>
            @endif

            @if ($order)
                <div class="gift-payment-result__details">
                    <p><strong>Presente:</strong> {{ $order->gift?->nome ?? '—' }}</p>
                    <p><strong>Valor:</strong> R$ {{ number_format($order->total_value, 2, ',', '.') }}</p>
                    @if ($order->guest_name)
                        <p><strong>Nome:</strong> {{ $order->guest_name }}</p>
                    @endif
                </div>
            @endif

            <a href="{{ route('gift-list') }}" class="btn-primary">
                Voltar para a lista de presentes
            </a>

        </div>
    </section>
</x-site.app>
