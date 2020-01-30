<template>
    <div class="container mt-4">
        <table class="table table-warning">
            <thead>
            <tr class="row m-0">
                <th class="d-inline-block col-12">
                    <div class="has-search">
                        <span class="fa fa-search form-control-feedback"></span>
                        <input type="text" id="all-questions-search-form" class="search form-control" placeholder="Meno alebo priezvisko"  v-model="filter_name">
                    </div>
                </th>
            </tr>
            <tr class="row m-0 text-primary">
                <th class="d-inline-block col-sm-12 col-md-2">#</th>
                <th class="d-inline-block col-sm-12  col-md-4">Hráč</th>
                <th class="d-inline-block col-md-3">Správne odpovede</th>
                <th class="d-inline-block col-md-3">Nesprávne odpovede</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(row, index) in filteredRows.slice(pageStart, pageStart + countOfPage)" class="row m-0">
                <th class="d-inline-block col-sm-12 col-md-2 text-info">{{ (currPage-1) * countOfPage + index + 1 }}</th>
                <th class="d-inline-block col-sm-12 col-md-4">{{row.name+" "+row.surname}}</th>
                <th class="d-inline-block col-sm-12 col-md">{{row.count_good_answers}}</th>
                <th class="d-inline-block col-sm-12 col-md">{{row.count_bad_answers}}</th>
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
    </div>
</template>

<script>

    export default {
        data() {
            return {
                rows: window.winnersGameTicTacToe,
                countOfPage: 4,
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

                return this.rows.filter((row) => {
                    if(row.name.toLowerCase().includes(this.filter_name.toLowerCase()) ||  row.surname.toLowerCase().includes(this.filter_name.toLowerCase())) {
                        this.currPage = 1;
                        return row;
                    }
                });
            },
            pageStart: function () {
                return (this.currPage - 1) * this.countOfPage;
            },
            totalPage: function () {
                return Math.ceil(this.filteredRows.length / this.countOfPage);
            }
        },
        methods: {
            setPage(idx) {
                if (idx <= 0 || idx > this.totalPage) {
                    return;
                }
                this.currPage = idx;
            },
        }
    }
</script>
