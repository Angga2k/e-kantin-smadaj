<?php

namespace App\Services;

use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;
use GuzzleHttp\Client;
use Exception;

class XenditService
{
    protected $client;

    public function __construct()
    {
        // Set API Key untuk SDK Invoice
        Configuration::setXenditKey(env('XENDIT_SECRET_KEY'));

        // HTTP Client untuk Disbursement
        $this->client = new Client([
            'base_uri' => 'https://api.xendit.co/',
            'auth' => [env('XENDIT_SECRET_KEY'), ''], // Basic Auth
        ]);
    }

    /**
     * =======================================================
     * ===============  MONEY IN (INVOICE) ====================
     * =======================================================
     */
    public function createInvoice($externalId, $amount, $payerEmail, $description)
    {
        $apiInstance = new InvoiceApi();

        $create_invoice_request = new CreateInvoiceRequest([
            'external_id' => $externalId,
            'amount' => (float)$amount,
            'payer_email' => $payerEmail,
            'description' => $description,
            // --- TAMBAHAN PENTING ---
            // Tetapkan durasi invois kepada 30 minit (1800 saat)
            'invoice_duration' => 1800, 
            // ------------------------

            
            'success_redirect_url' => route('payment.status', ['status' => 'success']),

            // "Kalau user gagal bayar/expired, lempar ke sini"
            'failure_redirect_url' => route('payment.status', ['status' => 'failed']),
        ]);

        try {
            return $apiInstance->createInvoice($create_invoice_request);
        } catch (Exception $e) {
            throw new Exception("Gagal membuat invoice: " . $e->getMessage());
        }
    }

    /**
     * Helper: Hitung Total Bayar + Biaya Admin
     * * @param float $amount Total belanja
     * @param string $type Jenis pembayaran ('E_WALLET' atau 'BANK_TRANSFER')
     * @return float Total yang harus dibayar
     */
    public function calculateTotalWithFee($amount, $type)
    {
        if ($type === 'E_WALLET') {
            // Biaya 2%
            return $amount + ($amount * 0.02); 
        } elseif ($type === 'BANK_TRANSFER') {
            // Biaya Flat 4500
            return $amount + 4500; 
        }
        
        // Default tanpa biaya tambahan
        return $amount;
    }


    /**
     * =======================================================
     * ==============  MONEY OUT (DISBURSEMENT) ==============
     * =======================================================
     * Disbursement sudah TIDAK ada di SDK v7
     * Jadi harus pakai HTTP API (Guzzle)
     */
    public function createDisbursement($externalId, $amount, $bankCode, $accountName, $accountNumber)
    {
        try {
            $response = $this->client->post('disbursements', [
                'json' => [
                    'external_id' => $externalId,
                    'amount' => (int)$amount,
                    'bank_code' => strtoupper($bankCode),
                    'account_holder_name' => $accountName,
                    'account_number' => $accountNumber,
                    'description' => 'Penarikan Dana E-Kantin',
                ]
            ]);

            return json_decode($response->getBody(), true);

        } catch (Exception $e) {
            throw new Exception("Gagal membuat disbursement: " . $e->getMessage());
        }
    }
}
