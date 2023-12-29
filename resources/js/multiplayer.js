console.log("game_id: "+game_id)
import {PLAYERS} from './helper.js'

const mainCards = ["02_Trefl", "03_Trefl", "04_Trefl", "05_Trefl", "06_Trefl", "07_Trefl", "08_Trefl", "09_Trefl", "10_Trefl", "0J_Trefl", "0Q_Trefl", "0K_Trefl", "0A_Trefl", "02_Pik", "03_Pik", "04_Pik", "05_Pik", "06_Pik", "07_Pik", "08_Pik", "09_Pik", "10_Pik", "0J_Pik", "0Q_Pik", "0K_Pik", "0A_Pik", "02_Kier", "03_Kier", "04_Kier", "05_Kier", "06_Kier", "07_Kier", "08_Kier", "09_Kier", "10_Kier", "0J_Kier", "0Q_Kier", "0K_Kier", "0A_Kier", "02_Karo", "03_Karo", "04_Karo", "05_Karo", "06_Karo", "07_Karo", "08_Karo", "09_Karo", "10_Karo", "0J_Karo", "0Q_Karo", "0K_Karo", "0A_Karo"]
let mainCardsImg = []

let bot1Cards = document.querySelector("#cardsBot1")
let youCards = document.querySelector(".cardsHuman")
let coverMainCard = document.getElementById("coverMainCards")
let whoNowText = document.getElementById("whoNow")
let sumaText = document.getElementById("suma")
if(start != null) startGame()

window.Echo.private('PrivateGameChannel.user.'+id)
    .listen('.private_game', (e) => {
        console.log(e)
        continute(e)
    })

function startGame(){
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
    sumaText.textContent = e.sum ? e.sum : 0
    whoNowText.textContent = e.whoNow
    if(e.card == "add") bot1Cards.append(creatCard(null, PLAYERS.BOT))

    if(e.start !=undefined){
        if(document.getElementById("watting") != undefined) document.getElementById("watting").remove()
        document.querySelector(".game").style.display = "block"
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
    if(PLAYERS.HUMAN == whoNowText.textContent){
        fetch(route, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({
                userId: userId,
                game: game_id,
                card: card
            }),
        })
        .then(function(response) {
            console.log(response)
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
    else{
        alert("Nie twój ruch")
    }
}

function addCard(){
    if(PLAYERS.HUMAN == whoNowText.textContent){
        console.log(route)
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
            console.log(response)
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log(data)
            whoNowText.textContent = data.whoNow
            let img = creatCard(data.card, PLAYERS.HUMAN)
            youCards.appendChild(img)
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }
    else{
        alert("Nie twój ruch")
    }
}
