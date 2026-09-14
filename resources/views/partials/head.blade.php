<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>
    {{ filled($title ?? null) ? $title.' - '.config('app.name', 'PeliHuellas') : config('app.name', 'PeliHuellas') }}
</title>

    <link rel="icon" href="/footprint_huella_logo.svg" sizes="any">
    <link rel="icon" href="/footprint_huella_logo.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/footprint_huella_logo.svg">

@fonts

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
