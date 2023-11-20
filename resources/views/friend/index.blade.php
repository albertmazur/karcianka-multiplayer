<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Friend') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="flex max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="w-full gap-3 bg-cyan-500 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex justify-center p-6 text-gray-900">
                    @if ($friends->count()>1)
                        @foreach ($friends as $friend)
                            {{$friend->name}}
                        @endforeach
                    @else
                        {{__("Not friend")}}
                    @endif
                </div>
            </div>

            <div class="w-full bg-cyan-500 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex flex-col justify-center p-6 text-gray-900">
                    {{__("Add friend")}}
                    <form action="{{route("friend.add")}}" method="post" class="flex flex-col gap-3">
                        @method('PUT')
                        @csrf
                        <x-text-input name="name" />
                        <x-primary-button>{{__("Add")}}</x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
