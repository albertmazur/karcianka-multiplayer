import {special_card_check} from './helper.js'

export function heuristic(cards, uncoverMainCardImg, youCardsCount, suma){
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
            if(special_card_check(cardSign))cardsSpecial.unshift(card)
            else cardsNotSpecial.unshift(card)
        }
    }

    if(youCardsCount<=2){
        console.log(youCardsCountr)
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
