<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatHistory extends Model
{
    protected $fillable = [
        'user_id', 'matched_scheme_id', 'matched_faq_id', 'session_id', 'query', 'response', 'intent', 'language', 'confidence_score',
    ];

    protected function casts(): array
    {
        return ['confidence_score' => 'float'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scheme()
    {
        return $this->belongsTo(Scheme::class, 'matched_scheme_id');
    }

    public function faq()
    {
        return $this->belongsTo(Faq::class, 'matched_faq_id');
    }
}
