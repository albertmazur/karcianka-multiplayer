console.log("id: "+id)

window.Echo.private('PrivateGameChannel.user.'+id)
    .listen('.private_game', (e) => {
        console.log(e)
        if(e.data.start !=undefined){
            document.querySelector(".game").style.display = "block"
            document.getElementById("watting").style.display = "none"

            
        }
    })
