<x-app-layout>
    <div class="py-12">
        <div class="flex gap-3 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="w-full gap-3 bg-cyan-500 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex justify-center p-6 text-gray-900">
                    @if ($friends->count()>=1)
                        <ul class="mt-3 flex flex-col">
                        @foreach ($friends as $friend)
                            <li class="inline-flex items-center gap-x-2 py-3 px-4 text-sm border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg">
                                <div class="flex items-center gap-3 justify-between w-full">
                                    <p>{{$friend->userFriend->name}}<p>
                                        <form method="POST" action="{{route("friend.remove")}}" class="inline-flex items-center py-1 px-2 rounded-full text-xs font-medium bg-blue-500 text-white">
                                            @method("delete")
                                            @csrf
                                            <input type="hidden" name="id" value="{{$friend->id}}">
                                            <button>{{__('friend.remove')}}<button>
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

            <div class="w-full bg-cyan-500 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex flex-col justify-center p-6 text-gray-900">
                    {{__("friend.add")}}
                    <form action="{{route("friend.add")}}" method="post" class="flex flex-col gap-3">
                        @method('PUT')
                        @csrf
                        <x-text-input name="name" />
                        <x-primary-button>{{__("Add")}}</x-primary-button>
                    </form>
                </div>
            </div>
        </div>

        <div class="w-90 bg-cyan-500 overflow-hidden shadow-sm sm:rounded-lg m-4">
            @if ($invitations->count()>=1)
            <ul class="mt-3 flex flex-col p-2">
            @foreach ($invitations as $invitation)
                <li class="inline-flex items-center gap-x-2 py-3 px-4 text-sm border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg">
                    <div class="flex items-center gap-3 justify-between w-full">
                        <p>{{$invitation->userFriend->name}}<p>
                            <form method="POST" action="{{route("friend.accepted")}}" class="inline-flex items-center py-1 px-2 rounded-full text-xs font-medium bg-blue-500 text-white">
                                @csrf
                                <input type="hidden" name="id" value="{{$invitation->userFriend->id}}">
                                <x-my-button>{{__('friend.addinvitations')}}</x-my-button>
                            </form>
                            <form method="POST" action="{{route("friend.not-accepted")}}" class="inline-flex items-center py-1 px-2 rounded-full text-xs font-medium bg-blue-500 text-white">
                                @method("delete")
                                @csrf
                                <input type="hidden" name="id" value="{{$invitation->userFriend->id}}">
                                <x-my-button>{{__('friend.removeinvitations')}}</x-my-button>
                            </form>
                    </div>
                </li>
            @endforeach
            <ul>
        @else
            <p class="text-center m-5">{{__("friend.notinvitations")}}</p>
        @endif
        </div>

    </div>
</x-app-layout>
