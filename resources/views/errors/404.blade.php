<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 Not Found</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=unbounded:700|dm-sans:400,500,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
<div class="relative text-center flex justify-center flex-col items-center min-h-screen space-y-5 bg-gray-100">
    <div
        class="absolute inset-0 -z-10 h-full w-full bg-white bg-[linear-gradient(to_right,#f0f0f0_1px,transparent_1px),linear-gradient(to_bottom,#f0f0f0_1px,transparent_1px)] bg-[size:6rem_4rem]">
        <div
            class="absolute bottom-0 left-0 right-0 top-0 bg-[radial-gradient(circle_800px_at_100%_200px,#d5c5ff,transparent)]"></div>
    </div>
    <h1 class="scroll-m-20 text-4xl font-extrabold tracking-tight lg:text-7xl font-unbounded text-destructive rotate-3">
        404</h1>

    <h1 class="scroll-m-20 text-4xl font-extrabold tracking-tight lg:text-5xl font-unbounded">Tidak ditemukan</h1>

    <p class="text-xl text-muted-foreground">
        {{ $exception->getMessage() ?: 'Hmm, kamu pergi terlalu jauh.' }}
    </p>

    @auth
        <a href="{{ route('dashboard') }}" class="font-dmsans inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive bg-primary text-primary-foreground shadow-xs h-9 px-4 py-2 has-[>svg]:px-3 hover:bg-primary/90">
            Kembali ke Dashboard
        </a>
    @else
        <a href="{{ route('home') }}" class="font-dmsans inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive bg-primary text-primary-foreground shadow-xs h-9 px-4 py-2 has-[>svg]:px-3 hover:bg-primary/90">
            Kembali ke Beranda
        </a>
    @endauth
</div>
</body>
</html>
