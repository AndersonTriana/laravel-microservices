@extends('template')
@section('body')
    <div class="text-center select-none">
        <form id="orderForm" onsubmit="event.preventDefault(); " action="/new-order" method="post" class="mb-3 mt-36 md:mt-36 lg:mt-40">
            @csrf
            <input type="hidden" name="orderQuantity" id="clickCountInput" value="0">
            <div class="relative inline-flex  group">
                <div
                    class="absolute transitiona-all duration-1000 opacity-70 -inset-px bg-gradient-to-r from-black via-green-400 to-black rounded-xl blur-lg group-hover:opacity-100 group-hover:-inset-1 group-hover:duration-200 animate-tilt">
                </div>
                <button
                    class="relative text-4xl md:text-6xl lg:text-8xl inline-flex items-center justify-center px-8 py-4 font-bold text-slate-300 transition-all duration-200 bg-slate-900 rounded-3xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-950 active:bg-slate-950 active:shadow-none active:translate-y-1 "
                    id="submitButton" type="submit">Crear Orden</button>
            </div>
        </form>

        @if (@session('message'))
            <h3>{{ session('message') }}</h3>
        @endif

        <script>
            var clickCount = 0;

            function handleClick() {
                clickCount++;
                document.getElementById("clickCountInput").value = clickCount;
            }

            function submitForm() {
                document.getElementById("orderForm").submit();
                clickCount = 0;
                document.getElementById("clickCountInput").value = clickCount;
            }

            document.getElementById("submitButton").addEventListener("click", handleClick);

            var timeout;
            document.getElementById("submitButton").addEventListener("mouseup", function() {
                clearTimeout(timeout);
                timeout = setTimeout(submitForm, 1000);
            });
        </script>
    </div>
@endsection
