console.log("id: "+id)
import {shuffleCards, PLAYERS} from './helper.js'

const mainCards = ["02_Trefl", "03_Trefl", "04_Trefl", "05_Trefl", "06_Trefl", "07_Trefl", "08_Trefl", "09_Trefl", "10_Trefl", "0J_Trefl", "0Q_Trefl", "0K_Trefl", "0A_Trefl", "02_Pik", "03_Pik", "04_Pik", "05_Pik", "06_Pik", "07_Pik", "08_Pik", "09_Pik", "10_Pik", "0J_Pik", "0Q_Pik", "0K_Pik", "0A_Pik", "02_Kier", "03_Kier", "04_Kier", "05_Kier", "06_Kier", "07_Kier", "08_Kier", "09_Kier", "10_Kier", "0J_Kier", "0Q_Kier", "0K_Kier", "0A_Kier", "02_Karo", "03_Karo", "04_Karo", "05_Karo", "06_Karo", "07_Karo", "08_Karo", "09_Karo", "10_Karo", "0J_Karo", "0Q_Karo", "0K_Karo", "0A_Karo"]
let mainCardsImg = []

let bot1Cards = document.querySelector("#cardsBot1")
let youCards = document.querySelector(".cardsHuman")
let coverMainCard = document.getElementById("coverMainCards")
if(game_id != null) start()

window.Echo.private('PrivateGameChannel.user.'+id)
    .listen('.private_game', (e) => {
        console.log(e)
        game_id = e.start
        continute(e)
    })

function start(){
    console.log("userId: "+userId)
    fetch(route, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({
            userId: userId,
            game: game_id,
            start: true
        }),
    })
    .then(function(response) {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
    .then(data => {
        continute(data)
        console.log(data)
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}



function continute(e){
    if(e.start !=undefined){
        if(document.getElementById("watting") != undefined) document.getElementById("watting").remove()
        document.querySelector(".game").style.display = "block"
        document.getElementById("suma").textContent = e.suma
        document.getElementById("uncoverMainCards").setAttribute("src", `/storage/cards/${e.uncover}.png`)

        coverMainCard.classList.add("cover")
        coverMainCard.addEventListener("click",function(){
            addCard()
        })

        for(let i=0; i<mainCards.length; i++){
            let img = document.createElement("img")
            img.setAttribute("alt", mainCards[i])
            img.setAttribute("src", '/storage/cards/'+mainCards[i]+'.png')
            mainCardsImg.unshift(img)
        }

        e.user1.forEach(card => {
            let img = creatCard(card, PLAYERS.HUMAN)
            youCards.appendChild(img)
        })

        for(let i=0; i< e.user2; i++){
            let img = creatCard(null, PLAYERS.BOT)
            bot1Cards.appendChild(img)
        }
    }
}


function creatCard(card, u){
    let img = document.createElement("img")
    if(u == PLAYERS.HUMAN){
        img.setAttribute("src", '/storage/cards/'+card+'.png')
        img.setAttribute("alt", card)
        img.addEventListener("click", function(e){
            clickForCard(e.target.getAttribute('alt'))
        })
    }
    if(u == PLAYERS.BOT) img.setAttribute("src", '/storage/cards/background_card.png')
    return img
}


function clickForCard(card){
    console.log(card)
    // fetch(route, {
    //     method: 'POST',
    //     headers: {
    //         'Content-Type': 'application/json',
    //         'X-CSRF-TOKEN': token
    //     },
    //     body: JSON.stringify({
    //         userId: userId,
    //         game: game_id,
    //         start: true
    //     }),
    // })
    // .then(function(response) {
    //         if (!response.ok) {
    //             throw new Error(`HTTP error! Status: ${response.status}`);
    //         }
    //         return response.json();
    //     })
    // .then(data => {
    //     continute(data)
    //     console.log(data)
    // })
    // .catch((error) => {
    //     console.error('Error:', error);
    // });
}

function addCard(){
    fetch(route, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({
            userId: userId,
            game: game_id,
            card: "add"
        }),
    })
    .then(function(response) {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
    .then(data => {
        let img = creatCard(data.card, PLAYERS.HUMAN)
        youCards.appendChild(img)
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}
