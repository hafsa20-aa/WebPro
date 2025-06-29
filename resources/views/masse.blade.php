<x-app-layout>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("Consulter Masse Horaire") }}
                </div>
                


<div class="p-6 lg:p-20 bg-white w-full text-sm">
    <h2 class="text-2xl font-bold mb-6">Calcul de la Masse Horaire des Matières</h2>

    <form id="masseForm" class="space-y-4">
        <!-- Filière -->
        <div>
            <label for="filiere" class="font-semibold">Filière :</label>
            <select id="filiere" name="filiere" required class="border p-2 w-full text-[#1f75bc]">
                <option value="">-- Sélectionner une filière --</option>
                @foreach ($filieres as $filiere)
                    <option value="{{ $filiere }}">{{ $filiere }}</option>
                @endforeach
            </select>
        </div>

        <!-- Niveau -->
        <div>
            <label for="niveau" class="font-semibold">Niveau :</label>
            <select id="niveau" name="niveau" required class="border p-2 w-full text-[#1f75bc]">
                <option value="">-- Choisissez une filière d'abord --</option>
            </select>
        </div>

        <!-- Semestre -->
        <div>
            <label for="semestre" class="font-semibold">Semestre :</label>
            <select id="semestre" name="semestre" required class="border p-2 w-full text-[#1f75bc]">
                <option value="">-- Choisissez un niveau d'abord --</option>
            </select>
        </div>
    </form>

    <!-- Table of Matieres -->
    <div id="matieresContainer" class="hidden mt-8">
        <h3 class="text-xl font-semibold mb-4">Liste des Matières et leur Masse Horaire</h3>
        <table class="w-full border-collapse mt-8 bg-green-100 border border-green-400 text-green-700 p-4 rounded text-left">
            <thead class="bg-green-200">
                <tr>
                    <th class="border p-2">Matière</th>
                    <th class="border p-2">Masse Horaire Totale (heures)</th>
                </tr>
            </thead>
            <tbody id="matieresTableBody">
                <!-- Filled by JavaScript -->
            </tbody>
        </table>
    </div>
</div>

<script>
    document.getElementById('filiere').addEventListener('change', function () {
        let filiere = this.value;
        fetch(`/get-niveaux/${filiere}`)
            .then(res => res.json())
            .then(data => {
                let niveauSelect = document.getElementById('niveau');
                niveauSelect.innerHTML = `<option value="">-- Choisissez --</option>`;
                data.forEach(niveau => {
                    niveauSelect.innerHTML += `<option value="${niveau}">${niveau}</option>`;
                });

                document.getElementById('semestre').innerHTML = `<option value="">-- Choisissez un niveau d'abord --</option>`;
                document.getElementById('matieresContainer').classList.add('hidden');
            });
    });

    document.getElementById('niveau').addEventListener('change', function () {
        let filiere = document.getElementById('filiere').value;
        let niveau = this.value;
        fetch(`/get-semestres/${filiere}/${niveau}`)
            .then(res => res.json())
            .then(data => {
                let semestreSelect = document.getElementById('semestre');
                semestreSelect.innerHTML = `<option value="">-- Choisissez --</option>`;
                data.forEach(semestre => {
                    semestreSelect.innerHTML += `<option value="${semestre}">${semestre}</option>`;
                });

                document.getElementById('matieresContainer').classList.add('hidden');
            });
    });

    document.getElementById('semestre').addEventListener('change', function () {
        let filiere = document.getElementById('filiere').value;
        let niveau = document.getElementById('niveau').value;
        let semestre = this.value;
        fetch(`/get-matieres/${filiere}/${niveau}/${semestre}`)
            .then(res => res.json())
            .then(data => {
                let tableBody = document.getElementById('matieresTableBody');
                tableBody.innerHTML = '';
                if (data.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="2" class="border p-2 text-red-600">Aucune matière trouvée.</td></tr>';
                } else {
                    data.forEach(item => {
                        tableBody.innerHTML += `
                            <tr>
                                <td class="border p-2">${item.matiere}</td>
                                <td class="border p-2">${item.total}</td>
                            </tr>
                        `;
                    });
                }
                document.getElementById('matieresContainer').classList.remove('hidden');
            });
    });
</script>

            </div>
        </div>
    </div>
</x-app-layout>
