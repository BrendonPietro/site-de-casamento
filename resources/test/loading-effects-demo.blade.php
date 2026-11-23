{{-- 
=== DEMONSTRAÇÃO DOS EFEITOS DE LOADING ===
Exemplos de como usar cada tipo de animação de saída
--}}

{{-- 1. Zoom Out (Padrão) - Suave diminuição e fade --}}
<x-site.system.loading 
    :icon="'site.logo.default'" 
    :type="'fill'" 
    :exitAnimation="'zoom-out'"
    :debug="true" 
/>

{{-- 2. Fade Simples - Desaparece suavemente --}}
<x-site.system.loading 
    :icon="'site.logo.default'" 
    :type="'fill'" 
    :exitAnimation="'fade'"
    :debug="true" 
/>

{{-- 3. Swipe Up - Desliza para cima rapidamente --}}
<x-site.system.loading 
    :icon="'site.logo.default'" 
    :type="'fill'" 
    :exitAnimation="'swipe-up'"
    :debug="true" 
/>

{{-- 4. Swipe Down - Desliza para baixo rapidamente --}}
<x-site.system.loading 
    :icon="'site.logo.default'" 
    :type="'fill'" 
    :exitAnimation="'swipe-down'"
    :debug="true" 
/>

{{-- 5. Swipe Left - Desliza para esquerda --}}
<x-site.system.loading 
    :icon="'site.logo.default'" 
    :type="'fill'" 
    :exitAnimation="'swipe-left'"
    :debug="true" 
/>

{{-- 6. Swipe Right - Desliza para direita --}}
<x-site.system.loading 
    :icon="'site.logo.default'" 
    :type="'fill'" 
    :exitAnimation="'swipe-right'"
    :debug="true" 
/>

{{-- 7. Slide Up - Desliza para cima com bounce --}}
<x-site.system.loading 
    :icon="'site.logo.default'" 
    :type="'fill'" 
    :exitAnimation="'slide-up'"
    :debug="true" 
/>

{{-- 8. Slide Down - Desliza para baixo com bounce --}}
<x-site.system.loading 
    :icon="'site.logo.default'" 
    :type="'fill'" 
    :exitAnimation="'slide-down'"
    :debug="true" 
/>

{{-- 9. Rotate Fade - Gira e diminui dramaticamente --}}
<x-site.system.loading 
    :icon="'site.logo.default'" 
    :type="'fill'" 
    :exitAnimation="'rotate-fade'"
    :debug="true" 
/>

{{-- 10. Scale Fade - Aumenta com blur e desaparece --}}
<x-site.system.loading 
    :icon="'site.logo.default'" 
    :type="'fill'" 
    :exitAnimation="'scale-fade'"
    :debug="true" 
/>

{{-- 11. Blur Fade - Desfoca e aumenta levemente --}}
<x-site.system.loading 
    :icon="'site.logo.default'" 
    :type="'fill'" 
    :exitAnimation="'blur-fade'"
    :debug="true" 
/>

{{-- 
=== COMO USAR ===

Apenas adicione o parâmetro exitAnimation:

<x-site.system.loading :exitAnimation="'swipe-up'" />

=== TIPOS DISPONÍVEIS ===

• zoom-out     → Diminui e desaparece (padrão)
• fade         → Fade simples
• swipe-up     → Desliza para cima
• swipe-down   → Desliza para baixo  
• swipe-left   → Desliza para esquerda
• swipe-right  → Desliza para direita
• slide-up     → Slide bounce para cima
• slide-down   → Slide bounce para baixo
• rotate-fade  → Gira e diminui
• scale-fade   → Aumenta com blur
• blur-fade    → Desfoca e aumenta

=== CARACTERÍSTICAS ===

✨ Cada efeito tem duração otimizada
🎯 Cubic-bezier personalizados para suavidade
🔄 Compatível com modo debug
🎨 Efeitos profissionais e modernos
--}}
