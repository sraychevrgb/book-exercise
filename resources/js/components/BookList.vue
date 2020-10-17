<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Book List</div>

                    <div class="card-body">
                        <!-- show add button-->
                        <div>
                            <input type="text" placeholder="Type a book name..." v-model='search_term' />
                            <button type="button" class="btn btn-primary" @click="search">Search</button>
                        </div>
                        <!-- show search results-->
                        <div v-if="search_results && search_results.length > 0">
                            Books found:
                            <ul class="list-group">
                                <li v-for="result in search_results" :key="result.id" class="list-group-item">
                                    {{ result.volumeInfo.title }}, {{ result.volumeInfo.publishedDate }}
                                    <button type="button" class="btn btn-success float-right" @click="addBook(result.volumeInfo.title, result.volumeInfo.publishedDate, result.id)">Add</button>
                                </li>
                            </ul>
                        </div>
                        <br>

                        <!-- show user list -->
                        <div v-if="books && books.length > 0" >
                            My list:
                            <ul class="list-group">
                                <li v-for="book in books" :key="book.title" class="list-group-item">
                                    {{ book.title }}, {{ book.publish_date }}
                                    <button type="button" class="btn btn-danger float-right" @click="deleteBook(book.id)">Delete</button>
                                </li>
                            </ul>
                        </div>
                        <div v-else >
                            There currently no books in your list.
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [ 'books_url', 'passed_books'],
        data: function() {
            return {
                books: this.passed_books,
                search_term: null,
                search_results: []
            }
        },
        mounted() {
            //console.log(this.books);
        },
        methods:{
            search: function() {
                //Google books API doesn not require API key or token for search, so we just pass the search string to the endpoint
                //If it did, then we would set API key in the .env file and attach it to the request
                axios.get('https://www.googleapis.com/books/v1/volumes?q='+encodeURI(this.search_term))
                .then( response => {
                    this.search_results = response.data.items;
                })
                .catch( (error) => {
                    this.$root.$emit("showDangerTopMessage", "Somewthing went wrong and we could not find any books right now.");
                    this.$root.isLoading = false;
                });

            },
            addBook: function(title, publish_date, id){
                var book = {title:title, publish_date:publish_date, google_api_id:id};

                //send book to database
                axios.post( this.books_url, {
                    book: book
                })
                .then( response => {
                    if('failure' in response.data){
                        this.$root.$emit("showDangerTopMessage", response.data.failure);
                    }
                    else{
                        //this.books.push(book);
                        //instead of pushing book push the book coming froms erver as it has the database id which is later used 
                        //for the laravel destroy route
                        this.books.push(response.data.book);
                        this.search_term = '';
                        this.search_results = [];
                        this.$root.$emit("showSuccessNotificationTopMessage", "Book has been saved.");
                    }
                })
                .catch( (error) => {
                    this.$root.$emit("showDangerTopMessage", "Somewthing went wrong and we can not save your book.");
                    this.$root.isLoading = false;
                    this.search_term = '';
                    this.search_results = [];
                });
            },
            deleteBook: function(id){
                axios.post( this.books_url+'/'+id, {
                    _method: "DELETE"
                })
                .then( response => {
                    if('failure' in response.data){
                        this.$root.$emit("showDangerTopMessage", response.data.failure);
                    }
                    else{
                        this.books.splice(this.books.findIndex(function(i){
                            return i.id == id;
                        }), 1);
                        this.$root.$emit("showSuccessNotificationTopMessage", "Book has been deleted.");
                    }
                })
                .catch( (error) => {
                    this.$root.$emit("showDangerTopMessage", "Somewthing went wrong and we can not delete your book.");
                    this.$root.isLoading = false;
                });
            }
        }
    }
</script>
