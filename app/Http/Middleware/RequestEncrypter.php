<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Payment\Contracts\EncrypterInterface;
use Closure;

class RequestEncrypter
{
    private EncrypterInterface $encrypter;

    public function __construct(EncrypterInterface $encrypter)
    {
        $this->encrypter = $encrypter;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     * @throws ValidationException
     */
    public function handle(Request $request, Closure $next)
    {
        if (config('app.env') !== 'local') {
            if (!$request->has('data')) {
                throw ValidationException::withMessages(['data' => 'data input is required']);
            }

            $request->merge($this->encrypter->decrypt($request->get('data')));
        }

        return $next($request);
    }
}
