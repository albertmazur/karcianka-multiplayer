<x-app-layout>
    <div class="flex flex-col py-12 sm:px-6 lg:px-8">
        <div class="flex gap-3 sm:flex-col">
            <div class="w-full gap-3 bg-white overflow-hidden shadow-sm sm:rounded-lg">
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

            <div class="w-full bg-white  overflow-hidden shadow-sm sm:rounded-lg">
                    <form action="{{route("friend.add")}}" method="post" class="flex flex-col gap-3 m-4">
                        @method('PUT')
                        @csrf
                        <x-input-label>{{__("friend.add")}}</x-input-label>
                        <x-text-input name="name" />
                        <x-primary-button class="w-20">{{__("Add")}}</x-primary-button>
                    </form>
                </div>
            </div>

            <div class="w-90 bg-white overflow-hidden shadow-sm sm:rounded-lg my-4">
                @if ($invitations->count()>=1)
                <ul class="mt-3 flex flex-col p-2">
                    @foreach ($invitations as $invitation)
                        <li class="inline-flex items-center gap-x-2 py-3 px-4 text-sm border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg">
                            <div class="flex items-center gap-3 justify-between w-full">
                                <p>{{$invitation->userFriend->name}}<p>
                                    <form method="POST" action="{{route("friend.accepted")}}" class="inline-flex items-center py-1 px-2 rounded-full text-xs font-medium bg-blue-500 text-white">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$invitation->userFriend->id}}">
                                        <x-primary-button>{{__('friend.invitation.add')}}</x-primary-button>
                                    </form>
                                    <form method="POST" action="{{route("friend.not-accepted")}}" class="inline-flex items-center py-1 px-2 rounded-full text-xs font-medium bg-blue-500 text-white">
                                        @method("delete")
                                        @csrf
                                        <input type="hidden" name="id" value="{{$invitation->userFriend->id}}">
                                        <x-primary-button class="">{{__('friend.invitation.remove')}}</x-primary-button>
                                    </form>
                            </div>
                        </li>
                    @endforeach
                <ul>
                @else
                    <p class="text-center m-5">{{__("friend.invitation.not")}}</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
