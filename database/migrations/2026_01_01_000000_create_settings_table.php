<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Single flexible table for ALL settings types:
     * - Simple settings (site_name, email, etc.)
     * - Complex settings (partners, customers, team members, etc.)
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            // Grouping
            $table->string('group')->index(); // general, contact, social, partners, customers, team, etc.
            $table->string('key')->index(); // site_name, email, or entry type for complex data

            // Value storage (flexible for all data types)
            $table->json('value')->nullable(); // Simple value (string, number, boolean)
            $table->json('translations')->nullable(); // Multilingual values
            $table->json('meta')->nullable(); // Additional metadata (for complex structures)
            $table->json('extra_data')->nullable(); // For partners, customers, team members, etc.

            // Status & Visibility
            $table->boolean('is_public')->default(false)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('is_featured')->default(false)->index(); // For highlighting items

            // Ordering & Categorization
            $table->string('type')->default('default')->index(); // For categorization within group
            $table->integer('order')->default(0)->index(); // For sorting
            $table->timestamp('published_at')->nullable();

            // Audit Trail
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->unique(['group', 'key']);
            $table->index(['group', 'type']);
            $table->index(['group', 'is_active', 'order']);
            $table->index(['group', 'is_featured', 'order']);
            $table->index(['group', 'published_at']);

            // Foreign keys for audit trail (optional - only if users table exists)
            if (Schema::hasTable('users')) {
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
