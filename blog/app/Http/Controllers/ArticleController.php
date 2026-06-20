<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth")->except(['index', 'detail']);
    }

    public function index()
    {
        $data = Article::with(["user", "category", "comments"])->latest()->paginate(5);

        return view("articles.index", [
            'articles' => $data,
            "categories" => Category::withCount("articles")->orderBy("name")->get(),
            "selectedCategory" => null,
        ]);
    }

    public function detail(string $id)
    {
        $article = Article::findOrFail($id);

        return view("articles.detail", [
            "article" => $article,
        ]);
    }

    public function add()
    {
        return view("articles.add", [
            "categories" => Category::all(),
        ]);
    }

    public function create()
    {
        $validator = validator(request()->all(), [
            "title" => "required",
            "body" => "required",
            "category_id" => "required",
            "feature_image" => "nullable|image|max:2048",
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $article = new Article;
        $article->title = request()->title;
        $article->body = request()->body;
        $article->category_id = request()->category_id;
        $article->user_id = Auth::id();

        if(request()->hasFile("feature_image")) {
            $article->feature_image = request()->file("feature_image")->store("articles", "public");
        }

        $article->save();

        return redirect("/articles");
    }

    public function edit(string $id)
    {
        $article = Article::findOrFail($id);

        if(!Gate::allows("update-article", $article)) {
            return back()->with("info", "Unauthorized to edit");
        }

        return view("articles.edit", [
            "article" => $article,
            "categories" => Category::all(),
        ]);
    }

    public function update(string $id)
    {
        $article = Article::findOrFail($id);

        if(!Gate::allows("update-article", $article)) {
            return back()->with("info", "Unauthorized to edit");
        }

        $validator = validator(request()->all(), [
            "title" => "required",
            "body" => "required",
            "category_id" => "required|exists:categories,id",
            "feature_image" => "nullable|image|max:2048",
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $article->title = request()->title;
        $article->body = request()->body;
        $article->category_id = request()->category_id;

        if(request()->hasFile("feature_image")) {
            if($article->feature_image) {
                Storage::disk("public")->delete($article->feature_image);
            }

            $article->feature_image = request()->file("feature_image")->store("articles", "public");
        }

        $article->save();

        return redirect("/articles/detail/$article->id")->with("info", "Article is updated");
    }

    public function delete(string $id)
    {
        $article = Article::findOrFail($id);

        if(Gate::allows("delete-article", $article)) {
            if($article->feature_image) {
                Storage::disk("public")->delete($article->feature_image);
            }

            $article->delete();
            return redirect("/articles")->with("info", "An article is deleted");
        } else {
            return back()->with("info", "Unauthorized to delete");
        }
    }
}
