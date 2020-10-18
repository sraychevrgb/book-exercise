<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BookController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $loggedInUser = auth()->user();
        $bookExist = Book::where('user_id', $loggedInUser->id)
                            ->where('google_api_id', $request->book['google_api_id'])
                            ->first();

        if( $bookExist ){
            return response()->json(['failure'=> "Book already exist."], 200);
        }
        else{
            $book = Book::create([
                'user_id'=> $loggedInUser->id,
                'title' => $request->book['title'],
                'publish_date'=> $request->book['publish_date'],
                'google_api_id' => $request->book['google_api_id']
            ]);
    
            if( $book ){
                return response()->json(['success'=> "Book saved.", "book"=> $book], 200);
            }
            else{
                return response()->json(['failure'=> "Book not saved."], 200);
            }
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        $bookInfo = null;
        $response = Http::get('https://www.googleapis.com/books/v1/volumes/'.$book->google_api_id);
        if( $response->successful() ){
            $bookInfo = $response->json();
            return view('bookDetails', compact('bookInfo'));
        }
        else{
            abort(404);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        $loggedInUser = auth()->user();

        //check if the book we are trting to delete actually belongs to the logged in user
        if( $book->user_id == $loggedInUser->id ){
            $book->delete();
            return response()->json(['success'=> "Book has been deleted."], 200);
        }
        else{
            return response()->json(['failure'=> "This book does not belong to you!"], 200);
        }

    }


    public function updateBooksSortOrderInBulk(Request $request){
        $loggedInUser = auth()->user();

        if( isset($request->sorted_books) ){
            foreach ($request->sorted_books as $sorted_book) {
                $book = Book::where('id', $sorted_book['id'])
                            ->where('user_id', $loggedInUser->id)
                            ->first();
                
                //if we have a match then there is such a book that belongs to the logged in user
                if( $book ){
                    $book->sort_order = $sorted_book['sort_order'];
                    $book->save();
                }
                //else we do nothing we keep looping
            }
            return response()->json(['success'=> "Books sorted."], 200);
        }
        else{
            return response()->json(['failure'=> "No books to sort."], 200);
        }

    }
}
