@props([
	'icon'				=> 0,			// Ícone/Imagem do botão
	'label'				=> 0,			// Texto do botão

	'tag'				=> 'a',			// 'a' / 'button'
	'style'				=> 'btn-1',			// Cheque buttons.scss para compatibilidade.
	'theme'				=> 'light',		// 'light' / 'dark'
	'border'			=> '',			// '' / 'circle' / 'smooth'
	'class'				=> '',			// Classes adicionais (se houver necessidade)

	'labelOnDesktop'	=> 0,			// Se ativo, permite a exibição da label somente em telas acima de 1024px
])

<{{ $tag }} class="dp-flex row v-center h-center gap-10px {{ $style }} {{ $theme }} {{ $border }} {{ $class }}" {{ $attributes }}>
	@if($icon)<figure class="dp-flex v-center h-center">@svg($icon)</figure>@endif
	@if($label)<label @if($labelOnDesktop) class="desktop" @endif>{{ $label }}</label>@endif
</{{ $tag }}>
