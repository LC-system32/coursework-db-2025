<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    public $timestamps = false;
    protected $fillable = ['participant_id', 'score', 'language', 'code','submitted_at'];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

}
