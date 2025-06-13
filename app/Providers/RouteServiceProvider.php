<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * El nombre del espacio de nombre para las rutas.
     *
     * @var string
     */
    protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define las rutas para la aplicación.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    /**
     * Mapea las rutas de la API.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api') // Prefijo para las rutas de la API
            ->middleware('api') // Middleware para las rutas de la API
            ->namespace($this->namespace) // Espacio de nombres para los controladores de la API
            ->group(base_path('routes/api.php')); 

    }

    /**
     * Mapea las rutas web.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web') // Middleware para las rutas web (CSRF, sesión, etc.)
            ->namespace($this->namespace) // Espacio de nombres para los controladores web
            ->group(base_path('routes/web.php')); // El archivo que contiene las rutas web
    }
}
