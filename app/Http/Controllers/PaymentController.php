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
use App\Models\Seat;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    public function __construct()
    {
        Configuration::setXenditKey(env('XENDIT_SECRET_KEY'));
    }
    public function create(Request $request)
    {
        $params = [
            'external_id' => (string) Str::uuid(),
            'amount' => $request->input('amount'),
            'order_id' => $request->input('order_id'),
            'payer_email' => $request->input('payer_email'),
            'description' => 'Payment for order',
            'user_id' => $request->input('user_id', Auth::id()),
            'checkout_url' => 'test.com',
            'seat' => $request->input('seat'),
            'transportasi_id' => $request->input('transportasi_id'),
            'rute_id' => $request->input('rute_id'),
        ];
        // foreach ($params['seat'] as $seat_id) {
        //     $seat = Seat::find($seat_id);
        //     if ($seat->is_booked) {
        //         return response()->json(['message' => 'One or more of the selected seats are already booked'], 400);
        //     }
        // }
        $apiInstance = new InvoiceApi();
        $createInvoiceRequest = new CreateInvoiceRequest([
            'external_id' => $params['external_id'],
            'amount' => $params['amount'],
            'description' => $params['description'],
            'payer_email' => $params['payer_email'],
        ]);

        $result = $apiInstance->createInvoice($createInvoiceRequest);

        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric',
            'rute_id' => 'required|exists:rute,id',
            'transportasi_id' => 'required|exists:transportasi,id',
        ]);
        $order = Order::create([
            'order_id' => $params['order_id'],
            'user_id' => $validatedData['user_id'],
            'total' => $validatedData['amount'],
            'status' => Order::STATUS_PENDING,
            'rute_id' => $validatedData['rute_id'],
            'transportasi_id' => $validatedData['transportasi_id'],
        ]);
        // dd($order);


        if (!$order) {
            throw ValidationException::withMessages(['message' => 'Failed to create order']);
        }


        $payment = new Payment;
        $payment->status = 'pending';
        // $payment->order_id = $params['order_id'];
        $payment->order_id = $order->order_id;
        $payment->user_id = $params['user_id'];
        $payment->external_id = $params['external_id'];
        $payment->checkout_url = $result['invoice_url'];
        $payment->transportasi_id = $params['transportasi_id'];
        $payment->rute_id = $params['rute_id'];

        // dd($payment);

        $payment->save();


        foreach ($params['seat'] as $seat_id) {
            $payment->seats()->attach($seat_id, ['order_id' => $order->order_id]);
        }
        // if ($payment->status == 'settled') {
        //     $order->status = Order::STATUS_COMPLETED;
        //     $order->save();
        //     return redirect()->route('history');
        // }


        return redirect($result['invoice_url']);
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



    // public function history()
    // {
    //     $user = Auth::user(); // Mendapatkan user yang sedang login

    //     // Mendapatkan riwayat pesanan untuk user yang sedang login
    //     $history = $user->orders()->with(['transportasi', 'rute'])->get();

    //     return view('client.history', compact('history'));
    // }

    public function history()
    {
        $user = Auth::user(); // Mendapatkan user yang sedang login

        // Mendapatkan riwayat pesanan yang 'completed' untuk user yang sedang login
        $completedOrders = $user->orders()->where('status', 'completed')->with(['transportasi', 'rute'])->get();

        // Mendapatkan riwayat pesanan yang 'pending' untuk user yang sedang login
        $pendingOrders = $user->orders()->where('status', 'pending')->with(['transportasi', 'rute'])->get();

        return view('client.history', compact('completedOrders', 'pendingOrders'));
    }

    public function detailTicket($order)
    {
        $order = Order::where('order_id', $order)->with(['transportasi', 'rute'])->first();
        if (!$order) {
            return redirect()->route('history')->with('error', 'Order not found');
        }

        return view('client.detail-ticket', compact('order'));
    }
}
