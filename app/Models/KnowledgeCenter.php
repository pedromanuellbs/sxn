<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnowledgeCenter extends Model
{
    
    protected $table = 'knowledge_center';

  
    protected $fillable = [
        'title',
        'status',
        'size',
        'file_path',
    ];
}
