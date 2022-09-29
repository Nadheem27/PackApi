<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use App\Http\Controllers\CommonController;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use Illuminate\Support\Facades\Validator;

use App\Models\Books;

class AdminBooksController extends CommonController
{
    public function booksList(Request $request)
    {
        $search = $request->search['value'];

        $books = Books::orderBy('id', 'DESC');

        if($search != null) {
            $books->where(function($q) use ($search) {
                $q->search($search);
            });
        }
                
        $result['recordsTotal'] = $books->count();
        $result['recordsFiltered'] = $books->count();

        $books = $books->take($request->length)->skip($request->start)->get();
            
        $result['data'] = count($books) == 0 ? [] : $this->bookData($books);

        return response($this->returnResponse(0, 'Success', $result));
    }

    public function storeBook(StoreBookRequest $request)
    {
        $book = Books::create([
            'title' => $request->title,
            'author' => $request->author_name,
            'genre' => $request->genre,
            'description' => $request->description,
            'isbn' => $request->isbn,
            'image' => $this->imageStore('books/image', $request->image),
            'published' => $request->published_date,
            'publisher' => $request->publisher_name,
            'is_active' => $request->status
        ]);

        return response($this->returnResponse(0, 'Book Created Successfully'));
    }

    public function editBook(Request $request)
    {
        $rules = array(
            'id' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails())
            return response($this->returnResponse(3, $validator->messages()));

        $book = Books::find($request->id);

        if(is_null($book))
            return response($this->returnResponse(1, 'Book not Found'));        

        return response($this->returnResponse(0, 'Success', $this->singleBook($book)));
    }

    public function updateBook(UpdateBookRequest $request)
    {
        $book = Books::find($request->id);

        $book->update([
            'title' => $request->title,
            'author' => $request->author_name,
            'genre' => $request->genre,
            'description' => $request->description,
            'isbn' => $request->isbn,
            'image' => $request->hasFile('image') ? $this->imageStore('books/image', $request->image) : $book->image,
            'published' => $request->published_date,
            'publisher' => $request->publisher_name,
            'is_active' => $request->status
        ]);

        return response($this->returnResponse(0, 'Book Updated Successfully'));
    }

    public function deleteBook(Request $request)
    {
        $rules = array(
            'id' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails())
            return response($this->returnResponse(3, $validator->messages()));

        $book = Books::find($request->id);

        if(is_null($book))
            return response($this->returnResponse(1, 'Book not Found'));

        $book->delete();

        return response($this->returnResponse(0, 'Book deleted Successfully'));
    }

    private function bookData($books)
    {
        $return = array();

        foreach($books as $key => $book)
        {
            $return[] = array(
                'id' => $book->id,
                'title' => $book->title,
                'author' => $book->author,
                'isbn' => $book->isbn,
                'date' => date('Y-m-d', strtotime($book->published)),
                'publisher' => $book->publisher,
                'status' => $book->is_active,
                'image' => $this->imageUrl($book->image)
            );
        }

        return $return;
    }

    public function singleBook($book)
    {
        $data = array(
            'id' => $book->id,
            'title' => $book->title,
            'author_name' => $book->author,
            'genre' => $book->genre,
            'description' => $book->description,
            'isbn' => $book->isbn,
            'image' => $this->imageUrl($book->image),
            'published_date' => $book->published,
            'publisher_name' => $book->publisher,
            'status' => (string) $book->is_active
        );

        return $data;
    }
}
