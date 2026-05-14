<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Actions\Newsletter\ConfirmDoubleOptInAction;
use App\Actions\Newsletter\SubscribeAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Public\NewsletterSubscribeRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(NewsletterSubscribeRequest $request): JsonResponse|RedirectResponse
    {
        $subscriber = app(SubscribeAction::class)->execute(
            $request->email,
            $request->name,
            'website',
            app()->getLocale()
        );
        
        if ($request->expectsJson() || $request->hasHeader('X-Livewire')) {
            return response()->json([
                'success' => true,
                'message' => 'Grazie per l\'iscrizione! Ti abbiamo inviato una email di conferma.',
                'status' => $subscriber->status,
            ]);
        }
        
        return back()->with('success', 'Grazie per l\'iscrizione! Ti abbiamo inviato una email di conferma.');
    }
    
    public function confirm(Request $request, string $email, string $token): RedirectResponse
    {
        try {
            $subscriber = app(ConfirmDoubleOptInAction::class)->execute($email, $token);
            
            return redirect()
                ->route('public.home')
                ->with('success', 'Iscrizione alla newsletter confermata con successo!');
        } catch (\Exception $e) {
            return redirect()
                ->route('public.home')
                ->withErrors(['newsletter' => 'Link di conferma non valido o scaduto.']);
        }
    }
}