<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    public $timestamps = false;
    protected $fillable = ['full_name', 'school'];

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }
}
