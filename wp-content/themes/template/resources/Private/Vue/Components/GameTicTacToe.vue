<template>
    <div class="container">
        <div v-if="activeGame.showQuizForm === false">
            <div class="">
                <p>Výhry hráč 1( 0 ): {{ activeGame.winnersPlayerOne }} </p>
                <p>Výhry hráč 2:( X ): {{ activeGame.winnersPlayerTwo }} </p>
            </div>
            <div id="game-view-info">
               {{ infoMessage }}
            </div>
            <div v-if="activeGame.hideGameForm === false" id="game-view-squares">
                <div
                        v-for="(square, i) in activeGame.squares"
                        @click="activeGame.makeMove(i)"
                        v-bind:class="{ highlighted: square.isHighlighted }"
                        class="game-view-square">
                    {{ square.value }}
                </div>
            </div>
            <div v-if="activeGame.showResetButton" class="again-button">
                <button type="button" class="btn btn-primary" @click.prevent="activeGame.resetValues()">Este raz</button>
            </div>
            <div v-if="activeGame.hideGameForm === true" id="winner-information">
                <p>Hráč <strong>{{ activeGame.winnerPlayer }}</strong> vyhral nad súperom <strong>{{ activeGame.looserPlayer }}</strong> </p>

                <p>Vyberte si, z ktorej kategórie chcete odpovedať na otázky</p>
                <p>Slideshow 2:</p>
                <div class="slideshow-container">
                    <div class="mySlides" v-for="(category, index) in jsonAllCategories" v-bind:style="index === 0 ? 'display: block' : ''">
                        <div class="numbertext">1 / 3</div>
                        <img src="" style="width:100%">
                        <button type="button" class="btn btn-info btn-small text category-name" @click="setRandomQuizQuestionsCategory">{{category.name}}</button>
                    </div>
                    <a class="prev" @click="plusSlides(-1)">&#10094;</a>
                    <a class="next" @click="plusSlides(1)">&#10095;</a>
                </div>
                <br>
                <div style="text-align:center">
                    <span v-for="(category,index) in jsonAllCategories" class="dot" v-bind:class="index === 0? 'active' : ''"></span>
                </div>
                <button type="button" @click.prevent="activeGame.showQuiz()" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">odpovedat</button>
            </div>
        </div>

        <div v-if="activeGame.showQuizForm === true">
            <div class="container-fluid bg-info">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div id="enter-name-form" class="p-4" style="display: none">
                            <form type="POST" onsubmit="return activeGame.handle_my_input()">
                                <div class="form-group">
                                    <label class="mb-2">Zadajte svoje meno a priezvisko</label>
                                    <input type="text" class="form-control" id="name" aria-describedby="emailHelp" placeholder="Vaše meno" required>
                                    <input type="text" class="form-control" id="surname" aria-describedby="emailHelp" placeholder="Vaše priezvisko" required>
                                    <small id="emailHelp" class="form-text text-muted">Vaše meno a priezvisko sa zapíše do tabuľky najlepších hráčov</small>
                                </div>
                                <button type="submit" class="btn btn-primary">Odoslať</button>
                            </form>
                        </div>
                        <div id="quiz-content">
                            <input type="hidden" name="question_index" value="0">
                            <input type="hidden" name="questions_array" value="0">
                            <!-- modal-header class before -->
                            <div class="modal-window">
                                <h3 id="question-title"><span class="label label-warning" id="qid"></span>
                                    {{ jsonRandomQuestionsTitles[0].post_title }}
                                </h3>
                                <hr>
                                <div>
                                    <h4>Počet správnych odpovedí:<span id="count_right_answers" class="ml-2">0</span></h4>
                                    <h4>Počet nesprávnych odpovedí:<span id="count_incorrect_answers" class="ml-2">0</span></h4>
                                </div>
                            </div>

                            <div class="modal-body">
                                <div class="col-xs-3 col-xs-offset-5">
                                </div>
                                <div class="quiz" id="quiz"  @click.prevent="activeGame.test">
                                    <button id="1" class="choice element-animation1 btn btn-lg btn-primary btn-block">
                                        <span class="btn-label"><i class="glyphicon glyphicon-chevron-right"></i></span>
                                        {{ jsonRandomQuestionsAnswers[0].answer_a }}
                                    </button>
                                    <button id="2" class="choice element-animation2 btn btn-lg btn-primary btn-block"><span class="btn-label"><i class="glyphicon glyphicon-chevron-right"></i></span>
                                        {{ jsonRandomQuestionsAnswers[0].answer_b }}
                                    </button>
                                    <button id="3" class="choice element-animation3 btn btn-lg btn-primary btn-block"><span class="btn-label"><i class="glyphicon glyphicon-chevron-right"></i></span>
                                        {{ jsonRandomQuestionsAnswers[0].answer_c }}
                                    </button>
                                    <button id="4" class="choice element-animation4 btn btn-lg btn-primary btn-block mb-2-half"><span class="btn-label"><i class="glyphicon glyphicon-chevron-right"></i></span>
                                        {{ jsonRandomQuestionsAnswers[0].answer_d }}
                                    </button>
                                    <!-- right answer for current question-->
                                    <p id="rightAnswer" style="display: none">{{jsonRandomQuestionsAnswers[0].right_answer}}</p>
<!--                                    <input type="hidden" name="question_right_answer" id="rightAnswer" value="">-->
                                </div>
                                <div class="d-flex">
                                    <div class="question-information-counter"><span id="question-number">1</span> z 5</div>
                                    <div v-if="activeGame.arrowVisibility === true" @click.prevent="activeGame.setNextQuestion()" class="ml-auto p-2"><span id="arrow-next">&#8594;</span></div>
                                    <button v-if="activeGame.formClicked === true && activeGame.questionIndex === 4" @click.prevent="activeGame.showNameForm()" class="btn btn-primary ml-auto" type="button">Skončiť test</button>
                                </div>

                            </div>
                            <div class="modal-footer text-muted">
                                <span id="answer"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

    let activeGame = new Game();
    export default {
        data() {
            return {
                activeGame: activeGame,
                jsonAllCategories: window.categories,
                jsonRandomQuestionsTitles: window.randomQuizQuestionsTitles,
                jsonRandomQuestionsAnswers: window.randomQuestionsAnswers,
                slideIndex: 1
            }
        },
        computed: {
            infoMessage: function () {
                if(activeGame.inProgress) {
                    return 'Na rade je ' + activeGame.currentTurn;
                }

                else if (activeGame.winner) {

                    if(activeGame.winner === 'O') {

                        // show reset button
                        activeGame.showResetButton = true;
                        activeGame.winnersPlayerOne++;
                        this.setQuizFormVisibility();
                    }

                    else {

                        // show reset button
                        activeGame.showResetButton = true;
                        activeGame.winnersPlayerTwo++;
                        this.setQuizFormVisibility();
                    }

                    return activeGame.winner + ' vyhráva';
                }

                else {
                    return 'Nevyhráva nikto';
                }
            }
        },
        methods: {
            setRandomQuizQuestionsCategory: function(event) {
                let clickedId = event.target;
                let category = clickedId.innerText;

                this.activeGame.showQuiz();

                $.ajax({
                    url: ajaxurl,
                    type: "POST",
                    data:
                        {
                            "action": "getRandomQuestions",
                            "category": category
                        },
                    success: function (data) {

                    }
                });

            },
            plusSlides: function(n) {
                this.showSlides(this.slideIndex += n);
            },
            currentSlide: function (n) {
                this.showSlides(this.slideIndex = n);
            },
            showSlides: function(n) {
                let i;
                let slides = document.getElementsByClassName("mySlides");
                let dots = document.getElementsByClassName("dot");
                if (n > slides.length) {this.slideIndex = 1}
                if (n < 1) {this.slideIndex = slides.length}
                for (i = 0; i < slides.length; i++) {
                    slides[i].style.display = "none";
                }
                for (i = 0; i < dots.length; i++) {
                    dots[i].className = dots[i].className.replace(" active", "");
                }
                slides[this.slideIndex-1].style.display = "block";
                dots[this.slideIndex-1].className += " active";
            }
        }
    }

</script>
