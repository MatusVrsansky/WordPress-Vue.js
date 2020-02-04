<script>
    let quiz = new Quiz();

    export default {
        name: 'TestComponent',
        props: [
            'table',
            'time'
        ],
        data() {
            return {
                jsonAllCategories: window.categories,
                jsonRandomQuestionsTitles: {},
                jsonRandomQuestionsAnswers: {},
                slideIndex: 1,
                showQuizForm: false,
                quizFormVisibility: false,
                quiz: quiz,
                name_player: '',
                surname_player: '',
                attemptSubmit: false,
            }
        },
        computed: {
            missingPlayerName: function () {
                return this.name_player === '';
            },
            missingPlayerSurname: function () {
                return this.surname_player === '';
            }
        },
        methods: {
            plusSlides: function(n) {
                this.showSlides(this.slideIndex += n);
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
                    dots[i].className = dots[i].className.replace(" current-slide-category", "");
                }
                slides[this.slideIndex-1].style.display = "flex";
                dots[this.slideIndex-1].className += " current-slide-category";
            },
            addNewPlayer(e) {

                this.attemptSubmit = true;
                if (this.missingPlayerName || this.missingPlayerSurname) {
                    e.preventDefault();
                } else {
                    e.preventDefault();
                    let name = this.name_player;
                    let surname = this.surname_player;

                    // let table = '';
                    // console.log(window.location.pathname);
                    // switch ( window.location.pathname) {
                    //     case '/piskorky/': table = 'top_players_tic_tac_toe';break;
                    //     case '/pexeso/': table = 'top_players_memory_game'; break;
                    //     default: break;
                    // }
                    // let table = document.getElementById('tableName').value;
                    let table = this.table;
                    let finalTime = this.time;

                    let good_answers = document.getElementById('count_right_answers').innerText;
                    let bad_answers = document.getElementById('count_incorrect_answers').innerText;

                    $.ajax({
                        url: ajaxurl,
                        // id: 3,
                        type: "POST",
                        data: {"action": "addToUserTable", "name":name, "surname":surname,"good_answers":good_answers,
                            "bad_answers":bad_answers, "table":table, "finalTime": finalTime},
                        success:function(data) {
                            var str= '';
                            document.getElementById('enter-name-form').innerHTML = "Vaše meno bolo pridané do tabuľky najlepších hráčov. Veľa šťastia pri ďaľšej hre";
                            document.getElementById('enter-name-form').innerHTML += " <a href='/'>Domov</a>"
                        }
                    });

                    return false;
                }
            },
            setValues: function(data1, data2) {
                this.jsonRandomQuestionsTitles = data1;
                this.jsonRandomQuestionsAnswers = data2;
                this.quizFormVisibility = true;

                window.questions = this.jsonRandomQuestionsTitles;
                window.answers = this.jsonRandomQuestionsAnswers;
            },
            setRandomQuizQuestionsCategory: function(event) {
                let self = this;

                event.preventDefault();
                let clickedId = event.target;

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
        }
    }

</script>

<template>
    <div class="container mt-3 mt-sm-4 p-0">
        <template v-if="quizFormVisibility===false">
            <p class="mb-3 pl-2 text-center">Vyberte si, z ktorej kategórie chcete odpovedať na otázky</p>
            <div class="slideshow-container">
                <div class="mySlides text-center" v-for="(category, index) in jsonAllCategories" v-bind:style="index === 0 ? 'display: flex' : ''">
<!--                    <div class="numbertext">1 / 3</div>-->
                    <div class="category"  @click="setRandomQuizQuestionsCategory">
                        <h2>{{category.name}}</h2>
                    </div>
<!--                    <button type="button" class="btn btn-info btn-lg slider-category category-name" @click="setRandomQuizQuestionsCategory">{{category.name}}</button>-->
                </div>
                <a class="prev" @click="plusSlides(-1)">&#10094;</a>
                <a class="next" @click="plusSlides(1)">&#10095;</a>
            </div>
            <br>
            <div style="text-align:center">
                <span v-for="(category,index) in jsonAllCategories" class="dot" v-bind:class="index === 0? 'current-slide-category' : ''"></span>
            </div>
        </template>
        <template v-else>

                <div class="modal-dialog">
                    <div class="modal-content">
                        <div id="enter-name-form" class="p-4" style="display: none">
                            <form method="POST" @submit="addNewPlayer">
                                <div class="form-group">
                                    <label class="mb-2">Zadajte svoje meno</label>
                                    <input id="name" class="form-control form-control-warning" placeholder="Vaše meno" v-bind:class="{ 'has-warning': attemptSubmit && missingPlayerName }"  type="text" v-model="name_player">
                                    <div class="form-control-feedback" v-if="attemptSubmit && missingPlayerName">Vyplňte prosím toto políčko</div>
                                    <label class="mb-2">Zadajte svoje priezvisko</label>
                                    <input id="surname" class="form-control form-control-warning" placeholder="Vaše priezvisko" v-bind:class="{ 'has-warning': attemptSubmit && missingPlayerSurname }"  type="text" v-model="surname_player">
                                    <div class="form-control-feedback" v-if="attemptSubmit && missingPlayerSurname">Vyplňte prosím toto políčko</div>
                                    <!--                        <input type="text" class="form-control" id="name" aria-describedby="emailHelp" placeholder="Vaše meno" required>-->
                                </div>
                                <button type="submit" class="btn btn-primary">Odoslať</button>
                                <small id="emailHelp" class="form-text text-muted">Vaše meno a priezvisko sa zapíše do tabuľky najlepších hráčov</small>
                            </form>
                        </div>
                        <div id="quiz-content">
                            <input type="hidden" name="question_index" value="0">
                            <input type="hidden" name="questions_array" value="0">
                            <!-- modal-header class before -->
                            <div class="quiz-window">
                                <h3 id="question-title"><span class="label label-warning" id="qid"></span>
                                    {{ jsonRandomQuestionsTitles[0].post_title }}
                                </h3>
                                <hr>
                                <div>
                                    <h4>Počet správnych odpovedí:<span id="count_right_answers" class="ml-2">0</span></h4>
                                    <h4>Počet nesprávnych odpovedí:<span id="count_incorrect_answers" class="ml-2">0</span></h4>
                                </div>
                            </div>
                            <div class="quiz-body">
                                <div class="col-xs-3 col-xs-offset-5">
                                </div>
                                <div class="quiz" id="quiz"  @click.prevent="quiz.test">
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
                                    <div v-if="quiz.arrowVisibility === true" @click.prevent="quiz.setNextQuestion()" class="ml-auto p-2"><span id="arrow-next">&#8594;</span></div>
                                    <button v-if="quiz.formClicked === true && quiz.questionIndex === 4" @click.prevent="quiz.showNameForm()" class="btn btn-primary ml-auto" type="button">Skončiť test</button>
                                </div>
                            </div>
                            <div class="modal-footer text-muted">
                                <span id="answer"></span>
                            </div>
                        </div>
                    </div>
                </div>
        </template>
    </div><!-- /container -->
</template>
