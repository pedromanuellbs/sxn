<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'knowledge_source',
        'assistant_name',
        'tone',
        'additional_instruction',
        'language',
    ];

    /**
     * Relationship: Preference belongs to a User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
