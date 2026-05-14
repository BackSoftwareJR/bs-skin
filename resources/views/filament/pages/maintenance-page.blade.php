<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <x-heroicon-o-wrench-screwdriver class="h-8 w-8 text-blue-500"/>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        Strumenti di Manutenzione
                    </h3>
                    <p class="text-sm text-gray-500">
                        Gestisci la pulizia e manutenzione del sistema
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h4 class="text-md font-medium text-gray-900 mb-4">
                    Pulizia Dati
                </h4>
                <div class="space-y-3 text-sm text-gray-600">
                    <div class="flex items-center justify-between">
                        <span>• Codici OTP scaduti</span>
                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">Sicuro</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>• Carrelli abbandonati (>30gg)</span>
                        <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Attenzione</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>• Log attività (>90gg)</span>
                        <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Attenzione</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>• Sessioni scadute</span>
                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">Sicuro</span>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h4 class="text-md font-medium text-gray-900 mb-4">
                    Ottimizzazioni
                </h4>
                <div class="space-y-3 text-sm text-gray-600">
                    <div class="flex items-center justify-between">
                        <span>• Rigenerazione sitemap</span>
                        <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Consigliato</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>• Svuotamento cache</span>
                        <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">Critico</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <x-heroicon-o-exclamation-triangle class="h-5 w-5 text-yellow-400"/>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">
                        Avvertenza
                    </h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>
                            Le operazioni di manutenzione possono influire sulle prestazioni del sito. 
                            Si consiglia di eseguirle durante orari di basso traffico.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>