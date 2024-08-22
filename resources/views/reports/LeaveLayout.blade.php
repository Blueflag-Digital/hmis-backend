<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        leave form
    </title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header img {
            width: 100px;
            margin-bottom: 20px;
        }

        .content {
            padding: 20px;
        }

        .label {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .value {
            margin-bottom: 20px;
        }

        .signatures p {
            margin-top: 30px;
        }

        .form-control {
            border: none;
            border-bottom: 1px solid #ccc;
            border-radius: 0;
            box-shadow: none;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            {{-- <img src="{{ public_path('assets/images/background-login.png') }}" alt="Kenyatta Logo"> --}}
            <h1>{{ $hospital->hospital_name }}</h1>
            <h2>{{ $title }}</h2>
        </div>
        @yield('content')
    </div>
</body>

</html>
