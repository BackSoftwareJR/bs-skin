<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <x-heroicon-o-document-magnifying-glass class="h-8 w-8 text-blue-500"/>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        Log Attività Sistema
                    </h3>
                    <p class="text-sm text-gray-500">
                        Visualizza tutte le attività registrate nel sistema
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <x-heroicon-o-information-circle class="h-5 w-5 text-blue-400"/>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">
                        Informazioni
                    </h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>
                            I log di attività vengono registrati automaticamente per tutte le operazioni importanti. 
                            Usa i filtri per cercare attività specifiche per data, utente o categoria.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{ $this->table }}
    </div>
</x-filament-panels::page>