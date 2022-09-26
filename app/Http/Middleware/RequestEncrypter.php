<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
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
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('data')) {
            $request->request->add($this->encrypter->decrypt($request->request->get('data')));
            $request->request->remove('data');
        }

        return $next($request);
    }
}
