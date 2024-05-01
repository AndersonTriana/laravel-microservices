@extends('template')

@section('body')

    <h1 class="mb-8 text-3xl text-center font-bold">Recetas Disponibles</h1>

    <div class="m-0 p-0 mx-auto w-10/12 md:w-8/12 lg:w-7/12 grid gap-3 grid-cols-2 md:grid-cols-3 lg:grid-cols-3">
        @if (!$recipes['data'])
            <h1 class="text-4xl mt-20 col-span-2 md:col-span-3 lg:col-span-3 text-slate-700 text-center">Aún no hay nada aquí.</h1>
        @endif

        @php
            $sortByRecipeName = function ($a, $b) {
                return strcmp($a['name'], $b['name']);
            };

            usort($recipes['data'], $sortByRecipeName);
        @endphp

        @foreach ($recipes['data'] as $recipe)
            <div
                class="flex flex-col justify-stretch px-3 py-auto min-h-18 min-w-20 bg-slate-300 dark:bg-slate-900 py-2 pb-2.5 text-center rounded-xl hover:shadow-black hover:shadow-lg hover:scale-110 transition-all duration-200">

                <p class="text-left text-sm mb-0.5"><strong>{{ $recipe['name'] }}</strong></p>

                @php($ingredients = $recipe['ingredients'])

                <div class="flex flex-row justify-between text-slate-500 mb-2">
                    <p class="text-xs">Ingrediente</p>
                    <p class="text-xs">Cantidad</p>
                </div>

                @foreach ($ingredients as $ingredient)
                    <p class="flex justify-between text-xs">
                        <strong>{{ __('ingredients.' . $ingredient['name']) }}</strong>
                        {{ $ingredient['quantity'] }}
                    </p>
                @endforeach

            </div>
        @endforeach
    </div>


    {{-- PAGINATION --}}
    @php($lastPage = $recipes['meta']['last_page'])

    @if ($lastPage > 1)
        @php($currentPage = $recipes['meta']['current_page'])

        <div class="my-5 text-center cursor-pointer select-none">
            @if ($currentPage > 1)
                <a class="hover:text-slate-500" href="{{ route('recipes', $currentPage - 1) }}">&laquo; Anterior</a>
                <a class="hover:text-slate-500" href="{{ route('recipes', 1) }}">| 1 ...</a>
            @endif

            <a class="cursor-default p-1 mx-1">{{ $currentPage }}</a>

            @if ($currentPage != $lastPage)
                <a class="hover:text-slate-500" href="{{ route('recipes', $lastPage) }}">... {{ $lastPage }} |</a>
                <a class="hover:text-slate-500" href="{{ route('recipes', $currentPage + 1) }}">Siguiente &raquo;</a>
            @endif
        </div>
    @endif
@endsection
