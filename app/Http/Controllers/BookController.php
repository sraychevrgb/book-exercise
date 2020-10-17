<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;

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
}
