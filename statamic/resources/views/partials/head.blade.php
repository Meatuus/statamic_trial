<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width">
<meta id="csrf-token" value="{{ csrf_token() }}" />
<meta name="robots" content="noindex">

<title>{{ $title or '' }} | Statamic</title>
<link href="{{ cp_resource_url('css/cp.css') }}?v={{ STATAMIC_VERSION }}" rel="stylesheet" />
<link rel="shortcut icon" href="{{ cp_resource_url('img/favicon.png') }}" />
<script>
    var Statamic = {
    	'siteRoot': '{!! SITE_ROOT !!}',
    	'cpRoot': '{!! $cp_root !!}',
    	'urlPath': '/{!! request()->path() !!}',
    	'resourceUrl': '{!! cp_resource_url('/') !!}',
    	'locales': {!! json_encode(Statamic\API\Config::getLocales()) !!}
    };
</script>

{!! $layout_head !!}
