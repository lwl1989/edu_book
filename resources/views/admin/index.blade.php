<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <meta name="renderer" content="webkit" />
    <meta name="force-rendering" content="webkit" />
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <title>edu book</title>
</head>

<body>
<div id="app">
    <admin-component>
    </admin-component>
</div>
<script src="{{ mix('js/manifest.js') }}"></script>
<script src="{{ mix('js/vue.js') }}"></script>
<script src="{{ mix('js/element-ui.js') }}"></script>
{{--<script src="{{ mix('js/element-ui.js') }}"></script>--}}
<script src="{{ mix('js/admin.js') }}"></script>
</body>
</html>
