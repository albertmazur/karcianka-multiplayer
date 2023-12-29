@isset($user)
@vite(['resources/js/multiplayer.js'])
<script>
    const userId = {{$user->id}}
    const id = {{Auth::id()}}
    const token = '{{ csrf_token() }}'
    const route = "{{route('game.broadcast')}}"
    const game_id = {{$game_id}}
    let start

    @isset($start)
        start = {{$start}}

        document.querySelector(".game").style.display = "block"
        document.getElementById("watting").remove()
    @endisset($start)
</script>
@endisset($user)
