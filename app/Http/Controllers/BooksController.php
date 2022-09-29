<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use App\Http\Controllers\AdminBooksController;
use Illuminate\Support\Facades\Validator;

use App\Models\Books;
use GuzzleHttp\Client;

class BooksController extends AdminBooksController
{
    public function insert()
    {
        $client = new Client();
        $request = $client->get('https://fakerapi.it/api/v1/books?_quantity=100');
        $response = $request->getBody()->getContents();

        $data = json_decode($response);

        if($data->status == 'OK' && $data->code == 200)
        {
            foreach($data->data as $record)
            {
                $book = Books::create([
                    'title' => $record->title,
                    'author' => $record->author,
                    'genre' => $record->genre,
                    'description' => $record->description,
                    'isbn' => $record->isbn,
                    'image' => null,
                    'published' => $record->published,
                    'publisher' => $record->publisher,
                    'is_active' => 1
                ]);
            }
        }

        return response($this->returnResponse(0, 'Record inserted Successfully'));
    }

    public function bookList(Request $request)
    {
        $search = $request->search;
        $books = Books::where('is_active', 1)->orderBy('id', 'DESC');

        if($search != null) {
            $books->where(function($q) use ($search) {
                $q->search($search);
            });
        }

        if(isset($request->start) && $request->start != null)
            $books->where('published', '>=', $request->start);

        if(isset($request->end) && $request->end != null)
            $books->where('published', '<=', $request->end);

        $books = $books->get();

        $data = count($books) == 0 ? [] : $this->bookData($books);

        return response($this->returnResponse(0, 'Success', $this->paginate($data)));
    }

    public function getBook(Request $request)
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

    private function bookData($books)
    {
        $return = array();

        foreach($books as $book)
        {
            $return[] = array(
                'id' => $book->id,
                'title' => $book->title,
                'image' => $this->imageUrl($book->image),
                'author' => $book->author,
                'date' => date('Y-m-d', strtotime($book->published)),
                'isbn' => $book->isbn
            );
        }

        return $return;
    }
}
