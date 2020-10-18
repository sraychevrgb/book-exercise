<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\User;
use App\Book;

class BookTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function logged_out_users_will_be_redirected(){
        
        $response = $this->get('/home')->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_users_can_see_book_list(){
        
        $this->actingAs( factory(User::class)->create() );
        $response = $this->get('/home')->assertOK();
    }

    /** @test */
    public function a_book_can_be_saved_to_database(){
        
        $this->actingAs( factory(User::class)->create() );
        $response = $this->post( '/books', [
            'book' => [
                'title' => "My book",
                'publish_date' => "2020",
                'google_api_id' => "xxxxxxxxx"
            ]
        ] );
        $this->assertCount( 1, Book::all() );
    }

    /** @test */
    public function a_book_can_be_deleted_from_database(){
    
        $this->actingAs( factory(User::class)->create() );

        $response = $this->post( '/books', [
            'book' => [
                'title' => "My book",
                'publish_date' => "2020",
                'google_api_id' => "xxxxxxxxx"
            ]
        ] );
        $this->assertCount( 1, Book::all() );

        //so far we have a book added, now delete it
        $response = $this->delete( '/books/1' );
        $this->assertCount( 0, Book::all() );
    }

    /** @test */
    public function a_list_of_books_can_be_sorted(){
    
        $this->actingAs( factory(User::class)->create() );

        //Add three books in decrementing alphabetical order
        $response = $this->post( '/books', [
            'book' => [
                'title' => "Z book",
                'publish_date' => "2020",
                'google_api_id' => "x10"
            ]
        ] );
        $response = $this->post( '/books', [
            'book' => [
                'title' => "S book",
                'publish_date' => "2020",
                'google_api_id' => "x11"
            ]
        ] );
        $response = $this->post( '/books', [
            'book' => [
                'title' => "A book",
                'publish_date' => "2020",
                'google_api_id' => "x12"
            ]
        ] );
        $this->assertCount( 3, Book::all() );

        //so far we have a 3 books
        $response = $this->post( '/books/update-books-order', [
            'sorted_books' => [
                [
                    'id'=>1, //first book be last
                    'sort_order'=>3
                ],
                [
                    'id'=>2,
                    'sort_order'=>2 //second books stays the same
                ],
                [
                    'id'=>3,
                    'sort_order'=>1 //third books be first
                ]
            ]
        ] );

        $this->assertDatabaseHas('books', ['title' => 'A book', 'sort_order'=> 1]);
        $this->assertDatabaseHas('books', ['title' => 'S book', 'sort_order'=> 2]);
        $this->assertDatabaseHas('books', ['title' => 'Z book', 'sort_order'=> 3]);
    }

    /** @test */
    public function book_details_endpoint_can_retrieve_api_data(){

        $this->actingAs( factory(User::class)->create() );

        $response = $this->post( '/books', [
            'book' => [
                'title' => "Maugli",
                'publish_date' => "2013-01",
                'google_api_id' => "QfhKcNWyUccC"
            ]
        ] );
        $this->assertCount( 1, Book::all() );

        //so far we have a book added, see if we can load the book details
        $response = $this->get('/books/1')->assertStatus(200); 
        $response = $this->get('/books/1')->assertSeeText("Maugli");
    }

}
