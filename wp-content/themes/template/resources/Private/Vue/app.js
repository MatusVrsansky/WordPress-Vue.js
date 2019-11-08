import "../Sass/style.scss";
import "../Sass/rte.scss";

import Vue from 'vue';
import {
    Collapse as ElCollapse,
    CollapseItem as ElCollapseItem,
} from "element-ui";
import {swiper, swiperSlide} from 'vue-awesome-swiper';

import VideoPlayer from './Directives/VideoPlayer/index';

import PhotoSwipe from './Mixins/PhotoSwipe';

import AjaxForm from "./Components/AjaxForm.vue";
import AjaxList from "./Components/AjaxList.vue";
import CookiesInfoBox from "./Components/CookiesInfoBox.vue";

const MOUSE_MIDDLE_BUTTON = 2,
    PREVENT_LOAD_LINK_CLASSES = ["[href^=mailto]", "[href^=tel]", "[href^=javascript]", "[href^=\\#]", "[target^=_blank]", ".photoswipe", "#scroll-to-top", ".download", ".ajax", "[href*=tx_typoscriptrendering]", "[href*=ajax]"];

Vue.component('ElCollapse', ElCollapse); //Accordion
Vue.component('ElCollapseItem', ElCollapseItem); //Accordion item
Vue.component('AjaxForm', AjaxForm);
Vue.component('AjaxList', AjaxList);

Vue.directive('video-player', VideoPlayer);

Vue.use(PhotoSwipe);

let activeGame = new Game();

// new Vue({
//     el: "#game-view",
//     data: activeGame
// });


new Vue({
    el: '#page',
    data: {
        preventUnloading: false,
        time: 1500,
        myData : activeGame
    },
    delimiters: ['<%', '%>'],
    components: {
        swiper,
        swiperSlide,
        CookiesInfoBox
    },
    methods: {
        load() {
            document.body.classList.add("loaded");
        },
        unload() {
            document.body.classList.remove("loaded");
        },
        getScrollOffset() {
            let offset = 0;

            if (this.$refs.header) {
                offset -= this.$refs.header.offsetHeight;
            }

            return offset;
        },

        initUnload() {
            let linksSelector = "a";

            PREVENT_LOAD_LINK_CLASSES.forEach((className) => {
                linksSelector += `:not(${className})`;
            });

            document.querySelectorAll(linksSelector).forEach((element) => {
                element.addEventListener("click", (e) => {
                    const isCommandPressed = e.metaKey,
                        isCtrlPressed = e.ctrlKey,
                        isShiftPressed = e.shiftKey;


                    if (isCtrlPressed === true ||
                        isShiftPressed === true ||
                        isCommandPressed === true ||
                        e.which === MOUSE_MIDDLE_BUTTON) {

                        return true;
                    }

                    if (e.currentTarget.pathname === window.location.pathname) {
                        return true;
                    }

                    if (e.currentTarget.getAttribute("id") === "history-back") {
                        e.preventDefault();
                        if (window.history.length > 1) {
                            window.history.back();
                        }

                        return false;
                    }

                    if (this.preventUnloading) {
                        e.preventDefault();
                        this.preventUnloading = false;

                        return false;
                    } else {
                        this.unload();
                    }

                    return true;
                });
            });
        },
        scrollDown() {
            function scrollToSmoothly(pos, time){
                /*Time is only applicable for scrolling upwards*/
                /*Code written by hev1*/
                /*pos is the y-position to scroll to (in pixels)*/
                if(isNaN(pos)){
                    throw "Position must be a number";
                }
                if(pos<0){
                    throw "Position can not be negative";
                }
                var currentPos = window.scrollY||window.screenTop;
                if(currentPos<pos){
                    if(time){
                        var x;
                        var i = currentPos;
                        x = setInterval(function(){
                            window.scrollTo(0, i);
                            i += 10;
                            if(i>=pos){
                                clearInterval(x);
                            }
                        }, time);
                    } else {
                        var t = 10;
                        for(let i = currentPos; i <= pos; i+=10){
                            t+=10;
                            setTimeout(function(){
                                window.scrollTo(0, i);
                            }, t/2);
                        }
                    }
                } else {
                    time = time || 2;

                    console.log(time);


                    var i = currentPos;
                    var x;
                    x = setInterval(function(){
                        window.scrollTo(0, i);
                        i -= 12;
                        if(i<=pos){
                            clearInterval(x);
                        }
                    }, time);


                }
            }
            let up = document.getElementsByClassName('header');
            scrollToSmoothly(up[0].offsetHeight - 100);

        }
    },
    beforeCreate() {
        window.addEventListener('load', () => {
            this.load();
            this.initUnload();
        });
    }
});

window.addEventListener("unload", () => {
    // back button fix
});

// back button fix - ios
window.onpageshow = (event) => {
    if (event.persisted) {
        window.location.reload();
    }
};
