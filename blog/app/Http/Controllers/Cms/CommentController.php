<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        $this->authorizeCms();

        return view("cms.comments.index", [
            "comments" => Comment::with(["user", "article"])->latest()->paginate(15),
        ]);
    }

    public function edit(string $id)
    {
        $this->authorizeCms();

        return view("cms.comments.edit", [
            "comment" => Comment::with(["user", "article"])->findOrFail($id),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $this->authorizeCms();

        $request->validate([
            "content" => "required",
        ]);

        $comment = Comment::findOrFail($id);
        $comment->content = $request->content;
        $comment->save();

        return redirect("/cms/comments")->with("info", "Comment is updated");
    }

    public function delete(string $id)
    {
        $this->authorizeCms();

        Comment::findOrFail($id)->delete();

        return redirect("/cms/comments")->with("info", "Comment is deleted");
    }

    private function authorizeCms()
    {
        abort_unless(auth()->user()->isAdmin(), 403);
    }
}
