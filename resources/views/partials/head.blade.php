<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>
    {{ filled($title ?? null) ? $title.' - '.config('app.name', 'PeliHuellas') : config('app.name', 'PeliHuellas') }}
</title>

    <link rel="icon" href="/logo-pelihuellas.svg" sizes="any">
    <link rel="icon" href="/logo-pelihuellas.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/logo-pelihuellas.svg">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&display=swap">

    <style>
        .pf-serif { font-family: 'Playfair Display', Georgia, serif; }
    </style>

@fonts

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
