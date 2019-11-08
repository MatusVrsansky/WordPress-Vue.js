import Player from "./Player";

class HTMLPlayer extends Player {
    constructor(element) {
        super(element);

        this.media = this.element.querySelector('video');
        if (this.media) {
            this.player = this.media;
            this.onPlayerReady();
        }
    }
    play() {
        this.player.play();
    }
    stop() {
        this.player.pause();
    }
}

export default HTMLPlayer;
