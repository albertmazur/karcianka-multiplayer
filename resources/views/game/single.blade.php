<x-app-layout>
<div class="game">
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
    @include("game.ruls")
    <style>
        /*Background pattern from sublepatterns.com*/
        .board{
            background-image: url({{asset("storage/background/pool_table_green.png")}})
        }
        .centerBoard{
            background-image: url({{asset("storage/background/pool_table_red.png")}})
        }
    </style>
    @vite(['resources/js/game.js'])
</div>
</x-app-layout>
