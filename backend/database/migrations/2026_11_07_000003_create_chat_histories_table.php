<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chat_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('matched_scheme_id')->nullable()->constrained('schemes')->nullOnDelete();
            $table->foreignId('matched_faq_id')->nullable()->constrained('faqs')->nullOnDelete();
            $table->string('session_id', 100)->nullable()->index();
            $table->text('query');
            $table->text('response');
            $table->string('intent', 40)->nullable()->index();
            $table->string('language', 5)->default('en')->index();
            $table->decimal('confidence_score', 6, 5)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_histories');
    }
};
