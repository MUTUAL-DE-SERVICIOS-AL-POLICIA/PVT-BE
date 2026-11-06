<?php

namespace Muserpol\Http\Middleware;

use Closure;

/**
 * Pone un techo al parametro "length" de DataTables.
 *
 * La interfaz ofrece "Todos" (-1) y el navegador lo manda tal cual. En las
 * tablas grandes eso intenta materializar miles de filas con columnas
 * calculadas y agota la memoria del worker (500 despues de ~15s), que el
 * usuario ve como "DataTables warning: Ajax error".
 *
 * Con el tope, "Todos" devuelve la primera pagina maxima en vez de romper.
 * Para el dataset completo estan los botones de exportacion.
 */
class ClampDataTablesLength
{
    /** Filas maximas por pagina que se aceptan. */
    const MAX = 200;

    public function handle($request, Closure $next)
    {
        if ($request->has('length')) {
            $length = (int) $request->input('length');

            if ($length <= 0 || $length > self::MAX) {
                $request->merge(['length' => self::MAX]);
            }
        }

        return $next($request);
    }
}
