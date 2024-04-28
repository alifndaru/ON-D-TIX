<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Xendit\Configuration;

use Illuminate\Support\Str;
use Xendit\Invoice\Invoice;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;


class PaymentController extends Controller
{
    public function __construct()
    {
        Configuration::setXenditKey(env('XENDIT_SECRET_KEY'));
    }

    public function create(Request $request)
    {

        $apiInstance = new InvoiceApi();
        $create_invoice_request = new CreateInvoiceRequest([
            'external_id' => 'test1234',
            'description' => 'Test Invoice',
            'amount' => 10000,
            'invoice_duration' => 172800,
            'currency' => 'IDR',
            'reminder_time' => 1
        ]);
        // $for_user_id = "62efe4c33e45694d63f585f0"; // string | Business ID of the sub-account merchant (XP feature)

        try {
            $result = $apiInstance->createInvoice($create_invoice_request);
            print_r($result);
        } catch (\Xendit\XenditSdkException $e) {
            echo 'Exception when calling InvoiceApi->createInvoice: ', $e->getMessage(), PHP_EOL;
            echo 'Full Error: ', json_encode($e->getFullError()), PHP_EOL;
        }
    }
}
