


<template>
    <div class="container mt-4">
        <section class="memory-game">
            <div class="memory-card" data-framework="react" style="order: 11;" @click.prevent="memoryCard.flipCard">
                <img class="front-face" src="/wp-content/themes/template/resources/images/react.svg" alt="React">
                <img class="back-face" src="/wp-content/themes/template/resources/images/card.png" alt="Memory Card">
            </div>
            <div class="memory-card" data-framework="react" style="order: 1;" @click.prevent="memoryCard.flipCard">
                <img class="front-face" src="/wp-content/themes/template/resources/images/react.svg" alt="React">
                <img class="back-face" src="/wp-content/themes/template/resources/images/card.png" alt="Memory Card">
            </div>

            <div class="memory-card" data-framework="angular" style="order: 1;" @click.prevent="memoryCard.flipCard">
                <img class="front-face" src="/wp-content/themes/template/resources/images/angular.svg" alt="Angular">
                <img class="back-face" src="/wp-content/themes/template/resources/images/card.png" alt="Memory Card">
            </div>

            <div class="memory-card" data-framework="angular" style="order: 12;" @click.prevent="memoryCard.flipCard">
                <img class="front-face" src="/wp-content/themes/template/resources/images/angular.svg" alt="Angular">
                <img class="back-face" src="/wp-content/themes/template/resources/images/card.png" alt="Memory Card">
            </div>

            <div class="memory-card" data-framework="ember" style="order: 10;" @click.prevent="memoryCard.flipCard">
                <img class="front-face" src="/wp-content/themes/template/resources/images/ember.svg" alt="Ember">
                <img class="back-face" src="/wp-content/themes/template/resources/images/card.png" alt="Memory Card">
            </div>

            <div class="memory-card" data-framework="ember" style="order: 3;" @click.prevent="memoryCard.flipCard">
                <img class="front-face" src="/wp-content/themes/template/resources/images/ember.svg" alt="Ember">
                <img class="back-face" src="/wp-content/themes/template/resources/images/card.png" alt="Memory Card">
            </div>

            <div class="memory-card" data-framework="vue" style="order: 10;" @click.prevent="memoryCard.flipCard">
                <img class="front-face" src="/wp-content/themes/template/resources/images/vue.svg" alt="Vue">
                <img class="back-face" src="/wp-content/themes/template/resources/images/card.png" alt="Memory Card">
            </div>

            <div class="memory-card" data-framework="vue" style="order: 1;" @click.prevent="memoryCard.flipCard">
                <img class="front-face" src="/wp-content/themes/template/resources/images/vue.svg" alt="Vue">
                <img class="back-face" src="/wp-content/themes/template/resources/images/card.png" alt="Memory Card">
            </div>

            <div class="memory-card" data-framework="backbone" style="order: 2;" @click.prevent="memoryCard.flipCard">
                <img class="front-face" src="/wp-content/themes/template/resources/images/backbone.svg" alt="Backbone">
                <img class="back-face" src="/wp-content/themes/template/resources/images/card.png" alt="Memory Card">
            </div>

            <div class="memory-card" data-framework="backbone" style="order: 6;" @click.prevent="memoryCard.flipCard">
                <img class="front-face" src="/wp-content/themes/template/resources/images/backbone.svg" alt="Backbone">
                <img class="back-face" src="/wp-content/themes/template/resources/images/card.png" alt="Memory Card">
            </div>

            <div class="memory-card" data-framework="aurelia" style="order: 2;" @click.prevent="memoryCard.flipCard">
                <img class="front-face" src="/wp-content/themes/template/resources/images/aurelia.svg" alt="Aurelia">
                <img class="back-face" src="/wp-content/themes/template/resources/images/card" alt="Memory Card">
            </div>

            <div class="memory-card" data-framework="aurelia" style="order: 9;" @click.prevent="memoryCard.flipCard">
                <img class="front-face" src="/wp-content/themes/template/resources/images/aurelia.svg" alt="Aurelia">
                <img class="back-face" src="/wp-content/themes/template/resources/images/card.png" alt="Memory Card">
            </div>
        </section>
    </div>
</template>

<script>

    let memoryCard = new MemoryCard();
    export default {
        data() {
            return {
                memoryCard: memoryCard
            }
        },
        methods: {
            getAllCards() {
                let randomPos = '';
                document.querySelectorAll('.memory-card').forEach((element) => {
                    randomPos = Math.floor(Math.random() * 12);
                    element.style.order = randomPos;
                })
            },
        },
        mounted() {
            this.getAllCards();
        }
    }

</script>
