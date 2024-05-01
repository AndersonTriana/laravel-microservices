@extends('template')

@section('body')

    <h1 class="mb-8 text-3xl text-center font-bold">Historial de compras</h1>

    <div
        class="m-0 p-0 mb-5 mx-auto w-10/12 min-w-[380px] md:w-10/12 lg:w-9/12 max-w-[777px] grid gap-3 grid-cols-1 md:grid-cols-1 lg:grid-cols-2">

        @if (!$marketPurchases['data'])
            <h1 class="text-4xl mt-20 lg:col-span-2 text-slate-700 text-center">Aún no hay nada aquí.</h1>
        @endif

        @foreach ($marketPurchases['data'] as $marketPurchase)
            <div
                class="flex flex-col justify-stretch px-3 mx-auto min-h-18 min-w-[370px] max-w-96 bg-slate-300 dark:bg-slate-900 py-2 pb-2.5 text-center rounded-xl hover:shadow-black hover:shadow-lg hover:scale-110 transition-all duration-200">

                <div class="flex justify-between text-sm mb-2">
                    <strong>Compra {{ $marketPurchase['id'] }}</strong>
                    @php
                        $date = new DateTime($marketPurchase['created_at']);
                        $formatted_date = $date->format('Y-m-d h:ia');
                        echo "<p class='text-xs text-slate-700 dark:text-slate-400 mr-2'>$formatted_date</p>";
                    @endphp
                </div>

                @php($ingredients = $marketPurchase['ingredients'])

                <div class="flex flex-row justify-evenly text-slate-500 mb-2 text-center">
                    <p class="text-xs">Ingrediente</p>
                    <p class="text-xs w-4/12 ml-14">Cantidad requerida</p>
                    <p class="text-xs w-4/12">Cantidad comprada</p>
                </div>

                @foreach ($ingredients as $ingredient)
                    <div class="flex justify-between text-xs">
                        <strong class="w-4/12 text-left">{{ __('ingredients.' . $ingredient['name']) }}</strong>

                        @if ($ingredient['pivot']['requested_quantity'] - $ingredient['pivot']['recieved_quantity'] != 0)
                            <div class="flex w-8/12 justify-around text-amber-400">
                                <p>{{ $ingredient['pivot']['requested_quantity'] }}</p>
                                <p>{{ $ingredient['pivot']['recieved_quantity'] }}</p>
                            </div>
                        @else
                            <div class="flex w-8/12 justify-around">
                                <p>{{ $ingredient['pivot']['requested_quantity'] }}</p>
                                <p>{{ $ingredient['pivot']['recieved_quantity'] }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach

            </div>
        @endforeach
    </div>


    {{-- PAGINATION --}}
    @php($lastPage = $marketPurchases['meta']['last_page'])

    @if ($lastPage > 1)
        @php($currentPage = $marketPurchases['meta']['current_page'])

        <div class="my-5 text-center cursor-pointer select-none">
            @if ($currentPage > 1)
                <a class="hover:text-slate-500" href="{{ route('marketPurchases', $currentPage - 1) }}">&laquo; Anterior</a>
                <a class="hover:text-slate-500" href="{{ route('marketPurchases', 1) }}">| 1 ...</a>
            @endif

            <a class="cursor-default p-1 mx-1">{{ $currentPage }}</a>

            @if ($currentPage != $lastPage)
                <a class="hover:text-slate-500" href="{{ route('marketPurchases', $lastPage) }}">... {{ $lastPage }}
                    |</a>
                <a class="hover:text-slate-500" href="{{ route('marketPurchases', $currentPage + 1) }}">Siguiente
                    &raquo;</a>
            @endif
        </div>
    @endif
@endsection
