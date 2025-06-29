<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("Liste des Emplois") }}
                </div>

                <div class="p-6 lg:p-20 bg-white text-[13px] leading-[20px] w-full">
                    @foreach ($emplois as $emploi)
                        <!-- Emploi Header Info -->
                        <div class="mb-4 p-4 border rounded-lg bg-[#d9e5eb] flex justify-between items-center">
                            <div>
                                <div class="ml-5"><strong>Filière :</strong> {{ $emploi->filiere }}</div>
                                <div class="ml-5"><strong>Niveau :</strong> {{ $emploi->niveau }}</div>
                                <div class="ml-5"><strong>Semestre :</strong> {{ $emploi->semestre }}</div>
                                <div class="ml-5"><strong>Année :</strong> {{ $emploi->annee }}</div>
                            </div>
                            @if (Auth::user()->usertype !== 'user')
                                <div class="text-right mr-5">
                                    <a href="{{ route('emplois.edit', $emploi->id) }}" class="text-[#1f75bc] mr-4 p-2 rounded-md hover:bg-[#1f75bc] hover:text-white">✎ Modifier</a>
                                    <form action="{{ route('emplois.destroy', $emploi->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Voulez-vous vraiment supprimer cet emploi ?')" class="text-red-600 p-2 hover:bg-red-500 hover:text-white hover:rounded-md">
                                            × Supprimer
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>

                        <!-- Emploi Table -->
                        <div class="overflow-auto mb-10">
                            <table class="w-full border-collapse border border-gray-300 text-center">
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
                                        foreach ($emploi->seances as $seance) {
                                            $seanceData[$seance->jour][$seance->creneau] = $seance;
                                        }
                                    @endphp

                                    @foreach ($jours as $day)
                                        <tr class="border-t-2 border-gray-500 bg-gray-100">
                                            <td class="border border-gray-400 p-3 bg-[#82a7d0]" rowspan="7"><b>{{ $day }}</b></td>
                                            <td class="border border-gray-400 bg-[#b6d9ed]"><b>Matière</b></td>
                                            @foreach ($creneaux as $creneau)
                                                @php $s = $seanceData[$day][$creneau] ?? null; @endphp
                                                <td class="border border-gray-400 bg-[#b6d9ed]">{{ $s->matiere ?? '-' }}</td>
                                            @endforeach
                                        </tr>
                                        <tr class="bg-gray-50">
                                            <td class="border border-gray-400">Nature Ens</td>
                                            @foreach ($creneaux as $creneau)
                                                @php $s = $seanceData[$day][$creneau] ?? null; @endphp
                                                <td class="border border-gray-400">
                                                    {{ $s && is_array($s->nature_ens) ? implode(', ', $s->nature_ens) : '-' }}
                                                </td>
                                            @endforeach
                                        </tr>
                                        <tr class="bg-gray-100">
                                            <td class="border border-gray-400">Professeur</td>
                                            @foreach ($creneaux as $creneau)
                                                @php $s = $seanceData[$day][$creneau] ?? null; @endphp
                                                <td class="border border-gray-400">{{ $s->professeur ?? '-' }}</td>
                                            @endforeach
                                        </tr>
                                        <tr class="bg-gray-50">
                                            <td class="border border-gray-400">Salle</td>
                                            @foreach ($creneaux as $creneau)
                                                @php $s = $seanceData[$day][$creneau] ?? null; @endphp
                                                <td class="border border-gray-400">{{ $s->salle ?? '-' }}</td>
                                            @endforeach
                                        </tr>
                                        <tr class="bg-gray-100">
                                            <td class="border border-gray-400">Semaine Range</td>
                                            @foreach ($creneaux as $creneau)
                                                @php $s = $seanceData[$day][$creneau] ?? null; @endphp
                                                <td class="border border-gray-400">{{ $s->semaine_range ?? '-' }}</td>
                                            @endforeach
                                        </tr>
                                        <tr class="bg-gray-50">
                                            <td class="border border-gray-400">Nb Semaines</td>
                                            @foreach ($creneaux as $creneau)
                                                @php $s = $seanceData[$day][$creneau] ?? null; @endphp
                                                <td class="border border-gray-400">{{ $s->nombre_semaines ?? '-' }}</td>
                                            @endforeach
                                        </tr>
                                        <tr class="bg-gray-50">
                                            <td class="border border-gray-400">Durée</td>
                                            @foreach ($creneaux as $creneau)
                                                @php $s = $seanceData[$day][$creneau] ?? null; @endphp
                                                <td class="border border-gray-400">{{ $s->duree ?? '-' }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                    <hr>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
