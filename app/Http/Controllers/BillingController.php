<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\BillingItem;
use App\Models\PatientVisit;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Models\Patient;
use App\Models\Person;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;

class BillingController extends Controller
{


    public function searchBillByParam($request){

        $patientId = null;
        $message = "This patient is not found";
        if(!$person = Person::where('first_name','like','%'.$request['searchData']['nameParam'].'%')->first()){
            throw new \Exception($message, 1);
        }

        if(!$patient = Patient::where('person_id',$person->id)->first()){
            throw new \Exception($message, 1);
        }
         //person is found.
        $patientId  = $patient->id;
        //search in the

        $bills = PatientVisit::with(['patient.person:id,name', 'billingItems'])
                ->whereHas('billingItems', function ($query) use ($request) {
                    $query->where('status', $request['searchData']['statusParam']);
                })
                ->where('hospital_id', $request->user()->hospital_id)
                ->where('patient_id', $patientId)
                ->take(20)
                ->get()
                ->map(function ($visit) use ($request) {
                    return [
                        'visit_id' => $visit->id,
                        'patient_name' => $visit->patient->person->getName(),
                        'visit_date' => Carbon::parse($visit->created_at)->format('d/m/Y'),
                        // Use the correct variable and access the billingItems property
                        'total_pending' => $visit->billingItems
                            ->where('status', $request['searchData']['statusParam']) // Correctly use $searchData['statusParam']
                            ->sum('amount')
                    ];
                });

            return $bills;

    }

    public function searchBill(Request $request){

        $searchData = [
            'nameParam' =>$request->name,
            'statusParam' => $request->status,
            'byDate' => $request->byDate,
            'date_between' => $request->date_between
        ];
        $request['searchData'] = $searchData;

        $bills = $this->searchBillByParam($request);
        return response()->json([
            'data'=>$bills
        ]);
    }


    public function getBillsByStatus(Request $request, $status)
    {

        $bills = PatientVisit::with(['patient.person', 'billingItems'])
            ->whereHas('billingItems', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->where('hospital_id', $request->user()->hospital_id)
            ->take(20)
            ->get()
            ->map(function ($visit) use ($status) {

                $total = $visit->billingItems()
                        ->where('status', $status)
                        ->sum('amount');

                return [
                    'visit_id' => $visit->id,
                    'patient_name' => $visit->patient->person->getName(),
                    'visit_date' => Carbon::parse($visit->created_at)->format('d/m/Y'),
                    'total_pending' => number_format($total,2)
                ];
            });
        return $bills;
    }
    public function getPaidBills(Request $request)
    {
        $paidBills = $this->getBillsByStatus($request, 'paid');

        return response()->json($paidBills);
    }

    public function getPendingBills(Request $request)
    {
        $pendingBills = $this->getBillsByStatus($request, 'pending');
        return response()->json($pendingBills);
    }

    public function getBillDetailsByStatus($visitId, $status)
    {
        $visit = PatientVisit::with([
            'patient.person',
            'billingItems.billable'
        ])->findOrFail($visitId);

        $billDetails = [
            'visit_id' => $visit->id,
            'patient_name' => $visit->patient->person->getName(),
            'visit_date' => Carbon::parse($visit->created_at)->format('d/m/Y'),
            'total_amount' => $visit->billingItems()->where('status', $status)->sum('amount'),
            'items' => $visit->billingItems()
                ->where('status', $status)
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'description' => $item->billable->name ?? null,
                        'type' => class_basename($item->billable_type),
                        'amount' => number_format($item->amount,2)
                    ];
                })
        ];
        return  $billDetails;
    }
    public function getBillDetails(Request $request, $visitId)
    {
        $status = 'pending';
        if ($request->status == 'paid') {
            $status = 'paid';
        }
        $billDetails =  $this->getBillDetailsByStatus($visitId, $status);
        return response()->json($billDetails);
    }

    public function paymentMethods()
    {
        $paymentMethods = Helper::paymentMethods();
        return response()->json(['data' => $paymentMethods]);
    }

    public function processPayment(Request $request, $visitId)
    {

        $request->validate([
            'payment_method' => 'required|string',
            'payment_reference' => 'required|string',
            'items' => 'required|array'
        ]);

        // if($request->payment_method == "")

        $itemIds = $request->items;

        $billingItemsQuery = BillingItem::whereIn('id', $itemIds);


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
            'processed_by' => $request->user()->id
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

    public function printBillReceipt(Request $request){
        $visitId = $request->visitId;
        if(!$patientVisit = PatientVisit::find($visitId)){
            throw new \Exception("Visit with this id does not exist", 1);
        }
        if(!$billingItems = BillingItem::where('patient_visit_id',$visitId)->where('status','pending')->get()){
             throw new \Exception("billing item does not exist", 1);
        }
        //get the patients personal information
        $data['patientsInformation'] = $patientVisit->patient->person->personData2();
        $data['hospital'] = $request->user()->getHospital();
        $data['served_by'] = $request->user()->userData();


        $data['items'] = $billingItems->map(function($item){
            return [
                    'description' => $item->billable->name,
                    'amount' => $item->amount,
                    'unit_price' =>$item->unit_price,
                    'quantity' =>$item->quantity,
                    'total_amount' => number_format(($item->unit_price * $item->quantity),2),
                    'bill_date' =>Carbon::parse($item->created_at)->format('d/m/Y')
                ];
        });

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('receipts.bill-receipt-template', compact('data'));
        return $pdf->download('invoice.pdf');

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
                    'description' => $item->billable->name,
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
