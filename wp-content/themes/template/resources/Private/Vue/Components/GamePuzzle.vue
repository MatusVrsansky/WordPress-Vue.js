<template>
    <div class="container mt-4">
        <template v-if="showCategorySlider === false">
            <div class="game-tic-tac-toe-info text-center p-2 mb-4" style="background-color: #eee;" id="gameRules">
                <h3>Pravidlá</h3>
                <p>Usporiadať čísla podľa správneho poradia v čo najkratšom čase.<br>
                Tabuľka pod číslami zobrazuje ich aktuálne správne poradie.</p>
            </div>
            <div id='playBoard'>
                <div class="playBoard-content">
                    <div id ='grid' class="mb-2">
                        <div v-for="index in 9" :key="index" class="box" :id="'s' + index" @click="checkRule('s'+index)">
                            <div class="box-number">{{index}}</div>
                        </div>
                    </div>
                    <div id='controlPanel'>
                        <div class="row">
                            <div class="col p-0 pr-md-2-half">
                                <div id='movesInc' class="mb-1 mt-3">posuny: 0</div>
                                <div id="puzzleTime" class="">Čas: {{time}}</div>
                                <div id='msg' style="display: none"></div>
                            </div>
                            <div class="col">
                                <div class="row">
                                    <div class="col pr-0 pr-sm-4">
                                        <table id='solution'>
                                            <tr><th id='ss1'>1</th><th id='ss2'>2</th><th id='ss3'>3</th></tr>
                                            <tr><th id='ss4'>4</th><th id='ss5'>5</th><th id='ss6'>6</th></tr>
                                            <tr><th id='ss7'>7</th><th id='ss8'>8</th><th id='ss9'>-</th></tr>
                                        </table>
                                    </div>
                                    <div class="">
                                        <button class="button-animated" id="button-shuffle" @click="shuffleBoard">
                                            <div id="spin"></div>
                                            <a href="#playBoard">Let's Go!</a>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template v-else>
            <div class="container mt-4 p-0">
                <h4 class="pl-2 text-center">Gratulujeme, podarilo sa Vám vyriešiť skladačku v čase: <strong>{{time}}</strong></h4>
                <quiz-component :table="'top_players_puzzle_game'" :time="time"></quiz-component>
            </div>
        </template>
    </div>
</template>

<script>
    import {_} from 'vue-underscore';
    import moment from 'moment';
    import QuizComponent from "./test/TestComponent.vue";

    export default {
        data() {
            return {
                pageLoad: false,
                time: "00:00",
                started : false,
                startTime: 0,
                moves: 1,
                showCategorySlider: false
            }
        },
        components: {
            QuizComponent
        },
        methods: {
            startGame() {
                this.startTime = moment();

                this.timer = setInterval(() => {
                    this.time = moment(moment().diff(this.startTime)).format("mm:ss");
                }, 1000);
            },
            isValidPattern(pattern){
                let inversion = 0;
                for (let i=0; i<9;i++){
                    for (let j=i+1;j<9;j++){
                        if(pattern[i]!=='-' && pattern[j]!== '-' && parseInt(pattern[i]) > parseInt(pattern[j])){
                            inversion++;
                        }
                    }
                }
                if(inversion%2 === 0){
                    return true;
                } else {
                    return false;
                }
            },
             shuffleBoard() {
                document.querySelector('#button-shuffle a').innerText = 'Reset';
                let newBoardPattern = _.shuffle(['1', '2' , '3', '4', '5', '6', '7', '8', '-']);
                if(this.isValidPattern(newBoardPattern)){
                    //totalGames++;
                    let boxes = document.querySelectorAll('.box'), i;

                    for (i = 0; i < boxes.length; ++i) {
                      let id = '#'+boxes[i].id;

                      if(boxes[i].innerText === '-') {
                         document.getElementById(boxes[i].id).style.backgroundColor = '#4682b4';
                      }
                    }

                    document.querySelector('#s1 div').innerText = newBoardPattern[0];
                    document.querySelector('#s2 div').innerText = newBoardPattern[1];
                    document.querySelector('#s3 div').innerText = newBoardPattern[2];
                    document.querySelector('#s4 div').innerText = newBoardPattern[3];
                    document.querySelector('#s5 div').innerText = newBoardPattern[4];
                    document.querySelector('#s6 div').innerText = newBoardPattern[5];
                    document.querySelector('#s7 div').innerText = newBoardPattern[6];
                    document.querySelector('#s8 div').innerText = newBoardPattern[7];
                    document.querySelector('#s9 div').innerText = newBoardPattern[8];

                    for (i = 0; i < boxes.length; ++i) {
                        let id = '#'+boxes[i].id;
                        if(boxes[i].innerText === '-') {
                           document.getElementById(boxes[i].id).style.backgroundColor = '#add8e6';
                        }
                    }

                    this.checkGoal();
                    document.getElementById('msg').innerText = '';
                    document.getElementById("movesInc").innerText = 'posuny: 0';

                    this.moves = 1;
                } else {
                    this.shuffleBoard();
                }
        },
        checkGoal() {
            let count = 0, loop = 1;
            let boxes = document.querySelectorAll('.box'), i;
            for (i = 0; i < boxes.length; ++i) {
                let id = '#'+boxes[i].id, sid = '#s'+boxes[i].id;

                if (parseInt(boxes[i].innerText) === loop++){
                    boxes[i].style.color = '#7FFE01';
                    document.getElementById("s"+boxes[i].id).style.background = '#90EE90';
                    count++;
                } else {
                    boxes[i].style.color = '#fff';
                   document.getElementById("s"+boxes[i].id).style.background = '#fff';

                }
            }

            if (count === 8){
                return true;
            } else {
                return false;
            }
        },
        checkRule(ID){

            if(this.started === false) {
                this.started = true;
            }

            let sourceId = "#"+ID;
            let sourceText = document.querySelector(sourceId + " div").innerText;
            let targetText = '-';

            let boxes = document.querySelectorAll('.box'), i;
            for (i = 0; i < boxes.length; ++i) {
                let id = '#'+boxes[i].id;
                let text = document.querySelector(id + " div").innerText;

                if(text === '-') {
                    if(this.ruleEngine(sourceId, id)) {
                        this.refreshGrid(id, sourceText);
                        document.querySelector(id).style.backgroundColor = '#4682b4';
                        this.refreshGrid(sourceId, targetText);
                        document.querySelector(sourceId).style.backgroundColor = '#add8e6';

                        if(this.checkGoal()) {
                            // hide game HTML and show Category Slider
                            this.showCategorySlider = true;
                            clearInterval(this.timer);
                        }
                        document.getElementById('movesInc').innerText = 'posuny: '+this.moves++;

                        return false;
                    }
                }
            }
        },
        refreshGrid(target, text) {
            document.querySelector(target+" div").innerText = text;
        },
        ruleEngine(sourceId, targetId){
            switch(targetId){
            case '#s9':
                if (sourceId === '#s8' || sourceId === '#s6'){
                    return true;
                } else {
                    return false;
                }
            case '#s8':
                if (sourceId === '#s7' || sourceId === '#s9' || sourceId === '#s5'){
                    return true;
                } else {
                    return false;
                }
            case '#s7':
                if (sourceId === '#s8' || sourceId === '#s4'){
                    return true;
                } else {
                    return false;
                }
            case '#s6':
                if (sourceId === '#s3' || sourceId === '#s5' || sourceId === '#s9'){
                    return true;
                } else {
                    return false;
                }
            case '#s5':
                if (sourceId === '#s2' || sourceId === '#s4' || sourceId === '#s8' || sourceId === '#s6'){
                    return true;
                } else {
                    return false;
                }
            case '#s4':
                if (sourceId === '#s1' || sourceId === '#s7' || sourceId === '#s5'){
                    return true;
                } else {
                    return false;
                }
            case '#s3':
                if (sourceId === '#s2' || sourceId === '#s6'){
                    return true;
                } else {
                    return false;
                }
            case '#s2':
                if (sourceId === '#s1' || sourceId === '#s3' || sourceId === '#s5'){
                    return true;
                } else {
                    return false;
                }
            case '#s1':
                if (sourceId === '#s2' || sourceId === '#s4'){
                    return true;
                } else {
                    return false;
                }
        }
        }
    },
    watch: {
        started() {
            if(this.started === true) {
                this.startGame();
            }

            else {
                clearInterval(this.timer);
            }
        }
    },
        mounted() {
            // when page is loaded
            this.shuffleBoard();
            this.checkGoal();
        },

    }

</script>
