<?php

use App\Http\Controllers\{AccessController, AdminController, AuthController, ChatController, SiteController};
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/news', [SiteController::class, 'news'])->name('news');
Route::post('/news/post', [SiteController::class, 'postNews'])->name('news.post');
Route::get('/news/{slug}', [SiteController::class, 'article'])->name('article');
Route::get('/branches/{branch:slug}', [SiteController::class, 'branch'])->name('branch');
Route::match(['get', 'post'], '/language/{locale}', [SiteController::class, 'language'])->name('language');
Route::post('/contact', [SiteController::class, 'contact'])->middleware('throttle:5,1')->name('contact');

Route::prefix('chat')->name('chat.')->middleware('throttle:60,1')->group(function () {
    Route::post('/init', [ChatController::class, 'init'])->name('init');
    Route::get('/messages', [ChatController::class, 'visitorMessages'])->name('messages');
    Route::post('/send', [ChatController::class, 'visitorSend'])->name('send');
});

Route::get('/login', [AuthController::class, 'form'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:30,1');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::middleware('permission:manage-content')->group(function () {
        // Header / Hero Section
        Route::get('/header', [AdminController::class, 'header'])->name('header');
        Route::post('/header', [AdminController::class, 'saveHeader'])->name('header.save');

        // Home Layout Blocks
        Route::get('/blocks', [AdminController::class, 'blocks'])->name('blocks.index');
        Route::get('/blocks/create', [AdminController::class, 'blockCreate'])->name('blocks.create');
        Route::post('/blocks', [AdminController::class, 'blockStore'])->name('blocks.store');
        Route::get('/blocks/{block}/edit', [AdminController::class, 'blockEdit'])->name('blocks.edit');
        Route::post('/blocks/{block}', [AdminController::class, 'blockUpdate'])->name('blocks.update');
        Route::delete('/blocks/{block}', [AdminController::class, 'blockDestroy'])->name('blocks.destroy');

        // News & Stories Management
        Route::get('/news', [AdminController::class, 'newsIndex'])->name('news.index');
        Route::get('/news/create', [AdminController::class, 'newsCreate'])->name('news.create');
        Route::post('/news', [AdminController::class, 'newsStore'])->name('news.store');
        Route::get('/news/{news}/edit', [AdminController::class, 'newsEdit'])->name('news.edit');
        Route::post('/news/{news}', [AdminController::class, 'newsUpdate'])->name('news.update');
        Route::delete('/news/{news}', [AdminController::class, 'newsDestroy'])->name('news.destroy');

        // Content
        Route::get('/content', [AdminController::class, 'index'])->name('content.index');
        Route::get('/content/data', [AdminController::class, 'data'])->name('content.data');
        Route::get('/content/create', [AdminController::class, 'create'])->name('content.create');
        Route::post('/content', [AdminController::class, 'store'])->name('content.store');
        Route::get('/content/{content}/edit', [AdminController::class, 'edit'])->name('content.edit');
        Route::post('/content/{content}', [AdminController::class, 'update'])->name('content.update');
        Route::delete('/content/{content}', [AdminController::class, 'destroy'])->name('content.destroy');

        // Branches
        Route::get('/branches', [AdminController::class, 'branches'])->name('branches.index');
        Route::get('/branches/create', [AdminController::class, 'branchCreate'])->name('branches.create');
        Route::post('/branches', [AdminController::class, 'branchStore'])->name('branches.store');
        Route::get('/branches/{branch}/edit', [AdminController::class, 'branchEdit'])->name('branches.edit');
        Route::post('/branches/{branch}', [AdminController::class, 'branchUpdate'])->name('branches.update');
        Route::delete('/branches/{branch}', [AdminController::class, 'branchDestroy'])->name('branches.destroy');

        // Site Settings
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::post('/settings', [AdminController::class, 'saveSettings'])->name('settings.save');
    });

    Route::middleware('permission:view-messages')->group(function () {
        Route::get('/chat', [ChatController::class, 'adminIndex'])->name('chat.index');
        Route::get('/chat/conversations', [ChatController::class, 'adminConversations'])->name('chat.conversations');
        Route::get('/chat/conversations/{conversation}', [ChatController::class, 'adminShow'])->name('chat.show');
        Route::post('/chat/conversations/{conversation}/reply', [ChatController::class, 'adminReply'])->name('chat.reply');
        Route::post('/chat/conversations/{conversation}/toggle-status', [ChatController::class, 'adminToggleStatus'])->name('chat.toggleStatus');
        Route::delete('/chat/conversations/{conversation}', [ChatController::class, 'adminDestroy'])->name('chat.destroy');

        Route::get('/messages', [AdminController::class, 'messages'])->name('messages');
        Route::delete('/messages/{message}', [AdminController::class, 'messageDestroy'])->name('messages.destroy');
    });

    Route::middleware('permission:manage-access')->group(function () {
        Route::get('/users', [AccessController::class, 'users'])->name('users');
        Route::post('/users', [AccessController::class, 'saveUser'])->name('users.save');
        Route::delete('/users/{user}', [AccessController::class, 'deleteUser'])->name('users.delete');
        Route::get('/roles', [AccessController::class, 'roles'])->name('roles');
        Route::post('/roles', [AccessController::class, 'saveRole'])->name('roles.save');
        Route::delete('/roles/{role}', [AccessController::class, 'deleteRole'])->name('roles.delete');
    });
});
