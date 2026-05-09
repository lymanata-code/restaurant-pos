<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function update(Request $request): JsonResponse
    {
        $supported = config('pos.locales', ['en', 'km']);
        $locale = (string) $request->input('locale');

        if (!in_array($locale, $supported, true)) {
            return response()->json([
                'ok' => false,
                'message' => 'Unsupported locale.',
            ], 422);
        }

        $request->session()->put('locale', $locale);
        app()->setLocale($locale);

        return response()->json([
            'ok' => true,
            'locale' => $locale,
        ]);
    }
}
