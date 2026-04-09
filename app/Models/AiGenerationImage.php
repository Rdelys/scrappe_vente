<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiGenerationImage extends Model
{
    protected $fillable = [
        'generation_id',
        'image_path'
    ];

    public function generation()
    {
        return $this->belongsTo(AiGeneration::class);
    }
}