<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill </title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .receipt {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .receipt-header h1 {
            margin: 0;
            font-size: 24px;
        }

        .receipt-header p {
            margin: 5px 0;
            color: #555;
        }

        .patient-info {
            margin-bottom: 20px;
        }

        .patient-info h3 {
            margin-bottom: 5px;
            font-size: 18px;
            color: #333;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table th,
        .items-table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .items-table th {
            background-color: #f8f8f8;
        }

        .total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="receipt">
        <div class="receipt-header">
            <h1>{{ $data['hospital']['hospital_name'] }}</h1>
            <p>Receipt</p>
        </div>

        <div class="patient-info">
            <h3>Patient Information</h3>
            <p><strong>Name:</strong> {{ $data['patientsInformation']['name'] }}</p>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Unit Price</th>
                    <th>Date</th>
                    <th>Quantity</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['items'] as $item)
                    <tr>
                        <td>{{ $item['description'] }}</td>
                        <td>KES . {{ number_format($item['unit_price'], 2) }}</td>
                        <td>{{ $item['bill_date'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>KES . {{ number_format($item['total_amount'], 2) }}</td>

                    </tr>
                @endforeach
            </tbody>
        </table>

        <p class="total">
            Total: KES .{{ number_format(collect($data['items'])->sum('total_amount'), 2) }}
        </p>
        <div>Served By {{ $data['served_by']['name'] }}</div>
    </div>
</body>

</html>
