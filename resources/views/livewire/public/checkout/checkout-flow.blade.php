<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if($step !== 'confirmation')
        <!-- Stepper -->
        <div class="mb-8">
            <nav aria-label="Progress">
                <ol role="list" class="flex items-center justify-center space-x-5">
                    @foreach(['address' => 'Indirizzo', 'shipping' => 'Spedizione', 'payment' => 'Pagamento', 'review' => 'Riepilogo'] as $stepKey => $stepLabel)
                        @php
                            $stepIndex = array_search($stepKey, $this->steps);
                            $isCurrent = $stepKey === $step;
                            $isCompleted = $currentStepIndex > $stepIndex;
                        @endphp
                        <li class="relative">
                            @if($isCompleted)
                                <div class="flex items-center">
                                    <span class="relative z-10 w-8 h-8 flex items-center justify-center bg-brand-primary rounded-full">
                                        <x-heroicon-m-check class="h-5 w-5 text-white" />
                                    </span>
                                    <span class="ml-3 text-sm font-medium text-neutral-900">{{ $stepLabel }}</span>
                                </div>
                            @elseif($isCurrent)
                                <div class="flex items-center">
                                    <span class="relative z-10 w-8 h-8 flex items-center justify-center border-2 border-brand-primary bg-white rounded-full">
                                        <span class="w-2.5 h-2.5 bg-brand-primary rounded-full"></span>
                                    </span>
                                    <span class="ml-3 text-sm font-medium text-brand-primary">{{ $stepLabel }}</span>
                                </div>
                            @else
                                <div class="flex items-center">
                                    <span class="relative z-10 w-8 h-8 flex items-center justify-center border-2 border-neutral-300 bg-white rounded-full">
                                        <span class="w-2.5 h-2.5 bg-neutral-300 rounded-full"></span>
                                    </span>
                                    <span class="ml-3 text-sm font-medium text-neutral-500">{{ $stepLabel }}</span>
                                </div>
                            @endif

                            @if(!$loop->last)
                                <div class="absolute top-4 left-4 -ml-px mt-0.5 h-full w-0.5 bg-neutral-300" aria-hidden="true"></div>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>
        </div>
    @endif

    <div class="lg:grid lg:grid-cols-12 lg:gap-12">
        <!-- Main content -->
        <div class="lg:col-span-7">
            @if($step === 'address')
                @include('livewire.public.checkout.steps.address')
            @elseif($step === 'shipping')
                @include('livewire.public.checkout.steps.shipping')
            @elseif($step === 'payment')
                @include('livewire.public.checkout.steps.payment')
            @elseif($step === 'review')
                @include('livewire.public.checkout.steps.review')
            @elseif($step === 'confirmation')
                @include('livewire.public.checkout.steps.confirmation')
            @endif
        </div>

        <!-- Order summary sidebar -->
        @if($step !== 'confirmation')
            <div class="lg:col-span-5 mt-8 lg:mt-0">
                <div class="bg-neutral-50 rounded-2xl p-6 sticky top-8">
                    <h2 class="text-lg font-semibold text-neutral-900 mb-4">Riepilogo ordine</h2>
                    
                    <!-- Items -->
                    <div class="space-y-3 mb-6">
                        @foreach($cartItems as $item)
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-white rounded-lg flex-shrink-0">
                                    @if($item->product->featured_image)
                                        <img src="{{ $item->product->featured_image }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover rounded-lg">
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-neutral-900 line-clamp-1">{{ $item->product->name }}</p>
                                    @if($item->variant)
                                        <p class="text-xs text-neutral-500">{{ $item->variant->name }}</p>
                                    @endif
                                    <p class="text-xs text-neutral-500">Qty: {{ $item->quantity }}</p>
                                </div>
                                <div class="text-sm font-medium text-neutral-900">
                                    €{{ number_format($item->price * $item->quantity, 2, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Totals -->
                    <div class="border-t border-neutral-200 pt-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span>Subtotale</span>
                            <span>€{{ number_format($subtotal, 2, ',', '.') }}</span>
                        </div>
                        @if($shippingCost > 0)
                            <div class="flex justify-between text-sm">
                                <span>Spedizione</span>
                                <span>€{{ number_format($shippingCost, 2, ',', '.') }}</span>
                            </div>
                        @else
                            <div class="flex justify-between text-sm text-success">
                                <span>Spedizione</span>
                                <span>Gratuita</span>
                            </div>
                        @endif
                        @if($discount > 0)
                            <div class="flex justify-between text-sm text-success">
                                <span>Sconto</span>
                                <span>-€{{ number_format($discount, 2, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-base font-semibold border-t border-neutral-200 pt-2">
                            <span>Totale</span>
                            <span>€{{ number_format($total, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>