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
        @yield("ruls")
        <style>
            /*Background pattern from sublepatterns.com*/
            .board{
                background-image: url({{asset("storage/background/pool_table_green.png")}})
            }
            .centerBoard{
                background-image: url({{asset("storage/background/pool_table_red.png")}})
            }
        </style>
       @include("game.ruls")
       @include("game.script-multiplayer")
    </div>
    </x-app-layout>
