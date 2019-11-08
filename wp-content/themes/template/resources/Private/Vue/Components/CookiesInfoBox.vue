<template>
  <transition name="fade">
    <div class="info-panel" v-if="!isAccepted">
      <div class="container">
        <div class="row">
          <div class="col-10">
            <p class="m-0">
              <slot></slot>
            </p>
          </div>
          <div class="col-2 text-right">
            <button type="button" class="btn btn-icon info-panel-close" :aria-label="closeLabel" @click="accept">
              <close-icon/>
            </button>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
    import CloseIcon from '../../Partials/Icons/Close.svg';

    const DAYS_IN_YEAR = 365,
        HOURS_PER_DAY = 24,
        MILISECONDS_PER_SECOND = 1000,
        MINUTES_PER_HOURS = 60,
        SECONDS_PER_MINUTE = 60;

    export default {
        data() {
            return {
                isAccepted: false,
            }
        },

        props: {
            closeLabel: String
        },

        components: {
            CloseIcon
        },

        created() {
            this.isAccepted = this.getCookie("cookiesAccepted") === "1";
        },

        methods: {
            accept() {
                this.setCookie("cookiesAccepted", "1", DAYS_IN_YEAR);
                this.isAccepted = true;
            },

            setCookie(cname, cvalue, exdays) {
                const d = new Date(),
                    MILISECONDS_PER_DAY = HOURS_PER_DAY * MINUTES_PER_HOURS * SECONDS_PER_MINUTE * MILISECONDS_PER_SECOND;

                d.setTime(d.getTime() + (exdays * MILISECONDS_PER_DAY));
                document.cookie = `${cname}=${cvalue}; expires=${d.toUTCString()}; path=/`;
            },

            getCookie(cname) {
                const ca = document.cookie.split(";"),
                    name = `${cname}=`;


                for (let i = 0; i < ca.length; i++) {
                    let c = ca[i];

                    while (c.charAt(0) === " ") {
                        c = c.substring(1);
                    }

                    if (c.indexOf(name) === 0) {
                        return c.substring(name.length, c.length);
                    }
                }
                return "";
            }
        }
    }
</script>
