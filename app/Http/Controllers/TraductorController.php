<?php
// app/Http/Controllers/TraductorController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TraductorController extends Controller
{
    public function traducir(Request $request)
    {
        $validated = $request->validate([
            'texto' => ['required', 'string', 'max:5000'],
            'idioma' => ['required', 'string', 'size:2'],
        ], [
            'texto.required' => 'Escribe algo para traducir.',
            'texto.max' => 'El texto no puede pasar de :max caracteres.',
            'idioma.required' => 'Selecciona un idioma.',
            'idioma.size' => 'El idioma seleccionado no es válido.',
        ]);

        $texto = $validated['texto'];

        $idiomas = [
            'ES' => 'español',
            'EN' => 'inglés',
            'FR' => 'francés',
            'RU' => 'ruso',
            'ZH' => 'chino mandarín',
        ];

        $texto = $validated['texto'];
        $nombreIdioma = $idiomas[$validated['idioma']];

        $rol = 'Eres un traductor profesional. Traduce el texto del usuario al '
            . $nombreIdioma . '. Responde únicamente con la traducción, sin '
            . 'comentarios, explicaciones ni formato markdown.';

        $respuesta = Http::withToken(config('services.groq.key'))
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => config('services.groq.model'),
                'messages' => [
                    ['role' => 'system', 'content' => $rol],
                    ['role' => 'user', 'content' => $texto],
                ],
            ]);

        if ($respuesta->failed()) {
            Log::error('Groq devolvió un error', [
                'status' => $respuesta->status(),
                'body' => $respuesta->json(),
            ]);

            return back()->withInput()->withErrors([
                'texto' => 'No se pudo traducir. Intenta de nuevo.',
            ]);
        }

        $traduccion = $respuesta->json('choices.0.message.content');

        if (blank($traduccion)) {
            Log::warning('Groq respondió sin texto traducido', [
                'body' => $respuesta->json(),
            ]);

            return back()->withInput()->withErrors([
                'texto' => 'La respuesta vino vacía. Intenta de nuevo.',
            ]);
        }

        return back()->withInput()->with('traduccion', $traduccion);
    }
}