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



    public function getAllTransactions()
    {
        $apiInstance = new TransactionApi();
        $for_user_id = "5dbf20d7c8eb0c0896f811b6"; // string | The sub-account user-id that you want to make this transaction for. This header is only used if you have access to xenPlatform. See xenPlatform for more information
        $types = ["DISBURSEMENT", "PAYMENT"]; // \BalanceAndTransaction\TransactionTypes[] | Transaction types that will be included in the result. Default is to include all transaction types
        $statuses = ["SUCCESS", "PENDING"]; // \BalanceAndTransaction\TransactionStatuses[] | Status of the transaction. Default is to include all status.
        $channel_categories = ["BANK", "INVOICE"]; // \BalanceAndTransaction\ChannelsCategories[] | Payment channels in which the transaction is carried out. Default is to include all channels.
        $reference_id = "ref23232"; // string | To filter the result for transactions with matching reference given (case sensitive)
        $product_id = "d290f1ee-6c54-4b01-90e6-d701748f0701"; // string | To filter the result for transactions with matching product_id (a.k.a payment_id) given (case sensitive)
        $account_identifier = "123123123"; // string | Account identifier of transaction. The format will be different from each channel. For example, on `BANK` channel it will be account number and on `CARD` it will be masked card number.
        $amount = 100; // float | Specific transaction amount to search for
        $currency = new \Xendit\BalanceAndTransaction\Currency(); // Currency
        $created = array('key' => new \Xendit\BalanceAndTransaction\DateRangeFilter()); // DateRangeFilter | Filter time of transaction by created date. If not specified will list all dates.
        $updated = array('key' => new \Xendit\BalanceAndTransaction\DateRangeFilter()); // DateRangeFilter | Filter time of transaction by updated date. If not specified will list all dates.
        $limit = 10; // float | number of items in the result per page. Another name for \"results_per_page\"
        $after_id = null; // string
        $before_id = null; // string
        try {
            $result = $apiInstance->getAllTransactions($for_user_id, $types, $statuses, $channel_categories, $reference_id, $product_id, $account_identifier, $amount, $currency, $created, $updated, $limit, $after_id, $before_id);
            print_r($result);
        } catch (\Xendit\XenditSdkException $e) {
            echo 'Exception when calling TransactionApi->getAllTransactions: ', $e->getMessage(), PHP_EOL;
            echo 'Full Error: ', json_encode($e->getFullError()), PHP_EOL;
        }
    }
}
