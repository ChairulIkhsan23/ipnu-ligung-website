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
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('thumbnail')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
        
            // Total Views
            $table->unsignedInteger('views')->default(0);

            // Untuk realtime tracking
            $table->unsignedInteger('views_today')->default(0);
            $table->unsignedInteger('views_this_week')->default(0);
            $table->unsignedInteger('views_this_month')->default(0);
            $table->timestamp('last_viewed_at')->nullable();
            
            // Untuk trending calculation
            $table->decimal('view_velocity', 8, 2)->default(0)->comment('Views per hour');
            $table->string('og_image')->nullable();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('published_at')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->boolean('is_published')->default(true);
            $table->timestamps();
            
            $table->index(['status', 'published_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
