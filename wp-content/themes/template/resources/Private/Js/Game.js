// var custom_fields = JSON.parse(cars_array);
// var my_images = JSON.parse(passed_object);

let arrayAllAnswers = [];

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


        /*
        Set visibility of quiz Form
         */
        this.showQuizForm = true;

        ////////////////////////////////////////////////////////////////////////////////////// Quiz Form section variables and functions()

        // question index
        this.questionIndex = 0;

        /*
        * Set clicked status of quiz form
         */
        this.formClicked = false;

        /*
        * Variable for arrow visibility
         */
        this.arrowVisibility = false;

        // count of right answers
        this.countRightAnswers = 0;

        // count of incorrect answers
        this.countIncorrectAnswers = 0;

        /*
        * Variables on already answered questions
         */
        this.showAlreadyAnswerQuestions = false;

        
    }

    showQuiz() {
        this.showQuizForm = true;
    }

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

    // section with Quiz
     /*
     * would like to separate it soon
      */
     test(clickedId) {
         var rightAnswer = document.getElementById('rightAnswer');
         var elements = document.getElementsByClassName('choice');


         // array for collection of all answers


         console.log("*****************************");
         console.log(rightAnswer.value);
         console.log("*****************************");


          // get current ID
          clickedId = clickedId || window.event;
          clickedId = clickedId.target || clickedId.srcElement;

          // if we clicked on one of our answers
          if(clickedId.id === '1' || clickedId.id === '2' || clickedId.id === '3' || clickedId.id === '4') {

              if(this.formClicked === false) {
                  // set form clicked on TRUE
                  this.formClicked = true;

                  // consider it!!!
                  // var els = document.querySelectorAll('.choice');
                  // for (var i=0; i < els.length; i++) {
                  //     els[i].setAttribute("disabled", "");
                  // }


                  // remove active class for all buttons
                  for(let i=0;i<elements.length;i++) {
                      elements[i].classList.remove('active');
                  }

                  // right answer
                  if(parseInt(clickedId.textContent) === parseInt(rightAnswer.value)) {

                      clickedId.style.border = "thick solid #00ff00";
                      arrayAllAnswers.push({right_answer_id: clickedId.id, incorrect_answer_id: 0, question_index: this.questionIndex});

                      // increment right answers count
                      this.countRightAnswers++;

                      // call function for setting answers count
                      this.setAnswersCount();

                      // call function for setting arrow visibility and set values for next question
                      if(this.questionIndex !== 4) {
                          this.arrowVisibility = true;
                      }

                      // call function for entering name
                  }

                  else {
                      clickedId.style.border = "thick solid #ff0000";

                      // set value for right answer
                      for(let i=0;i<elements.length;i++) {
                          if(parseInt(elements[i].textContent) === parseInt(rightAnswer.value)) {
                              elements[i].style.border = "thick solid #00ff00";
                              arrayAllAnswers.push({right_answer_id: elements[i].id, incorrect_answer_id: clickedId.id, question_index: this.questionIndex});
                          }
                      }

                      // increment incorrect answers count
                      this.countIncorrectAnswers++;

                      // call function for setting answers count
                      this.setAnswersCount();

                      // call function for setting arrow visibility and set values for next question
                      if(this.questionIndex !== 4) {
                          this.arrowVisibility = true;
                      }
                  }

                  console.log('//////////////////////////');
                  console.log(arrayAllAnswers);
                  console.log('//////////////////////////');

              }
          }
     }

     setNextQuestion() {
         let myAnswers = JSON.parse(answers);
         let myQuestions = JSON.parse(questions);

         document.getElementById('question-number').innerHTML = this.questionIndex+1;
         let elements = document.getElementsByClassName('choice');

         // set arrow again on visible value
         this.arrowVisibility = false;

         // remove active class for all buttons and remove border styles too
         for(let i=0;i<elements.length;i++) {
             elements[i].classList.remove('active');
             elements[i].style.border = "";
         }

         this.questionIndex+=1;

         // set new question
         document.getElementById('question-title').innerHTML = myQuestions[this.questionIndex].post_title;

         // set new answers
         document.getElementById('1').innerHTML = myAnswers[this.questionIndex].answer_a;
         document.getElementById('2').innerHTML = myAnswers[this.questionIndex].answer_b;
         document.getElementById('3').innerHTML = myAnswers[this.questionIndex].answer_c;
         document.getElementById('4').innerHTML = myAnswers[this.questionIndex].answer_d;

         // set right answer
         document.getElementById('rightAnswer').value = myAnswers[this.questionIndex].right_answer;

         // set text value for information of question number
         document.getElementById('question-number').innerHTML = this.questionIndex+1;


         // set form again to be clicked
         this.formClicked = false;
     }

     setAnswersCount() {
         // set count for span with correct answers
         document.getElementById('count_right_answers').innerHTML = this.countRightAnswers;
         document.getElementById('count_incorrect_answers').innerHTML = this.countIncorrectAnswers;
     }

    showNameForm() {
        document.getElementById("enter-name-form").style.display = "block";
        document.getElementById("quiz-content").style.display = "none";
    }
}

Game.O = 'O';
Game.X = 'X';
