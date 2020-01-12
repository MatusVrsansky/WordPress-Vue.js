<template>
    <div class="container mt-4">
        <table class="all-question-table table table-warning">
            <thead>
            <tr class="row m-0">
                <th class="d-inline-block col-12">
                    <input type="text" id="all-questions-search-form" class="search form-control" placeholder="Hľadajte názov otázky" v-model="filter_name">
                </th>
            </tr>
            <tr class="row m-0">
                <th class="d-inline-block col-sm-12 col-md-2">#</th>
                <th class="d-inline-block col-sm-12 col-lg-8 col-md-6">Kategória</th>
                <th class="d-inline-block col-lg-2 col-md-4">Akcie</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(row, index) in filteredRows.slice(pageStart, pageStart + countOfPage)" class="row m-0">
                <th class="d-inline-block col-2">{{ (currPage-1) * countOfPage + index + 1 }}</th>
                <td id="postTitleCurrent" class="d-inline-block col-lg-8 col-md-6">{{row.name}}</td>
                <input type="hidden" id="postId" :value="row.term_id">
                <td class="d-inline-block col-lg-2 col-md-4">
                    <button type="button" id="button-all-questions" class="btn btn-default btn-sm btn-success mb-md-0 mb-lg-2 mb-xl-0" @click.prevent="editCategory(row.term_id)" data-toggle="modal" data-target=".bs-example-modal-new">
                        <span class="fa fa-pencil"></span> Edit
                    </button>
                    <button type="button" id="" class="btn btn-default btn-sm btn-danger" @click.prevent="removeCategory(row.term_id, row.name)" data-toggle="modal" data-target=".bs-example-modal-delete">
                        <span class="fa fa-pencil"></span> Vymazať
                    </button>
                </td>
            </tr>
            </tbody>
        </table>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <li class="page-item" v-bind:class="{'disabled': (currPage === 1)}" @click.prevent="setPage(currPage-1)"><a class="page-link" href="">Predchádzajúca</a></li>
                <li class="page-item" v-for="n in totalPage" v-bind:class="{'active': (currPage === (n))}" @click.prevent="setPage(n)"><a class="page-link" href="">{{n}}</a></li>
                <li class="page-item" v-bind:class="{'disabled': (currPage === totalPage)}" @click.prevent="setPage(currPage+1)"><a class="page-link" href="">Ďaľšia</a></li>
            </ul>
        </nav>
        <div class="modal fade bs-example-modal-new" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

            <div class="modal-dialog modal-lg">
                <!-- Modal Content: begins -->
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h2 class="w-100 text-center">Upraviť kategóriu</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <div class="body-message">
                            <div class="row justify-content-center">
                                <div class="col-md-8">
<!--                                    <form id="form" method="POST" @submit="validateForm">-->
<!--                                        <transition name="fade" v-on:enter="enter">-->
<!--                                            <div v-if="show" class="alert alert-success" role="alert">-->
<!--                                                Kategória bola úspešne upravená-->
<!--                                            </div>-->
<!--                                        </transition>-->
<!--                                        <input type="hidden" id="current_question_id" value="0"></input>-->
<!--                                        <div class="form-group">-->
<!--                                            <label class="form-control-label" for="new_question_title">Názov</label>-->
<!--                                            <input id="new_question_title" name="name_question" class="form-control form-control-warning" v-bind:class="{ 'has-warning': attemptSubmit && missingQuestionName }" type="text" v-model="name">-->
<!--                                            <div class="form-control-feedback" v-if="attemptSubmit && missingQuestionName">Vyplňte prosím toto políčko</div>-->
<!--                                            &lt;!&ndash;                                            <div class="form-control-feedback" v-if="attemptSubmit && addExistedQuestion">Otázka s takýmto názvom už existuje</div>&ndash;&gt;-->
<!--                                        </div>&lt;!&ndash; /form-group &ndash;&gt;-->
<!--                                        <div class="form-group">-->
<!--                                            <label class="form-control-label" for="new_question_answer_a">Odpoveď A</label>-->
<!--                                            <input id="new_question_answer_a" name="question_answer_a" class="form-control form-control-warning" v-bind:class="{ 'has-warning': attemptSubmit && missingQuestionAnswerA }"  type="text" v-model="answer_a">-->
<!--                                            <div class="form-control-feedback" v-if="attemptSubmit && missingQuestionAnswerA">Vyplňte prosím toto políčko</div>-->
<!--                                        </div>&lt;!&ndash; /form-group &ndash;&gt;-->
<!--                                        <div class="form-group">-->
<!--                                            <label class="form-control-label" for="new_question_answer_b">Odpoveď B</label>-->
<!--                                            <input id="new_question_answer_b" name="question_answer_b" class="form-control form-control-warning" v-bind:class="{ 'has-warning': attemptSubmit && missingQuestionAnswerB }"  type="text" v-model="answer_b">-->
<!--                                            <div class="form-control-feedback" v-if="attemptSubmit && missingQuestionAnswerB">Vyplňte prosím toto políčko</div>-->
<!--                                        </div>&lt;!&ndash; /form-group &ndash;&gt;-->
<!--                                        <div class="form-group">-->
<!--                                            <label class="form-control-label" for="new_question_answer_c">Odpoveď C</label>-->
<!--                                            <input id="new_question_answer_c" name="question_answer_c" class="form-control form-control-warning" v-bind:class="{ 'has-warning': attemptSubmit && missingQuestionAnswerC }"  type="text" v-model="answer_c">-->
<!--                                            <div class="form-control-feedback" v-if="attemptSubmit && missingQuestionAnswerC">Vyplňte prosím toto políčko</div>-->
<!--                                        </div>&lt;!&ndash; /form-group &ndash;&gt;-->
<!--                                        <div class="form-group">-->
<!--                                            <label class="form-control-label" for="new_question_answer_d">Odpoveď D</label>-->
<!--                                            <input id="new_question_answer_d" name="question_answer_d" class="form-control form-control-warning" v-bind:class="{ 'has-warning': attemptSubmit && missingQuestionAnswerD }"  type="text" v-model="answer_d">-->
<!--                                            <div class="form-control-feedback" v-if="attemptSubmit && missingQuestionAnswerD">Vyplňte prosím toto políčko</div>-->
<!--                                        </div>&lt;!&ndash; /form-group &ndash;&gt;-->
<!--                                        <div class="form-group">-->
<!--                                            <label>Správna odpoveď</label>-->
<!--                                            <select id="right_answer_select" class="form-control form-control-warning" v-bind:class="{ 'has-warning': attemptSubmit && missingRightAnswer }"  v-model="rightAnswer">-->
<!--                                                <option value="">Vybrať správnu odpoveď</option>-->
<!--                                                <option value="answer_a">Odpoveď A</option>-->
<!--                                                <option value="answer_b">Odpoveď B</option>-->
<!--                                                <option value="answer_c">Odpoveď C</option>-->
<!--                                                <option value="answer_d">Odpoveď D</option>-->
<!--                                            </select>-->
<!--                                            <div class="form-control-feedback" v-if="attemptSubmit && missingRightAnswer">Vyberte prosím správnu odpoveď pre danú otázku</div>-->
<!--                                        </div>-->
<!--                                        <div class="form-group">-->
<!--                                            <label>Kategória</label>-->
<!--                                            <select id="question_selected_category" class="form-control form-control-warning" v-bind:class="{ 'has-warning': attemptSubmit && missingSelectedCategory }"  v-model="selectedCategory">-->
<!--                                                <option value="">Vyberte príslušnú kategóriu</option>-->
<!--                                                <option v-for="category in jsonAllCategories" :value="category.slug">{{category.name}}</option>-->
<!--                                            </select>-->
<!--                                            <div class="form-control-feedback" v-if="attemptSubmit && missingSelectedCategory">Priradte prosím príslušnú kategóriu k danej otázke</div>-->
<!--                                        </div>-->
<!--                                        <button  id="submit_new_question_button" class="btn btn-small btn-primary" data-toggle="modal" data-target="">Upraviť otázku</button>-->
<!--                                    </form>-->
                                    <form id="form" method="POST" @submit="validateForm">
                                        <transition name="fade" v-on:enter="enter">
                                            <div v-if="show" class="alert alert-success" role="alert">
                                                Kategória bola úspešne upravená
                                            </div>
                                        </transition>
                                        <input type="hidden" id="current_category_id" value="0"></input>
                                        <div class="form-group">
                                            <label class="form-control-label" for="edit_category_title">Názov</label>
                                            <input id="edit_category_title" class="form-control form-control-warning" :class="{ 'has-warning': attemptSubmit && missingCategoryName }"  type="text" v-model="nameCategory">
                                            <div class="form-control-feedback" v-if="attemptSubmit && missingCategoryName">Vyplňte prosím toto políčko</div>
                                        </div><!-- /form-group -->
                                        <!--                    <button class="btn btn-primary">Submit</button>-->
                                        <button  id="submit_new_category_button" class="btn btn-small btn-primary" data-toggle="modal" data-target="">Odoslať</button>
                                    </form>
                                </div><!-- /col -->
                            </div><!-- /row -->
                        </div>
                    </div>
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <!--                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>-->
                    </div>
                </div>
                <!-- Modal Content: ends -->
            </div>
        </div>
        <div class="modal fade bs-example-modal-delete" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
            <div class="modal-dialog">
                <!-- Modal Content: begins -->
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h2 class="w-100 text-center">Vymazať kategóriu</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <div class="body-message">
                            <transition name="fade" v-on:enter="enterDelete">
                                <div v-if="showDeleteModal" class="alert alert-danger" role="alert">
                                    Kategória bola úspešne odstránená
                                </div>
                            </transition>
                            <h4>Naozaj chcete vymazať túto kategóriu?</h4><p>"{{deleteModalCategoryTitle}}"</p>
                            <h4 class="text-danger">Automaticky sa taktiež vymažú všetky otázky, ktoré boli priradené k aktuálnej kategórií</h4>
                        </div>
                    </div>
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary btn-success" @click.prevent="deleteCategory">Áno</button>
                        <button type="button" class="btn btn-primary btn-danger" data-dismiss="modal">Nie</button>
                    </div>
                </div>
                <!-- Modal Content: ends -->
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        data() {
            return {
                rows: window.categories,
                countOfPage: 5,
                currPage: 1,
                filter_name: '',
                customIndex: 0,
                show: false,
                set: false,
                deleteModalCategoryTitle: '',
                showDeleteModal : false,
                nameCategory: '',
                jsonAllCategories: window.categories,
                matchExistedCategoryName: false,
                attemptSubmit: false,
            }
        },
        computed: {
            addExistedCategory: function () {
                this.matchExistedCategoryName = false;
                for (let i = 0; i < this.jsonAllCategories.length; i++) {
                    if (this.nameCategory === this.jsonAllCategories[i].name) {
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
                return this.nameCategory === '';
            },
            filteredRows: function () {
                let filter_name = this.filter_name.toLowerCase();

                return (this.filter_name.trim() !== '') ?
                    this.rows.filter(function (d) {
                        return d.name.toLowerCase().indexOf(filter_name) > -1;
                    }) :
                    this.rows;
            },
            pageStart: function () {
                return (this.currPage - 1) * this.countOfPage;
            },
            totalPage: function () {
                return Math.ceil(this.filteredRows.length / this.countOfPage);
            }
        },
        methods: {
            removeCategory(categoryId, categoryTitle) {
                document.getElementById('current_category_id').value = categoryId;
                this.deleteModalCategoryTitle = categoryTitle;
            },
            fadeMe() {
                this.show = !this.show;
            },
            fadeModalDelete() {
                this.showDeleteModal = !this.showDeleteModal;
            },
            setEditCategoryName() {
                this.nameCategory = document.getElementById('edit_category_title').value;
            },
            enter: function (el, done) {
                let that = this;

                setTimeout(function () {
                    that.show = false;

                    // reload page
                    location.reload();
                }, 2500); // hide the message after 3 seconds
            },
            enterDelete: function (el, done) {
                let that = this;

                setTimeout(function () {
                    that.showDeleteModal = false;

                    // reload page
                    location.reload();
                }, 2500); // hide the message after 3 seconds
            },
            setPage(idx) {
                if (idx <= 0 || idx > this.totalPage) {
                    return;
                }
                this.currPage = idx;
            },
            editCategory(ID) {
                document.getElementById('current_category_id').value = ID;

                console.log("IIIIIIIID: "+ID);

                $.ajax({
                    url: ajaxurl,
                    type: "POST",
                    async:false,
                    dataType: 'json',
                    data: {"action": "getCategoryById", "id": ID},
                    success: function (data) {
                        document.getElementById('edit_category_title').value = data;
                    }
                }).complete(function(){
                    this.setEditCategoryName();
                }.bind(this));
            },

            GoToHomepage() {
                window.location.href = '/';
            },
            ReloadCurrentPage() {
                location.reload();
            },
            validateForm(event) {
                this.attemptSubmit = true;

                if (this.missingCategoryName) {
                    event.preventDefault();
                } else {
                    event.preventDefault();

                    // show edit Alert Message
                    this.fadeMe();

                    let idCategory =  document.getElementById('current_category_id').value;
                    let nameCategory = document.getElementById('edit_category_title').value;

                    // if input fields are not empty!
                    $.ajax({
                        url: ajaxurl,
                        type: "POST",
                        data:
                            {
                                "action": "editCategory",
                                "id" : idCategory,
                                "nameCategory": nameCategory,
                            },
                        success: function (data) {
                        }
                    });
                }
            },
            deleteCategory() {
                this.fadeModalDelete();
                let idCategory = document.getElementById('current_category_id').value;

                $.ajax({
                    url: ajaxurl,
                    type: "POST",
                    data:
                        {
                            "action": "deleteCategory",
                            "id" : idCategory,
                        },
                    success: function (data) {
                    }
                });
            }
        }
    }
</script>
