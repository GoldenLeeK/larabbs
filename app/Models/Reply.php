<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reply extends Model {
    use HasFactory;

    protected $fillable = ['content'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }


}
