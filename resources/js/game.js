import './gameStyle'

const PLAYERS = Object.freeze({ HUMAN: "Człowek", BOT: "Bot" });

//-----------------Card names--------------------------------
const mainCards = ["02_Trefl", "03_Trefl", "04_Trefl", "05_Trefl", "06_Trefl", "07_Trefl", "08_Trefl", "09_Trefl", "10_Trefl", "0J_Trefl", "0Q_Trefl", "0K_Trefl", "0A_Trefl", "02_Pik", "03_Pik", "04_Pik", "05_Pik", "06_Pik", "07_Pik", "08_Pik", "09_Pik", "10_Pik", "0J_Pik", "0Q_Pik", "0K_Pik", "0A_Pik", "02_Kier", "03_Kier", "04_Kier", "05_Kier", "06_Kier", "07_Kier", "08_Kier", "09_Kier", "10_Kier", "0J_Kier", "0Q_Kier", "0K_Kier", "0A_Kier", "02_Karo", "03_Karo", "04_Karo", "05_Karo", "06_Karo", "07_Karo", "08_Karo", "09_Karo", "10_Karo", "0J_Karo", "0Q_Karo", "0K_Karo", "0A_Karo"];
let mainCardsImg = []

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
let coverMainCardImg = document.querySelector("#coverMainCards")
let uncoverMainCardImg = document.querySelector("#uncoverMainCards")

//-----------------Whose move----------------------------------
let whoNow = document.getElementById("whoNow")

//------------------Game launch and launch support----------------------------------------------
let mode
let firstGame = true
let canContinue = true
let newGame = document.getElementById("start")
newGame.addEventListener("click", start)

//-------------------------Start the game----------------------------
function start(){
    if(firstGame){
        for(let i=0; i<mainCards.length; i++){
            let img = document.createElement("img")
            img.setAttribute("alt", mainCards[i])
            img.setAttribute("src", '/storage/cards/'+mainCards[i]+'.png')
            mainCardsImg.unshift(img);
        }
    }

    newGame.textContent="Od nowa"
    firstGame = false

    coverMainCardImg.addEventListener("click", drawUncoverMain)

    resetGames()

    let whoWin = document.querySelector("#whoWin")
    if(whoWin != null)  whoWin.remove()

    whoNow.innerText=PLAYERS.HUMAN

    coverMainCardImg.classList.add("cover")

    coverMainCards = shuffleCards(mainCards)

    for(let i=0; i<10;i++){
        let img = creatCard(coverMainCards.shift())

        if(i%2==0) youCards.appendChild(img)
        if(i%2==1){
            //img.setAttribute("src", '/storage/cards/background_card.png');
            bot1Cards.appendChild(img)
        }
    }


    let sing = coverMainCards.at(0).substring(0,2)
    if(sing=="02" || sing=="03" || sing=="0J" || sing=="0Q" || sing=="0K" || sing=="0A") start()

    uncoverMainCards.unshift(coverMainCards.shift())
    uncoverMainCardImg.setAttribute("src", "/storage/cards/"+uncoverMainCards.at(0)+".png")
    uncoverMainCardImg.setAttribute("alt", uncoverMainCards.at(0))

    let history = document.getElementById("history")
    while (history.firstChild) {
        history.removeChild(history.firstChild);
    }
    history.appendChild(uncoverMainCardImg.cloneNode(true))

    for(let card of youCards.children){
        card.addEventListener("click", selectingCard)
    }
    canContinue = true
    mode = document.querySelector('input[name="tryb"]:checked').value
}

//-------------Adding a card for grasz after clicking face down--------------------
function drawUncoverMain(){
    if(whoNow.innerText==PLAYERS.HUMAN){
        if(canContinue){
            for(let i=1;i<suma;i++) drawCard(PLAYERS.HUMAN)
            drawCard(PLAYERS.HUMAN)
        }


        suma = 0
        sumaSpan.innerText = ""

        whoNow.innerText=PLAYERS.BOT

         setTimeout(function (){
             bot()
           }, 2000)
    }
    else alert("Nie twój ruch nie możesz brać karty")
}

//----------------Creating cards-----------------------------
function creatCard(card){
    let img = document.createElement("img")
    if (whoNow.innerText==PLAYERS.HUMAN) img.setAttribute("src", '/storage/cards/'+card+'.png')
    // else img.setAttribute("src", '/storage/cards/background_card.png')
    else img.setAttribute("src", '/storage/cards/'+card+'.png')
    img.setAttribute("alt", card)
    return img
}

//-----------Adding a card for player------------------
function drawCard(who){
    let img = creatCard(coverMainCards.shift())

    img.style.opacity = 0

    if(who==PLAYERS.HUMAN && whoNow.innerText==PLAYERS.HUMAN){
        img.addEventListener("click", selectingCard)
        youCards.appendChild(img)
    }
    if(who==PLAYERS.BOT)  bot1Cards.appendChild(img)

    setTimeout(function(){
        img.classList.add("addCard")
    }, 50)

    checkCoverCards()

    if(coverMainCards.length==0 && uncoverMainCards.length==1){
        let whoWin = document.createElement("p")
        whoWin.id="whoWin"
        if(youCards.children.length > bot1Cards.children.length) whoWin.textContent="Koniec gry wygrał bot"
        if(youCards.children.length < bot1Cards.children.length) whoWin.textContent="Koniec gry wygrałeś ty"
        document.querySelector(".centerBoard").insertBefore(whoWin, document.querySelector(".centerBoard p"))
        setTimeout(function(){
            resetGames()
        }, 600)
        canContinue = false
    }
}

//-------------------------Adding cards to the game if taking from the pile-------------
function checkCoverCards(){
    if(coverMainCards.length==0){
        coverMainCards = shuffleCards(uncoverMainCards)
        uncoverMainCards.splice(0, uncoverMainCards.length-1)
        coverMainCards.splice(coverMainCards.indexOf(uncoverMainCards[0]), 1)
    }
}

//-----------------------Shuffle the cards------------------------------------
function shuffleCards(deckToShuffle) {
    let n=deckToShuffle.length;
    let k=n;
    let numbers = new Array(n);
    let coverCards = []

    for (let i=0; i<n; i++) {
        numbers[i] = i + 1
    }

    for (let i=0; i<k; i++) {
        let r = Math.floor(Math.random()*n)

        coverCards[i]=deckToShuffle[numbers[r]-1]

        numbers[r] = numbers[n - 1]
        n--
    }

    return coverCards
}

//---------------------------Function called after selecting a card-----------------------------
function selectingCard(){
    let selectingCardAlt = this.getAttribute('alt')

    if(whoNow.innerText==PLAYERS.HUMAN && checkCard(selectingCardAlt)){
        uncoverMainCards.unshift(selectingCardAlt)

        uncoverMainCardImg.setAttribute("src", "/storage/cards/"+selectingCardAlt+".png")
        uncoverMainCardImg.setAttribute("alt", selectingCardAlt)

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
    else if(whoNow.innerText!=PLAYERS.HUMAN) alert("Nie twój ruch")
    else alert("Tą kartą nie można zagrać")
}

//-------------------------Obsługa boty----------------------------
function bot(){
    if(whoNow.innerText==PLAYERS.BOT) ruchBota(bot1Cards.children)
}

//------------------------------Bot movement-------------------------------------
function ruchBota(cards){
    let playedCard
    let isCard=false
    if(mode == "Heurystyczne") playedCard = heurystyczne(cards)
    if(mode == "MCTS") playedCard = mcts(cards)

    if(playedCard != null){
        checkCard(playedCard.getAttribute("alt"))
        isCard=true
    }

    changeCard(isCard, playedCard)
}

function heurystyczne(cards){
    let cardsSpecial = []
    let cardsNotSpecial = []

    let unCoverCardAlt = uncoverMainCardImg.getAttribute("alt")
    for(let card of cards){
        let cardAlt = card.getAttribute("alt")

        let cardSign = cardAlt.substring(0,2)
        let cardFigure = cardAlt.substring(3, cardAlt.length)
        let unCoverCardSign = unCoverCardAlt.substring(0,2)
        let unCoverCardFigure = unCoverCardAlt.substring(3, unCoverCardAlt.length)

        if(cardSign==unCoverCardSign || cardFigure==unCoverCardFigure){

            if(cardSign == "02" ||
               cardSign == "03" ||
               cardSign == "0K" ||
               cardSign == "0J" ||
               cardSign == "0Q" ||
               cardSign == "0A")
            {
                cardsSpecial.unshift(card)
            }
            else{
                cardsNotSpecial.unshift(card)
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

    if (cardsNotSpecial.length > 0 && suma==0) {
        return cardsNotSpecial.shift()
    }

    if(cardsSpecial.length>0){
        return cardsSpecial.shift()
    }

    return null
}

//------------------Monte Carlo Tree Search-----------------------
function mcts(cardPlayed) {
    let cardPlayedAlt = [];
    let youCardsdAlt = [];
    for (let card of cardPlayed) {
        cardPlayedAlt.unshift(card.alt);
    }
    for (let card of youCards.children) {
        youCardsdAlt.unshift(card.alt);
    }

    let initialState = new GameState(cardPlayedAlt, youCardsdAlt, coverMainCards, uncoverMainCards, PLAYERS.BOT, suma);
    let bestMove = runMCTS(initialState, 1000); // 1000 iterations
    if(bestMove == "add"){
        return null
    }
    else return document.querySelector(`img[alt="${bestMove}"]`);
}

//-------------------Checks if this card can be played----------------
function checkCard(selectedCard){
    let coverCard = uncoverMainCardImg.getAttribute("alt")

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
        uncoverMainCardImg.setAttribute("src", selectedCard.getAttribute("src"))
        uncoverMainCardImg.setAttribute("alt", selectedCard.getAttribute("alt"))
        uncoverMainCards.unshift(selectedCard.getAttribute("alt"))

        selectedCard.classList.remove("addCard")
        selectedCard.classList.add("removeCard")
        addForHistory(selectedCard)
    }
    else{
        if(canContinue){
            for(let i=1; i<suma; i++) drawCard(whoNow.innerText)
            drawCard(whoNow.innerText)
        }
        suma=0;
        sumaSpan.innerText=""

    }
    setTimeout(function(){
        checkWin(whoNow.innerText)
    }, 800)
}

//--------------Checking if someone won------------
function checkWin(who){
    let cards
    switch(who){
        case PLAYERS.HUMAN:
            cards = youCards.children
            whoNow.innerText=PLAYERS.BOT
            break;
        case PLAYERS.BOT:
            cards = bot1Cards.children
            whoNow.innerText=PLAYERS.HUMAN
            break;
    }

    if((cards.length)==0) win(who)
}

//-------------------What happens if someone wins?-----------------------------
function win(who){
    let text
    if(who==PLAYERS.HUMAN) text = "Wygrałeś"
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

    uncoverMainCardImg.classList.remove("coverMainCards")
}

//---------------------Add history----------------
function addForHistory(card){
    let historyGame = document.getElementById("history")
    card.style = ""
    card.classList.remove("removeCard")
    historyGame.insertBefore(card, historyGame.firstChild)
}


class GameState {
    constructor(botCards, humanCards, coverCards, uncoverCards, player = PLAYERS.HUMAN, suma=0) {
        this.botCards = [...botCards]
        this.humanCards = [...humanCards]
        this.coverCards = [...coverCards]
        this.uncoverCards = [...uncoverCards]
        this.player = player
        this.suma = suma
    }

    // Generate possible moves based on the game rules
    getPossibleMoves() {
        let possibleCard = []

        let unCard = this.uncoverCards[0]
        let uncoverCardSign = unCard.substring(0, 2)
        let uncoverCardFigure = unCard.substring(3, unCard.length)
        this.botCards.forEach(card => {
            let cardSign = card.substring(0,2)
            let cardFigure = card.substring(3, card.length)

            if(cardSign==uncoverCardSign || cardFigure==uncoverCardFigure){
                if(this.suma == 0) possibleCard.unshift(card)
                else if(cardSign == "02" ||
                cardSign == "03" ||
                cardSign == "0K" ||
                cardSign == "0J" ||
                cardSign == "0Q" ||
                cardSign == "0A")possibleCard.unshift(card)
            }
        })
        if(this.coverCards.length>0 && this.suma<=this.coverCards.length){
            possibleCard.push("add")
        }
        return possibleCard
    }

    // Apply a move and return the new state
    makeMove(move) {
        let newBotCards = [...this.botCards]
        let newHumanCards = [...this.humanCards]
        let newCoverCards = [...this.coverCards]
        let newUncoverCards = [...this.uncoverCards]
        let newSuma = this.suma
        if(this.player == PLAYERS.BOT){
            if(move == "add"){
                for(let i=1; i<newSuma; i++) newBotCards.unshift(newCoverCards.shift())
                newBotCards.unshift(newCoverCards.shift())
                newSuma = 0
            }
            else{
                newUncoverCards.unshift(move)
                newBotCards.splice(newBotCards.indexOf(move), 1);
            }
        }
        if(this.player == PLAYERS.HUMAN){
            if(move == "add"){
                for(let i=1; i<newSuma; i++) newHumanCards.unshift(newCoverCards.shift())
                newHumanCards.unshift(newCoverCards.shift())
                newSuma = 0
            }
            else{
                newUncoverCards.unshift(move)
                newHumanCards.splice(newHumanCards.indexOf(move), 1)
            }
        }

        console.log("move:" + move)
        
        if(move != "add")
        {
            let sing = move.substring(0, 2)
            switch(sing){
                case "02":
                    newSuma+=2
                    break
                case "03":
                    newSuma+=3
                    break
                case "0Q":
                    newSuma=0
                    break
                case "0J":
                    if(newSuma<=5){
                        newSuma=0;
                    }
                    else{
                        newSuma-=5
                    }
                    break
                case "0K":
                    newSuma+=5
                    break
            }
        }
        else{
            if(newCoverCards.length==0){
                newCoverCards = shuffleCards(newUncoverCards)
                newUncoverCards.splice(0, newUncoverCards.length-1)
                newCoverCards.pop()
            }
        }

        let newPlayer = this.player === PLAYERS.HUMAN ? PLAYERS.BOT : PLAYERS.HUMAN
        return new GameState(newBotCards, newHumanCards, newCoverCards, newUncoverCards, newPlayer, newSuma);
    }

    // Check if the game has ended
    isGameOver() {
        if(this.humanCards.length == 0 || this.botCards.length == 0 || ((this.coverCards.length == 0 && this.uncoverCards.length==1) || (this.coverCards.length<this.suma))){
            return true
        }
        else return false
    }
}

class MCTSNode {
    constructor(parent = null, move = null, state) {
        this.parent = parent;
        this.move = move;
        this.state = state;
        this.children = [];
        this.wins = 0;
        this.visits = 0;
    }

    // Select a child node using the UCT (Upper Confidence Bound applied to Trees) formula
    selectChild() {
        let selectedChild;
        let bestValue = -Infinity;
        this.children.forEach(child => {
            let uctValue =
                child.wins / child.visits +
                Math.sqrt(2) * Math.sqrt(Math.log(this.visits) / child.visits);
            if (uctValue > bestValue) {
                selectedChild = child;
                bestValue = uctValue;
            }
        });
        return selectedChild;
    }

    // Add a child node
    addChild(move, state) {
        const child = new MCTSNode(this, move, state);
        this.children.push(child);
        return child;
    }

    // Update this node after a simulation
    update(result) {
        this.visits++;
        this.wins += result;
    }
}

function runMCTS(rootState, iterations) {
    const rootNode = new MCTSNode(null, null, rootState);

    for (let i = 0; i < iterations; i++) {
        let node = rootNode;
        let state = rootState; // Deep copy might be needed

        // Selection
        while (node.children.length && node.state.getPossibleMoves().length) {
            node = node.selectChild();
            state = state.makeMove(node.move);
        }

        // Expansion
        if (node.state.getPossibleMoves().length) {
            const moves = node.state.getPossibleMoves();
            const move = moves[Math.floor(Math.random() * moves.length)];
            state = state.makeMove(move);
            node = node.addChild(move, state);
        }

        // Simulation
        while (!state.isGameOver()) {
            const moves = state.getPossibleMoves();
            const move = moves[Math.floor(Math.random() * moves.length)];
            state = state.makeMove(move);
        }

        // Backpropagation
        let result = state.winner === PLAYERS.BOT ? 1 : 0;
        while (node) {
            node.update(result);
            node = node.parent;
        }
    }

    // Choose the best move at the root
    return rootNode.selectChild().move;
}
