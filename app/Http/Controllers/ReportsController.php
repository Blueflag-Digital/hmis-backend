<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Person;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function patientsReport(Request $request)
    {
        // $data['startDate'] = $request->startDate;
        // $data['endDate'] = $request->endDate;
        try {

            if (!$hospital = $request->user()->getHospital()) {
                throw new \Exception("Hospital does not exist", 1);
            }

            if ($request->value) {
                $reportDate =  $request->report;

                if ($reportDate == 'W') {
                    $data['endDate'] = Carbon::today();
                    $data['startDate'] = $data['endDate']->copy()->subWeek();
                }

                if ($reportDate == 'M') {

                    $endDate  = Carbon::today();
                    $data['startDate']  = Carbon::parse($endDate->copy()->subMonth()->toDateString());
                    $data['endDate']  = Carbon::parse($endDate->toDateString());
                }
            } else {
                $data['startDate'] = $request->startDate ? Carbon::parse($request->startDate) : Carbon::today();
                $data['endDate'] = $request->endDate ? Carbon::parse($request->endDate) : Carbon::today();
            }

            $patientsQuery = Patient::where('hospital_id', $hospital->id)
                ->whereBetween('created_at', [$data['startDate'], $data['endDate']])
                ->latest();

            $paginatedData = $patientsQuery->get();
            $count = $patientsQuery->count();


            // $paginatedData = Patient::where('hospital_id', $hospital->id)->whereBetween('created_at', [$data['startDate'], $data['endDate']])->latest()->get();
            // $count = Patient::where('hospital_id', $hospital->id)->whereBetween('created_at', [$data['startDate'], $data['endDate']])->count();

            $patients = $paginatedData->map(function ($patient) {
                return $patient->patientData();
            });

            info($data['startDate']->format('d/m/Y'));
            info($data['endDate']->format('d/m/Y'));

            if ($patients->count() > 0) {
                $pdf = app('dompdf.wrapper');
                $pdf->loadView('reports.patients', compact('patients', 'count', 'data'));
                return $pdf->download('report.pdf');
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
            info($th->getMessage());
        }
    }
}
