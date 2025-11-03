<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ToeicScore extends Model
{
        use HasFactory;

    protected $table = 'toeic_scores';

       protected $fillable = ['pdf', 'user_id'];


       public function user()
{
    return $this->belongsTo(User::class);
}


}

