<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-cyan-500 m-3 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("Lista dostepnych znajomych.") }}
                </div>
            </div>
            <x-primary-button class="m-3"><a href="{{route("game.single")}}">{{__("game.single")}}</a></x-primary-button>
        </div>
    </div>
</x-app-layout>
