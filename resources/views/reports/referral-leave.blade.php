<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        {{ $title }}
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

        <div class="content">
            <p class="label">Patient Name: <span
                    class="value">{{ isset($leave->patient) && isset($leave->patient->person) ? $leave->patient->person->getName() : '' }}</span>
            </p>

            <p class="label">Patient ID: <span
                    class="value">{{ isset($leave->patient) && isset($leave->patient->person) ? $leave->patient->person->identifier_number : '' }}</span>
            </p>

            <p class="label">Working Days for Sickness Leave: <span
                    class="value">{{ $leave->working_days ?? '' }}</span></p>

            <p class="label">Start Date: <span class="value">{{ $leave->start_date ?? '' }}</span></p>

            <p class="label">End Date: <span class="value">{{ $leave->end_date ?? '' }}</span></p>

            <p class="label">Reason for Sickness Leave:</p>
            <textarea class="form-control">{{ $leave->reason ?? 'Flu and fever.' }}</textarea>

            <p class="label">Doctor Name: <span
                    class="value">{{ isset($leave->user) ? $leave->user->name : '' }}</span></p>
            <p class="label">Doctor Phone: <span
                    class="value">{{ isset($leave->user) ? $leave->user->phone : '' }}</span></p>

            <div class="signatures">
                <p>Patient's Signature: ______________________</p>
                <p>Doctor's Signature: ______________________</p>
            </div>
        </div>

    </div>
</body>

</html>
