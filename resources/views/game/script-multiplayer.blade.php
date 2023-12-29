@isset($user)
@vite(['resources/js/multiplayer.js'])
<script>
    const userId = {{$user->id}}
    const id = {{Auth::id()}}
    const token = '{{ csrf_token() }}'
    const route = "{{route('game.broadcast')}}"
    let game_id

    @isset($game_id)
    game_id = {{$game_id}}

        document.querySelector(".game").style.display = "block"
        document.getElementById("watting").remove()
    @endisset($game_id)
</script>
@endisset($user)
