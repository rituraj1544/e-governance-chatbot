<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = [
        'question', 'answer', 'question_hinglish', 'answer_hinglish', 'category',
        'official_source_url', 'keywords', 'language', 'is_active',
    ];

    protected function casts(): array
    {
        return ['keywords' => 'array', 'is_active' => 'boolean'];
    }

    public function chatHistories()
    {
        return $this->hasMany(ChatHistory::class, 'matched_faq_id');
    }
}
