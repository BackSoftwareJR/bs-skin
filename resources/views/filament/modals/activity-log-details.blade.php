<div class="space-y-4">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <h4 class="text-sm font-medium text-gray-900">Descrizione</h4>
            <p class="mt-1 text-sm text-gray-600">{{ $record->description }}</p>
        </div>
        <div>
            <h4 class="text-sm font-medium text-gray-900">Categoria</h4>
            <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                {{ $record->log_name }}
            </span>
        </div>
        <div>
            <h4 class="text-sm font-medium text-gray-900">Utente</h4>
            <p class="mt-1 text-sm text-gray-600">{{ $record->causer?->name ?? 'Sistema' }}</p>
        </div>
        <div>
            <h4 class="text-sm font-medium text-gray-900">Data</h4>
            <p class="mt-1 text-sm text-gray-600">{{ $record->created_at->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>

    @if($record->subject_type)
        <div>
            <h4 class="text-sm font-medium text-gray-900">Soggetto</h4>
            <div class="mt-1 text-sm text-gray-600">
                <span class="font-mono">{{ class_basename($record->subject_type) }}</span>
                @if($record->subject_id)
                    <span class="text-gray-500">#{{ $record->subject_id }}</span>
                @endif
            </div>
        </div>
    @endif

    @if($record->properties && $record->properties->count() > 0)
        <div>
            <h4 class="text-sm font-medium text-gray-900">Proprietà</h4>
            <div class="mt-1 bg-gray-50 rounded p-3">
                <pre class="text-xs text-gray-700 overflow-x-auto">{{ json_encode($record->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        </div>
    @endif
</div>