import './gameStyle'

//-----------------Card names--------------------------------
let mainCards = [];

mainCards[0] = "02_Trefl"
mainCards[1] = "03_Trefl"
mainCards[2] = "04_Trefl"
mainCards[3] = "05_Trefl"
mainCards[4] = "06_Trefl"
mainCards[5] = "07_Trefl"
mainCards[6] = "08_Trefl"
mainCards[7] = "09_Trefl"
mainCards[8] = "10_Trefl"
mainCards[9] = "0J_Trefl"
mainCards[10] = "0Q_Trefl"
mainCards[11] = "0K_Trefl"
mainCards[12] = "0A_Trefl"
mainCards[13] = "02_Pik"
mainCards[14] = "03_Pik"
mainCards[15] = "04_Pik"
mainCards[16] = "05_Pik"
mainCards[17] = "06_Pik"
mainCards[18] = "07_Pik"
mainCards[19] = "08_Pik"
mainCards[20] = "09_Pik"
mainCards[21] = "10_Pik"
mainCards[22] = "0J_Pik"
mainCards[23] = "0Q_Pik"
mainCards[24] = "0K_Pik"
mainCards[25] = "0A_Pik"
mainCards[26] = "02_Kier"
mainCards[27] = "03_Kier"
mainCards[28] = "04_Kier"
mainCards[29] = "05_Kier"
mainCards[30] = "06_Kier"
mainCards[31] = "07_Kier"
mainCards[32] = "08_Kier"
mainCards[33] = "09_Kier"
mainCards[34] = "10_Kier"
mainCards[35] = "0J_Kier"
mainCards[36] = "0Q_Kier"
mainCards[37] = "0K_Kier"
mainCards[38] = "0A_Kier"
mainCards[39] = "02_Karo"
mainCards[40] = "03_Karo"
mainCards[41] = "04_Karo"
mainCards[42] = "05_Karo"
mainCards[43] = "06_Karo"
mainCards[44] = "07_Karo"
mainCards[45] = "08_Karo"
mainCards[46] = "09_Karo"
mainCards[47] = "10_Karo"
mainCards[48] = "0J_Karo"
mainCards[49] = "0Q_Karo"
mainCards[50] = "0K_Karo"
mainCards[51] = "0A_Karo"

let mainCardsImg = []

//----------------Player names------------------------
let bot1 = "BOT 1"
let human = "TY"
//--------------------Stacks of cards--------------------
let coverMainCards = []
let uncoverMainCards = []

//---------------Player cards----------------------------------------------------------
let bot1Cards = document.querySelector("#cardsBot1")
let youCards = document.querySelector(".cardsHuman")

//---------------Support for the number of cards to download--------------------------------
let sumaSpan = document.querySelector(".centerBoard span")
let suma = 0

//-----------------The last card that was played--------------------------------------
let coverMainCard = document.querySelector("#coverMainCards")
let uncoverMainCard = document.querySelector("#uncoverMainCards")

//-----------------Whose move----------------------------------
let whoNow = document.getElementById("whoNow")

//------------------Game launch and launch support----------------------------------------------
let mode
let firstGame = true
let newGame = document.getElementById("start")
newGame.addEventListener("click", start)

//-------------------------Start the game----------------------------
function start(){
    if(firstGame){
        for(let i=0; i<mainCards.length; i++){
            let img = document.createElement("img")
            img.setAttribute("alt", mainCards[i])
            img.setAttribute("src", '/storage/cards/'+mainCards[i]+'.png')
            mainCardsImg.push(img);
        }
    }

    newGame.textContent="Od nowa"
    firstGame = false

    uncoverMainCard.addEventListener("click", drawUncoverMain)

    resetGames()

    let whoWin = document.querySelector("#whoWin")
    if(whoWin != null)  whoWin.remove()

    whoNow.innerText=human

    uncoverMainCard.classList.add("zakryte")

    shuffleCards(mainCards)

    for(let i=0; i<10;i++){
        let img = creatCard(coverMainCards[i])

        if(i%2==0) youCards.appendChild(img)
        if(i%2==1){
            //img.setAttribute("src", '/storage/cards/background_card.png');
            bot1Cards.appendChild(img)
        }
    }

    coverMainCards.splice(0,10)

    uncoverMainCards.push(coverMainCards[0])
    coverMainCards.splice(0,1)

    let sing = uncoverMainCards[0].substring(0,2)
    if(sing=="02" || sing=="03" || sing=="0J" || sing=="0Q" || sing=="0K" || sing=="0A") start()

    coverMainCard.setAttribute("src", "/storage/cards/"+uncoverMainCards[0]+".png")
    coverMainCard.setAttribute("alt", uncoverMainCards[0])

    let history = document.getElementById("history")
    while (history.firstChild) {
        history.removeChild(history.firstChild);
    }
    history.appendChild(coverMainCard.cloneNode(true))

    for(let card of youCards.children){
        card.addEventListener("click", selectingCard)
    }

    mode = document.querySelector('input[name="tryb"]:checked').value
}

//-------------Adding a card for grasz after clicking face down--------------------
function drawUncoverMain(){
    if(whoNow.innerText==human){
        for(let i=1;i<suma;i++) drawCard(human)
        drawCard(human)

        suma = 0
        sumaSpan.innerText = ""

        whoNow.innerText=bot1

         setTimeout(function (){
             bot()
           }, 2000)
    }
    else alert("Nie twój ruch nie możesz brać karty")
}

//----------------Creating cards-----------------------------
function creatCard(card){
    let img = document.createElement("img")
    if (whoNow.innerText==human) img.setAttribute("src", '/storage/cards/'+card+'.png')
    // else img.setAttribute("src", '/storage/cards/background_card.png')
    else img.setAttribute("src", '/storage/cards/'+card+'.png')
    img.setAttribute("alt", card)
    return img
}

//-----------Adding a card for grasz------------------
function drawCard(who){
    let img = creatCard(coverMainCards[0])

    img.style.opacity = 0

    if(who==human && whoNow.innerText==human){
        img.addEventListener("click", selectingCard)
        youCards.appendChild(img)
    }
    if(who==bot1)  bot1Cards.appendChild(img)

    setTimeout(function(){
        img.classList.add("addCard")
    }, 50)

    coverMainCards.splice(0,1)
    checkCoverCards()
}

//-------------------------Adding cards to the game if taking from the pile-------------
function checkCoverCards(){
    console.log("zakryte: "+coverMainCards.length+" odkryte: "+uncoverMainCards.length)
    if(coverMainCards.length==0){
        shuffleCards(uncoverMainCards)
        uncoverMainCards.splice(0, uncoverMainCards.length-1)
        coverMainCards.splice(coverMainCards.indexOf(uncoverMainCards[0]), 1)
    }
    if(coverMainCards.length==0 && uncoverMainCards.length==1){
        let whoWin = document.createElement("p")
        whoWin.id="whoWin"
        if(youCards.length > bot1Cards.length) whoWin.innerText="Koniec gry wygrał bot"
        if(youCards.length < bot1Cards.length) whoWin.innerText="Koniec gry wygrałeś ty"
        document.querySelector(".centerBoard").insertBefore(whoWin, document.querySelector(".centerBoard p"))
        resetGames()
    }
}

//-----------------------Shuffle the cards------------------------------------
function shuffleCards(deckToShuffle) {
    let n=deckToShuffle.length;
    let k=n;
    let numbers = new Array(n);

    for (let i=0; i<n; i++) {
        numbers[i] = i + 1
    }

    for (let i=0; i<k; i++) {
        let r = Math.floor(Math.random()*n)

        coverMainCards[i]=deckToShuffle[numbers[r]-1]

        numbers[r] = numbers[n - 1]
        n--
    }
}

//---------------------------Function called after selecting a card-----------------------------
function selectingCard(){
    let wybranaKarta = this.getAttribute('alt')

    if(whoNow.innerText==human && checkCard(wybranaKarta)){
        uncoverMainCards.push(wybranaKarta)

        coverMainCard.setAttribute("src", "/storage/cards/"+wybranaKarta+".png")
        coverMainCard.setAttribute("alt", wybranaKarta)

        this.classList.remove("addCard")
        this.classList.add("removeCard")

        addForHistory(this)

        setTimeout(function(){
            checkWin(whoNow.innerText)
        }, 800);

        setTimeout(function(){
            bot()
        }, 2000)
    }
    else if(whoNow.innerText!=human) alert("Nie twój ruch")
    else alert("Tą kartą nie można zagrać")
}

//-------------------------Obsługa boty----------------------------
function bot(){
    let who = whoNow.innerText
    if(who==bot1) ruchBota(bot1Cards.children)
}

//------------------------------Bot movement-------------------------------------
function ruchBota(cards){
    let playedCard
    let isCard=false
    if(mode == "Heurystyczne") playedCard = heurystyczne(cards)


    if(playedCard != null){
        checkCard(playedCard.getAttribute("alt"))
        isCard=true
    }

    changeCard(isCard, playedCard)
}

function heurystyczne(cards){
    let cardsSpecial = []
    let cardsNotSpecial = []

    for(let card of cards){
        let cardAlt = card.getAttribute("alt")
        let coverCardAlt = coverMainCard.getAttribute("alt")

        let cardsSpecialSign = cardAlt.substring(0,2)
        let cardsSpecialFigure = cardAlt.substring(3, cardAlt.length)
        let coverCardSign = coverCardAlt.substring(0,2)
        let coverCardFigure = coverCardAlt.substring(3, coverCardAlt.length)

        if(cardsSpecialSign==coverCardSign || cardsSpecialFigure==coverCardFigure){

            if(cardsSpecialSign == "02" ||
               cardsSpecialSign == "03" ||
               cardsSpecialSign == "0K" ||
               cardsSpecialSign == "0J" ||
               cardsSpecialSign == "0Q" ||
               cardsSpecialSign == "0A")
            {
                cardsSpecial.push(card)
            }
            else{
                cardsNotSpecial.push(card)
            }
        }
    }

    if(youCards.length<=2){
        for(let card of cardsSpecial){
            let znak = card.getAttribute("alt").substring(0,2)
            if(znak=="02" || znak=="03" || znak=="0K"){
                return card
            }
        }
    }

    let coverCardSign = coverMainCard.getAttribute("alt").substring(0,2)
    console.log("Znka cover karty: " + coverCardSign)
    if (cardsNotSpecial.length > 0 &&
        ((suma == 0 && !helpConditionHeurystyczne(coverCardSign)) ||
         (suma != 0 &&  helpConditionHeurystyczne(coverCardSign)))) {
        //console.log("nie bitewne: " + cardsNotSpecial)
        return cardsNotSpecial.pop()
    }

    if(cardsSpecial.length>0){
        //console.log("bitewne: "+ cardsSpecial)
        return cardsSpecial.pop()
    }
    return null
}

function helpConditionHeurystyczne(sing){
    return (sing == "02" || sing == "03" || sing == "0J" || sing == "0K" || sing == "0A")
}

//-------------------Checks if this card can be played----------------
function checkCard(selectedCard){
    let coverCard = coverMainCard.getAttribute("alt")

    let selectedCardSign = selectedCard.substring(0,2)
    let selectedCardFigure = selectedCard.substring(3, selectedCard.length)
    let coverCardSign = coverCard.substring(0,2)
    let coverCardFigure = coverCard.substring(3, coverCard.length)

    if((selectedCardSign==coverCardSign || selectedCardFigure==coverCardFigure)){
        switch(selectedCardSign){
            case "02":
                suma+=2
                sumaSpan.innerText=suma
                break
            case "03":
                suma+=3
                sumaSpan.innerText=suma
                break
            case "0Q":
                suma=0
                sumaSpan.innerText=""
                break
            case "0J":
                if(suma<=5){
                    suma=0;
                    sumaSpan.innerText=""
                }
                else{
                    suma-=5
                    sumaSpan.innerText=suma
                }
                break
            case "0K":
                suma+=5
                sumaSpan.innerText=suma;
                break
            case "0A":
                break;
            default:
                if(suma==0) return true
                else return false
        }
        return true
    }
    else return false
}

//---------------------------Putting the card that has been played into the stack--------------------
function changeCard(isCard, selectedCard){
    if(isCard){
        coverMainCard.setAttribute("src", mainCardsImg.at(mainCards.indexOf(selectedCard.getAttribute('alt'))).getAttribute("src"))
        coverMainCard.setAttribute("alt", selectedCard.getAttribute("alt"))
        uncoverMainCards.push(selectedCard.getAttribute("alt"))

        selectedCard.classList.remove("addCard")
        selectedCard.classList.add("removeCard")
        setTimeout(function(){
            addForHistory(selectedCard)
            checkWin(whoNow.innerText)
        }, 800);
    }
    else{
        for(let i=1; i<suma; i++) drawCard(whoNow.innerText)
        drawCard(whoNow.innerText)
        suma=0;
        sumaSpan.innerText=""
        setTimeout(function(){
            checkWin(whoNow.innerText)
        }, 800)
    }
}

//--------------Checking if someone won------------
function checkWin(who){

    let cards
    switch(who){
        case human:
            cards = youCards.children
            whoNow.innerText=bot1
            break;
        case bot1:
            cards = bot1Cards.children
            whoNow.innerText=human
            break;
    }

    if((cards.length)==0) win(who)
}

//-------------------What happens if someone wins?-----------------------------
function win(who){
    let text
    if(who==human) text = "Wygrałeś"
    else text = "Wygrał " + who
    let whoWinParagraph = document.createElement("p")
    whoWinParagraph.id="whoWin"
    whoWinParagraph.innerText=text
    document.querySelector(".centerBoard").insertBefore(whoWinParagraph, document.querySelector(".centerBoard p"))
    resetGames()
}

//---------------------Resetting the game----------------
function resetGames(){
    whoNow.innerText = ""
    sumaSpan.innerText = ""

    youCards.innerHTML = ""
    bot1Cards.innerHTML = ""

    coverMainCards.splice(0, coverMainCards.length)
    uncoverMainCards.splice(0, uncoverMainCards.length)

    suma=0

    uncoverMainCard.classList.remove("coverMainCards")
}

//---------------------Add history----------------
function addForHistory(card){
    let historyGame = document.getElementById("history")
    card.style = ""
    card.classList.remove("removeCard")
    historyGame.insertBefore(card, historyGame.firstChild)
}
