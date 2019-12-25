<template>
    <div class="container mt-4">
<!--        <table class="table table-warning">-->
<!--            <thead>-->
<!--            <tr class="row m-0 text-alig">-->
<!--                <th class="d-inline-block col-12 text-left">-->
<!--                    <input type="text" id="all-questions-search-form" class="search form-control" placeholder="Hľadajte názov otázky" v-model="search">-->
<!--                </th>-->
<!--            </tr>-->
<!--            <tr class="row m-0">-->
<!--                <th class="d-inline-block col-2">#</th>-->
<!--                <th class="d-inline-block col-8">Otázka</th>-->
<!--                <th class="d-inline-block col-2">Akcie</th>-->
<!--            </tr>-->
<!--            </thead>-->
<!--            <tbody>-->
<!--            <tr class="row m-0" v-for="(question, index) in questionsList">-->
<!--                <th class="d-inline-block col-2">{{++index}}</th>-->
<!--                <td class="d-inline-block col-8">{{question.post_title}}</td>-->
<!--                <td class="d-inline-block col-2">-->
<!--                    <button type="button" class="btn btn-default btn-sm btn-success">-->
<!--                        <span class="fa fa-pencil"></span> Edit-->
<!--                    </button>-->
<!--                    <button type="button" class="btn btn-default btn-sm btn-danger">-->
<!--                        <span class="fa fa-pencil"></span> Vymazať-->
<!--                    </button>-->
<!--                </td>-->
<!--            </tr>-->
<!--            </tbody>-->
<!--        </table>-->
        <table class="table table-warning">
            <thead>
                <tr class="row m-0">
                    <th class="d-inline-block col-12">
                        <input type="text" id="all-questions-search-form" class="search form-control" placeholder="Hľadajte názov otázky" v-model="filter_name">
                    </th>
                </tr>
                <tr class="row m-0">
                    <th class="d-inline-block col-2">#</th>
                    <th class="d-inline-block col-8">Otázka</th>
                    <th class="d-inline-block col-2">Akcie</th>
                </tr>
            </thead>
            <tbody>
            <tr v-for="(r, index) in filteredRows.slice(pageStart, pageStart + countOfPage)" class="row m-0">
                <th class="d-inline-block col-2">{{ (currPage-1) * countOfPage + index + 1 }}</th>
                <td id="postTitleCurrent" class="d-inline-block col-8">{{r.post_title}}</td>
                <td class="d-inline-block col-2">
                <button type="button" id="button-all-questions" class="btn btn-default btn-sm btn-success" @click.prevent="test(r.post_title)">
                    <span class="fa fa-pencil"></span> Edit
                </button>
                <button type="button" class="btn btn-default btn-sm btn-danger">
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
                customIndex: 0
            }
        },
        computed: {
            filteredRows: function(){
                var filter_name = this.filter_name.toLowerCase();

                return ( this.filter_name.trim() !== '' ) ?
                    this.rows.filter(function(d){ return d.post_title.toLowerCase().indexOf(filter_name) > -1; }) :
                    this.rows;
            },
            pageStart: function(){
                return (this.currPage - 1) * this.countOfPage;
            },
            totalPage: function(){
                return Math.ceil(this.filteredRows.length / this.countOfPage);
            }
        },
        methods: {
            setPage: function(idx){
                if( idx <= 0 || idx > this.totalPage ){
                    return;
                }
                this.currPage = idx;
            },
            test(r) {
               console.log(r)
            }
        },
    }
</script>
