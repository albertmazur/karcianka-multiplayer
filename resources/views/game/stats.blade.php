<x-app-layout>
    <h1>Moje statustyki</h1>
    <div class="flex gap-3 flex-col sm:flex-row">
        <div class="w-full gap-3 bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="flex justify-center p-6 text-gray-900">
                @if ($friends->count()>=1)
                    <ul class="mt-3 flex flex-col w-full">
                    @foreach ($friends as $friend)
                        <li class="inline-flex items-center gap-x-2 py-3 px-4 text-sm border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg">
                            <div class="flex items-center gap-3 justify-between w-full">
                                <p>{{$friend->nick}}<p>
                                    <form method="POST" action="{{route("friend.remove")}}">
                                        @method('DELETE')
                                        @csrf
                                        <input type="hidden" name="id" value="{{$friend->id}}">
                                        <x-primary-button class="red">{{__('friend.remove')}}</x-primary-button>
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
        <h1>Statystyki przyjaciój</h1>
        <div class="flex gap-3 flex-col sm:flex-row">
            <div class="w-full gap-3 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex justify-center p-6 text-gray-900">
                    @if ($friends->count()>=1)
                        <ul class="mt-3 flex flex-col w-full">
                        @foreach ($friends as $friend)
                            <li class="inline-flex items-center gap-x-2 py-3 px-4 text-sm border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg">
                                <div class="flex items-center gap-3 justify-between w-full">
                                    <p>{{$friend->nick}}<p>
                                        <form method="POST" action="{{route("friend.remove")}}">
                                            @method('DELETE')
                                            @csrf
                                            <input type="hidden" name="id" value="{{$friend->id}}">
                                            <x-primary-button class="red">{{__('friend.remove')}}</x-primary-button>
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

</x-app-layout>
