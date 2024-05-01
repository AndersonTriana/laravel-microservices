@extends('template')

@section('body')

    <h1 class="mb-8 text-3xl text-center font-bold">Ingredientes Disponibles</h1>

    <div class="m-0 p-0 mx-auto w-10/12 grid gap-3 grid-cols-2 md:grid-cols-3 lg:grid-cols-5">

        @if (!$ingredients['data'])
            <h1 class="text-4xl mt-20 col-span-2 md:col-span-3 lg:col-span-5 text-slate-700 text-center">Aún no hay nada aquí.</h1>
        @endif

        @php
            $sortByIngredientName = function ($a, $b) {
                return strcmp(__('ingredients.' . $a['name']), __('ingredients.' . $b['name']));
            };

            usort($ingredients['data'], $sortByIngredientName);
        @endphp

        @foreach ($ingredients['data'] as $ingredient)
            <div
                class="flex justify-between px-3 min-h-18 min-w-20 my-auto bg-slate-300 dark:bg-slate-900 py-2 pb-2.5 mb-3 text-center rounded-xl hover:shadow-black hover:shadow-lg hover:scale-110 transition-all duration-200">

                <p><strong>{{ __('ingredients.' . $ingredient['name']) }}</strong></p>

                @php($availableQuantity = $ingredient['available_quantity']['available_quantity'])

                @if ($availableQuantity > 1)
                    <p class='text-slate-700 dark:text-green-400'>{{ $availableQuantity }}</p>
                @else
                    <p class='text-slate-700 dark:text-amber-400'>{{ $availableQuantity }}</p>
                @endif

            </div>
        @endforeach
    </div>


    {{-- PAGINATION --}}
    @php($lastPage = $ingredients['meta']['last_page'])

    @if ($lastPage > 1)
        @php($currentPage = $ingredients['meta']['current_page'])

        <div class="my-5 text-center cursor-pointer select-none">
            @if ($currentPage > 1)
                <a class="hover:text-slate-500" href="{{ route('ingredients', $currentPage - 1) }}">&laquo; Anterior</a>
                <a class="hover:text-slate-500" href="{{ route('ingredients', 1) }}">| 1 ...</a>
            @endif

            <a class="cursor-default p-1 mx-1">{{ $currentPage }}</a>

            @if ($currentPage != $lastPage)
                <a class="hover:text-slate-500" href="{{ route('ingredients', $lastPage) }}">... {{ $lastPage }} |</a>
                <a class="hover:text-slate-500" href="{{ route('ingredients', $currentPage + 1) }}">Siguiente &raquo;</a>
            @endif
        </div>
    @endif
@endsection
