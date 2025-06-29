<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seance extends Model {

    protected $casts = ['nature_ens' => 'array',];

    protected $fillable = [
        'emploi_id', 'jour', 'creneau', 'matiere', 'nature_ens', 
        'professeur','salle', 'semaine_range', 'nombre_semaines', 'duree'
    ];

    public function emploi(){
        return $this->belongsTo(Emploi::class);
    }
}
