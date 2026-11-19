<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('blocks', function (Blueprint $t) {
            $t->id();
            $t->string('section')->index();
            $t->string('key')->unique();
            $t->json('title');
            $t->json('subtitle')->nullable();
            $t->json('body')->nullable();
            $t->string('image')->nullable();
            $t->string('icon')->nullable();
            $t->string('link_url')->nullable();
            $t->json('link_text')->nullable();
            $t->json('extra')->nullable();
            $t->boolean('published')->default(true);
            $t->unsignedInteger('sort_order')->default(0);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocks');
    }
};
