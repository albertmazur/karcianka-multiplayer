const PLAYERS = Object.freeze({ HUMAN: document.querySelector(".human p").textContent, BOT: "Bot" });

export function mcts(cardPlayed, youCards, coverMainCards, uncoverMainCards, PLAYERS, suma) {
    PLAYERS = PLAYERS
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
