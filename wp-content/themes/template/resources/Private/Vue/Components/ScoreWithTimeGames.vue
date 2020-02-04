<template>
    <div class="container mt-4">

        <table class="table-border-properties table table-warning">
            <thead>
            <tr class="row m-0">
                <th class="d-inline-block col-12">
                    <div class="has-search">
                        <Search></Search>
                        <input type="text" id="table-search-form" class="search form-control" placeholder="Meno alebo priezvisko"  v-model="filter_name">
                    </div>
                </th>
            </tr>
            <tr class="row m-0 text-primary table-header-bottom-border">
                <td class="d-inline-block col-sm-12 col-md-1">#</td>
                <td class="d-inline-block col-sm-12  col-md-3">Hráč</td>
                <td class="d-inline-block col-md">Správne odpovede</td>
                <td class="d-inline-block col-md">Nesprávne odpovede</td>
                <td class="d-inline-block col-md">Čas</td>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(row, index) in filteredRows.slice(pageStart, pageStart + countOfPage)" class="row m-0">
                <td class="d-inline-block col-sm-12 col-md-1 text-info">{{ (currPage-1) * countOfPage + index + 1 }}</td>
                <td class="d-inline-block col-sm-12 col-md-3">{{row.name+" "+row.surname}}</td>
                <td class="d-inline-block col-sm-12 col-md">{{row.count_good_answers}}</td>
                <td class="d-inline-block col-sm-12 col-md">{{row.count_bad_answers}}</td>
                <td class="d-inline-block col-sm-12 col-md">{{row.time}}</td>
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
    import Search from "../../../../views/images/search-solid.svg";

    export default {
        props: [
            'table'
        ],
        data() {
            return {
                rows: '',
                countOfPage: 4,
                currPage: 1,
                filter_name: '',
                customIndex: 0,
                show: false,
                set: false,
            }
        },
        components: {
            Search
        },
        created() {
            if(this.table === 'top_players_memory_game') {
                this.rows = window.winnersMemoryGame;
            }

            else {
                this.rows = window.winnersPuzzleGame;
            }
        },
        computed: {
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
        },
    }
</script>
