<x-app-layout>
<div class="game">
    <div class="flex flex-col-reverse sm:flex-row sm:m-2">
        <div class="bg-white flex flex-col sm:items-stretch m-6 h-56 sm:h-[900px] sm:m-3 sm:w-48">
            <p class="my-5">{{__("game.history")}}</p>
            <div id="history" class="overflow-auto flex flex-row sm:flex-col gap-4 px-8">
            </div>
        </div>

        <div class="board sm:w-full">
            <div class="bot bot1">
                <p>Bot 1</p>
                <div id="cardsBot1" ></div>
            </div>
            <div class="center">
                <div class="centerBoard" style="background-image: url({{asset("storage/background/pool_table_red.png")}})">
                    <p>Suma kart: <span id="suma"></span></p>
                    <img id="coverMainCards" src={{asset("storage/cards/background_card.png")}} alt="Cover cards">
                    <img id="uncoverMainCards" src={{asset("storage/cards/background_card.png")}} alt="Uncover cards">
                    <p>Ruch grasza:</p><p id="whoNow"></p>
                </div>
            </div>
                <div class="human">
                    <p>{{Auth::user()->name}}</p>
                    <div class="cardsHuman"></div>
                </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col items-center gap-3 m-6 p-6">
        <p class="" >Tryby gry</p>
        <div class="flex justify-center gap-3">
            <div class="flex items-center">
                <input checked id="default-radio-1" type="radio" value="Heurystyczne" name="tryb" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300">
                <x-input-label for="default-radio-1" class="ms-2">Heurystyczne</x-input-label>
            </div>

            <div class="flex items-center">
                <input id="default-radio-2" type="radio" value="MCTS" name="tryb" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300">
                <x-input-label for="default-radio-2" class="ms-2">MCTS</x-input-label>
            </div>
        </div>
        <x-primary-button id="start" class="w-28 justify-center">Start</x-primary-button>
    </div>
    <div class="mt-4 p-4">
        <h1 class="mb-5">Zasady gry</h1>
        <ul>
            <li><span>Wykładanie kart</span> – podczas rozgrywki wykładać można 1 kartę. Karty na środek dokłada się według zasady koloru i figury. Jeśli więc na stole leży 5 kier, gracz może położyć dowolną 5 lub dowolnego kiera. Wyjątkiem są karty funkcyjne, które wymuszają wyłożenie na stół konkretnych figur.</li>
            <li><span>Dobieranie kart</span> – kiedy gracz nie ma w dłoni karty, którą może wyłożyć na stół, dobiera jedną z kupki i traci kolejkę. Karty dobiera też, jeśli zbiera karę. Część z kart funkcyjnych określa, ile kart powinno się dobrać. Jeśli karta karna zostaje przebita kolejną, liczba kart w karze zwiększa się. Kara kumuluje się, póki któryś z graczy nie musi dobrać karty z kupki – wtedy ponosi karę, dobierając z talii skumulowaną w karze liczbę kart.</li>
            <li><span>Karty bitewne (dwójki, trójki i króle)</span> – zmuszają gracza do dobrania tylu kart, ile na niej widnieje (Król 5 kart). Można przebijać wartości dobieranych kart tylko z kart bitewnych jeśli pasuje figura lub kolor.</li>
            <li><span>Walet</span> – Zmniejsza ilość kart do zebrania o 5.</li>
            <li><span>Dama</span> – kończy bitwę bez brania kart.</li>
            <li><span>As</span> – przenosi kart do zebrania na następnego gracza.</li>
            <li><span>Karty nie funkcyjne</span> - 4, 5, 6, 7, 8, 9, 10.</li>
        </ul>
    </div>
    <div class="stopka">
        <p>Autor grafiki kart: <a target="_blank" href="http://www.freepik.com">Designed by macrovector / Freepik</a></p>
    </div>
    <script>

    </script>
    <style>
        .board{
            background-image: url({{asset("storage/background/pool_table_green.png")}})
        }
        .centerBoard{
            background-image: url({{asset("storage/background/pool_table_red.png")}})
        }
    </style>
    @vite([ 'resources/js/main.js',])
</div>
</x-app-layout>
