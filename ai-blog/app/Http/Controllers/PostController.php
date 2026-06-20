<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Post::class);

        $posts = Post::with(['user', 'category'])
            ->latest()
            ->paginate(9);

        return view('posts.index', compact('posts'));
    }

    public function create(): View
    {
        $this->authorize('create', Post::class);

        $categories = Category::orderBy('name')->get();

        return view('posts.create', compact('categories'));
    }

    public function store(StorePostRequest $request): RedirectResponse
    {
        $post = Post::create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
        ]);

        return redirect()->route('posts.show', $post)->with('success', 'Post created.');
    }

    public function show(Post $post): View
    {
        $this->authorize('view', $post);

        $post->load(['user', 'category', 'comments.user']);

        return view('posts.show', compact('post'));
    }

    public function edit(Post $post): View
    {
        $this->authorize('update', $post);

        $categories = Category::orderBy('name')->get();

        return view('posts.edit', compact('post', 'categories'));
    }

    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        $post->update($request->validated());

        return redirect()->route('posts.show', $post)->with('success', 'Post updated.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $this->authorize('delete', $post);

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted.');
    }
}
