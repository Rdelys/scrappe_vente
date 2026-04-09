<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiGeneration extends Model
{
    protected $fillable = [
        'user_id',
        'network',
        'prompt',
        'generated_text'
    ];

    public function images()
    {
        return $this->hasMany(AiGenerationImage::class, 'generation_id');
    }
}