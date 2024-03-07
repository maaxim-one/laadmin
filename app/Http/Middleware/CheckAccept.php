<?php

namespace MaaximOne\LaAdmin\Http\Middleware;

use MaaximOne\LaAdmin\Exceptions\RoleException;
use Symfony\Component\HttpFoundation\Response;
use MaaximOne\LaAdmin\Facades\LaAdminRole;
use Illuminate\Support\Facades\Auth;
use MaaximOne\LaAdmin\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Closure;

class CheckAccept
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     * @throws RoleException
     */
    public function handle(Request $request, Closure $next): Response
    {
//        LaAdminRole::make('users')
//            ->setAccept(true)
//            ->setAddRule(true)
//            ->setEditRule(true)
//            ->setDeleteRule(true)
//            ->setAbbreviation('Пользователи')
//            ->setCustomRule('reset', true, 'Сброс паролей');
//
//        LaAdminRole::make('roles')
//            ->setAccept(true)
//            ->setAddRule(true)
//            ->setEditRule(true)
//            ->setDeleteRule(true)
//            ->setAbbreviation('Роли');
//
//        LaAdminRole::make('errors')
//            ->setAccept(true)
//            ->setCustomParams([
//                'read' => [
//                    'abbreviation' => 'Просматривать',
//                    'value' => true,
//                ],
//                'comment' => [
//                    'abbreviation' => 'Комментировать',
//                    'value' => true,
//                ],
//                'fixed' => [
//                    'abbreviation' => 'Отмечать как "Исправлено"',
//                    'value' => true,
//                ]
//            ])
//            ->setAbbreviation('Ошибки');
//
//        $u = Role::findOrFail(2);
//        $u->rules = LaAdminRole::__toResponse();
//        $u->save();

        $page = $request->input('page');
        $role = Role::findOrFail(Auth::user()->role_id);
        $rules = $role->rules;

        $response = response()->json([
            'title' => 'Доступ запрещён',
            'text' => "Для вашей роли \"$role->role_name\" установлено ограничение на эту страницу"
        ], 401);

        foreach ($rules as $key => $rule) {
            LaAdminRole::make($key)
                ->setAbbreviation($rule->abbreviation)
                ->setCustomParams((array)$rule->params);
        }

        if ($page == null) {
            $page = explode('/', url()->current());
            $page = $page[(count($page) - 1)];

            if ($this->hasPage($rules, $page)) {
                if ($rules->$page->accept) {
                    return $next($request);
                }
            }
        } else {
            $page = explode('.', $page);

            if ($this->hasPage($rules, $page[0])) {
                if (count($page) == 1) {
                    $page = $page[0];
                    if (!$rules->$page->accept) {
                        return $response;
                    }
                } else {
                    $page_root = $page[0];
                    $page_sub = $rules->$page_root->params;
                    $page = (object)Arr::except($page, [0]);

                    foreach ($page as $item) {
                        $page_sub = $page_sub->$item;
                    }

                    if (!$page_sub->value) {
                        return $response;
                    }
                }
            }
        }

        return $next($request);
    }

    /**
     * @throws RoleException
     */
    protected function hasPage($rules, $page): bool
    {
        if (Arr::has((array)$rules, $page)) {
            return true;
        } else {
            throw new RoleException("В таблице правил страницы [$page] нет", 500);
        }
    }
}
