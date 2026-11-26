<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $search   = $request->query('search');
        $genre    = $request->query('genre');
        $featured = $request->query('featured');

        $sort = $request->query('sort', 'title');

        if (! in_array($sort, ['title', 'year'])) {
            $sort = 'title';
        }

        $query = Book::with('genre');

        if ($search) {
            $query = $query->where('title', 'like', "%{$search}%");
        }

        if ($genre) {
            $query = $query->where('genre_id', $genre);
        }

        if ($featured !== null && $featured !== '') {
            $query = $query->where('is_featured', $featured);
        }

        $books = $query->orderBy($sort)->paginate(8)->withQueryString();

        return view('books.index', [
            'books'  => $books,
            'genres' => Genre::all(),
            'sort'   => $sort,
        ]);
    }

    public function create()
    {
        Gate::authorize('create', Book::class);

        return view('books.create', [
            'genres' => Genre::all(),
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Book::class);

        $validated = $request->validate([
            'title'    => 'required|string|max:255',
            'author'   => 'required|string|max:255',
            'year'     => 'required|numeric|digits:4',
            'genre_id' => 'required|exists:genres,id',
        ]);

        $book = Book::create($validated + ['is_featured' => false]);

        Log::info('Book saved', [
            'id'    => $book->id,
            'title' => $book->title,
        ]);

        $total = Book::count();

        return redirect()->route('books.index')
            ->with('success', "Book added successfully. You now have {$total} books.");
    }

    public function show(Book $book)
    {
        return view('books.show', ['book' => $book]);
    }

    public function edit(Book $book)
    {
        Gate::authorize('update', $book);

        $genres = Genre::all();

        return view('books.edit', compact('book', 'genres'));
    }

    public function update(Request $request, Book $book)
    {
        Gate::authorize('update', $book);

        $validated = $request->validate([
            'title'    => 'required|string|max:255',
            'author'   => 'required|string|max:255',
            'year'     => 'required|numeric|digits:4',
            'genre_id' => 'required|exists:genres,id',
        ]);

        $book->update($validated);

        return redirect()->route('books.index')
            ->with('success', 'Book updated.');
    }

    public function destroy(Book $book)
    {
        Gate::authorize('delete', $book);

        abort_if($book->genre->books()->count() === 1, 403);

        $book->delete();

        return redirect()->route('books.index')
            ->with('success', 'Book deleted.');
    }

    public function feature()
    {
        return view('books.feature', [
            'book' => Book::with('genre')->where('is_featured', true)->firstOrFail(),
        ]);
    }

    public function redirectOldFilter(?string $genre = null)
    {
        return redirect()->route('books.index', $genre ? ['genre' => $genre] : []);
    }
}