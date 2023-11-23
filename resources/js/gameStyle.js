window.addEventListener("resize", function(){
    let bot2Cards = document.querySelector("#cardsBot2");

    if(window.innerWidth<=700){
        if(bot2Cards.children.length>5){
            for(let card of bot2Cards.children.length){
                card.style.width = "25px";
            }
        }
    }
    else if(window.innerWidth>700 && window.innerWidth<=1500){
          if(bot2Cards.children.length>5){
              for(let card of bot2Cards.children.length){
                  card.style.width = "50px";
              }
          }
    }
    else{
        for(let card of bot2Cards.children) card.style.width = "";
    }
});
