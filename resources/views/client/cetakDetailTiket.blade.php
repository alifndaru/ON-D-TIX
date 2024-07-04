<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Detail Tiket</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            padding: 0;
        }

        .order-details {
            margin-bottom: 20px;
        }

        .order-details th,
        .order-details td {
            text-align: left;
            padding: 5px;
        }

        .order-details th {
            background-color: #f0f0f0;
        }

        .section {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Detail Tiket</h1>
        </div>
        <div class="section">
            <h2>Informasi Order</h2>
            <table class="order-details">
                <tr>
                    <th>Order ID</th>
                    <td>{{ $order->order_id }}</td>
                </tr>
                <tr>
                    <th>Nama Pemesan</th>
                    <td>{{ $order->user->name }}</td>
                </tr>
                <tr>
                    <th>Jadwal Berangkat</th>
                    <td>{{ $order->rute->tanggal_keberangkatan }} <br> Jam : {{ $order->rute->jam }} WIB</td>
                </tr>
            </table>
        </div>
        <div class="section">
            <h2>Informasi Transportasi</h2>
            <table class="order-details">
                <tr>
                    <th>Nama Transportasi</th>
                    <td>{{ $order->transportasi->name }}</td>
                </tr>
                <tr>
                    <th>Rute</th>
                    <td>
                        <h4>{{ $order->rute->start }}</h4>
                        <hr>
                        <h4>{{ $order->rute->end }}</h4>
                    </td>
                </tr>
            </table>
        </div>
        <div class="section">
            <h2>Detail Kursi</h2>
            <table class="order-details">
                @foreach ($kursi as $item)
                    <p>No Kursi :{{ $item->seat_id }}</p>
                @endforeach
            </table>
        </div>
    </div>
</body>

</html>
