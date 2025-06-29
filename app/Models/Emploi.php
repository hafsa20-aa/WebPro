<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emploi extends Model {
    
    protected $fillable = ['filiere', 'niveau', 'semestre', 'annee'];

    public function seances(){
        return $this->hasMany(Seance::class);
    }
}
