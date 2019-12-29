<template>
    <div class="container mt-3 mt-sm-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form id="form" method="POST" @submit="addNewCategory">
                    <div class="form-group">
                        <label class="form-control-label" for="new_category_title">Názov</label>
                        <input id="new_category_title" class="form-control form-control-warning" v-bind:class="{ 'has-warning': attemptSubmit && missingCategoryName || addExistedCategory }"  type="text" v-model="name_category">
                        <div class="form-control-feedback" v-if="attemptSubmit && missingCategoryName">Vyplňte prosím toto políčko</div>
                        <div class="form-control-feedback" v-if="attemptSubmit && addExistedCategory">Kategória s takýmto názvom už existuje</div>
                    </div><!-- /form-group -->
                    <!--                    <button class="btn btn-primary">Submit</button>-->
                    <button  id="submit_new_category_button" class="btn btn-small btn-primary" data-toggle="modal" data-target="">Pridať kategóriu</button>
                </form>

                <!-- I removed fade  -->
                <div class="modal fade" id="staticBackdrop" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <p class="m-0">Kategória <strong>{{name_category}}</strong> bola úspešne pridaná</p>
                            </div>
                            <div class="modal-footer justify-content-start">
                                <span>Chcete pridať ďaľšiu kategóriu?</span>
                                <button type="button" @click="GoToHomepage" class="btn btn-secondary">Nie</button>
                                <button type="button" @click="ReloadCurrentPage" class="btn btn-primary">Áno</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /col -->
        </div><!-- /row -->
    </div><!-- /container -->
</template>

<script>
    export default {
        data() {
            return {
                name_category: '',
                jsonAllCategories: window.categories,
                matchExistedCategoryName: false,
                attemptSubmit: false,
            }
        },
        computed: {
            addExistedCategory: function () {
                this.matchExistedCategoryName = false;
                for (let i = 0; i < this.jsonAllCategories.length; i++) {
                    if (this.name_category === this.jsonAllCategories[i].name) {
                        this.matchExistedCategoryName = true;
                    }
                }

                switch (this.matchExistedCategoryName) {
                    case true:
                        return true;
                    case false:
                        return false;
                }
            },
            missingCategoryName: function () {
                return this.name_category === '';
            },
        },
        methods: {
            GoToHomepage() {
                window.location.href = '/';
            },
            ReloadCurrentPage() {
                location.reload();
            },
            addNewCategory(e) {

                this.attemptSubmit = true;
                if (this.addExistedCategory || this.missingCategoryName) {
                    e.preventDefault();
                } else {
                    e.preventDefault();
                    document.getElementById('submit_new_category_button').dataset.target = "#staticBackdrop";
                    document.getElementById('submit_new_category_button').click();

                    $.ajax({
                        url: ajaxurl,
                        type: "POST",
                        data:
                            {
                                "action": "addNewCategory",
                                "category": this.name_category
                            },
                        success: function (data) {

                        }
                    });
                }
            }
        },
    }

</script>
