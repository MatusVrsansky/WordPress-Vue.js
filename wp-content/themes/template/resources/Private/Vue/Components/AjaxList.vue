<script>
    /*eslint no-console: "off"*/

    /**
     *
     * Default component for all list plugins which use Ajax calls
     *
     */

    import axios from "axios";
    import { Html5Entities } from "html-entities";
    import Vue from "vue";

    export default {
        props: {
            getItemsUrl: String,
            getItemsContentUrl: String,
            itemsPerPage: Number
        },
        data() {
            return {
                isLoading: true,
                items: [],
                filter: {},
                filteredItems: [],
                numberOfItemsToShow: 0
            }
        },
        computed: {
            showLoadMoreButton() {
                return this.numberOfItemsToShow < this.filteredItems.length;
            }
        },
        watch: {
            filter: {
                handler() {
                    this.filterItems();
                    this.resetNumberOfItemsToShow();
                    this.nextPage();
                    const coordY = 1000;

                    if (this.scrollToListWhenFilterChange && this.$refs.list) {
                        this.$scrollTo(this.$refs.list.$el, coordY, {
                            offset: this.$root.getScrollOffset
                        });
                    }
                },
                deep: true
            },
            isLoading(value) {
                if (this.$refs.loadButton) {
                    const buttonLabel = this.$refs.loadButton.querySelector('.button__label'),
                        loadText = this.$refs.loadButton.dataset.loadText,
                        loadingText = this.$refs.loadButton.dataset.loadingText;

                    if (value) {
                        this.$refs.loadButton.classList.add('is-loading');
                    } else {
                        this.$refs.loadButton.classList.remove('is-loading');
                    }

                    if (loadText && loadingText) {
                        if (this.$refs.loadButton.classList.contains('is-loading')) {
                            buttonLabel.textContent = loadingText;
                        } else {
                            buttonLabel.textContent = loadText;
                        }
                    }
                }
            }
        },
        created() {
            axios.get(this.getItemsUrl)
                .then((response) => {
                    this.addItems(response.data.reverse(), false, false);
                    this.nextPage();
                })
                .catch(() => {
                    console.error("Oops an error occurred.");
                });
        },
        methods: {
            addItems(items, doInsertion = true, showItems = true) {
                items.forEach((item) => {
                    if (item.content) {
                        item.content = Html5Entities.decode(item.content);
                    }

                    if (doInsertion) {
                        let index = -1;

                        if (item.parent) {
                            index = this.items.findIndex((elem) => {
                                return elem.uid === item.parent;
                            });
                        }
                        this.items.splice(index + 1, 0, item);
                    } else {
                        this.items.unshift(item);
                    }
                });
                this.filterItems();
                if (showItems) {
                    this.numberOfItemsToShow += items.length;
                }
            },
            compile(item) {
                return Vue.compile(item.content);
            },
            /**
             * Abstract function when list has also filter functionality
             * @return {array} Filtered Items.
             */
            filterItems() {
                this.filteredItems = this.items;
            },
            increaseNumberOfItemsToShow() {
                this.numberOfItemsToShow = Math.min(this.numberOfItemsToShow + this.itemsPerPage, this.filteredItems.length);
            },
            loadItemsContent() {
                let uids = [];

                for (let i = 0; i < this.numberOfItemsToShow; i++) {
                    if (!this.filteredItems[i].content) {
                        uids.push(this.filteredItems[i].uid);
                    }
                }

                this.isLoading = true;
                axios.get(this.getItemsContentUrl, {
                    params: {
                        uids: uids.join(',')
                    }
                }).then((response) => {
                    response.data.forEach((dataItem) => {
                        let index = this.items.findIndex((item) => {
                            return item.uid === dataItem.uid;
                        });

                        this.$set(this.items[index], 'content', Html5Entities.decode(dataItem.content));
                    });
                }).catch(() => {
                    console.error("Oops an error occurred.");
                }).then(() => {
                    this.isLoading = false;
                });
            },
            nextPage() {
                this.increaseNumberOfItemsToShow();
                this.loadItemsContent();
            },
            resetNumberOfItemsToShow() {
                this.numberOfItemsToShow = 0;
            }
        }
    }
</script>
