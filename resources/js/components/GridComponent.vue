<template>
    <div v-if="results.length">
        <div class="nav" style="text-align:center;">
            <div style="width:25%; float:left; padding:30px;">
            <a href="/"><u>Go Back</u></a>
            </div> 
            <div style="width:50%; float:left;">
             <h1>Sorted By Coloum: {{sortByColumn}}</h1>
            </div>
            <div style="width:25%; float:left; padding-top:25px;">
            <button class="next" v-show="!isPaginated" @click="showPaginatedresult">Apply Pagination</button>  
            <p v-show="isPaginated">Showing {{itemPerPage*(currentPage-1)+1}} - {{currentPage*itemPerPage}} out of {{total}} </p>
            </div>
        </div>

        <div class="main">
            <div class="tbl-header">
                <table cellpadding="0" cellspacing="0" border="0">
                    <thead>
                        <tr>
                        <th v-for="(col, idx) in headers" :key="idx">
                            <button class="next"
                                type="button"
                                @click="sortTable(col)"
                                :disabled="checkDisable(col)">
                            {{col.toUpperCase()}}</button>
                        </th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="tbl-content">
                <table cellpadding="0" cellspacing="0" border="0">
                    <tbody>
                        <tr v-for="(result, idx) in results" :key="idx">
                            <td> {{ result.Code }} </td>
                            <td> {{ result.name }} </td>
                            <td> {{ result.latitude }} </td>
                            <td> {{ result.longitude }} </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="footer">
            <button class="previous" v-show="isPaginated && currentPage>1" @click="prevPage">&laquo; Previous</button> 
            <button class="next" v-show="isPaginated" @click="nextPage">Next &raquo;</button>
        </div>
    </div>
</template>

<script>
    export default{
        name: 'Grid',
        mounted (){
            console.log('Component mounted.');
            this.getStations();
        },
        data(){
            return {
                currentPage: 1,
                itemPerPage: null,
                sortByColumn: 'Code',
                ascending: true,
                headers:[],
                results: [],
                total: null,
                isDisable: false,
                sortableColumns: ['latitude', 'longitude'],
                isPaginated: false
            }
        },
        methods: {
            getStations: function() {

                let params = {
                    'pageNumber': this.currentPage,
                    'itemPerPage': this.itemPerPage,
                    'sortByColumn': this.sortByColumn,
                    'ascending': this.ascending
                }

                console.log("parmas", params);
                axios.get('/api/stations', {params: params}).then(response => {
                    console.log("ressss", response.data)
                    this.results = response.data.data;
                    this.headers = response.data.columns;
                    this.total = response.data.total;
                });
            },
            sortTable: function(col){

                this.sortByColumn = col;
                if (this.sortByColumn === col) {

                    this.ascending = !this.ascending;
                } 
                this.getStations();
            },
            showPaginatedresult: function(){
                this.isPaginated = true;
                this.itemPerPage = 10;
                this.getStations();
            },
            nextPage: function() {

                this.currentPage++;
                this.getStations();
            },
            prevPage: function() {

                if(this.currentPage > 1) this.currentPage--;
                this.getStations();
            },
            checkDisable: function(col){

                if(this.sortableColumns.includes(col)){
                    return true
                }
            }
        }
    }
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #000000;
  margin-top: 50px;
}
h1{
  font-size: 30px;
  color: #fff;
  text-transform: uppercase;
  font-weight: 300;
  text-align: center;
  margin-bottom: 15px;
}
table{
  width:100%;
  table-layout: fixed;
}
.tbl-header{
  background-color: rgba(255,255,255,0.3);
 }
.tbl-content{
  height:500px;
  overflow-x:auto;
  margin-top: 0px;
  border: 1px solid rgba(255,255,255,0.3);
}
th{
  padding: 20px 15px;
  text-align: left;
  font-weight: 500;
  font-size: 12px;
  color: #fff;
  text-transform: uppercase;
}
td{
  padding: 15px;
  text-align: left;
  vertical-align:middle;
  font-weight: 300;
  font-size: 12px;
  color: #fff;
  border-bottom: solid 1px rgba(255,255,255,0.1);
}

@import url(https://fonts.googleapis.com/css?family=Roboto:400,500,300,700);
body{
  background: -webkit-linear-gradient(left, #25c481, #25b7c4);
  background: linear-gradient(to right, #25c481, #25b7c4);
  font-family: 'Roboto', sans-serif;
}
section{
  margin: 50px;
}

.previous {
  background-color: #f1f1f1;
  color: black;
}

.next {
  background-color: #04AA6D;
  color: white;
}
button {
  text-decoration: none;
  display: inline-block;
  padding: 8px 16px;
  font-size: 16px;
}

.footer {
  text-align: center;
  width: 100%;
  padding: 20px;
}

button:hover {
  background-color: #ddd;
  color: black;
  cursor: pointer;
}
button:disabled,
button[disabled]{
  border: 1px solid #999999;
  background-color: #cccccc;
  color: #666666;
}
.nav{
    width:100%;
    display: inline-block;
    /* background-color: #1a212c; */
}

a{
    padding: 20px;
}
.sort{
    position: absolute;
    color: crimson;
    padding-left: 150px;
}

.main{
    padding-left: 20px;
    padding-right: 20px;
}

.center {
  padding-left: 25px;
  padding-right: 25px;
}
</style>
