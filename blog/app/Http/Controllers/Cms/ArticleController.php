<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        $this->authorizeCms();

        return view("cms.articles.index", [
            "articles" => Article::with(["user", "category", "comments"])->latest()->paginate(10),
        ]);
    }

    public function edit(string $id)
    {
        $this->authorizeCms();

        return view("cms.articles.edit", [
            "article" => Article::findOrFail($id),
            "categories" => Category::orderBy("name")->get(),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $this->authorizeCms();

        $request->validate([
            "title" => "required",
            "body" => "required",
            "category_id" => "required|exists:categories,id",
            "feature_image" => "nullable|image|max:2048",
        ]);

        $article = Article::findOrFail($id);
        $article->title = $request->title;
        $article->body = $request->body;
        $article->category_id = $request->category_id;

        if($request->hasFile("feature_image")) {
            if($article->feature_image) {
                Storage::disk("public")->delete($article->feature_image);
            }

            $article->feature_image = $request->file("feature_image")->store("articles", "public");
        }

        $article->save();

        return redirect("/cms/articles")->with("info", "Article is updated");
    }

    public function delete(string $id)
    {
        $this->authorizeCms();

        $article = Article::findOrFail($id);

        if($article->feature_image) {
            Storage::disk("public")->delete($article->feature_image);
        }

        $article->comments()->delete();
        $article->delete();

        return redirect("/cms/articles")->with("info", "Article is deleted");
    }

    private function authorizeCms()
    {
        abort_unless(auth()->user()->isAdmin(), 403);
    }
}
