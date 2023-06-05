<!DOCTYPE html>
<html lang="{{ getLang() }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>{{ $settings->general->site_name ?? 'Error' }} — @yield('title')</title>
    <link rel="shortcut icon" href="{{ asset($settings->media->favicon ?? '') }}">
    <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/fontawesome/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extra/css/colors.css') }}">
</head>
<style>
    body {
        font-family: 'Roboto', 'Almarai', sans-serif;
    }

    .error--page {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        min-height: 90vh;
        padding: 3rem;
    }

    .error--page .error--card {
        text-align: center;
    }

    .error--page .error--card .error--code {
        margin: 0;
        font-weight: 600;
        font-size: 10rem;
    }

    .error--page .error--card .line {
        width: 150px;
        margin: 1rem auto;
        height: 3px;
        background: var(--primaryColor);
    }

    .error--page .error--card .error--title {
        color: #585858;
        margin: 1rem 0;
    }

    @media (max-width:575.98px) {
        .error--page .error--card .error--code {
            font-size: 5rem;
        }

        .error--page .error--card .line {
            width: 100px;
        }
    }
</style>

<body class="error--page">
    <div class="error--card">
        <h1 class="error--code">@yield('code')</h1>
        <div class="line"></div>
        <h1 class="error--title">@yield('message')</h1>
        <div class="row">
            <div class="col-lg-7 m-auto">
                <p>{{ lang('description', 'errors') }}.
                </p>
            </div>
        </div>
        <a href="{{ url('/') }}" class="btn btn-dark"><i
                class="fa fa-home me-1"></i>{{ lang('Back to home', 'errors') }}</a>
    </div>
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
