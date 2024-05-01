<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Xendit\Configuration;
use Xendit\Invoice\Invoice;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;
use Illuminate\Support\Str;
use Xendit\VirtualAccounts;
use Illuminate\Support\Facades\Auth;
use App\Models\Seat;
use Xendit\BalanceAndTransaction\TransactionApi;

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
            'payer_email' => $request->input('payer_email'),
            'description' => 'Payment for order',
            'user_id' => $request->input('user_id', Auth::id()),
            'checkout_url' => 'test.com',
            'seat' => $request->input('seat'),
            'transportasi_id' => $request->input('transportasi_id'),
            'rute_id' => $request->input('rute_id'),
        ];

        // dd($params);

        // Check if any of the selected seats are already booked
        // foreach ($params['seat'] as $seat_id) {
        //     $seat = Seat::find($seat_id);
        //     if ($seat->is_booked) {
        //         return redirect()->back()->with('error', 'One or more of the selected seats are already booked');
        //         // Session::flash('message', 'One or more of the selected seats are already booked');
        //         // return response()->json(['message' => 'One or more of the selected seats are already booked'], 400);
        //     }
        // }
        foreach ($params['seat'] as $seat_id) {
            $seat = Seat::find($seat_id);
            if ($seat->is_booked) {
                // return redirect()->back()->with('error', 'One or more of the selected seats are already booked');
                return response()->json(['message' => 'One or more of the selected seats are already booked'], 400);
            }
        }
        $apiInstance = new InvoiceApi();
        $createInvoiceRequest = new CreateInvoiceRequest([
            'external_id' => $params['external_id'],
            'amount' => $params['amount'],
            'description' => $params['description'],
            'payer_email' => $params['payer_email'],
        ]);

        $result = $apiInstance->createInvoice($createInvoiceRequest);

        $payment = new Payment;
        $payment->status = 'pending';
        $payment->user_id = $params['user_id'];
        $payment->external_id = $params['external_id'];
        $payment->checkout_url = $result['invoice_url'];
        $payment->transportasi_id = $params['transportasi_id'];
        $payment->rute_id = $params['rute_id'];
        $payment->save();
        // $payment->seats()->attach($request->input('seat'));

        foreach ($params['seat'] as $seat_id) {
            $payment->seats()->attach($seat_id);
        }

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
            if ($payment->status === 'settled') {
                return response()->json(['message' => 'Payment has been already processed']);
            }
            if (!isset($result['status'])) {
                return response()->json(['message' => 'Status not found'], 404);
            }

            $payment->status = strtolower($result['status']);
            $payment->save();

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
