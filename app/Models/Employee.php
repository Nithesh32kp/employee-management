<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Employee extends Model
{
    protected $fillable = [
        'employee_id',
        'firstname',
        'lastname',
        'date_of_birth',
        'education_qualification',
        'address',
        'email',
        'phone',
        'photo',
        'resume',
    ];

    protected function diskName(): string
    {
        $d = config('filesystems.default');
        return $d === 'local' ? 'public' : $d;
    }

    public function getPhotoUrlAttribute()
    {
        return $this->photo ? Storage::disk($this->diskName())->url($this->photo) : null;
    }

    public function getResumeUrlAttribute()
    {
        return $this->resume ? Storage::disk($this->diskName())->url($this->resume) : null;
    }
}