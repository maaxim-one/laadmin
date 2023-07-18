<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LaAdminPanel</title>
    {{
        Vite::useBuildDirectory('laadmin')
            ->withEntryPoints(['resources/js/app.js'])
    }}
</head>
<body>
<div id="app">
    @section('content')
        //
    @show
</div>
@if(env('APP_ENV') == 'local')
    @routes
@endif
</body>
</html>
