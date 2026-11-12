<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scheme extends Model
{
    protected $fillable = [
        'translation_key', 'title', 'category', 'description', 'eligibility',
        'required_documents', 'application_process', 'title_hinglish', 'description_hinglish',
        'eligibility_hinglish', 'required_documents_hinglish', 'application_process_hinglish',
        'official_source_url', 'keywords', 'language', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'required_documents' => 'array',
            'required_documents_hinglish' => 'array',
            'keywords' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function chatHistories()
    {
        return $this->hasMany(ChatHistory::class, 'matched_scheme_id');
    }
}
