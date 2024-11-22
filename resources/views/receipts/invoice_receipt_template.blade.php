<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .receipt-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background: #f9f9f9;
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .receipt-header h1 {
            margin: 0;
        }

        .receipt-details,
        .receipt-items,
        .receipt-summary {
            margin-bottom: 20px;
        }

        .receipt-summary {
            text-align: right;
        }

        .receipt-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9rem;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="receipt-container">
        <!-- Receipt Header -->
        <div class="receipt-header">
            <h1>Receipt</h1>
            <p class="text-muted">Invoice #: {{ $invoice_number }}</p>
            <p class="text-muted">Date: {{ $date }}</p>
        </div>

        <!-- Patient Details -->
        <div class="receipt-details">
            <h5>Patient Details</h5>
            <p><strong>Name:</strong> {{ $patient_name }}</p>
        </div>

        <!-- Items -->
        <div class="receipt-items">
            <h5>Items</h5>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th class="text-end">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td>{{ $item['description'] }}</td>
                            <td class="text-end">${{ number_format($item['amount'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <div class="receipt-summary">
            <h5>Total Amount: ${{ number_format($total_amount, 2) }}</h5>
            <p><strong>Payment Method:</strong> {{ $payment_method }}</p>
            <p><strong>Payment Reference:</strong> {{ $payment_reference }}</p>
        </div>

        <!-- Footer -->
        <div class="receipt-footer">
            <p>Thank you for your payment!</p>
            <p class="text-muted">This is a system-generated receipt.</p>
        </div>
    </div>
</body>

</html>
