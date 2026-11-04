<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Supported locales.
     *
     * @var array<string>
     */
    protected array $supportedLocales = ['id', 'en'];

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = null;

        // 1. From query parameter ?lang=en or ?lang=id
        if ($request->filled('lang') && in_array($request->query('lang'), $this->supportedLocales, true)) {
            $locale = $request->query('lang');
            if ($request->hasSession()) {
                $request->session()->put('locale', $locale);
            }
        }
        // 2. From session
        elseif ($request->hasSession() && $request->session()->has('locale')) {
            $sessionLocale = $request->session()->get('locale');
            if (in_array($sessionLocale, $this->supportedLocales, true)) {
                $locale = $sessionLocale;
            }
        }
        // 3. From X-Locale header
        elseif ($request->hasHeader('X-Locale')) {
            $headerLocale = strtolower(trim((string) $request->header('X-Locale')));
            if (in_array($headerLocale, $this->supportedLocales, true)) {
                $locale = $headerLocale;
            }
        }
        // 4. From Accept-Language header
        elseif ($request->hasHeader('Accept-Language')) {
            $header = $request->header('Accept-Language');
            // Ignore Symfony's synthetic test default in unit tests so APP_LOCALE is respected
            if (! (app()->runningUnitTests() && $header === 'en-us,en;q=0.5')) {
                $preferred = $request->getPreferredLanguage($this->supportedLocales);
                if ($preferred) {
                    $locale = $preferred;
                }
            }
        }

        if ($locale) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
