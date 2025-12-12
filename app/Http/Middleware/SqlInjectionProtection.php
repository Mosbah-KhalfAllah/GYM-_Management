<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SqlInjectionProtection
{
    private $patterns = [
        '/(\bunion\b.*\bselect\b)/i',
        '/(\bselect\b.*\bfrom\b)/i',
        '/(\binsert\b.*\binto\b)/i',
        '/(\bdelete\b.*\bfrom\b)/i',
        '/(\bdrop\b.*\btable\b)/i',
        '/(\bupdate\b.*\bset\b)/i',
        '/(\'|\"|;|--|\*|\|)/i',
    ];

    public function handle(Request $request, Closure $next)
    {
        $input = $request->all();
        
        foreach ($input as $key => $value) {
            if (is_string($value) && $this->containsSqlInjection($value)) {
                Log::critical('Tentative d\'injection SQL détectée', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'input' => $key,
                    'value' => $value,
                    'url' => $request->fullUrl()
                ]);
                
                abort(403, 'Requête suspecte détectée');
            }
        }

        return $next($request);
    }

    private function containsSqlInjection($value): bool
    {
        foreach ($this->patterns as $pattern) {
            if (preg_match($pattern, $value)) {
                return true;
            }
        }
        return false;
    }
}