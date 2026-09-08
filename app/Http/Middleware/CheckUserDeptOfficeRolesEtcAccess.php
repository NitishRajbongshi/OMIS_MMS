<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserDeptOfficeRolesEtcAccess
{
    public function handle(Request $request, Closure $next, ...$conditions)
    {
        foreach ($conditions as $condition) {
            [$key, $allowedValues] = explode('=', $condition, 2);

            $allowedValues = explode('|', $allowedValues);

            if ($key === 'department') {
                $department = (string) session('user_dept_cd');

                if (!in_array($department, $allowedValues, true)) {
                    abort(403, 'Your department is not authorised.');
                }
            }

            if ($key === 'office') {
                $officeType = (string) session('users_office_type_cd');

                if (!in_array($officeType, $allowedValues, true)) {
                    abort(403, 'Your office type is not authorised.');
                }
            }

            if ($key === 'role') {
                $userRoles = array_map(
                    'strval',
                    (array) session('user_role_ids', [])
                );

                if (!array_intersect($allowedValues, $userRoles)) {
                    abort(403, 'Your role is not authorised.');
                }
            }
        }
        return $next($request);
    }
}
