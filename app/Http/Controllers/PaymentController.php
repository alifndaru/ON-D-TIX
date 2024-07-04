<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Xendit\BalanceAndTransaction\TransactionApi;
use App\Models\Payment;
use App\Models\Order;
use App\Models\PaymentSeat;
use App\Models\Seat;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Xendit\XenditSdkException;

class PaymentController extends Controller
{
    public function __construct()
    {
        Configuration::setXenditKey(env('XENDIT_SECRET_KEY'));
    }

    public function create(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric',
            'rute_id' => 'required|exists:rute,id',
            'transportasi_id' => 'required|exists:transportasi,id',
            'order_id' => 'required',
            'seat' => 'required|array'
        ]);

        if ($validatedData->fails()) {
            return response()->json(['message' => $validatedData->errors()->first()], 400);
        }

        $params = [
            'external_id' => (string) Str::uuid(),
            'amount' => $request->amount,
            'order_id' => $request->order_id,
            'payer_email' => $request->payer_email,
            'description' => 'Payment for order',
            'user_id' => $request->user_id,
            'checkout_url' => 'test.com',
            'seat' => $request->seat,
            'transportasi_id' => $request->transportasi_id,
            'rute_id' => $request->rute_id
        ];

        foreach ($params['seat'] as $seat_id) {
            $seat = PaymentSeat::find($seat_id);
            // dd($seat);

            if ($seat->is_booked) {
                return response()->json(['message' => 'One or more of the selected seats are already booked'], 400);
            }
        }

        $apiInstance = new InvoiceApi();
        $createInvoiceRequest = new CreateInvoiceRequest([
            'external_id' => $params['external_id'],
            'amount' => $params['amount'],
            'description' => $params['description'],
            'payer_email' => $params['payer_email'],
            'currency' => 'IDR',
            'success_redirect_url' => route('history'),
            'invoice_duration' => 172800
        ]);

        try {
            $result = $apiInstance->createInvoice($createInvoiceRequest);
        } catch (XenditSdkException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

        $order = Order::create([
            'order_id' => $params['order_id'],
            'user_id' => $request->user_id,
            'total' => $request->amount,
            'status' => Order::STATUS_PENDING,
            'rute_id' => $request->rute_id,
            'transportasi_id' => $request->transportasi_id,
        ]);

        if (!$order) {
            throw ValidationException::withMessages(['message' => 'Failed to create order']);
        }

        $payment = new Payment;
        $payment->status = 'pending';
        $payment->order_id = $order->order_id;
        $payment->user_id = $params['user_id'];
        $payment->external_id = $params['external_id'];
        $payment->checkout_url = $result['invoice_url'];
        $payment->transportasi_id = $params['transportasi_id'];
        $payment->rute_id = $params['rute_id'];
        $payment->save();


        // $checkoutUrl = $payment->checkout_url;
        // dd($checkoutUrl);

        // dd($params);

        foreach ($params['seat'] as $seat_id) {
            $payment->seats()->attach($seat_id, ['order_id' => $order->order_id]);
        }

        if ($payment->status == 'settled') {
            $order->status = Order::STATUS_COMPLETED;
            $order->save();
            return redirect()->route('history');
        }

        // return response()->json(['message' => 'Payment created successfully', 'checkout_url' => $result['invoice_url']]);
        // return redirect()->($result['invoice_url']);
        return redirect()->away($result['invoice_url']);
    }

    public function webhook(Request $request)
    {
        try {
            $apiInstance = new InvoiceApi();
            $invoidId = $request->id;
            $result = $apiInstance->getInvoiceById($invoidId);
            $payment = Payment::where('external_id', $request->external_id)->first();

            if (!$payment) {
                return response()->json(['message' => 'Payment not found'], 404);
            }

            if (!isset($result['status'])) {
                return response()->json(['message' => 'Status not found'], 404);
            }

            $payment->status = strtolower($result['status']);
            $payment->save();


            if ($payment->status === 'settled') {
                $order = Order::where('order_id', $payment->order_id)->first();
                if ($order) {
                    $order->markAsCompleted();
                }
            }



            if ($payment->status === 'settled') {
                foreach ($payment->seats as $seat) {
                    $seat->is_booked = true;
                    $seat->save();
                }
            }


            return response()->json(['message' => 'Payment status successfully updated']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function history()
    {
        $user = Auth::user(); // Mendapatkan user yang sedang login

        // Mendapatkan riwayat pesanan yang 'completed' untuk user yang sedang login
        $completedOrders = $user->orders()->where('status', 'completed')->with(['transportasi', 'rute'])->orderBy('created_at', 'desc')->get();

        // Mendapatkan riwayat pesanan yang 'pending' untuk user yang sedang login
        $pendingOrders = $user->orders()->where('status', 'pending')->with(['transportasi', 'rute'])->orderBy('created_at', 'desc')->get();

        return view('client.history', compact('completedOrders', 'pendingOrders'));
    }

    public function detailTicket($order)
    {
        // $pemesanan = Pemesanan::with('rute', 'penumpang')->orderBy('created_at', 'desc')->get();

        $kursi = PaymentSeat::where('order_id', $order)->get();
        $order = Order::where('order_id', $order)->with(['transportasi', 'rute', 'paymentSeats'])->first();
        // dd($order);
        if (!$order) {
            return redirect()->route('history')->with('error', 'Order not found');
        }

        return view('client.detail-ticket', compact('order', 'kursi'));
    }


    public function cetakDetail($order)
    {
        // $tiketHistory = Order::findOrfail($order);
        $kursi = PaymentSeat::where('order_id', $order)->get();
        $order = Order::where('order_id', $order)->with(['transportasi', 'rute', 'paymentSeats'])->first();

        // dd($order);
        $pdf = PDF::loadview('client.cetakDetailTiket', ['order' => $order], ['kursi' => $kursi]);
        return $pdf->stream('tiket-pdf');
    }
}
