<x-app-layout>
    <div role="status" class="relative max-w-2xl mt-4 mx-auto">
        <button id="buttonId">Test</button>
        <p class="absolute bottom-1/2 right-1/2 translate-x-1/2 translate-y-1/2">{{__("game.watting")}}
        @isset($start)
            {{$start}}
        @endisset ($start)
        </p>
        <svg aria-hidden="true" class="inline w-3xl mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 500 500" fill="none" xmlns="http://www.w3.org/2000/svg">
              <ellipse style="stroke-width: 15px; fill: none; stroke: rgb(18, 18, 211); stroke-dasharray: 4;" cx="250.804" cy="250.115" rx="241.479" ry="241.868" transform="matrix(0.9999999999999999, 0, 0, 0.9999999999999999, 0, 0)"></ellipse>
        </svg>
        <span class="sr-only">Loading...</span>
      </div>
    <div class="game" style="display: none">
        <div class="flex flex-col-reverse sm:flex-row sm:m-2 relative">
            <div class="bg-white flex flex-col sm:items-stretch m-6 h-56 2xl:h-[90vh] sm:h-[100vh] sm:m-3 sm:w-48">
                <p class="my-5">{{__("game.history")}}</p>
                <div id="history" class="overflow-auto flex flex-row sm:flex-col gap-4 px-8">
                </div>
            </div>

            <div class="board 2xl:h-[90vh] sm:h-[100vh] sm:w-full">
                <div class="absolute top-4 left-1/2 origin-left">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input id="showCardsSwitcher" type="checkbox" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        <span class="ms-3 text-sm font-medium text-gray-300">{{ __('game.show_cards')}}</span>
                      </label>
                </div>
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
                        <p>{{Auth::user()->nick}}</p>
                        <div class="cardsHuman"></div>
                    </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col items-center gap-4 m-6 p-3">
            <p class="" >Tryby gry</p>
            <div class="flex flex-row">
                <div class="flex flex-col gap-3">
                    <div class="flex items-center">
                        <input checked id="default-radio-1" type="radio" value="heuristic" name="tryb" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300">
                        <x-input-label for="default-radio-1" class="ms-2">Heurystyczne</x-input-label>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="flex">
                            <input id="default-radio-2" type="radio" value="MCTS" name="tryb" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300">
                            <x-input-label for="default-radio-2" class="ms-2">MCTS</x-input-label>
                        </div>
                        <x-text-input id="mctsIteration" type="number" value="1000" min='1' step="1"></x-text-inpu>
                    </div>
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
            /*Background pattern from sublepatterns.com*/
            .board{
                background-image: url({{asset("storage/background/pool_table_green.png")}})
            }
            .centerBoard{
                background-image: url({{asset("storage/background/pool_table_red.png")}})
            }
        </style>
        <script>
            const but = document.getElementById("buttonId");
            but.addEventListener("click", ()=>{
                fetch("{{route('game.broadcast')}}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        message: "Test do danych"
                    }),
                })
                .then(function(response) {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                .then(data => {
                    console.log(data);
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
            })
        </script>
        @vite([ 'resources/js/multiplayer.js',])
    </div>
    </x-app-layout>
