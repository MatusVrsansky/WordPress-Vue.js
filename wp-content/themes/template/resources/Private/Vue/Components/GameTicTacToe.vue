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

                <p>Mozete ist odpovedat na otazky</p>
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
                                    <label for="exampleInputEmail1" class="mb-2">Zadajte svoje meno a priezvisko</label>
                                            <input type="text" class="form-control" id="name" oninvalid="activeGame.InvalidMsg(this);" aria-describedby="emailHelp" placeholder="Vaše meno" required>
                                    <input type="text" class="form-control" id="surname" oninvalid="activeGame.InvalidMsg(this);" aria-describedby="emailHelp" placeholder="Vaše priezvisko" required>
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
                                <div class="quiz" id="quiz" data-toggle="buttons" @click.prevent="activeGame.test(this.id)">
                                    <label id="1" class="choice element-animation1 btn btn-lg btn-primary btn-block">
                                        <span class="btn-label"><i class="glyphicon glyphicon-chevron-right"></i></span>
<!--                                           <input type="radio" name="q_answer" value="1">-->
                                        {{ jsonRandomQuestionsAnswers[0].answer_a }}
                                    </label>
                                    <label id="2" class="choice element-animation2 btn btn-lg btn-primary btn-block"><span class="btn-label"><i class="glyphicon glyphicon-chevron-right"></i></span>
                                        {{ jsonRandomQuestionsAnswers[0].answer_b }}
                                    </label>
                                    <label id="3" class="choice element-animation3 btn btn-lg btn-primary btn-block"><span class="btn-label"><i class="glyphicon glyphicon-chevron-right"></i></span>
                                        {{ jsonRandomQuestionsAnswers[0].answer_c }}
                                    </label>
                                    <label id="4" class="choice element-animation4 btn btn-lg btn-primary btn-block mb-2-half"><span class="btn-label"><i class="glyphicon glyphicon-chevron-right"></i></span>
                                        {{ jsonRandomQuestionsAnswers[0].answer_d }}
                                    </label>
                                    <!-- right answer for current question-->
                                    <input type="hidden" name="question_right_answer" id="rightAnswer" value="">
                                </div>
                                <div class="d-flex">
                                    <div class="question-information-counter"><span id="question-number">1</span> z 5</div>
                                       <div v-if="activeGame.questionIndex > 0"><span id="arrow-back">&#8592;</span></div>

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
                jsonRandomQuestionsTitles: window.randomQuizQuestionsTitles,
                jsonRandomQuestionsAnswers: window.randomQuestionsAnswers,

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
            setQuizFormVisibility: function() {
                if(activeGame.winnersPlayerOne === 2 || activeGame.winnersPlayerTwo === 2) {
                    if(activeGame.winnersPlayerOne === 2) {
                        activeGame.winnerPlayer = 'O';
                        activeGame.looserPlayer = 'X';
                        activeGame.hideGameForm = true;

                        // set reset button on false
                        activeGame.showResetButton = false;
                    }

                    else {
                        activeGame.winnerPlayer = 'X';
                        activeGame.looserPlayer = 'O';
                        activeGame.hideGameForm = true;

                        // set reset button on false
                        activeGame.showResetButton = false;
                    }
                }
            }
        }
    }

</script>
