@extends('layouts.app')

@section('title', 'History')

@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h2>History Transaksi</h2>
            </div>
            <div class="card-body">
                <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-completed-tab" data-toggle="pill" href="#pills-completed"
                            role="tab" aria-controls="pills-completed" aria-selected="true">Completed</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-pending-tab" data-toggle="pill" href="#pills-pending" role="tab"
                            aria-controls="pills-pending" aria-selected="false">Pending</a>
                    </li>
                </ul>

                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-completed" role="tabpanel"
                        aria-labelledby="pills-completed-tab">
                        @if ($completedOrders->count() > 0)
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Username</th>
                                        <th>Transportasi</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>tanggal Berangkat</th>
                                        <th>Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($completedOrders as $order)
                                        <tr>
                                            <td>{{ $order->id }}</td>
                                            <td>{{ $order->user->name }}</td>
                                            <td>{{ $order->transportasi->name }}</td>
                                            <td>{{ $order->status }}</td>
                                            <td>{{ $order->total }}</td>
                                            <td>{{ optional($order->rute)->tanggal_keberangkatan }}</td>
                                            <th>
                                                <a href="{{ route('detailTicket', ['order_id' => $order->order_id]) }}"
                                                    class="btn btn-primary">Detail</a>
                                            </th>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>No completed orders found</p>
                        @endif
                    </div>
                    <div class="tab-pane fade" id="pills-pending" role="tabpanel" aria-labelledby="pills-pending-tab">
                        @if ($pendingOrders->count() > 0)
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>User ID</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>tanggal Berangkat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pendingOrders as $order)
                                        <tr>
                                            <td>{{ $order->id }}</td>
                                            <td>{{ $order->user_id }}</td>
                                            <td>{{ $order->status }}</td>
                                            <td>{{ $order->total }}</td>
                                            <td>{{ $order->rute->tanggal_keberangkatan }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>No pending orders found</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
