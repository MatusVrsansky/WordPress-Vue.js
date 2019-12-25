<script>

    import TestComponent from "./test/TestComponent.vue";

    window.table = 'Table Game Tic Tac';

    let activeGame = new Game();
    export default {
        data() {
            return {
                quizFormVisibility: false,
                activeGame: activeGame,
                jsonAllCategories: window.categories,
                jsonRandomQuestionsTitles: {},
                jsonRandomQuestionsAnswers: {},
                slideIndex: 1,
                name_player: '',
                surname_player: '',
                attemptSubmit: false,
                hideTicTacToeGame : false,
                test: 'I am test from parent Component'
            }
        },
        components: {
            TestComponent
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
                    activeGame.showResetButton = true;
                    return 'Nevyhráva nikto';
                }
            },
            missingPlayerName: function () {
                return this.name_player === '';
            },
            missingPlayerSurname: function () {
                return this.surname_player === '';
            }
        },
        methods: {
            setQuizFormVisibility: function() {
                if(activeGame.winnersPlayerOne === 2 || activeGame.winnersPlayerTwo === 2) {
                    if(activeGame.winnersPlayerOne === 2) {
                        activeGame.winnerPlayer = 'O';
                        activeGame.looserPlayer = 'X';
                        this.hideTicTacToeGame = true;

                        // set reset button on false
                        activeGame.showResetButton = false;
                    }

                    else {
                        activeGame.winnerPlayer = 'X';
                        activeGame.looserPlayer = 'O';
                        this.hideTicTacToeGame = true;

                        // set reset button on false
                        activeGame.showResetButton = false;
                    }
                }
            },
            setRandomQuizQuestionsCategory: function(event) {
                let self = this;

                event.preventDefault();
                let clickedId = event.target;

                console.log(clickedId.textContent);

                $.ajax({
                    url: ajaxurl,
                    type: "POST",
                    dataType: "json",
                    data:
                        {
                            "action": "getRandomQuestions",
                            "category": clickedId.textContent
                        },
                    success: function (data) {

                        // this.jsonRandomQuestionsTitles = data;
                        self.setValues(data.data1, data.data2);


                      // /  this.activeGame.showQuizForm = true;
                    }
                });

            },
            setValues: function(data1, data2) {
                this.jsonRandomQuestionsTitles = data1;
                this.jsonRandomQuestionsAnswers = data2;
                this.quizFormVisibility = true;

                window.questions = this.jsonRandomQuestionsTitles;
                window.answers = this.jsonRandomQuestionsAnswers;
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
            },
            addNewPlayer(e) {

                this.attemptSubmit = true;
                if (this.missingPlayerName || this.missingPlayerSurname) {
                    e.preventDefault();
                } else {
                    e.preventDefault();
                    let name = this.name_player;
                    let surname = this.surname_player;

                    $.ajax({
                        url: ajaxurl,
                        // id: 3,
                        type: "POST",
                        data: {"action": "addToUserTable", "name":name, "surname":surname},
                        success:function(data) {
                            var str= '';
                            document.getElementById('enter-name-form').innerHTML = "Vaše meno bolo pridané do tabuľky najlepších hráčov. Veľa šťastia pri ďaľšej hre";
                            document.getElementById('enter-name-form').innerHTML += " <a href='/'>Domov</a>"
                        }
                    });

                    return false;
                }
            }
        }
    }

</script>
<template>
    <div class="container mt-4">
        <template v-if="!(hideTicTacToeGame)">
            <div class="game-tic-tac-toe-info text-center p-2 mb-4" style="background-color: #eee;">
                <h3>Pravidlá</h3>
                <p>
                    Dvaja hráčí: <strong>X</strong> a <strong>O</strong>
                    <br>
                    Každý z dvojice sa dopredu musí rozhodnúť, za aký symbol bude hrať.
                    <br>
                    Poradie začínajúceho hráča je náhodne(napr. v prvom kole ide prvý <strong>X</strong>, v ďaľšom kole <strong>O</strong>)
                </p>
            </div>
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
                <button type="button" class="btn btn-primary" @click.prevent="activeGame.resetValues()">Ešte raz</button>
            </div>
        </template>
        <template v-else>
            <p>Hráč <strong>{{ activeGame.winnerPlayer }}</strong> vyhral nad súperom <strong>{{ activeGame.looserPlayer }}</strong></p>
           <TestComponent/>
        </template>
    </div>
</template>
