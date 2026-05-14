<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <x-heroicon-o-puzzle-piece class="h-8 w-8 text-blue-500"/>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        Integrazioni di Terze Parti
                    </h3>
                    <p class="text-sm text-gray-500">
                        Configura e gestisci le integrazioni con servizi esterni
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            <!-- Stripe -->
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                <x-heroicon-o-credit-card class="h-5 w-5 text-white"/>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-lg font-medium text-gray-900">Stripe</h4>
                            <p class="text-sm text-gray-500">Pagamenti online</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $data['stripe_enabled'] ?? false ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $data['stripe_enabled'] ?? false ? 'Attivo' : 'Inattivo' }}
                    </span>
                </div>
                <button 
                    wire:click="testConnection('stripe')"
                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200"
                >
                    Testa Connessione
                </button>
            </div>

            <!-- PayPal -->
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 bg-yellow-500 rounded-lg flex items-center justify-center">
                                <x-heroicon-o-banknotes class="h-5 w-5 text-white"/>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-lg font-medium text-gray-900">PayPal</h4>
                            <p class="text-sm text-gray-500">Pagamenti PayPal</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $data['paypal_enabled'] ?? false ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $data['paypal_enabled'] ?? false ? 'Attivo' : 'Inattivo' }}
                    </span>
                </div>
                <button 
                    wire:click="testConnection('paypal')"
                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-yellow-700 bg-yellow-100 hover:bg-yellow-200"
                >
                    Testa Connessione
                </button>
            </div>

            <!-- Mailchimp -->
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 bg-red-500 rounded-lg flex items-center justify-center">
                                <x-heroicon-o-envelope class="h-5 w-5 text-white"/>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-lg font-medium text-gray-900">Mailchimp</h4>
                            <p class="text-sm text-gray-500">Email marketing</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $data['mailchimp_enabled'] ?? false ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $data['mailchimp_enabled'] ?? false ? 'Attivo' : 'Inattivo' }}
                    </span>
                </div>
                <button 
                    wire:click="testConnection('mailchimp')"
                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200"
                >
                    Testa Connessione
                </button>
            </div>

            <!-- Aruba FIC -->
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 bg-green-500 rounded-lg flex items-center justify-center">
                                <x-heroicon-o-document-text class="h-5 w-5 text-white"/>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-lg font-medium text-gray-900">Aruba FIC</h4>
                            <p class="text-sm text-gray-500">Fatturazione elettronica</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $data['aruba_fic_enabled'] ?? false ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $data['aruba_fic_enabled'] ?? false ? 'Attivo' : 'Inattivo' }}
                    </span>
                </div>
                <button 
                    wire:click="testConnection('aruba_fic')"
                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200"
                >
                    Testa Connessione
                </button>
            </div>

            <!-- Fatture in Cloud -->
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 bg-purple-500 rounded-lg flex items-center justify-center">
                                <x-heroicon-o-cloud class="h-5 w-5 text-white"/>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-lg font-medium text-gray-900">Fatture in Cloud</h4>
                            <p class="text-sm text-gray-500">Gestione fatture</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $data['fatture_cloud_enabled'] ?? false ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $data['fatture_cloud_enabled'] ?? false ? 'Attivo' : 'Inattivo' }}
                    </span>
                </div>
                <button 
                    wire:click="testConnection('fatture_cloud')"
                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-purple-700 bg-purple-100 hover:bg-purple-200"
                >
                    Testa Connessione
                </button>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h4 class="text-lg font-medium text-gray-900">Configurazione</h4>
            </div>
            <div class="p-6">
                {{ $this->form }}
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
                            Le chiavi API vengono memorizzate in modo sicuro. Utilizza sempre le chiavi di produzione per l'ambiente live 
                            e le chiavi di test per l'ambiente di sviluppo.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>