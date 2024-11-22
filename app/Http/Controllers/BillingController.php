<?php
namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\BillingItem;
use App\Models\PatientVisit;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;

class BillingController extends Controller
{
    public function getPaidBills(Request $request)
    {
        $pendingBills = PatientVisit::with(['patient.person', 'billingItems'])
            ->whereHas( 'billingItems', function ($query) {
                $query->where('status', 'paid');
            })
            ->where('hospital_id', $request->user()->hospital_id)
            ->get()
            ->map(function ($visit) {
                return [
                    'visit_id' => $visit->id,
                    'patient_name' => $visit->patient->person->getName(),
                    'visit_date' => Carbon::parse($visit->created_at)->format('d/m/Y') ,
                    'total_pending' => $visit->billingItems()
                        ->where('status', 'paid')
                        ->sum('amount')
                ];
            });

        return response()->json($pendingBills);
    }

    public function getPendingBills(Request $request)
    {
        $pendingBills = PatientVisit::with(['patient.person', 'billingItems'])
            ->whereHas( 'billingItems', function ($query) {
                $query->where('status', 'pending');
            })
            ->where('hospital_id', $request->user()->hospital_id)
            ->get()
            ->map(function ($visit) {
                return [
                    'visit_id' => $visit->id,
                    'patient_name' => $visit->patient->person->getName(),
                    'visit_date' => Carbon::parse($visit->created_at)->format('d/m/Y') ,
                    'total_pending' => $visit->billingItems()
                        ->where('status', 'pending')
                        ->sum('amount')
                ];
            });

        return response()->json($pendingBills);
    }

    public function getBillDetails($visitId)
    {
        $visit = PatientVisit::with([
            'patient.person',
            'billingItems.billable'
        ])->findOrFail($visitId);

        $billDetails = [
            'visit_id' => $visit->id,
            'patient_name' => $visit->patient->person->getName(),
            'visit_date' => Carbon::parse($visit->created_at)->format('d/m/Y') ,
            'total_amount' => $visit->billingItems()->where('status', 'pending')->sum('amount'),
            'items' => $visit->billingItems()
                ->where('status', 'pending')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'description' => $item->billable->name,
                        'type' => class_basename($item->billable_type),
                        'amount' => $item->amount
                    ];
                })
        ];

        return response()->json($billDetails);
    }

    public function paymentMethods(){
        $paymentMethods = Helper::paymentMethods();
        return response()->json(['data'=>$paymentMethods]);
    }

    public function processPayment(Request $request, $visitId)
    {

     $amount = 0;
     $request->validate([
            'payment_method' => 'required|string',
            'payment_reference' => 'required|string',
            'items' => 'required|array'
        ]);

        // if($request->payment_method == "")

        $itemIds = $request->items;

        $billingItemsQuery = BillingItem::whereIn('id',$itemIds);


        $visit = PatientVisit::findOrFail($visitId);

        $invoice = Invoice::create([
            'patient_visit_id' => $visitId,
            'hospital_id' => $request->user()->hospital_id,
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'total_amount' => $billingItemsQuery->sum('amount'),
            'status' => 'paid',
            'paid_at' => now(),
            'payment_method' => $request->payment_method,
            'payment_reference' => $request->payment_reference,
            'processed_by' =>$request->user()->id
        ]);

        foreach ($billingItemsQuery->get() as $billingItem) {
            $billingItem->update(['status' => 'paid']);

            $invoice->items()->create([
                'billing_item_id' => $billingItem->id,
                'amount' => $billingItem->amount
            ]);
        }

        return response()->json([
            'message' => 'Payment processed successfully',
            'invoice_id' => $invoice->id
        ]);
    }

    public function getReceipt($invoiceId)
    {
        $invoice = Invoice::with([
            'patientVisit.patient.person',
            'items.billingItem.billable'
        ])->findOrFail($invoiceId);

        $receiptData = [
            'invoice_number' => $invoice->invoice_number,
            'date' => $invoice->created_at->format('Y-m-d H:i:s'),
            'patient_name' => $invoice->patientVisit->patient->person->getName(),
            'items' => $invoice->items->map(function ($item) {
                return [
                    'description' => $item->billingItem->billable->name,
                    'amount' => $item->amount
                ];
            }),
            'total_amount' => $invoice->total_amount,
            'payment_method' => $invoice->payment_method,
            'payment_reference' => $invoice->payment_reference
        ];
      

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('receipts.invoice_receipt_template', compact('receiptData'));
        return $pdf->download('invoice.pdf');

    }
}
