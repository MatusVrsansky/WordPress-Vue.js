import Player from "./Player";

class VimeoPlayer extends Player {
    constructor(element) {
        super(element);

        this.media = this.element.querySelector('iframe');

        if (this.media) {
            this.importScript(() => {
                this.player = new Vimeo.Player(this.media);
                this.onPlayerReady();
            });
        }
    }
    importScript(onload) {
        if (!document.scripts.namedItem("script-vimeo-player")) {
            super.importScript("https://player.vimeo.com/api/player.js", "script-vimeo-player", onload);
        } else {
            if (onload) {
                onload();
            }
        }
    }
    play() {
        this.player.play();
    }
    stop() {
        this.player.pause();
    }
}

export default VimeoPlayer;
