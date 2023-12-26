<x-app-layout>
    <div id="watting" role="status" class="relative max-w-2xl mt-4 mx-auto">
        <p class="absolute bottom-1/2 right-1/2 translate-x-1/2 translate-y-1/2">{{__("game.watting")}}
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
                <div class="bot bot1">
                    @isset($user)
                        <p>{{$user->nick}}</p>
                    @else
                        <p>Bot 1</p>
                    @endisset ($user)
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
            @isset($user)
                const userId = '{{$user->id}}'
                const id = '{{Auth::id()}}'
            @endisset ($user)

            function start(game_id){
                console.log("userId: "+userId)
                fetch("{{route('game.broadcast')}}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        userId: userId,
                        message: game_id
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
            }
            @isset($game_id)
                start("{{$game_id}}")

                document.querySelector(".game").style.display = "block"
                document.getElementById("watting").remove()
            @endisset ($game_id)
        </script>
        @vite([ 'resources/js/multiplayer.js',])
    </div>
    </x-app-layout>
