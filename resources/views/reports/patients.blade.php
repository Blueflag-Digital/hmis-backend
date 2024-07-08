<!DOCTYPE html>
<html>

<head>
    <title>Patient Report : </title>
    <style>
        /* CSS for table styling */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1>Patients Report as from {{ $data['startDate']->format('d/m/Y') }} to {{ $data['endDate']->format('d/m/Y') }}
    </h1>
    <h3>Total Patients : {{ $count }} </h3>
    <table class="table table-bordered">
        <thead>
            <tr>

                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Gender</th>
                <th>D.O.B</th>
                <th>Age</th>
            </tr>
        </thead>
        <tbody>



            @foreach ($patients as $patient)
                <tr>

                    <td>{{ $patient['name'] }}</td>
                    <td>{{ $patient['email'] }}</td>
                    <td>{{ $patient['phone'] }}</td>
                    <td>{{ $patient['gender'] }}</td>
                    <td>{{ $patient['age'] }}</td>
                    <td>{{ $patient['dob'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
