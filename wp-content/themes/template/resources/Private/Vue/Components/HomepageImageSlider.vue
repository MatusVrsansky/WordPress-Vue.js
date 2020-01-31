<template>
    <div class="container mt-4">
        <h1 class="text-center p-3">Zahrajte si a otestuje sa</h1>
        <div class="arrow bounce">
            <div class="fa fa-arrow-down fa-2x text-warning"></div>
        </div>
        <div class="animated-text-wrapper">
            <div class="slides">
                <transition-group name="slide" mode="out-in" enter-class="slide-in" leave-class="slide-out" enter-active-class="animated slide-in-active" leave-active-class="animated slide-out-active">
                    <div v-for="index in slides" v-if="index === active" :key="index" class="game-name">
                        <a :href="games[index-1].url" class="text-dark">
                            {{games[index-1].name}}
                        </a>
                    </div>
                </transition-group>
            </div>
            <span class="preview-arrow" @click="move(-1)">
            <i class="fa fa-chevron-left" aria-hidden="true"></i>
        </span>
            <span class="next-arrow" @click="move(1)">
            <i class="fa fa-chevron-right" aria-hidden="true"></i>
        </span>
            <ul class="dots p-0">
                <li v-for="(dot, index) in slides" :class="{ active: ++index === active }" @click="jump(index)"></li>
            </ul>
        </div>
    </div>
</template>

<script>

    export default {
        data() {
            return {
                slides: 3,
                active: 1,
                games: [
                    { name: 'Piškôrky', url: "/piskorky/"},
                    { name: 'Pexeso', url: "/pexeso/"},
                    { name: 'Skladačka', url: "/skladacka/"},
                ]
            }
        },
        methods: {
            move(amount) {
                let newActive;
                const newIndex = this.active + amount;
                if (newIndex > this.slides){
                    newActive = 1;
                }
                if (newIndex === 0) {
                    newActive = this.slides;
                }
                this.active = newActive || newIndex;
            },
            jump(index) {
                this.active = index;
            },
            addSlide() {
                this.slides = this.slides + 1;
            },
            removeSlide() {
                this.slides = this.slides - 1;
            }
        }

    }

</script>
