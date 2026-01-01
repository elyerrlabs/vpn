<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Vpn – Admin</title>

    <link rel="icon" href="{{ asset('third-party/vpn/favicon.png') }}" type="image/png">

    <link nonce={{ $nonce }} href="{{ asset('third-party/vpn/css/app.css') }}" rel="stylesheet">

    <x-vpn-translator />
    @inertiaHead
</head>
<body>
    @inertia
    <script nonce={{ $nonce }} src="{{ asset('third-party/vpn/js/app.js') }}"></script>
</body>

</html>
