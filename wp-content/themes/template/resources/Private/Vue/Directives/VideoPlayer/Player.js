import CustomEvent from "custom-event";

/**
 @abstract
 */
class Player {
    constructor(element) {
        if (new.target === Player) {
            throw new TypeError("Cannot construct Abstract instances directly");
        }

        this.element = element;
        this.events = {};
        this.events.play = new CustomEvent('videoPlayer.play');
        this.events.ready = new CustomEvent('videoPlayer.ready');
        this.events.scriptLoaded = new CustomEvent('videoPlayer.scriptLoaded');
        this.events.stateChange = new CustomEvent('videoPlayer.stateChange');
        this.events.stop = new CustomEvent('videoPlayer.stop');
    }

    onPlayerReady() {
        this.element.dispatchEvent(this.events.ready);
    }

    onPlayerStateChange() {
        this.element.dispatchEvent(this.events.stateChange);
    }

    importScript(url, id, onload) {
        let firstScriptTag = document.getElementsByTagName("BODY")[0].getElementsByTagName("SCRIPT")[0];
        let script = document.createElement("script");

        script.async = false;
        script.id = id;
        script.onload = onload;
        script.src = url;
        firstScriptTag.parentNode.insertBefore(script, firstScriptTag);
    }

    /* Abstract methods */
    getInfo() {
        throw new Error("Abstract method!");
    }
    play() {
        throw new Error("Abstract method!");
    }
    stop() {
        throw new Error("Abstract method!");
    }
}

export default Player;
