<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth")->except(["show"]);
    }

    public function index()
    {
        $this->authorizeCms();

        return view("cms.categories.index", [
            "categories" => Category::latest()->get(),
        ]);
    }

    public function add()
    {
        $this->authorizeCms();

        return view("cms.categories.add");
    }

    public function show(string $id)
    {
        $category = Category::findOrFail($id);
        $articles = $category->articles()
            ->with(["user", "category", "comments"])
            ->latest()
            ->paginate(5);

        return view("articles.index", [
            "articles" => $articles,
            "categories" => Category::withCount("articles")->orderBy("name")->get(),
            "selectedCategory" => $category,
        ]);
    }

    public function create(Request $request)
    {
        $this->authorizeCms();

        $request->validate([
            "name" => "required|max:255",
        ]);

        $category = new Category;
        $category->name = $request->name;
        $category->save();

        return redirect("/cms/categories")->with("info", "Category is created");
    }

    public function edit(string $id)
    {
        $this->authorizeCms();

        return view("cms.categories.edit", [
            "category" => Category::findOrFail($id),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $this->authorizeCms();

        $request->validate([
            "name" => "required|max:255",
        ]);

        $category = Category::findOrFail($id);
        $category->name = $request->name;
        $category->save();

        return redirect("/cms/categories")->with("info", "Category is updated");
    }

    public function delete(string $id)
    {
        $this->authorizeCms();

        $category = Category::findOrFail($id);

        if($category->articles()->exists()) {
            return back()->with("info", "Cannot delete a category with articles");
        }

        $category->delete();

        return redirect("/cms/categories")->with("info", "Category is deleted");
    }

    private function authorizeCms()
    {
        abort_unless(auth()->user()->isAdmin(), 403);
    }
}
