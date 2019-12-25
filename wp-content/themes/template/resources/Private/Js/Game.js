// var custom_fields = JSON.parse(cars_array);
// var my_images = JSON.parse(passed_object);

// let arrayAllAnswers = [];


class Game {
    constructor() {
        this.inProgress = true;
        this.winner = null; // O or X
        this.currentTurn = this.generateRandomNumber();
        this.movesMade = 0;
        this.squares = new Array(9).fill().map(s => new Square() );

        this.winnersPlayerOne = 0;
        this.winnersPlayerTwo = 0;

        this.hideGameForm = false;

        // show again button
        this.showResetButton = false;

        // set total winner and looser of the game
        this.winnerPlayer = '';
        this.looserPlayer = '';

        /**************************************************************/
        /* question or category creation */
        this.validQuestionForm = false;
    }

    // set random order of current player
    generateRandomNumber() {
        var number = Math.floor((Math.random() * 2) + 1);

        if(number === 1) {
            return Game.O;
        }

        else {
            return Game.X;
        }
    }

    makeMove(i) {
        if(this.inProgress && !this.squares[i].value) {
            this.squares[i].value = this.currentTurn;

            this.movesMade++;
            this.checkForWinner();
            this.currentTurn = (this.currentTurn === Game.O) ? Game.X : Game.O;
        }
    }

    resetValues() {
        // set Reset button on false
        this.showResetButton = false;

        this.inProgress = true;
        this.winner = null; // O or X
        this.currentTurn = this.generateRandomNumber();
        this.movesMade = 0;
        this.squares = new Array(9).fill().map(s => new Square() );
    }

    checkForWinner() {
        const winningCombinations = [
            [0,1,2],
            [3,4,5],
            [6,7,8],
            [0,3,6], //a=0, b=3, c=6
            [1,4,7],
            [2,5,8],
            [0,4,8],
            [2,4,6]
        ];

        winningCombinations.forEach((wc) => {
            const [a,b,c] = wc;

            const sqA = this.squares[a];
            const sqB = this.squares[b];
            const sqC = this.squares[c];

            if(sqA.value && sqA.value === sqB.value && sqA.value === sqC.value) {
                this.inProgress = false;
                this.winner = sqA.value;    // 'O' or 'X'
                sqA.isHighlighted = sqB.isHighlighted = sqC.isHighlighted = true;
            }

        });

        // Check for tie
        if(this.movesMade === this.squares.length) {
            this.inProgress = false; // inProgress = false AND winner = null
        }

    }

}

Game.O = 'O';
Game.X = 'X';
