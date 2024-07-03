<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(App\Models\Category::class)->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('author');
            $table->string('user')->nullable();
            $table->string('image')->nullable();
            $table->text('content');
            $table->string('tags');
            $table->tinyInteger('status')->default(0)->comment('0=draft, 1=published');
            $table->string('comments_count')->default(0);
            $table->string('views_count')->default(0);
            $table->string('likes_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
