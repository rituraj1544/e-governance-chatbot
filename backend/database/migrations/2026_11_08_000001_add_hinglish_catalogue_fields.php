<?php

use App\Support\HinglishCatalogueContent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('schemes', function (Blueprint $table) {
            $table->string('title_hinglish')->nullable();
            $table->text('description_hinglish')->nullable();
            $table->text('eligibility_hinglish')->nullable();
            $table->json('required_documents_hinglish')->nullable();
            $table->text('application_process_hinglish')->nullable();
        });

        Schema::table('faqs', function (Blueprint $table) {
            $table->text('question_hinglish')->nullable();
            $table->text('answer_hinglish')->nullable();
        });

        Schema::table('chat_histories', function (Blueprint $table) {
            $table->string('language', 10)->default('en')->change();
        });

        foreach (HinglishCatalogueContent::schemes() as $key => $content) {
            DB::table('schemes')
                ->where('translation_key', $key)
                ->where('language', 'en')
                ->update([
                    'title_hinglish' => $content['title'],
                    'description_hinglish' => $content['description'],
                ]);
        }

        foreach (HinglishCatalogueContent::faqs() as $englishQuestion => $content) {
            DB::table('faqs')
                ->where('language', 'en')
                ->where('question', $englishQuestion)
                ->update([
                    'question_hinglish' => $content['question'],
                    'answer_hinglish' => $content['answer'],
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('schemes', function (Blueprint $table) {
            $table->dropColumn([
                'title_hinglish', 'description_hinglish', 'eligibility_hinglish',
                'required_documents_hinglish', 'application_process_hinglish',
            ]);
        });

        Schema::table('faqs', function (Blueprint $table) {
            $table->dropColumn(['question_hinglish', 'answer_hinglish']);
        });

        Schema::table('chat_histories', function (Blueprint $table) {
            $table->string('language', 5)->default('en')->change();
        });
    }
};
