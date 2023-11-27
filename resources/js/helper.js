export const PLAYERS = Object.freeze({ HUMAN: document.querySelector(".human p").textContent, BOT: "Bot" })

export function shuffleCards(deckToShuffle) {
    let n=deckToShuffle.length
    let k=n
    let numbers = new Array(n)
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
