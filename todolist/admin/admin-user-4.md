# Illuminate\Database\QueryException - Internal Server Error

SQLSTATE[23000]: Integrity constraint violation: 1048 Column 'password' cannot be null (Connection: mysql, SQL: update `users` set `avatar` = ?, `password` = ?, `users`.`updated_at` = 2026-01-03 14:02:01 where `id` = 4)

PHP 8.3.26
Laravel 12.44.0
localhost:8000

## Stack Trace

0 - vendor\laravel\framework\src\Illuminate\Database\Connection.php:826
1 - vendor\laravel\framework\src\Illuminate\Database\Connection.php:780
2 - vendor\laravel\framework\src\Illuminate\Database\Connection.php:583
3 - vendor\laravel\framework\src\Illuminate\Database\Connection.php:535
4 - vendor\laravel\framework\src\Illuminate\Database\Query\Builder.php:3917
5 - vendor\laravel\framework\src\Illuminate\Database\Eloquent\Builder.php:1266
6 - vendor\laravel\framework\src\Illuminate\Database\Eloquent\Model.php:1316
7 - vendor\laravel\framework\src\Illuminate\Database\Eloquent\Model.php:1233
8 - vendor\laravel\framework\src\Illuminate\Database\Eloquent\Model.php:1090
9 - vendor\backpack\crud\src\app\Library\CrudPanel\Traits\Update.php:39
10 - vendor\backpack\crud\src\app\Library\CrudPanel\Traits\Update.php:34
11 - vendor\backpack\crud\src\app\Http\Controllers\Operations\UpdateOperation.php:104
12 - vendor\laravel\framework\src\Illuminate\Routing\Controller.php:54
13 - vendor\laravel\framework\src\Illuminate\Routing\ControllerDispatcher.php:43
14 - vendor\laravel\framework\src\Illuminate\Routing\Route.php:265
15 - vendor\laravel\framework\src\Illuminate\Routing\Route.php:211
16 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:822
17 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:180
18 - vendor\backpack\crud\src\app\Http\Controllers\CrudController.php:41
19 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:201
20 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
21 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull.php:31
22 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
23 - app\Http\Middleware\CheckIfAdmin.php:66
24 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
25 - app\Http\Middleware\SetLocale.php:18
26 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
27 - vendor\laravel\framework\src\Illuminate\Routing\Middleware\SubstituteBindings.php:50
28 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
29 - vendor\backpack\crud\src\app\Http\Middleware\AuthenticateSession.php:62
30 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
31 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken.php:87
32 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
33 - vendor\laravel\framework\src\Illuminate\View\Middleware\ShareErrorsFromSession.php:48
34 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
35 - vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php:120
36 - vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php:63
37 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
38 - vendor\laravel\framework\src\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse.php:36
39 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
40 - vendor\laravel\framework\src\Illuminate\Cookie\Middleware\EncryptCookies.php:74
41 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
42 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:137
43 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:821
44 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:800
45 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:764
46 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:753
47 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:200
48 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:180
49 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
50 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull.php:31
51 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
52 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
53 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TrimStrings.php:51
54 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
55 - vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePostSize.php:27
56 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
57 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance.php:109
58 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
59 - vendor\laravel\framework\src\Illuminate\Http\Middleware\HandleCors.php:48
60 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
61 - vendor\laravel\framework\src\Illuminate\Http\Middleware\TrustProxies.php:58
62 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
63 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\InvokeDeferredCallbacks.php:22
64 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
65 - vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePathEncoding.php:26
66 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
67 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:137
68 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:175
69 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:144
70 - vendor\laravel\framework\src\Illuminate\Foundation\Application.php:1220
71 - public\index.php:20
72 - vendor\laravel\framework\src\Illuminate\Foundation\resources\server.php:23

## Request

PUT /admin/user/4

## Headers

-   **host**: localhost:8000
-   **user-agent**: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0
-   **accept**: text/html,application/xhtml+xml,application/xml;q=0.9,_/_;q=0.8
-   **accept-language**: en-US,en;q=0.5
-   **accept-encoding**: gzip, deflate, br, zstd
-   **content-type**: multipart/form-data; boundary=----geckoformboundary49db2bbf4539cae9eb9152f537819d47
-   **content-length**: 1536
-   **origin**: http://localhost:8000
-   **sec-gpc**: 1
-   **connection**: keep-alive
-   **referer**: http://localhost:8000/admin/user/4/edit
-   **cookie**: remember_backpack_59ba36addc2b2f9401580f014c7f58ea4e30989d=eyJpdiI6IkdubUs1ekVTTkI3VDg2bWM4UHVzTXc9PSIsInZhbHVlIjoiZGgrbmhOczRxbW1VM25xWUJHVXB5N1RWUnMya2hSdTk5UnUzeTNEOGYwTHJFdi9Ca0pDdXE3UGRwOFFwcUIrQjhid2tKVDNiMW1tUXNxdzZjWWpyUWhiTlhRMkVkOEo1NGZBYnRxZmUzQVdCR2J4cVQra0pnbWtoL05OTHRsZG54TmJzbkprcGd2Q05vRjhBMVR1T1pPMkxUd3VaNDl1RmtaN2ZoSVA3TWFhVUxYTGxHQ2FkUk02cUY1ZlcyWUR0OXplbDg5R0w2K1JCU0I5OEFyK2N2ZkE1Yk02ekhhMFg0YTVqVXpOd3BBRT0iLCJtYWMiOiIyMmE1ZTgwOGFiZTNhZDBkMGJkMmVmNWZiNDc5MDE2NDdjYzliMTgwZDY2OTcxOTIxNzk2MzdlNDFiZTVhOTk2IiwidGFnIjoiIn0%3D; XSRF-TOKEN=eyJpdiI6IkF3MDRqSWtPVUY0Yy9LY0tZcDZISlE9PSIsInZhbHVlIjoiRlBaQUlKRHgzL1B3bS9INHBtSDZmMTJaamNoOHhOcDJMaE5ESFpYVTZ3KzNoTUg5MXNoRUIvbzNkNnQrL2dWbkVaTGx2NzFqYXJkNXc5cVBqWmwwTlRReXZzeFk0Zm96MjFLTnZVcGYzTEMvVEVKSHRuUHl2bVQ1OGthT3JOeU4iLCJtYWMiOiJlMzhlNTkwNDQxNzI3NjRiMzVmNWZmYTA4MzczNWMwMGZjZTZjODllZTJiY2M0N2Y2NmQxMTZkYWNjMmVlZGI2IiwidGFnIjoiIn0%3D; desk-by-horizon-session=eyJpdiI6IlVPSTRtNDZGS09QeDJRN2NOOHpXUXc9PSIsInZhbHVlIjoiRDhCRk9BSklLZ3dVSVVKZ0QvN1ZZQTBFL3o5dFN0akcvR1NnY0Vqc1l0REJ3dG1XdStkL0NodTVRK3RxZjdSSld5N1FEeFFzaHlVdHlvZUpJWTJBdEVPZ08zMExWanUydHNFM3k2QWtjdGlpOGh4dk1YV2ZkYmJlVklQeVZpWVkiLCJtYWMiOiI2OGY5NGFhMTdhY2QyM2Y0ZWRhZGMwYjMxYzljZDIyMDAzODcxMzIzZWI4YzFjNTg5MjE3ZGNjMmY4MDZhZmExIiwidGFnIjoiIn0%3D; remember_web_59ba36addc2b2f9401580f014c7f58ea4e30989d=eyJpdiI6InlJdHczbEh1b283cDRkYkluaCsrU2c9PSIsInZhbHVlIjoiZThsRVp3elBWSTJrTEJlU1Z0L1JIQjU3Y0VBUHNab1FQK1prMVdjNDNUQnhyZWo1cTJ1SHMzdlFVZFJxN09ka3FtNVZLa1RHTG9xMnRjZ1pxVUVLbGlPdlNHelltZWZwa3RrUWRhclJ6WWZtYWNWRnVHc2FkSEZ2RUU1c0IvaXVDOXFXVWp4WWtDRE5tSm1sVnpQcDVSaFRaTUt2OExTb1dHeTdJL2tlSktMMG8rcXNRWnpDME1GZThSM09GK1VCeFBTV1FiaEZBUy9yOWxITVVrK0p3akRyVUI2emFQckh2Tnd1WnZZZkpzOD0iLCJtYWMiOiI5NDE1NzQxZTM4MGZmZWMxM2IwODJmZmViMjA2MWU4NmZlNjZiN2M5M2M2YjdhNTYxMWI1NWU5MmY5ZWExZmY4IiwidGFnIjoiIn0%3D
-   **upgrade-insecure-requests**: 1
-   **sec-fetch-dest**: document
-   **sec-fetch-mode**: navigate
-   **sec-fetch-site**: same-origin
-   **sec-fetch-user**: ?1
-   **priority**: u=0, i

## Route Context

controller: App\Http\Controllers\Admin\UserCrudController@update
route name: user.update
middleware: web, admin, Closure

## Route Parameters

{
"id": "4"
}

## Database Queries

-   mysql - select \* from `sessions` where `id` = 'fUzz1d7QFowwDC9ubKLHCDlFiyUv6xIBOEnlJKvD' limit 1 (3.1 ms)
-   mysql - select \* from `users` where `id` = 1 limit 1 (0.62 ms)
-   mysql - select column_name as `name`, data_type as `type_name`, column_type as `type`, collation_name as `collation`, is_nullable as `nullable`, column_default as `default`, column_comment as `comment`, generation_expression as `expression`, extra as `extra` from information_schema.columns where table_schema = schema() and table_name = 'users' order by ordinal_position asc (1.31 ms)
-   mysql - select index_name as `name`, group_concat(column_name order by seq_in_index) as `columns`, index_type as `type`, not non_unique as `unique` from information_schema.statistics where table_schema = schema() and table_name = 'users' group by index_name, index_type, non_unique (1.25 ms)
-   mysql - select count(\*) as aggregate from `users` where `email` = 'sky@deskbyhorizon.com' and `id` <> '4' (0.6 ms)
-   mysql - select \* from `users` where `users`.`id` = '4' limit 1 (0.58 ms)
-   mysql - select \* from `users` where `id` = 1 limit 1 (0.6 ms)
