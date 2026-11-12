<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('schemes', function (Blueprint $table) {
            $table->id();
            $table->string('translation_key')->nullable()->index();
            $table->string('title');
            $table->string('category')->index();
            $table->text('description');
            $table->text('eligibility')->nullable();
            $table->json('required_documents')->nullable();
            $table->text('application_process')->nullable();
            $table->string('official_source_url')->nullable();
            $table->json('keywords')->nullable();
            $table->string('language', 5)->default('en')->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schemes');
    }
};
