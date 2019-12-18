// const cards = document.querySelectorAll('.memory-card');

class MemoryCard {
    // let hasFlippedCard = false;
    // let firstCard, secondCard;

    constructor() {
        this.hasFlippedCard = false;
        this.lockBoard = false;

        this.firstCard = '';
        this.secondCard = '';

    }

     flipCard(event) {
         let e = event.path[1];

         if(this.lockBoard) {
             return;
         }

         if(e === this.firstCard) {
             return;
         }


        e.classList.add('flip');

        if(!this.hasFlippedCard) {
            this.hasFlippedCard = true;
            this.firstCard = e;

            return;
        }

        // second click
        this.secondCard = e;

        this.checkForMatch();
    }

    checkForMatch() {
        let isMatch = this.firstCard.dataset.framework === this.secondCard.dataset.framework;

        isMatch ? this.disableCards() : this.unflipCards();
    }

    disableCards() {
        this.firstCard.removeEventListener('click', this.flipCard);
        this.secondCard.removeEventListener('click', this.flipCard);

        this.resetBoard();
    }

    unflipCards() {
        this.lockBoard = true;

        // not a match
        setTimeout(() => {
            this.firstCard.classList.remove('flip');
            this.secondCard.classList.remove('flip');

            this.resetBoard();
        }, 1500);
    }

    resetBoard() {
        this.hasFlippedCard = false;
        this.lockBoard = false;

        this.firstCard = null;
        this.secondCard = null;
    }

shuffle() {
        const cards = document.querySelectorAll('.memory-card');

        cards.forEach(card => {
            let randomPos = Math.floor(Math.random() * 12);
            card.style.order = randomPos;
        });
    };
}
