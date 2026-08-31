<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SavedOffer extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'offer_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function offer()
    {
        return $this->belongsTo(JobOpportunityOffer::class, 'offer_id');
    }
}
