@props([
    'title'    => 'Letícia & Brendon',
    'subtitle' => 'Temos a honra de convidar você para celebrar a união de nossas vidas no casamento',
    'tags'     => [],
])

<section class="home-intro">
    <div class="home-intro__inner">

        <h2 class="home-intro__title">{{ $title }}</h2>

        <p class="home-intro__subtitle">{{ $subtitle }}</p>

        @if (!empty($tags))
            <div class="home-intro__tags">
                @foreach ($tags as $tag)
                    <span class="home-intro__tag">{{ $tag }}</span>
                @endforeach
            </div>
        @endif

    </div>
</section>
