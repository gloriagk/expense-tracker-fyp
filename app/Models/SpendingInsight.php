<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpendingInsight extends Model
{
    protected $fillable = [
    'user_id',
    'insight_type',
    'description',
    'generated_date',
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
