<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <x-heroicon-o-squares-2x2 class="h-8 w-8 text-blue-500"/>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            Blocchi Globali
                        </h3>
                        <p class="text-sm text-gray-500">
                            Gestisci i blocchi di contenuto per le diverse sezioni del sito
                        </p>
                    </div>
                </div>
                <div class="min-w-0 flex-1 max-w-xs">
                    {{ $this->form }}
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h4 class="text-lg font-medium text-gray-900">
                    Blocchi per: {{ match($selectedLocation) {
                        'homepage' => 'Homepage',
                        'chi-siamo' => 'Chi Siamo',
                        'footer' => 'Footer',
                        'announcement_bar' => 'Barra Annunci',
                        default => $selectedLocation
                    } }}
                </h4>
            </div>

            @if($this->getBlocks()->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($this->getBlocks() as $block)
                        <div class="p-6 flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="h-2 w-2 bg-green-400 rounded-full"></div>
                                </div>
                                <div>
                                    <h5 class="text-sm font-medium text-gray-900">
                                        {{ $block['title'] }}
                                    </h5>
                                    <p class="text-sm text-gray-500">
                                        Tipo: 
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $block['type'] }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                <button 
                                    wire:click="toggleBlockActive({{ $block['id'] }})"
                                    class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md {{ $block['is_active'] ? 'text-green-700 bg-green-100 hover:bg-green-200' : 'text-gray-700 bg-gray-100 hover:bg-gray-200' }}"
                                >
                                    {{ $block['is_active'] ? 'Attivo' : 'Inattivo' }}
                                </button>
                                
                                <button 
                                    wire:click="editBlock({{ $block['id'] }})"
                                    class="inline-flex items-center px-3 py-1 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                                >
                                    Modifica
                                </button>
                                
                                <button 
                                    wire:click="deleteBlock({{ $block['id'] }})"
                                    class="inline-flex items-center px-3 py-1 border border-red-300 text-xs font-medium rounded-md text-red-700 bg-red-50 hover:bg-red-100"
                                >
                                    Elimina
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-12 text-center">
                    <x-heroicon-o-cube class="mx-auto h-12 w-12 text-gray-400"/>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Nessun blocco</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Inizia aggiungendo il primo blocco per questa sezione.
                    </p>
                </div>
            @endif
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <x-heroicon-o-information-circle class="h-5 w-5 text-yellow-400"/>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">
                        Nota
                    </h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>
                            I blocchi possono essere riordinati trascinandoli. Le modifiche saranno visibili immediatamente sul sito.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>