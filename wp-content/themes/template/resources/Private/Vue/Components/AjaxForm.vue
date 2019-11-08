<template>
  <form :action="action">
    <div
      v-if="htmlResponse"
      v-html="htmlResponse"
    />
    <slot v-if="!htmlResponse" />
    <slot
      v-if="!htmlResponse"
      name="fields"
      :send="send"
      :hasPropertyError="hasPropertyError"
      :getErrorMessage="getErrorMessage"
    />
  </form>
</template>

<script>
    /*eslint no-console: "off"*/

    import axios from "axios";
    import { Html5Entities } from "html-entities";

    export default {
        props: {
            action: String,
            onSuccess: Function,
            resetFormOnSuccess: {
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                htmlResponse: '',
                properties: []
            }
        },
        mounted() {
            this.$el.addEventListener("submit", (e) => {
                e.preventDefault();
                this.send();
            });
        },
        methods: {
            getErrorMessage(propertiesKeys) {
                let messages = [];

                for (let property of propertiesKeys.split(",")) {
                    property = property.trim();
                    if (this.properties[property]) {
                        messages = messages.concat(this.properties[property].map(error => error.message));
                    }
                }

                return messages.join("<br>");
            },
            handleErrorResponse(error) {
                if (error.response) {
                    this.properties = error.response.data;
                    this.$nextTick(() => {
                       this.$root.scrollToError();
                    });
                } else {
                    console.error("Oops an error occurred.");
                }
            },
            handleResponse() {
                this.$root.$emit('flashMessage.update');
            },
            handleSuccessResponse(response) {
                if (response.data && response.data.content) {
                    this.htmlResponse = Html5Entities.decode(response.data.content);
                }
                if (this.onSuccess) {
                    this.onSuccess(response.data);
                }
                this.reset(this.resetFormOnSuccess);
            },
            hasPropertyError(propertiesKeys) {
                for (let property of propertiesKeys.split(",")) {
                    property = property.trim();
                    if (this.properties[property] && this.properties[property].length) {
                        return true;
                    }
                }

                return false;
            },
            send() {
                let data = new FormData(this.$el);

                axios.post(this.action, data)
                    .then((response) => {
                        this.handleSuccessResponse(response);
                    })
                    .catch((error) => {
                        this.handleErrorResponse(error);
                    }).then(() => {
                        this.handleResponse();
                    });
            },
            reset(resetForm) {
                this.properties = [];
                if (resetForm) {
                    this.$el.reset();
                    this.$root.$emit("Form:reset");
                }
            }
        }
    }
</script>
