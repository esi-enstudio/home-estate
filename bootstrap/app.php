<?php

use App\Http\Middleware\CheckComingSoonMode;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // 'web' গ্রুপের সকল রাউটের জন্য আপনার Middleware-টি যোগ করা হচ্ছে।
        // এটিকে অ্যারের শেষে যোগ করা ভালো, যাতে এটি অন্যান্য Middleware-এর পরে চলে।
        $middleware->web(append: [
            CheckComingSoonMode::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
