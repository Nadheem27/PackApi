<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Books extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'books';
    public $timestamps = true;

    protected $dates = ['deleted_at'];
    protected $fillable = array('title', 'author', 'genre', 'description', 'isbn', 'image', 'published', 'publisher', 'is_active');

    public function scopeSearch($query, $search)
    {
        return $query->where('title', 'LIKE', '%'.$search.'%')
                    ->orWhere('author', 'LIKE', '%'.$search.'%')
                    ->orWhere('genre', 'LIKE', '%'.$search.'%')
                    ->orWhere('isbn', 'LIKE', '%'.$search.'%')
                    ->orWhere('publisher', 'LIKE', '%'.$search.'%');
    }
}
