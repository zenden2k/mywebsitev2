<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;

class Page extends Model
{
    use HasFactory;
    protected $fillable = ['title_ru', 'alias'];
}
