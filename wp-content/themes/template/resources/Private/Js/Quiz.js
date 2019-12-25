class Quiz {
    constructor() {
        /*
        * Variable for arrow visibility
       */
        this.arrowVisibility = false;

        // count of right answers
        this.countRightAnswers = 0;

        // count of incorrect answers
        this.countIncorrectAnswers = 0;

        /*
        * Set clicked status of quiz form
         */
        this.formClicked = false;

        // question index
        this.questionIndex = 0;


    }

    test(event) {
        let rightAnswer = document.getElementById('rightAnswer');
        let elements = document.getElementsByClassName('choice');

        if((this.formClicked === false) && (event.target.id === '1' || event.target.id === '2' || event.target.id === '3' || event.target.id === '4')) {
            // set form clicked on TRUE
            this.formClicked = true;

            // get clicked button
            let clickedId = event.target;

            // remove active class for all buttons
            for(let i=0;i<elements.length;i++) {
                elements[i].classList.remove('active');
            }

            // right answer
            if(clickedId.innerText === rightAnswer.innerText) {

                clickedId.style.border = "thick solid #00ff00";

                // increment right answers count
                this.countRightAnswers++;

                // call function for setting answers count
                this.setAnswersCount();

                // call function for setting arrow visibility and set values for next question
                if(this.questionIndex !== 4) {
                    this.arrowVisibility = true;
                }
            }

            else {
                clickedId.style.border = "thick solid #ff0000";

                // set value for right answer
                for(let i=0;i<elements.length;i++) {
                    if(elements[i].innerText === rightAnswer.innerText) {
                        elements[i].style.border = "thick solid #00ff00";
                        // arrayAllAnswers.push({right_answer_id: elements[i].id, incorrect_answer_id: clickedId.id, question_index: this.questionIndex});
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
        }
    }

    setNextQuestion() {
        let myAnswers = window.answers;
        let myQuestions = window.questions;

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
        document.getElementById('rightAnswer').innerHTML = myAnswers[this.questionIndex].right_answer;

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
