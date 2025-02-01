<?php
use Illuminate\Support\Facades\Route;


if (!function_exists('isActive')) {
    function isActive($routeName)
    {
        return request()->is(trim($routeName, '/')) ? 'bg-purple-600 text-white' : '';
    }
}

// if (!function_exists('isActive')) {
//     /**
//      * Determine if the current route matches the given route name.
//      *
//      * @param string|array $routeNames
//      * @return string
//      */
//     function isActive($routeNames)
//     {
//         if (is_array($routeNames)) {
//             return in_array(Route::currentRouteName(), $routeNames) ? 'bg-purple-100 text-purple-600' : '';
//         }

//         return Route::currentRouteName() === $routeNames ? '  absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
//                             aria-hidden="true"' : '';
//     }
// }