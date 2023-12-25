<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white  m-3 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex justify-center p-6 text-gray-900">
                    @if ($games->count()>=1)
                        <ul class="mt-3 flex flex-col w-full">
                        @foreach ($games as $game)
                            <li class="inline-flex items-center gap-x-2 py-3 px-4 text-sm border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg">
                                <div class="flex items-center gap-3 justify-between w-full">
                                    <p>{{$game->userFriend->nick}}<p>
                                        <form method="POST" action="{{route("game.join")}}">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$game->userFriend->id}}">
                                            <x-primary-button class="red">{{__('game.join')}}</x-primary-button>
                                        </form>
                                </div>
                            </li>
                        @endforeach
                        <ul>
                    @else
                        {{__("game.not_game")}}
                    @endif
                </div>
            </div>


            <div class="bg-white  m-3 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex justify-center p-6 text-gray-900">
                    @if ($friends->count()>=1)
                        <ul class="mt-3 flex flex-col w-full">
                        @foreach ($friends as $friend)
                            <li class="inline-flex items-center gap-x-2 py-3 px-4 text-sm border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg">
                                <div class="flex items-center gap-3 justify-between w-full">
                                    <p>{{$friend->nick}}<p>
                                        <form method="POST" action="{{route("game.multiplayer")}}">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$friend->id}}">
                                            <x-primary-button class="red">{{__('game.play')}}</x-primary-button>
                                        </form>
                                </div>
                            </li>
                        @endforeach
                        <ul>
                    @else
                        {{__("friend.not")}}
                    @endif
                </div>
            </div>
            <x-button-link class="m-3" href="{{route('game.single')}}">{{__("game.single")}}</x-button-link>
        </div>
    </div>
</x-app-layout>
