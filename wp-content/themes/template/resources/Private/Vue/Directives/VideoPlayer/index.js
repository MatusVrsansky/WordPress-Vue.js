import HTMLPlayer from "./HTMLPlayer";
import VimeoPlayer from "./VimeoPlayer";
import YoutubePlayer from "./YoutubePlayer";

const __getPlayer = function(el, key) {
    switch (key) {
        case "youtube":
            return new YoutubePlayer(el);
        case "vimeo":
            return new VimeoPlayer(el);
        default:
            return new HTMLPlayer(el);
    }
};

export default {
    bind(el, binding) {
        let playerKey, player;

        el.addEventListener('videoPlayer.ready', () => {
            const playButton = el.querySelector("button");
            el.addEventListener('videoPlayer.play', () => {
                player.play();
                el.classList.add("is-playing");
                el.classList.remove("is-paused");
            });
            el.addEventListener('videoPlayer.stop', () => {
                player.stop();
                el.classList.add("is-paused");
                el.classList.remove("is-playing");
            });
            playButton.addEventListener("click", () => {
                el.dispatchEvent(player.events.play);
            });
        });

         playerKey = binding.value ? binding.value.toLowerCase() : "";
         player = __getPlayer(el, playerKey);

    }
}
