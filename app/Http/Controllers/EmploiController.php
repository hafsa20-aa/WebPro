<?php

namespace App\Http\Controllers;

use App\Models\Emploi;
use App\Models\Seance;
use Illuminate\Http\Request;

class EmploiController extends Controller {

    public function index(){
        $emplois = Emploi::with('seances')->get();
        return view('list_emploi', compact('emplois'));
    }

    public function create(){ 
        return view('ajouter'); 
    }

    public function masse(){ 
        // return view('masse'); 
        $filieres = Emploi::distinct()->pluck('filiere');
        return view('masse', compact('filieres'));
    }

    public function store(Request $request){
        // Validation for required fields
        $request->validate([
            'filiere' => 'required',
            'semestre' => 'required',
            'annee' => 'required',
            'niveau' => 'required',
        ], [
            'filiere.required' => 'La filière est requise.',
            'semestre.required' => 'Le semestre est requis.',
            'annee.required' => 'L\'année est requise.',
            'niveau.required' => 'Le niveau est requis.',
        ]);

        // Create the emploi with validated data
        $emploi = Emploi::create($request->only('filiere', 'niveau', 'semestre', 'annee'));

        $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
        $creneaux = ['08h00 à 10h00', '10h15 à 12h15', '14h00 à 16h00', '16h15 à 18h15'];

        foreach ($jours as $jour) {
            foreach ($creneaux as $i => $creneau) {
                $emploi->seances()->create([
                    'jour' => $jour,
                    'creneau' => $creneau,
                    'matiere' => $request->input("matiere_{$jour}_{$i}"),
                    'nature_ens' => $request->input("nature_ens_{$jour}_{$i}"),
                    // 'nature_ens' => json_encode($request->input("nature_ens_{$jour}_{$i}", [])),
                    'professeur' => $request->input("professeur_{$jour}_{$i}"),
                    'salle' => $request->input("salle_{$jour}_{$i}"), 
                    'semaine_range' => $request->input("semaine_range_{$jour}_{$i}"),
                    'nombre_semaines' => $request->input("nombre_semaine_{$jour}_{$i}"),
                    'duree' => $request->input("duree_{$jour}_{$i}"),
                ]);
            }
        }

        return redirect()->route('emplois.index');
    }

    public function show(string $id){}

    public function edit(Emploi $emploi){
        $seances = $emploi->seances;
        return view('ajouter', compact('emploi', 'seances'));
    }

    public function update(Request $request, Emploi $emploi)
    {
        // Validation for required fields
        $request->validate([
            'filiere' => 'required',
            'semestre' => 'required',
            'annee' => 'required',
            'niveau' => 'required',
        ], [
            'filiere.required' => 'La filière est requise.',
            'semestre.required' => 'Le semestre est requis.',
            'annee.required' => 'L\'année est requise.',
            'niveau.required' => 'Le niveau est requis.',
        ]);

        // Update the emploi with validated data
        $emploi->update($request->only('filiere', 'niveau', 'semestre', 'annee'));

        // Delete existing seances and recreate them
        $emploi->seances()->delete();

        $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
        $creneaux = ['08h00 à 10h00', '10h15 à 12h15', '14h00 à 16h00', '16h15 à 18h15'];

        foreach ($jours as $jour) {
            foreach ($creneaux as $i => $creneau) {
                $emploi->seances()->create([
                    'jour' => $jour,
                    'creneau' => $creneau,
                    'matiere' => $request->input("matiere_{$jour}_{$i}"),
                    'nature_ens' => $request->input("nature_ens_{$jour}_{$i}"),
                    // 'nature_ens' => json_encode($request->input("nature_ens_{$jour}_{$i}", [])),
                    'professeur' => $request->input("professeur_{$jour}_{$i}"),
                    'salle' => $request->input("salle_{$jour}_{$i}"), 
                    'semaine_range' => $request->input("semaine_range_{$jour}_{$i}"),
                    'nombre_semaines' => $request->input("nombre_semaine_{$jour}_{$i}"),
                    'duree' => $request->input("duree_{$jour}_{$i}"),
                ]);
            }
        }

        return redirect()->route('emplois.index');
    }

    public function destroy(Emploi $emploi){
        $emploi->seances()->delete();
        $emploi->delete();
        return redirect()->route('emplois.index')->with('success', 'Emploi supprimé');
    }

















public function masseHoraireForm()
{
    $filieres = Emploi::distinct()->pluck('filiere');
    return view('masse', compact('filieres'));
}

public function getNiveaux($filiere)
{
    $niveaux = Emploi::where('filiere', $filiere)->distinct()->pluck('niveau');
    return response()->json($niveaux);
}

public function getSemestres($filiere, $niveau)
{
    $semestres = Emploi::where('filiere', $filiere)->where('niveau', $niveau)->distinct()->pluck('semestre');
    return response()->json($semestres);
}

// public function getMatieres($filiere, $niveau, $semestre){
//     $emploi = Emploi::where(compact('filiere', 'niveau', 'semestre'))->first();
//     $matieres = [];
//     if ($emploi) {
//         $matieres = $emploi->seances->pluck('matiere')->unique()->values();
//     }
//     return response()->json($matieres);
// }



public function getMatieres($filiere, $niveau, $semestre)
{
    $emploi = Emploi::where(compact('filiere', 'niveau', 'semestre'))->first();
    $matieresData = [];

    if ($emploi) {
        $seancesGrouped = $emploi->seances->groupBy('matiere');
        foreach ($seancesGrouped as $matiere => $seances) {
            $total = 0;
            foreach ($seances as $seance) {
                $total += ($seance->duree ?? 0) * ($seance->nombre_semaines ?? 0);
            }
            $matieresData[] = [
                'matiere' => $matiere,
                'total' => $total
            ];
        }
    }

    return response()->json($matieresData);
}





    public function calculateMasseHoraire(Request $request) {
        
        $request->validate([
            'filiere' => 'required',
            'niveau' => 'required',
            'semestre' => 'required',
            'matiere' => 'required',
        ]);

        $emploi = Emploi::where([
            'filiere' => $request->filiere,
            'niveau' => $request->niveau,
            'semestre' => $request->semestre,
        ])->first();

        $totalMasseHoraire = 0;

        if ($emploi) {
            $seances = $emploi->seances->where('matiere', $request->matiere);

            foreach ($seances as $seance) {
                $totalMasseHoraire += ($seance->duree ?? 0) * ($seance->nombre_semaines ?? 0);
            }
        }

        return back()->with([
            'totalHours' => $totalMasseHoraire,
            'selectedMatiere' => $request->matiere
        ]);
    }


}
