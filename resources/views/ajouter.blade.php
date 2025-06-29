<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("Ajouter un Nouveau Emploi") }}
                </div>



    <div class="text-[13px] w-full leading-[20px] flex-1 p-6 pb-12 lg:p-20 bg-white">
        <h1 class="mb-2 font-medium text-blue-600">
            {{ isset($emploi) ? 'Modifier l\'emploi' : 'Ajouter un emploi' }}
        </h1>
        <form method="POST" action="{{ isset($emploi) ? route('emploi.update', $emploi->id) : route('emploi.store') }}">
            @csrf
            @if(isset($emploi))
                @method('PUT')
            @endif
            <div class="filter-container">
                <label for="filiere">Filière : </label>
                <input type="text" name="filiere" value="{{ old('filiere', $emploi->filiere ?? '') }}" placeholder="Enter Filière" required>
                @error('filiere')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
                <label for="niveau">Niveau : </label>
                <input type="text" name="niveau" value="{{ old('niveau', $emploi->niveau ?? '') }}" placeholder="Enter Niveau" required>
                @error('niveau')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
                <label for="semestre">Semestre : </label>
                <input type="text" id="semestre" name="semestre" value="{{ old('semestre', $emploi->semestre ?? '') }}" placeholder="Enter Semestre" required>
                @error('semestre')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
                <label for="annee">Année universitaire : </label>
                <input type="text" id="annee" name="annee" value="{{ old('annee', $emploi->annee ?? '') }}" placeholder="Enter Année universitaire" required>
                @error('annee')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div><br/>
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-300 p-1">Jour</th>
                        <th class="border border-gray-300 p-1">Infos</th>
                        <th class="border border-gray-300 p-1">08h00 à 10h00</th>
                        <th class="border border-gray-300 p-1">10h15 à 12h15</th>
                        <th class="border border-gray-300 p-1">14h00 à 16h00</th>
                        <th class="border border-gray-300 p-1">16h15 à 18h15</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
                        $creneaux = ['08h00 à 10h00', '10h15 à 12h15', '14h00 à 16h00', '16h15 à 18h15'];
                        $seanceData = [];
                        if (isset($seances)) {
                            foreach ($seances as $seance) {
                                $seanceData[$seance->jour][$seance->creneau] = $seance;
                            }
                        }
                    @endphp
                    @foreach ($jours as $day)
                        <tr class="border-t-2 border-gray-500">
                            <td class="border border-gray-300 bg-gray-200 p-2" rowspan="7">{{ $day }}</td>
                            <td class="border border-gray-300 p-2">Matière</td>
                            @foreach ($creneaux as $i => $creneau)
                                @php
                                    $s = $seanceData[$day][$creneau] ?? null;
                                @endphp
                                <td class="border border-gray-300 p-2">
                                    <input type="text" name="matiere_{{ $day }}_{{ $i }}" value="{{ old("matiere_{$day}_{$i}", $s->matiere ?? '') }}" class="block w-full p-2 border border-gray-300 rounded-md" placeholder="Matière">
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-2">Nature Ens</td>
                            @foreach ($creneaux as $i => $creneau)
                                @php
                                    $s = $seanceData[$day][$creneau] ?? null;
                                    $nature = $s?->nature_ens ?? [];
                                @endphp
                                <td class="border border-gray-300 p-2">
                                    <label><input type="checkbox" name="nature_ens_{{ $day }}_{{ $i }}[]" value="Cours" {{ in_array('Cours', $nature) ? 'checked' : '' }}> Cours</label><br/>
                                    <label><input type="checkbox" name="nature_ens_{{ $day }}_{{ $i }}[]" value="TD" {{ in_array('TD', $nature) ? 'checked' : '' }}> TD</label><br/>
                                    <label><input type="checkbox" name="nature_ens_{{ $day }}_{{ $i }}[]" value="TP" {{ in_array('TP', $nature) ? 'checked' : '' }}> TP</label>
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-2">Professeur</td>
                            @foreach ($creneaux as $i => $creneau)
                                @php
                                    $s = $seanceData[$day][$creneau] ?? null;
                                @endphp
                                <td class="border border-gray-300 p-2">
                                    <input type="text" name="professeur_{{ $day }}_{{ $i }}" value="{{ old("professeur_{$day}_{$i}", $s->professeur ?? '') }}" class="block w-full p-2 border border-gray-300 rounded-md" placeholder="Professeur">
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-2">Salle</td>
                            @foreach ($creneaux as $i => $creneau)
                                @php
                                    $s = $seanceData[$day][$creneau] ?? null;
                                @endphp
                                <td class="border border-gray-300 p-2">
                                    <input type="text" name="salle_{{ $day }}_{{ $i }}" value="{{ old("salle_{$day}_{$i}", $s->salle ?? '') }}" class="block w-full p-2 border border-gray-300 rounded-md" placeholder="Salle">
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-2">Semaine Range</td>
                            @foreach ($creneaux as $i => $creneau)
                                @php
                                    $s = $seanceData[$day][$creneau] ?? null;
                                @endphp
                                <td class="border border-gray-300 p-2">
                                    <input type="text" name="semaine_range_{{ $day }}_{{ $i }}" value="{{ old("semaine_range_{$day}_{$i}", $s->semaine_range ?? '') }}" class="block w-full p-2 border border-gray-300 rounded-md" placeholder="ex: s1-s10">
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-2">Nb Semaines</td>
                            @foreach ($creneaux as $i => $creneau)
                                @php
                                    $s = $seanceData[$day][$creneau] ?? null;
                                @endphp
                                <td class="border border-gray-300 p-2">
                                    <input type="number" name="nombre_semaine_{{ $day }}_{{ $i }}" value="{{ old("nombre_semaine_{$day}_{$i}", $s->nombre_semaines ?? '') }}" class="block w-full p-2 border border-gray-300 rounded-md" placeholder="Nb Semaines">
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-2">Durée (h)</td>
                            @foreach ($creneaux as $i => $creneau)
                                @php
                                    $s = $seanceData[$day][$creneau] ?? null;
                                @endphp
                                <td class="border border-gray-300 p-2">
                                    <input type="number" name="duree_{{ $day }}_{{ $i }}" value="{{ old("duree_{$day}_{$i}", $s->duree ?? '') }}" class="block w-full p-2 border border-gray-300 rounded-md" placeholder="Durée (h)">
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Submit Button -->
            <div class="mt-4 text-center">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-400">
                    {{ isset($emploi) ? 'Mettre à jour' : 'Ajouter' }}
                </button>
            </div>
        </form>
    </div>

            </div>
        </div>
    </div>
</x-app-layout>
