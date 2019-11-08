<template>
    <!-- Root element of PhotoSwipe. Must have class pswp. -->
    <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

        <!-- Background of PhotoSwipe.
            It's a separate element as animating opacity is faster than rgba(). -->
        <div class="pswp__bg"></div>

        <!-- Slides wrapper with overflow:hidden. -->
        <div class="pswp__scroll-wrap">

            <!-- Container that holds slides.
                PhotoSwipe keeps only 3 of them in the DOM to save memory.
                Don't modify these 3 pswp__item elements, data is added later on. -->
            <div class="pswp__container">
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
            </div>

            <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
            <div class="pswp__ui pswp__ui--hidden">

                <div class="pswp__top-bar">

                    <!--  Controls are self-explanatory. Order can be changed. -->

                    <div class="pswp__counter"></div>

                    <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

                    <button class="pswp__button pswp__button--share" title="Share"></button>

                    <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

                    <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

                    <!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
                    <!-- element will get class pswp__preloader--active when preloader is running -->
                    <div class="pswp__preloader">
                        <div class="pswp__preloader__icn">
                            <div class="pswp__preloader__cut">
                                <div class="pswp__preloader__donut"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                    <div class="pswp__share-tooltip"></div>
                </div>

                <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
                </button>

                <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
                </button>

                <div class="pswp__caption">
                    <div class="pswp__caption__center"></div>
                </div>

            </div>

        </div>

    </div>
</template>

<script>
    import PhotoSwipe from 'photoswipe/dist/photoswipe';
    import PhotoSwipeDefaultUI from 'photoswipe/dist/photoswipe-ui-default';

    export default {
        data() {
            return {
                photoswipe: null
            }
        },
        methods: {
            onBeforeChange() {
                const currItem = this.photoswipe.currItem.container;
                let videoElements = Array.prototype.slice.call(this.photoswipe.container.querySelectorAll('.pswp__video'), 0);
                let currItemIframe = currItem.querySelector('.pswp__video');

                if (videoElements) {
                    Array.from(videoElements).forEach((elem) => {
                        elem.classList.remove('active');
                    });
                }

                if (currItemIframe) {
                    currItemIframe.classList.add('active');
                }

                if (videoElements) {
                    Array.from(videoElements).forEach((elem) => {
                        let dataSrc = elem.getAttribute('data-src');

                        if (!dataSrc) {
                            dataSrc = elem.getAttribute('src');
                            elem.setAttribute('data-src', dataSrc);
                        }

                        if (elem.classList.contains('active')) {
                            elem.setAttribute('src', `${dataSrc}&autoplay=1`);
                        } else {
                            elem.setAttribute('src', `${dataSrc}`);
                        }
                    });
                }
            },
            onClose() {
                let videoElements = Array.prototype.slice.call(this.photoswipe.container.querySelectorAll('.pswp__video'), 0);

                if (videoElements) {
                    Array.from(videoElements).forEach((elem) => {
                        elem.setAttribute('src', elem.getAttribute('data-src'));
                    });
                }
            },
            open(event, index) {
                let items = [];
                let itemsLinks = Array.prototype.slice.call(document.querySelectorAll(`.photoswipe[data-photoswipe="${event.currentTarget.dataset.photoswipe}"]`), 0);

                itemsLinks.forEach((element) => {
                    let [w, h] = element.getAttribute('data-size').split("x");
                    let item = {
                        title: element.getAttribute('data-photoswipe-title'),
                        w,
                        h
                    };

                    if (element.getAttribute('data-video')) {
                        item.html = element.getAttribute('data-video');
                    } else {
                        item.src = element.getAttribute('href');
                    }

                    items.push(item);
                });

                this.photoswipe = new PhotoSwipe(this.$el, PhotoSwipeDefaultUI, items, {
                    bgOpacity: 0.9,
                    clickToCloseNonZoomable: true,
                    closeOnScroll: false,
                    fullscreenEl: false,
                    history: false,
                    index: index,
                    preloaderEl: true,
                    shareEl: false,
                    tapToClose: true,
                    showHideOpacity: true,
                    timeToIdle: 10000
                });
                this.photoswipe.listen('beforeChange', this.onBeforeChange);
                this.photoswipe.init();
                this.photoswipe.listen('close', this.onClose);
            },

            close() {
                this.photoswipe.close();
            }
        }
    }
</script>
