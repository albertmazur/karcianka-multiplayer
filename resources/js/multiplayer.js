window.Echo.private('PrivateGameChannel.user.'+id)
    .listen('.private_game', (e) => {
        console.log(e.data)
        if(e.data.start == "start"){
            document.querySelector(".game").style.display = "block"
            document.getElementById("watting").style.display = "none"
        }
    })
