@extends('template')
@section('body')
    <h1 class="mb-8 text-3xl text-center font-bold">Ordenes en preparación</h1>

    <div class="flex justify-center text-sm">
        <a href="{{ route('orders', [1, 'finished']) }}"
            class="flex w-44 justify-around mb-7 pb-2 ml-auto text-lg items-center bg-gradient-to-b from-slate-900 shadow-black shadow-lg to-transparent rounded-l-xl h-12 hover:text-green-400 hover:bg-green-700">
            Finalizadas</a>

        <a href="{{ route('orders', [1, '']) }}"
            class="flex w-36 justify-around mb-7 pb-2 text-lg items-center bg-gradient-to-b from-slate-900 shadow-black shadow-lg to-transparent  h-12 hover:text-amber-400 hover:bg-amber-700">
            Todas</a>

        <a href="{{ route('orders', [1, 'preparing']) }}"
            class="flex w-44 justify-around mb-7 pb-2 mr-auto text-lg items-center bg-gradient-to-b from-slate-900 shadow-black shadow-lg to-transparent rounded-r-xl h-12 hover:text-amber-400 hover:bg-amber-700">
            En preparación</a>
    </div>

    <div class="m-0 p-0 mx-auto w-10/12 grid gap-3 grid-cols-3 md:grid-cols-6 lg:grid-cols-8">

        @if (!$orders['data'])
            <h1 class="text-4xl mt-20 col-span-3 md:col-span-6 lg:col-span-8 text-slate-700 text-center">Aún no hay nada aquí.</h1>
        @endif

        @foreach ($orders['data'] as $order)
            <div
                class="max-w-44 min-w-20 bg-slate-300 dark:bg-slate-900 py-2 pb-2.5 mb-3 text-center rounded-xl hover:shadow-black hover:shadow-lg hover:scale-110 transition-all duration-200">

                <p><strong>Orden {{ $order['id'] }}</strong></p>

                @if ($order['status'] == 'finished')
                    <p class="text-green-600 dark:text-green-400">
                        Finalizada
                    </p>
                    @php
                        $createdDateTime = new DateTime($order['created_at']);
                        $updatedDateTime = new DateTime($order['updated_at']);

                        $formattedDate = $createdDateTime->format('Y-m-d');
                        $formattedTime = round(
                            ($updatedDateTime->getTimestamp() - $createdDateTime->getTimestamp()) / 60,
                            0,
                        );

                        echo "<p class='text-xs text-slate-700 dark:text-slate-400'>$formattedDate</p>";
                    @endphp

                    <p class="text-xs text-slate-700 dark:text-slate-400">
                        {{-- <img src="{{ asset('svg/clock.svg') }}" alt="clock icon" class="w-3 dark:invert-100 inline"> --}}
                        {{ $formattedTime }} min
                    </p>
                @else
                    <p class="text-amber-500 dark:text-amber-300">
                        Preparando
                    </p>

                    @php
                        $date = new DateTime($order['created_at']);
                        $formatted_date = $date->format('Y-m-d');
                        $formatted_time = $date->format('h:ia');
                        echo "<p class='text-xs text-slate-700 dark:text-slate-400'>$formatted_date</p>";
                        echo "<p class='text-xs text-slate-700 dark:text-slate-400'>$formatted_time</p>";
                    @endphp
                @endif
            </div>
        @endforeach

    </div>

    {{-- PAGINATION --}}
    @php($lastPage = $orders['meta']['last_page'])

    @if ($lastPage > 1)
        @php($currentPage = $orders['meta']['current_page'])

        <div class="my-5 text-center cursor-pointer select-none">
            @if ($currentPage > 1)
                <a class="hover:text-slate-500" href="{{ route('orders', [$currentPage - 1, $status]) }}">&laquo;
                    Anterior</a>
                <a class="hover:text-slate-500" href="{{ route('orders', [1, $status]) }}">| 1 ...</a>
            @endif

            <a class="cursor-default p-1 mx-1">{{ $currentPage }}</a>

            @if ($currentPage != $lastPage)
                <a class="hover:text-slate-500" href="{{ route('orders', [$lastPage, $status]) }}">... {{ $lastPage }}
                    |</a>
                <a class="hover:text-slate-500" href="{{ route('orders', [$currentPage + 1, $status]) }}">Siguiente
                    &raquo;</a>
            @endif
        </div>
    @endif
@endsection
