<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('roles', function(Blueprint $t){$t->id();$t->string('name')->unique();$t->string('slug')->unique();$t->timestamps();});
  Schema::create('permissions', function(Blueprint $t){$t->id();$t->string('name')->unique();$t->string('slug')->unique();$t->timestamps();});
  Schema::create('permission_role', function(Blueprint $t){$t->foreignId('role_id')->constrained()->cascadeOnDelete();$t->foreignId('permission_id')->constrained()->cascadeOnDelete();$t->primary(['role_id','permission_id']);});
  Schema::create('users', function(Blueprint $t){$t->id();$t->foreignId('role_id')->constrained()->restrictOnDelete();$t->string('name');$t->string('email')->unique();$t->timestamp('email_verified_at')->nullable();$t->string('password');$t->rememberToken();$t->timestamps();});
  Schema::create('password_reset_tokens', function(Blueprint $t){$t->string('email')->primary();$t->string('token');$t->timestamp('created_at')->nullable();});
  Schema::create('sessions', function(Blueprint $t){$t->string('id')->primary();$t->foreignId('user_id')->nullable()->index();$t->string('ip_address',45)->nullable();$t->text('user_agent')->nullable();$t->longText('payload');$t->integer('last_activity')->index();});
  Schema::create('branches', function(Blueprint $t){$t->id();$t->string('slug')->unique();$t->json('name');$t->json('address')->nullable();$t->json('description')->nullable();$t->string('phone')->nullable();$t->string('email')->nullable();$t->string('image')->nullable();$t->boolean('published')->default(false);$t->unsignedInteger('sort_order')->default(0);$t->timestamps();});
  Schema::create('contents', function(Blueprint $t){$t->id();$t->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();$t->string('type')->index();$t->string('slug')->unique();$t->json('title');$t->json('body')->nullable();$t->string('image')->nullable();$t->boolean('published')->default(false);$t->unsignedInteger('sort_order')->default(0);$t->timestamps();});
  Schema::create('settings', function(Blueprint $t){$t->id();$t->string('key')->unique();$t->json('value')->nullable();$t->timestamps();});
  Schema::create('messages', function(Blueprint $t){$t->id();$t->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();$t->string('name');$t->string('email');$t->string('phone')->nullable();$t->text('message');$t->timestamps();});
 }
 public function down(): void {foreach(['messages','settings','contents','branches','sessions','password_reset_tokens','users','permission_role','permissions','roles'] as $table) Schema::dropIfExists($table);}
};
