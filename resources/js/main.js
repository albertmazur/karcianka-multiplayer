const PLAYERS = Object.freeze({ HUMAN: "Człowek", BOT: "Bot" });

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


let newGame = document.getElementById("start")
newGame.addEventListener("click", playGame)

let mainCardsImg = []

//--------------------Stacks of cards--------------------
let coverMainCards = []
let uncoverMainCards = []

//---------------Player cards----------------------------------------------------------
let botCards = document.querySelector("#cardsBot1")
let humanCards = document.querySelector(".cardsHuman")

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
let firstPlay = true

// Przykład
class GameState {
    // Planszę i grasza
    constructor(botCards, humanCards, coverCards, uncoverCards, player = PLAYERS.HUMAN, suma=0) {
        this.botCards = botCards
        this.humanCards = humanCards
        this.coverCards = coverCards
        this.uncoverCards= uncoverCards
        this.player = player
        this.suma = suma

        if(firstPlay){
            console.log("Kart bota")
            console.log(botCards)
            console.log("Kart człowieka")
            console.log(humanCards)
            console.log("Karty uncover")
            console.log(uncoverCards)
            console.log("Karty cover")
            console.log(coverCards)
            firstPlay = false
        }
    }


    // Możliwe ruchy
    get possibleMoves() {
        let possibleCard = []
        let unCard = this.uncoverCards[0]
        console.log("Uncard: " + unCard)
        let uncoverCardSign = unCard.substring(0, 2)
        let uncoverCardFigure = unCard.substring(3, unCard.length)
        console.log("Bot cards: "+  this.botCards)
        this.botCards.forEach(card => {

            let cardSign = card.substring(0,2)
            let cardFigure = card.substring(3, card.length)

            if((cardSign==uncoverCardSign || cardFigure==uncoverCardFigure)){
                possibleCard.push(card)
            }
        })
        console.log("CoverCads: "+this.coverCards.length)
        if(this.coverCards.length>0){
            possibleCard.push("add")
        }
        console.log("Możliwe ruchy:"+ possibleCard)
        return possibleCard

        //return this.board.map((v, i) => v === ' ' ? i : null).filter(i => i !== null);
    }

    // Robi ruch
    makeMove(move) {
        let newBotCards = this.botCards
        let newHumanCards = this.humanCards
        let newCoverCards = this.coverCards
        let newUncoverCards = this.uncoverCards
        let newSuma = this.suma
        if(this.player == PLAYERS.BOT){
            if(move == "add"){
                for(let i=1; i<newSuma; i++) newBotCards.push(newCoverCards.pop())
                newBotCards.push(newCoverCards.pop())
                newSuma = 0
            }
            else{
                newUncoverCards.push(newBotCards[newBotCards.indexOf(move)])
                delete newBotCards[newBotCards.indexOf(move)]
            }
        }
        if(this.player == PLAYERS.HUMAN){
            if(move == "add"){
                for(let i=1; i<newSuma; i++) newHumanCards.push(newCoverCards.pop())
                newHumanCards.push(newCoverCards.pop())
                newSuma = 0
            }
            else{
                newUncoverCards.push(newHumanCards[newHumanCards.indexOf(move)])
                delete newHumanCards[newHumanCards.indexOf(move)];
            }
        }
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
                uncoverMainCards.splice(0, newUncoverCards.length-1)
                newCoverCards.splice(newCoverCards.indexOf(newUncoverCards[0]), 1)
            }
        }

        let newPlayer = this.player === PLAYERS.HUMAN ? PLAYERS.BOT : PLAYERS.HUMAN
        return new GameState(newBotCards, newHumanCards, newCoverCards, newUncoverCards, newPlayer, newSuma);
    }

    // Czy to koniec gry
    get isGameOver() {
        if(this.humanCards.length == 0 || this.botCards.length == 0 || this.coverCards.length == 0) return true
        else return false
    }


    // zwraca zwicięscę
    get winner() {
        if (!this.isGameOver) {
            return null;
        }
        if(humanCards.length==0 || ((this.coverCards.length == 0 && this.uncoverCards.length==1) && humanCards.length < botCards.length)){
            return PLAYERS.HUMAN
        }
        if(botCards.length==0 || ((this.coverCards.length == 0  && this.uncoverCards.length==1) && humanCards.length > botCards.length)){
            return PLAYERS.BOT
        }
    }

    // Do klonowani gry
    clone() {
        return new GameState(this.botCards, this.humanCards, this.coverCards, this.uncoverCards, this.player, this.suma);
    }
}


let moveMian
let isNewCardMain = false
// ---------Uruchamia grę --------//
function playGame() {
    // Tworzy stan gry główny
    let hCards = []
    let bCards = []
    let coverCards = shuffleCards(mainCards)
    let uncoverCards = []
    for(let i=0; i<10;i++){
        if(i%2==0) hCards.push(coverCards.pop())
        if(i%2==1) bCards.push(coverCards.pop())
    }

    uncoverCards.push(coverCards.pop())

    let sing = uncoverCards[0].substring(0,2)
    if(sing=="02" || sing=="03" || sing=="0J" || sing=="0Q" || sing=="0K" || sing=="0A") playGame()
    else{
        let state = new GameState(hCards, bCards, coverCards, uncoverCards)

        // Do póki nie ma końca gry//
        while (!state.isGameOver) {
            let move;
            console.log("czyj ruch:"+ state.player)
            if (state.player === PLAYERS.BOT) {
                //Ruch bota
                move = mcts(state, 1);
            }
            else {
                move = "add"
                //Ruch grasza
                //move = prompt("Your move (0-8): ");
            }
            //Robi ruch
            state = state.makeMove(move);

            //Rysyję planszę  move, isNewCard
        }
        console.log("Wygrał: "+(state.winner))
        //console.log("Game Over. Winner:", state.winner);
    }
}


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

























class MCTSNode {
    // Korzeń gry, rodzica i ruch
    constructor(gameState, parent = null, move = null) {
        this.gameState = gameState;
        this.parent = parent;
        this.move = move;
        this.children = [];
        this.wins = 0;
        this.visits = 0;
    }

    // Sprawdza, czy bieżący węzeł został już w pełni rozwinięty,
    get isFullyExpanded() {
        return this.children.length === this.gameState.possibleMoves.length;
    }

    // Sprawdza, czy bieżący węzeł nie jest końcowym stanem gry
    get isTerminalNode() {
        return this.gameState.isGameOver;
    }

    // Wybór rodzca ze wzorku
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

    // Dodanie chiecka
    addChild(move, gameState) {
        const newChild = new MCTSNode(gameState, this, move);
        this.children.push(newChild);
        return newChild;
    }

    // update wynoków
    update(result) {
        this.visits++;
        this.wins += result;
    }
}

// Ruch grasza
function mcts(rootState, itermax) {
    // Tworzy korzeń gry
    const rootNode = new MCTSNode(rootState);

    //Ile razy symulacji
    for (let i = 0; i < itermax; i++) {
        //Tworzy stan gry
        let node = rootNode;

        // Tworzy klona
        let state = rootState.clone();

        // jest odpowiedzialny za przeszukiwanie drzewa MCTS od korzenia do liścia, wybierając na każdym etapie optymalne według pewnej strategii węzły,
        while (node.isFullyExpanded && !node.isTerminalNode) {
            node = node.selectChild();
            state = state.makeMove(node.move);
        }

        // wykonuje się, gdy algorytm osiąga węzeł, który nie jest jeszcze w pełni rozwinięty.
        if (!node.isFullyExpanded) {
            const moves = state.possibleMoves;
            const move = moves[Math.floor(Math.random() * moves.length)];
            state = state.makeMove(move);
            node = node.addChild(move, state);
        }

        // sumulacja gry
        while (!state.isGameOver) {
            const moves = state.possibleMoves;
            const move = moves[Math.floor(Math.random() * moves.length)];
            state = state.makeMove(move);
        }

        // Backpropagation
        let result = state.winner === PLAYERS.BOT ? 1 : 0;
        while (node !== null) {
            node.update(result);
            node = node.parent;
            result = 1 - result;  // Switch result for the other player
        }
    }

    return rootNode.children.sort((a, b) => b.visits - a.visits)[0].move;
}
