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


class PaymentController extends Controller
{
    public function __construct()
    {
        Configuration::setXenditKey(env('XENDIT_SECRET_KEY'));
    }


    // public function create1(Request $request)
    // {
    //     $userId = $request->input('user_id', Auth::id());
    //     $amount = $request->input('amount');
    //     $payerEmail = $request->input('payer_email');
    //     $externalId = (string) Str::uuid();

    //     // dd($amount);

    //     // Store order details in the database
    //     // dd($payment);

    //     // Generate checkout URL using Xendit API
    //     $apiInstance = new InvoiceApi();
    //     $createInvoiceRequest = new CreateInvoiceRequest([
    //         'external_id' => $externalId,
    //         'amount' => $amount,
    //         'payer_email' => $payerEmail,
    //     ]);

    //     try {
    //         $payment = Payment::create([
    //             'user_id' => $userId,
    //             'external_id' => $externalId, // Use external ID to match payment with order
    //             'payer_email' => $payerEmail, // Store payer email for reference
    //             'status' => 'pending', // Set initial status as pending
    //             'checkout_url' => "test.com", // Add this line
    //         ]);
    //         $result = $apiInstance->createInvoice($createInvoiceRequest);
    //         $payment->update(['checkout_url' => $result['invoice_url']]);

    //         return response()->json(['checkout_url' => $result['invoice_url']]);
    //     } catch (\Xendit\XenditSdkException $e) {
    //         // Handle exceptions
    //         return back()->withError('Failed to create invoice: ' . $e->getMessage());
    //     }
    // }

    public function create(Request $request)
    {
        $params = [
            'external_id' => (string) Str::uuid(),
            'amount' => $request->input('amount'),
            'payer_email' => $request->input('payer_email'),
            'description' => 'Payment for order',
            'user_id' => $request->input('user_id', Auth::id()),
            'checkout_url' => 'test.com',
        ];

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
        $payment->save();
        return redirect($result['invoice_url']);
    }

    // public function webhook(Request $request)
    // {
    //     $apiInstance = new InvoiceApi();
    //     $result = $apiInstance->getInvoiceById($request->id);

    //     $payment = Payment::where('external_id', $request->external_id)->first();
    //     if ($payment->status === 'settled') {
    //         return response()->json(['message' => 'Payment has been already processed']);
    //     }

    //     $payment->status = strtolower($result['status']);
    //     $payment->save();

    //     return response()->json(['message' => 'Payment status successfully updated']);

    // }
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
            return response()->json(['message' => 'Payment status successfully updated']);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
