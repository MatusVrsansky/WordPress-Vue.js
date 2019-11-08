import Player from "./Player";

class YoutubePlayer extends Player {
    constructor(element) {
        super(element);

        this.events.scriptLoaded = new CustomEvent('videoPlayer.youtubeScriptLoaded');
        this.media = this.element.querySelector('iframe');

        if (this.media) {
            this.importScript(() => {
                this.player = new YT.Player(this.media,{
                    events: {
                        'onReady': () => {
                            this.onPlayerReady();
                        },
                        'onStateChange': () => {
                            this.onPlayerStateChange();
                        }
                    }
                });
            });
        }
    }
    importScript(onload) {
        if (!document.scripts.namedItem("script-youtube-player")) {
            window.onYouTubeIframeAPIReady = () => {
                document.scripts["script-youtube-player"].dataset.loaded = true;
                document.dispatchEvent(this.events.scriptLoaded);
                if (onload) {
                    onload();
                }
            };
            super.importScript("https://www.youtube.com/iframe_api", "script-youtube-player");
        } else {
            if (!document.scripts["script-youtube-player"].dataset.loaded) {
                if (onload) {
                    document.addEventListener("videoPlayer.youtubeScriptLoaded", () => {
                        onload();
                    });
                }
            }
        }
    }
    play() {
        this.player.playVideo();
    }
    stop() {
        this.player.pauseVideo();
    }
}

export default YoutubePlayer;
