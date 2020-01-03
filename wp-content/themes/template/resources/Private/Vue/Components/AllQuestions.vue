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
                    <th class="d-inline-block col-sm-12 col-lg-8 col-md-6">Otázka</th>
                    <th class="d-inline-block col-lg-2 col-md-4">Akcie</th>
                </tr>
            </thead>
            <tbody>
            <tr v-for="(row, index) in filteredRows.slice(pageStart, pageStart + countOfPage)" class="row m-0">
                <th class="d-inline-block col-2">{{ (currPage-1) * countOfPage + index + 1 }}</th>
                <td id="postTitleCurrent" class="d-inline-block col-lg-8 col-md-6">{{row.post_title}}</td>
                <input type="hidden" id="postId" :value="row.ID">
                <td class="d-inline-block col-lg-2 col-md-4">
                <button type="button" id="button-all-questions" class="btn btn-default btn-sm btn-success mb-md-0 mb-lg-2 mb-xl-0" @click.prevent="editQuestion(row.ID)" data-toggle="modal" data-target=".bs-example-modal-new">
                    <span class="fa fa-pencil"></span> Edit
                </button>
                <button type="button" id="" class="btn btn-default btn-sm btn-danger" @click.prevent="removeQuestion(row.ID, row.post_title)" data-toggle="modal" data-target=".bs-example-modal-delete">
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
                        <h2 class="w-100 text-center">Upraviť otázku</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <div class="body-message">
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <form id="form" method="POST" @submit="validateForm">
                                        <transition name="fade" v-on:enter="enter">
                                            <div v-if="show" class="alert alert-success" role="alert">
                                                Otázka bola úspešne upravená
                                            </div>
                                        </transition>
                                        <input type="hidden" id="current_question_id" value="0"></input>
                                        <div class="form-group">
                                            <label class="form-control-label" for="new_question_title">Názov</label>
                                            <input id="new_question_title" name="name_question" class="form-control form-control-warning" v-bind:class="{ 'has-warning': attemptSubmit && missingQuestionName }" type="text" v-model="name">
                                            <div class="form-control-feedback" v-if="attemptSubmit && missingQuestionName">Vyplňte prosím toto políčko</div>
<!--                                            <div class="form-control-feedback" v-if="attemptSubmit && addExistedQuestion">Otázka s takýmto názvom už existuje</div>-->
                                        </div><!-- /form-group -->
                                        <div class="form-group">
                                            <label class="form-control-label" for="new_question_answer_a">Odpoveď A</label>
                                            <input id="new_question_answer_a" name="question_answer_a" class="form-control form-control-warning" v-bind:class="{ 'has-warning': attemptSubmit && missingQuestionAnswerA }"  type="text" v-model="answer_a">
                                            <div class="form-control-feedback" v-if="attemptSubmit && missingQuestionAnswerA">Vyplňte prosím toto políčko</div>
                                        </div><!-- /form-group -->
                                        <div class="form-group">
                                            <label class="form-control-label" for="new_question_answer_b">Odpoveď B</label>
                                            <input id="new_question_answer_b" name="question_answer_b" class="form-control form-control-warning" v-bind:class="{ 'has-warning': attemptSubmit && missingQuestionAnswerB }"  type="text" v-model="answer_b">
                                            <div class="form-control-feedback" v-if="attemptSubmit && missingQuestionAnswerB">Vyplňte prosím toto políčko</div>
                                        </div><!-- /form-group -->
                                        <div class="form-group">
                                            <label class="form-control-label" for="new_question_answer_c">Odpoveď C</label>
                                            <input id="new_question_answer_c" name="question_answer_c" class="form-control form-control-warning" v-bind:class="{ 'has-warning': attemptSubmit && missingQuestionAnswerC }"  type="text" v-model="answer_c">
                                            <div class="form-control-feedback" v-if="attemptSubmit && missingQuestionAnswerC">Vyplňte prosím toto políčko</div>
                                        </div><!-- /form-group -->
                                        <div class="form-group">
                                            <label class="form-control-label" for="new_question_answer_d">Odpoveď D</label>
                                            <input id="new_question_answer_d" name="question_answer_d" class="form-control form-control-warning" v-bind:class="{ 'has-warning': attemptSubmit && missingQuestionAnswerD }"  type="text" v-model="answer_d">
                                            <div class="form-control-feedback" v-if="attemptSubmit && missingQuestionAnswerD">Vyplňte prosím toto políčko</div>
                                        </div><!-- /form-group -->
                                        <div class="form-group">
                                            <label>Správna odpoveď</label>
                                            <select id="right_answer_select" class="form-control form-control-warning" v-bind:class="{ 'has-warning': attemptSubmit && missingRightAnswer }"  v-model="rightAnswer">
                                                <option value="">Vybrať správnu odpoveď</option>
                                                <option value="answer_a">Odpoveď A</option>
                                                <option value="answer_b">Odpoveď B</option>
                                                <option value="answer_c">Odpoveď C</option>
                                                <option value="answer_d">Odpoveď D</option>
                                            </select>
                                            <div class="form-control-feedback" v-if="attemptSubmit && missingRightAnswer">Vyberte prosím správnu odpoveď pre danú otázku</div>
                                        </div>
                                        <div class="form-group">
                                            <label>Kategória</label>
                                            <select id="question_selected_category" class="form-control form-control-warning" v-bind:class="{ 'has-warning': attemptSubmit && missingSelectedCategory }"  v-model="selectedCategory">
                                                <option value="">Vyberte príslušnú kategóriu</option>
                                                <option v-for="category in jsonAllCategories" :value="category.slug">{{category.name}}</option>
                                            </select>
                                            <div class="form-control-feedback" v-if="attemptSubmit && missingSelectedCategory">Priradte prosím príslušnú kategóriu k danej otázke</div>
                                        </div>
                                        <button  id="submit_new_question_button" class="btn btn-small btn-primary" data-toggle="modal" data-target="">Upraviť otázku</button>
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
                        <h2 class="w-100 text-center">Vymazať otázku</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <div class="body-message">
                            <transition name="fade" v-on:enter="enterDelete">
                                <div v-if="showDeleteModal" class="alert alert-danger" role="alert">
                                    Otázka bola úspešne odstránená
                                </div>
                            </transition>
                            <h4>Naozaj chcete vymazať túto otázku?</h4><p>"{{deleteModalQuestionTitle}}"</p>
                        </div>
                    </div>
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary btn-success" @click.prevent="deleteQuestion">Áno</button>
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
                rows: window.allQuestions,
                countOfPage: 5,
                currPage: 1,
                filter_name: '',
                customIndex: 0,
                name: '',
                answer_a: '',
                answer_b: '',
                answer_c: '',
                answer_d: '',
                jsonAllQuestions: window.allQuestions,
                jsonAllCategories: window.categories,
                rightAnswer: '',
                matchExistedQuestionName: false,
                selectedCategory: '',
                attemptSubmit: false,
                show: false,
                set: false,
                deleteModalQuestionTitle: '',
                showDeleteModal : false
            }
        },
        computed: {
            filteredRows: function () {
                var filter_name = this.filter_name.toLowerCase();

                return (this.filter_name.trim() !== '') ?
                    this.rows.filter(function (d) {
                        return d.post_title.toLowerCase().indexOf(filter_name) > -1;
                    }) :
                    this.rows;
            },
            pageStart: function () {
                return (this.currPage - 1) * this.countOfPage;
            },
            totalPage: function () {
                return Math.ceil(this.filteredRows.length / this.countOfPage);
            },
            addExistedQuestion: function () {
                this.matchExistedQuestionName = false;
                for (let i = 0; i < this.jsonAllQuestions.length; i++) {
                    if (this.name === this.jsonAllQuestions[i].post_title) {
                        this.matchExistedQuestionName = true;
                    }
                }

                switch (this.matchExistedQuestionName) {
                    case true:
                        return true;
                    case false:
                        return false;
                }
            },
            missingQuestionName: function () {
                return this.name === '';
            },
            missingQuestionAnswerA: function () {
                return this.answer_a === '';
            },
            missingQuestionAnswerB: function () {
                return this.answer_b === '';
            },
            missingQuestionAnswerC: function () {
                return this.answer_c === '';
            },
            missingQuestionAnswerD: function () {
                return this.answer_d === '';
            },
            missingRightAnswer: function () {
                return this.rightAnswer === '';
            },
            missingSelectedCategory: function () {
                return this.selectedCategory === '';
            }
        },
        methods: {
            removeQuestion(id, questionTitle) {
                this.deleteModalQuestionTitle = questionTitle;
            },
            fadeMe() {
                this.show = !this.show;
            },
            fadeModalDelete() {
                this.showDeleteModal = !this.showDeleteModal;
            },
            hh() {
                this.name = document.getElementById('new_question_title').value;
                this.answer_a = document.getElementById('new_question_answer_a').value;
                this.answer_b = document.getElementById('new_question_answer_b').value;
                this.answer_c = document.getElementById('new_question_answer_c').value;
                this.answer_d = document.getElementById('new_question_answer_d').value;
                this.rightAnswer = document.getElementById('right_answer_select').value;
                this.selectedCategory = document.getElementById('question_selected_category').value;
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
            editQuestion(ID) {

                document.getElementById('current_question_id').value = ID;

                $.ajax({
                    url: ajaxurl,
                    type: "POST",
                    async:false,
                    data: {"action": "getPostById", "id": ID},
                    success: function (data) {
                        this.set = true;
                        let my = JSON.parse(data);


                        // this.setInputs(my[0][0].post_title);
                        document.getElementById('new_question_title').value = my[0][0].post_title;
                        document.getElementById('new_question_answer_a').value = my[1][0].answer_a;
                        document.getElementById('new_question_answer_b').value = my[1][0].answer_b;
                        document.getElementById('new_question_answer_c').value = my[1][0].answer_c;
                        document.getElementById('new_question_answer_d').value = my[1][0].answer_d;
                        document.getElementById('question_selected_category').value = my[1][0].category[0].slug;

                        if (document.getElementById('new_question_answer_a').value === my[1][0].right_answer) {
                            document.getElementById('right_answer_select').value = 'answer_a';
                        } else if (document.getElementById('new_question_answer_b').value === my[1][0].right_answer) {
                            document.getElementById('right_answer_select').value = 'answer_b';
                        } else if (document.getElementById('new_question_answer_c').value === my[1][0].right_answer) {
                            document.getElementById('right_answer_select').value = 'answer_c';
                        } else {
                            document.getElementById('right_answer_select').value = 'answer_d';
                        }
                    }
                }).complete(function(){
                    console.log('ideeeeeeeeeeeeeeeeeeeeeeeeeee');
                    this.hh();
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

                if (this.missingQuestionName || this.missingQuestionAnswerA || this.missingQuestionAnswerB
                    || this.missingQuestionAnswerC || this.missingQuestionAnswerD || this.missingRightAnswer || this.missingSelectedCategory) {
                    event.preventDefault();
                } else {
                    event.preventDefault();
                    this.fadeMe();

                    let id = document.getElementById('current_question_id').value;
                    let title = document.getElementById('new_question_title').value;
                    let answer_a = document.getElementById('new_question_answer_a').value;
                    let answer_b = document.getElementById('new_question_answer_b').value;
                    let answer_c = document.getElementById('new_question_answer_c').value;
                    let answer_d = document.getElementById('new_question_answer_d').value;

                    // get right answer option from select box
                    let selector = document.getElementById('right_answer_select');
                    let value = selector[selector.selectedIndex].value;
                    let right_answer;

                    switch (value) {
                        case 'answer_a':
                            right_answer = answer_a;
                            break;
                        case 'answer_b':
                            right_answer = answer_b;
                            break;
                        case 'answer_c':
                            right_answer = answer_c;
                            break;
                        case 'answer_d':
                            right_answer = answer_d;
                            break;
                    }

                    // get option Category from select box
                    let category = document.getElementById('question_selected_category');
                    let selected_category = category[category.selectedIndex].value;

                    document.getElementById('submit_new_question_button').dataset.target = "#staticBackdropQuestion";
                    document.getElementById('submit_new_question_button').click();

                    // if input fields are not empty!
                    $.ajax({
                        url: ajaxurl,
                        type: "POST",
                        data:
                            {
                                "action": "editAdvertisement",
                                "id" : id,
                                "name": title,
                                "answer_a": answer_a,
                                "answer_b": answer_b,
                                "answer_c": answer_c,
                                "answer_d": answer_d,
                                "right_answer": right_answer,
                                "category": selected_category
                            },
                        success: function (data) {
                            // backOfferButton.dataset.target = "/";
                        }
                    });
                }
            },
            deleteQuestion() {
                this.fadeModalDelete();
                let id = document.getElementById('current_question_id').value;

                $.ajax({
                    url: ajaxurl,
                    type: "POST",
                    data:
                        {
                            "action": "deleteQuestion",
                            "id" : id,
                        },
                    success: function (data) {

                    }
                });
            }
        },
        watch: {
            set() {
                if (this.set === true) {
                    this.name = 'test';
                    console.log('watcher is activated');
                }

                else {
                    console.log('watcher is not activated');
                }
            }
        }
    }
</script>
