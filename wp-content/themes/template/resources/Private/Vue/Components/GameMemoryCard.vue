
<template>
    <div class="container">
        <section class="memory-game">
            <div class="memory-card" data-framework="react" style="order: 11;">
                <img class="front-face" src="/wp-content/themes/template/resources/images/react.svg" alt="React">
                <img class="back-face" src="/wp-content/themes/template/resources/images/js-badge.svg" alt="Memory Card">
            </div>
            <div class="memory-card" data-framework="react" style="order: 1;">
                <img class="front-face" src="/wp-content/themes/template/resources/images/react.svg" alt="React">
                <img class="back-face" src="/wp-content/themes/template/resources/images/js-badge.svg" alt="Memory Card">
            </div>

            <div class="memory-card" data-framework="angular" style="order: 1;">
                <img class="front-face" src="/wp-content/themes/template/resources/images/angular.svg" alt="Angular">
                <img class="back-face" src="/wp-content/themes/template/resources/images/js-badge.svg" alt="Memory Card">
            </div>

            <div class="memory-card" data-framework="angular" style="order: 12;">
                <img class="front-face" src="/wp-content/themes/template/resources/images/angular.svg" alt="Angular">
                <img class="back-face" src="/wp-content/themes/template/resources/images/js-badge.svg" alt="Memory Card">
            </div>

            <div class="memory-card" data-framework="ember" style="order: 10;">
                <img class="front-face" src="/wp-content/themes/template/resources/images/ember.svg" alt="Ember">
                <img class="back-face" src="/wp-content/themes/template/resources/images/js-badge.svg" alt="Memory Card">
            </div>

            <div class="memory-card" data-framework="ember" style="order: 3;">
                <img class="front-face" src="/wp-content/themes/template/resources/images/ember.svg" alt="Ember">
                <img class="back-face" src="/wp-content/themes/template/resources/images/js-badge.svg" alt="Memory Card">
            </div>

            <div class="memory-card" data-framework="vue" style="order: 10;">
                <img class="front-face" src="/wp-content/themes/template/resources/images/vue.svg" alt="Vue">
                <img class="back-face" src="/wp-content/themes/template/resources/images/js-badge.svg" alt="Memory Card">
            </div>

            <div class="memory-card" data-framework="vue" style="order: 1;">
                <img class="front-face" src="/wp-content/themes/template/resources/images/vue.svg" alt="Vue">
                <img class="back-face" src="/wp-content/themes/template/resources/images/js-badge.svg" alt="Memory Card">
            </div>

            <div class="memory-card" data-framework="backbone" style="order: 2;">
                <img class="front-face" src="/wp-content/themes/template/resources/images/backbone.svg" alt="Backbone">
                <img class="back-face" src="/wp-content/themes/template/resources/images/js-badge.svg" alt="Memory Card">
            </div>

            <div class="memory-card" data-framework="backbone" style="order: 6;">
                <img class="front-face" src="/wp-content/themes/template/resources/images/backbone.svg" alt="Backbone">
                <img class="back-face" src="/wp-content/themes/template/resources/images/js-badge.svg" alt="Memory Card">
            </div>

            <div class="memory-card" data-framework="aurelia" style="order: 2;">
                <img class="front-face" src="/wp-content/themes/template/resources/images/aurelia.svg" alt="Aurelia">
                <img class="back-face" src="/wp-content/themes/template/resources/images/js-badge.svg" alt="Memory Card">
            </div>

            <div class="memory-card" data-framework="aurelia" style="order: 9;">
                <img class="front-face" src="/wp-content/themes/template/resources/images/aurelia.svg" alt="Aurelia">
                <img class="back-face" src="/wp-content/themes/template/resources/images/js-badge.svg" alt="Memory Card">
            </div>
        </section>
    </div>
</template>

<script>
    const cards = document.querySelectorAll('.memory-card');

    let hasFlippedCard = false;
    let firstCard, secondCard;

    function flipCard() {
        this.classList.add('flip');

        if(!hasFlippedCard) {
            hasFlippedCard = true;
            firstCard = this;
        } else {
            hasFlippedCard = false;
            secondCard = this;

            if(firstCard.dataset.framework === secondCard.dataset.framework) {
                firstCard.removeEventListener('click', flipCard);
                secondCard.removeEventListener('click', flipCard);
            } else {
                setTimeout(() => {
                    firstCard.classList.remove('flip');
                    secondCard.classList.remove('flip');
                }, 1500);
            }
        }
    }

    // export default {
    //     data() {
    //         return {
    //
    //         }
    //     },
    //     methods: {
    //         test(e) {
    //            e.target.classList.add('flip');
    //
    //            // console.log("This: "+e.);
    //         }
    //     },
    // }

</script>

