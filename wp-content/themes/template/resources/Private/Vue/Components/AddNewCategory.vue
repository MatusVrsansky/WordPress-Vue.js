<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-7">
                <h1 class="text-dark pt-2">Pridanie Kategórie</h1>
                <form id="app" @submit="addNewCategory" method="post">
                    <ul v-if="errors.length" class="p-0" id="new_category_errors_list">
                        <li v-for="error in errors" class="list-unstyled text-danger">{{ error }}</li>
                    </ul>
                    <div class="form-group">
                        <input type="text" class="form-control" name="name" id="new_category_name" v-model="name">
                    </div>
                    <button type="submit" id="submit_new_category_button" class="btn btn-small btn-primary" data-toggle="modal" data-target="">Pridať kategóriu</button>
                </form>

                <!-- Modal -->
                <div class="modal fade" id="staticBackdrop" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <p class="m-0">Kategória: <strong>{{ name }}</strong> bola úspešne pridaná</p>
                            </div>
                            <div class="modal-footer justify-content-start">
                                <span>Chcete pridať ďaľšiu kategóriu?</span>
                                <button type="button" @click="GoToHomepage" class="btn btn-secondary">Nie</button>
                                <button type="button" @click="ReloadCurrentPage" class="btn btn-primary">Áno</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Cookies from 'js-cookie';

    const DAYS_IN_YEAR = 365;

    export default {
        data() {
            return {
                errors: [],
                name: null,
                match: false,
                showModal: false,
                myJSON: window.categories
            }
        },
        props: {
            closeLabel: String
        },
        created() {
            this.isAccepted = Cookies.get("cookiesAccepted") === "1";
        },

        methods: {
            accept() {
                Cookies.set("cookiesAccepted", "1", {expires: DAYS_IN_YEAR});
                this.isAccepted = true;
            }, GoToHomepage() {
                window.location.href = '/';
            },
            ReloadCurrentPage() {
                location.reload();
            },
            addNewCategory(e) {
                // set false again
                this.match = false;
                let input = document.getElementById('new_category_name');

                if (this.name && this.name !== "") {
                    // if input field is not empty!
                    for(let i = 0; i < this.myJSON.length; i++) {
                        if(this.name === this.myJSON[i].name) {
                            this.errors = [];
                            input.classList.add("border-danger");
                            this.errors.push('Táto kategória už existuje');
                            this.match = true;
                        }
                    }

                    if(this.match === false) {
                        document.getElementById('submit_new_category_button').dataset.target = "#staticBackdrop";
                        document.getElementById('submit_new_category_button').click();

                        $.ajax({
                            url: ajaxurl,
                            type: "POST",
                            data:
                                {
                                    "action": "addNewCategory",
                                    "category": this.name
                                },
                            success:function(data) {
                                this.errors = [];
                                input.classList.remove("border-danger");
                            }
                        });
                    }
                }

                else {
                    this.errors = [];
                    input.classList.add("border-danger");
                    this.errors.push('Vyplňte prosím toto políčko');
                }

                e.preventDefault();
            },
        }
    }
</script>

