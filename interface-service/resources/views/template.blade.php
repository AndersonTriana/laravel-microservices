<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite('resources/css/app.css')

    <title>¡Almuerzo gratis!</title>
</head>

<body class="font-sans w-screen m-0 p-0 bg-slate-100 text-slate-800 dark:bg-slate-950 dark:text-slate-300 select-none">
    <nav class="flex justify-around w-10/12 mx-auto mb-7 pb-2 md:text-md lg:text-lg items-center bg-gradient-to-b from-slate-900 shadow-black shadow-lg to-transparent rounded-b-xl h-12">
        <a class="text-center text-sm md:text-2xl font-bold">¡Día de Almuerzos Gratis!</a>
        <a class="hover:text-green-400 hover:scale-110 transition-transform duration-200" href="{{ route('createOrder') }}">Ordenar plato</a>
        <a class="hover:text-amber-400 hover:scale-110 transition-transform duration-200" href="{{ route('orders') }}">Ordenes</a>
        <a class="hover:text-amber-400 hover:scale-110 transition-transform duration-200" href="{{ route('ingredients') }}">Ingredientes</a>
        <a class="hover:text-amber-400 hover:scale-110 transition-transform duration-200" href="{{ route('recipes') }}">Recetas</a>
        <a class="hover:text-amber-400 hover:scale-110 transition-transform duration-200" href="{{ route('marketPurchases') }}">Historial de compras</a>
    </nav>

    @yield('body')
</body>

</html>
