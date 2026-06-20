<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArticleCategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_article_owner_can_update_article(): void
    {
        $user = User::factory()->create();
        $category = $this->createCategory("News");
        $newCategory = $this->createCategory("Tech");
        $article = $this->createArticle($user, $category);

        $response = $this->actingAs($user)->put("/articles/update/$article->id", [
            "title" => "Updated title",
            "body" => "Updated body",
            "category_id" => $newCategory->id,
        ]);

        $response->assertRedirect("/articles/detail/$article->id");
        $this->assertDatabaseHas("articles", [
            "id" => $article->id,
            "title" => "Updated title",
            "body" => "Updated body",
            "category_id" => $newCategory->id,
        ]);
    }

    public function test_article_can_be_created_with_feature_image(): void
    {
        Storage::fake("public");

        $user = User::factory()->create();
        $category = $this->createCategory("News");

        $response = $this->actingAs($user)->post("/articles/create", [
            "title" => "Article with image",
            "body" => "Article body",
            "category_id" => $category->id,
            "feature_image" => UploadedFile::fake()->image("feature.jpg", 1200, 675),
        ]);

        $response->assertRedirect("/articles");

        $article = Article::where("title", "Article with image")->firstOrFail();
        $this->assertNotNull($article->feature_image);
        Storage::disk("public")->assertExists($article->feature_image);
    }

    public function test_article_update_replaces_feature_image(): void
    {
        Storage::fake("public");

        $user = User::factory()->create();
        $category = $this->createCategory("News");
        $article = $this->createArticle($user, $category);
        $article->feature_image = UploadedFile::fake()->image("old.jpg")->store("articles", "public");
        $article->save();
        $oldPath = $article->feature_image;

        $response = $this->actingAs($user)->put("/articles/update/$article->id", [
            "title" => "Updated title",
            "body" => "Updated body",
            "category_id" => $category->id,
            "feature_image" => UploadedFile::fake()->image("new.jpg", 1200, 675),
        ]);

        $response->assertRedirect("/articles/detail/$article->id");

        $article->refresh();
        $this->assertNotEquals($oldPath, $article->feature_image);
        Storage::disk("public")->assertMissing($oldPath);
        Storage::disk("public")->assertExists($article->feature_image);
    }

    public function test_article_without_feature_image_uses_placeholder(): void
    {
        $user = User::factory()->create();
        $category = $this->createCategory("News");
        $article = $this->createArticle($user, $category);

        $response = $this->get("/articles/detail/$article->id");

        $response->assertOk();
        $response->assertSee("images/article-placeholder.svg");
    }

    public function test_article_body_is_rendered_as_markdown(): void
    {
        $user = User::factory()->create();
        $category = $this->createCategory("News");
        $article = $this->createArticle($user, $category);
        $article->body = "## Heading\n\nThis is **bold** text.";
        $article->save();

        $response = $this->get("/articles/detail/$article->id");

        $response->assertOk();
        $response->assertSee("<h2>Heading</h2>", false);
        $response->assertSee("<strong>bold</strong>", false);
    }

    public function test_user_profile_shows_user_info_and_articles(): void
    {
        $user = User::factory()->create([
            "name" => "Profile User",
            "email" => "profile@example.com",
        ]);
        $otherUser = User::factory()->create();
        $category = $this->createCategory("News");
        $article = $this->createArticle($user, $category);
        $otherArticle = $this->createArticle($otherUser, $category);
        $article->title = "Profile article";
        $article->save();
        $otherArticle->title = "Other article";
        $otherArticle->save();

        $response = $this->get("/users/$user->id");

        $response->assertOk();
        $response->assertSee("Profile User");
        $response->assertSee("profile@example.com");
        $response->assertSee("Profile article");
        $response->assertDontSee("Other article");
    }

    public function test_authenticated_nav_has_profile_in_account_dropdown(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get("/");

        $response->assertOk();
        $response->assertSee("dropdown-menu");
        $response->assertSee("My Profile");
        $response->assertSee("/users/$user->id");
    }

    public function test_login_redirects_to_home_page(): void
    {
        $user = User::factory()->create([
            "email" => "login@example.com",
            "password" => "password",
        ]);

        $response = $this->post("/login", [
            "email" => $user->email,
            "password" => "password",
        ]);

        $response->assertRedirect("/");
    }

    public function test_article_pages_link_to_user_profile(): void
    {
        $user = User::factory()->create();
        $category = $this->createCategory("News");
        $article = $this->createArticle($user, $category);

        $response = $this->get("/articles/detail/$article->id");

        $response->assertOk();
        $response->assertSee("/users/$user->id");
    }

    public function test_home_page_has_category_browser_and_bottom_paginator(): void
    {
        $user = User::factory()->create();
        $category = $this->createCategory("News");
        $this->createArticle($user, $category);

        $response = $this->get("/");

        $response->assertOk();
        $response->assertSee("category-browser");
        $response->assertSee("/categories/$category->id");
        $response->assertSee("All Categories");
    }

    public function test_category_page_filters_articles(): void
    {
        $user = User::factory()->create();
        $news = $this->createCategory("News");
        $tech = $this->createCategory("Tech");
        $newsArticle = $this->createArticle($user, $news);
        $techArticle = $this->createArticle($user, $tech);
        $newsArticle->title = "News article";
        $newsArticle->save();
        $techArticle->title = "Tech article";
        $techArticle->save();

        $response = $this->get("/categories/$news->id");

        $response->assertOk();
        $response->assertSee("Browsing News");
        $response->assertSee("News article");
        $response->assertDontSee("Tech article");
    }

    public function test_non_owner_cannot_update_article(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $category = $this->createCategory("News");
        $article = $this->createArticle($owner, $category);

        $response = $this
            ->actingAs($otherUser)
            ->from("/articles/detail/$article->id")
            ->put("/articles/update/$article->id", [
                "title" => "Changed by another user",
                "body" => "Changed body",
                "category_id" => $category->id,
            ]);

        $response->assertRedirect("/articles/detail/$article->id");
        $this->assertDatabaseMissing("articles", [
            "id" => $article->id,
            "title" => "Changed by another user",
        ]);
    }

    public function test_admin_can_manage_categories_in_cms(): void
    {
        $user = User::factory()->create(["role" => "admin"]);

        $createResponse = $this->actingAs($user)->post("/cms/categories/create", [
            "name" => "Tutorials",
        ]);
        $createResponse->assertRedirect("/cms/categories");

        $category = Category::where("name", "Tutorials")->firstOrFail();

        $updateResponse = $this->actingAs($user)->put("/cms/categories/update/$category->id", [
            "name" => "Guides",
        ]);
        $updateResponse->assertRedirect("/cms/categories");
        $this->assertDatabaseHas("categories", [
            "id" => $category->id,
            "name" => "Guides",
        ]);

        $deleteResponse = $this->actingAs($user)->delete("/cms/categories/delete/$category->id");
        $deleteResponse->assertRedirect("/cms/categories");
        $this->assertDatabaseMissing("categories", [
            "id" => $category->id,
        ]);
    }

    public function test_regular_user_cannot_access_cms(): void
    {
        $user = User::factory()->create(["role" => "user"]);

        $this->actingAs($user)->get("/cms")->assertForbidden();
        $this->actingAs($user)->get("/cms/categories")->assertForbidden();
        $this->actingAs($user)->get("/cms/articles")->assertForbidden();
        $this->actingAs($user)->get("/cms/comments")->assertForbidden();
        $this->actingAs($user)->get("/cms/users")->assertForbidden();
    }

    public function test_admin_can_manage_articles_in_cms(): void
    {
        $admin = User::factory()->create(["role" => "admin"]);
        $author = User::factory()->create();
        $category = $this->createCategory("News");
        $newCategory = $this->createCategory("Tech");
        $article = $this->createArticle($author, $category);

        $this->actingAs($admin)->get("/cms/articles")->assertOk();

        $updateResponse = $this->actingAs($admin)->put("/cms/articles/update/$article->id", [
            "title" => "CMS updated article",
            "body" => "CMS updated body",
            "category_id" => $newCategory->id,
        ]);

        $updateResponse->assertRedirect("/cms/articles");
        $this->assertDatabaseHas("articles", [
            "id" => $article->id,
            "title" => "CMS updated article",
            "category_id" => $newCategory->id,
        ]);

        $deleteResponse = $this->actingAs($admin)->delete("/cms/articles/delete/$article->id");

        $deleteResponse->assertRedirect("/cms/articles");
        $this->assertDatabaseMissing("articles", [
            "id" => $article->id,
        ]);
    }

    public function test_admin_can_manage_comments_in_cms(): void
    {
        $admin = User::factory()->create(["role" => "admin"]);
        $user = User::factory()->create();
        $category = $this->createCategory("News");
        $article = $this->createArticle($user, $category);
        $comment = $this->createComment($user, $article);

        $this->actingAs($admin)->get("/cms/comments")->assertOk();

        $updateResponse = $this->actingAs($admin)->put("/cms/comments/update/$comment->id", [
            "content" => "CMS updated comment",
        ]);

        $updateResponse->assertRedirect("/cms/comments");
        $this->assertDatabaseHas("comments", [
            "id" => $comment->id,
            "content" => "CMS updated comment",
        ]);

        $deleteResponse = $this->actingAs($admin)->delete("/cms/comments/delete/$comment->id");

        $deleteResponse->assertRedirect("/cms/comments");
        $this->assertDatabaseMissing("comments", [
            "id" => $comment->id,
        ]);
    }

    public function test_admin_can_manage_users_in_cms(): void
    {
        $admin = User::factory()->create(["role" => "admin"]);
        $user = User::factory()->create(["role" => "user"]);

        $this->actingAs($admin)->get("/cms/users")->assertOk();

        $updateResponse = $this->actingAs($admin)->put("/cms/users/update/$user->id", [
            "name" => "Managed User",
            "email" => "managed@example.com",
            "role" => "admin",
        ]);

        $updateResponse->assertRedirect("/cms/users");
        $this->assertDatabaseHas("users", [
            "id" => $user->id,
            "name" => "Managed User",
            "email" => "managed@example.com",
            "role" => "admin",
        ]);

        $deleteResponse = $this->actingAs($admin)->delete("/cms/users/delete/$user->id");

        $deleteResponse->assertRedirect("/cms/users");
        $this->assertDatabaseMissing("users", [
            "id" => $user->id,
        ]);
    }

    public function test_category_with_articles_cannot_be_deleted(): void
    {
        $user = User::factory()->create(["role" => "admin"]);
        $category = $this->createCategory("News");
        $this->createArticle($user, $category);

        $response = $this
            ->actingAs($user)
            ->from("/cms/categories")
            ->delete("/cms/categories/delete/$category->id");

        $response->assertRedirect("/cms/categories");
        $this->assertDatabaseHas("categories", [
            "id" => $category->id,
        ]);
    }

    private function createCategory(string $name): Category
    {
        $category = new Category;
        $category->name = $name;
        $category->save();

        return $category;
    }

    private function createArticle(User $user, Category $category): Article
    {
        $article = new Article;
        $article->title = "Original title";
        $article->body = "Original body";
        $article->user_id = $user->id;
        $article->category_id = $category->id;
        $article->save();

        return $article;
    }

    private function createComment(User $user, Article $article): Comment
    {
        $comment = new Comment;
        $comment->content = "Original comment";
        $comment->user_id = $user->id;
        $comment->article_id = $article->id;
        $comment->save();

        return $comment;
    }
}
