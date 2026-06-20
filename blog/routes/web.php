<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CmsController;
use App\Http\Controllers\Cms\ArticleController as CmsArticleController;
use App\Http\Controllers\Cms\CommentController as CmsCommentController;
use App\Http\Controllers\Cms\UserController as CmsUserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/detail/{id}', [ArticleController::class, 'detail']);
Route::get('/articles/delete/{id}', [ArticleController::class, 'delete']);
Route::get('/articles/edit/{id}', [ArticleController::class, 'edit']);

Route::get('/', [ArticleController::class, 'index']);

Route::post('/articles/create', [ArticleController::class, 'create']);
Route::get('/articles/add', [ArticleController::class, 'add']);
Route::put('/articles/update/{id}', [ArticleController::class, 'update']);

Route::get('/cms', [CmsController::class, 'index']);
Route::get('/cms/articles', [CmsArticleController::class, 'index']);
Route::get('/cms/articles/edit/{id}', [CmsArticleController::class, 'edit']);
Route::put('/cms/articles/update/{id}', [CmsArticleController::class, 'update']);
Route::delete('/cms/articles/delete/{id}', [CmsArticleController::class, 'delete']);
Route::get('/cms/categories', [CategoryController::class, 'index']);
Route::get('/cms/categories/add', [CategoryController::class, 'add']);
Route::post('/cms/categories/create', [CategoryController::class, 'create']);
Route::get('/cms/categories/edit/{id}', [CategoryController::class, 'edit']);
Route::put('/cms/categories/update/{id}', [CategoryController::class, 'update']);
Route::delete('/cms/categories/delete/{id}', [CategoryController::class, 'delete']);
Route::get('/cms/comments', [CmsCommentController::class, 'index']);
Route::get('/cms/comments/edit/{id}', [CmsCommentController::class, 'edit']);
Route::put('/cms/comments/update/{id}', [CmsCommentController::class, 'update']);
Route::delete('/cms/comments/delete/{id}', [CmsCommentController::class, 'delete']);
Route::get('/cms/users', [CmsUserController::class, 'index']);
Route::get('/cms/users/edit/{id}', [CmsUserController::class, 'edit']);
Route::put('/cms/users/update/{id}', [CmsUserController::class, 'update']);
Route::delete('/cms/users/delete/{id}', [CmsUserController::class, 'delete']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);

Route::post('/comments/create', [CommentController::class, 'create']);
Route::get('/comments/delete/{id}', [CommentController::class, 'delete']);

Route::get('/users/{id}', [UserController::class, 'show']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
