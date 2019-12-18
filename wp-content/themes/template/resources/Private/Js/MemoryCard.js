// const cards = document.querySelectorAll('.memory-card');

class MemoryCard {
    // let hasFlippedCard = false;
    // let firstCard, secondCard;

    constructor() {
        this.hasFlippedCard = false;

        this.firstCard = '';
        this.secondCard = '';
    }

     flipCard(event) {
         let e = event.path[1];

        e.classList.add('flip');

        if(!this.hasFlippedCard) {
            this.hasFlippedCard = true;
            this.firstCard = e;
        } else {
            this.hasFlippedCard = false;
            this.secondCard = e;

            if(this.firstCard.dataset.framework === this.secondCard.dataset.framework) {
                this.firstCard.removeEventListener('click', this.flipCard);
                this.secondCard.removeEventListener('click', this.flipCard);
            } else {
                setTimeout(() => {
                    this.firstCard.classList.remove('flip');
                    this.secondCard.classList.remove('flip');
                }, 500);
            }
        }
    }
}
