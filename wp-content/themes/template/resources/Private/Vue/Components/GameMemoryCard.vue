<script>
    import moment from 'moment';
    import TestComponent from "./test/TestComponent.vue";

    window.table = 'Table Memory Card';

    export default {
        data() {
            return {
                time: "00:00",
                started : false,
                stopTimer: false,
                startTime: 0,
                hasFlippedCard: false,
                lockBoard : false,
                firstCard : '',
                secondCard : '',
                showCategorySlider: false
            }
        },

        components: {
            TestComponent
        },
        props: ['title'],

        methods: {
            // test() {
            //     this.started = false;
            // },
            flipCard(event) {

                let e = event.path[1];

                if(this.lockBoard) {
                    return;
                }

                if(e === this.firstCard) {
                    return;
                }

                if(this.started === false) {
                    this.started = true;
                }

                e.classList.add('disable-click');
                e.classList.add('flip');

                // find out if all cards are showed
                this.checkAllCardsInverted();

                if(!this.hasFlippedCard) {
                    this.hasFlippedCard = true;
                    this.firstCard = e;

                    return;
                }

                // second click
                this.secondCard = e;
                this.checkForMatch();
            },
            checkAllCardsInverted() {
                let allTurned = true;

                document.querySelectorAll('.memory-card').forEach((element) => {
                    if(!(element.classList.contains('flip'))) {
                        allTurned = false;
                    }
                });

                if(allTurned) {
                    this.showCategorySlider = true;
                    // stop timer
                    clearInterval(this.timer);
                }
            },
            checkForMatch() {
                let isMatch = this.firstCard.dataset.framework === this.secondCard.dataset.framework;
                isMatch ? this.disableCards() : this.unflipCards();
            },
            disableCards() {
                this.firstCard.removeEventListener('click', this.flipCard);
                this.secondCard.removeEventListener('click', this.flipCard);

                this.resetBoard();
            },
            unflipCards() {
                this.lockBoard = true;

                // not a match
                setTimeout(() => {
                    this.firstCard.classList.remove('flip');
                    this.secondCard.classList.remove('flip');

                    this.firstCard.classList.remove('disable-click');
                    this.secondCard.classList.remove('disable-click');

                    this.resetBoard();
                }, 1500);
            },
            resetBoard() {
                this.hasFlippedCard = false;
                this.lockBoard = false;

                this.firstCard = null;
                this.secondCard = null;
            },
            setCardsRandomPosition() {
                let randomPos = '';
                document.querySelectorAll('.memory-card').forEach((element) => {
                    randomPos = Math.floor(Math.random() * 12);
                    element.style.order = randomPos;
                })
            },
            startGame() {
                this.startTime = moment();

                this.timer = setInterval(() => {
                    this.time = moment(moment().diff(this.startTime)).format("mm:ss");
                }, 1000);
            },
        },
        mounted() {
            this.setCardsRandomPosition();
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
        }
    }
</script>

<template>
    <div class="container">
        <template v-if="showCategorySlider === false">
            <div class="container mt-4">
                <div class="game-tic-tac-toe-info text-center p-2 mb-4" style="background-color: #eee;">
                    <h3>Pravidlá</h3>
                    <p>Nájsť dvojice kariet toho istého typu v čo najkratšom čase<br>Čas sa spustí po otočení prvej karty</p>
                </div>
                <p>Dľžka hry:{{time}}</p>
                <section class="memory-game">
                    <div class="memory-card" data-framework="react" style="order: 11;" @click.prevent="flipCard">
                        <img class="front-face" src="/wp-content/themes/template/resources/images/react.svg" alt="React">
                        <img class="back-face" src="/wp-content/themes/template/resources/images/card.png" alt="Memory Card">
                    </div>
                    <div class="memory-card" data-framework="react" style="order: 1;" @click.prevent="flipCard">
                        <img class="front-face" src="/wp-content/themes/template/resources/images/react.svg" alt="React">
                        <img class="back-face" src="/wp-content/themes/template/resources/images/card.png" alt="Memory Card">
                    </div>

                    <div class="memory-card" data-framework="angular" style="order: 1;" @click.prevent="flipCard">
                        <img class="front-face" src="/wp-content/themes/template/resources/images/angular.svg" alt="Angular">
                        <img class="back-face" src="/wp-content/themes/template/resources/images/card.png" alt="Memory Card">
                    </div>

                    <div class="memory-card" data-framework="angular" style="order: 12;" @click.prevent="flipCard">
                        <img class="front-face" src="/wp-content/themes/template/resources/images/angular.svg" alt="Angular">
                        <img class="back-face" src="/wp-content/themes/template/resources/images/card.png" alt="Memory Card">
                    </div>

                    <div class="memory-card" data-framework="ember" style="order: 10;" @click.prevent="flipCard">
                        <img class="front-face" src="/wp-content/themes/template/resources/images/ember.svg" alt="Ember">
                        <img class="back-face" src="/wp-content/themes/template/resources/images/card.png" alt="Memory Card">
                    </div>

                    <div class="memory-card" data-framework="ember" style="order: 3;" @click.prevent="flipCard">
                        <img class="front-face" src="/wp-content/themes/template/resources/images/ember.svg" alt="Ember">
                        <img class="back-face" src="/wp-content/themes/template/resources/images/card.png" alt="Memory Card">
                    </div>

                    <div class="memory-card" data-framework="vue" style="order: 10;" @click.prevent="flipCard">
                        <img class="front-face" src="/wp-content/themes/template/resources/images/vue.svg" alt="Vue">
                        <img class="back-face" src="/wp-content/themes/template/resources/images/card.png" alt="Memory Card">
                    </div>

                    <div class="memory-card" data-framework="vue" style="order: 1;" @click.prevent="flipCard">
                        <img class="front-face" src="/wp-content/themes/template/resources/images/vue.svg" alt="Vue">
                        <img class="back-face" src="/wp-content/themes/template/resources/images/card.png" alt="Memory Card">
                    </div>

                    <div class="memory-card" data-framework="backbone" style="order: 2;" @click.prevent="flipCard">
                        <img class="front-face" src="/wp-content/themes/template/resources/images/backbone.svg" alt="Backbone">
                        <img class="back-face" src="/wp-content/themes/template/resources/images/card.png" alt="Memory Card">
                    </div>

                    <div class="memory-card" data-framework="backbone" style="order: 6;" @click.prevent="flipCard">
                        <img class="front-face" src="/wp-content/themes/template/resources/images/backbone.svg" alt="Backbone">
                        <img class="back-face" src="/wp-content/themes/template/resources/images/card.png" alt="Memory Card">
                    </div>

                    <div class="memory-card" data-framework="aurelia" style="order: 2;" @click.prevent="flipCard">
                        <img class="front-face" src="/wp-content/themes/template/resources/images/aurelia.svg" alt="Aurelia">
                        <img class="back-face" src="/wp-content/themes/template/resources/images/card" alt="Memory Card">
                    </div>

                    <div class="memory-card" data-framework="aurelia" style="order: 9;" @click.prevent="flipCard">
                        <img class="front-face" src="/wp-content/themes/template/resources/images/aurelia.svg" alt="Aurelia">
                        <img class="back-face" src="/wp-content/themes/template/resources/images/card.png" alt="Memory Card">
                    </div>
                </section>
                <!--        <button type="button" @click="test">Test</button>-->
            </div>
        </template>

        <template v-else>
            <div class="container mt-4">
                <p>Gratulujeme, podarilo sa Vám nájsť všetky dvojice kartiet v čase: <strong>{{time}}</strong></p>
                <test-component title="test table name"></test-component>
            </div>
        </template>
    </div>
</template>
