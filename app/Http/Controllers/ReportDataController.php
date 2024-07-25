<?php

namespace App\Http\Controllers;

use App\Models\DiagnosisCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportDataController extends Controller
{
    /**
     * REPORT DATA :: Get Data For a Report
     */
    public function getReportData(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        if (!$startDate || !$endDate) {
            return response()->json(['error' => 'start_date and end_date are required'], 400);
        }

        // Data Point 1: Patients seen by workplace
        $workplaceData = DB::table('patient_visits')
            ->join('patients', 'patient_visits.patient_id', '=', 'patients.id')
            ->join('people', 'patients.person_id', '=', 'people.id')
            ->select('people.work_place_id', DB::raw('count(*) as total'))
            ->whereBetween('patient_visits.created_at', [$startDate, $endDate])
            ->groupBy('people.work_place_id')
            ->get();

        $workplaceDataFormatted = [];
        foreach ($workplaceData as $data) {
            $workplaceDataFormatted[] = [
                'workplace' => $data->work_place_id, // Replace with workplace name if available
                'patients_seen' => $data->total,
            ];
        }

        // Data Point 2: Reason for visit
        $reasonData = DB::table('consultations')
            ->join('patient_visits', 'consultations.patient_visit_id', '=', 'patient_visits.id')
            ->select('consultations.reason_for_visit', DB::raw('count(*) as total'))
            ->whereBetween('consultations.created_at', [$startDate, $endDate])
            ->groupBy('consultations.reason_for_visit')
            ->get();

        $reasonDataFormatted = [
            'Total Clinic Visits' => $reasonData->sum('total'),
            'Medical Treatment Cases' => $reasonData->where('reason_for_visit', 'Medical Treatment')->sum('total'),
            'First Aid Cases' => $reasonData->where('reason_for_visit', 'First Aid')->sum('total'),
            'Injury on Duty Cases' => $reasonData->where('reason_for_visit', 'Injury on Duty')->sum('total'),
            'Restricted Work Case' => $reasonData->where('reason_for_visit', 'Restricted Work')->sum('total'),
        ];

        // Data Point 3: Diagnosis for bar graph
        $diagnosisData = DB::table('consultations')
            ->select('consultations.diagnosis_ids')
            ->whereBetween('consultations.created_at', [$startDate, $endDate])
            ->get();

        $diagnosisCounts = [];
        foreach ($diagnosisData as $consultation) {
            $diagnoses = json_decode($consultation->diagnosis_ids, true);
            if (is_array($diagnoses)) {
                foreach ($diagnoses as $diagnosisId) {
                    if (!isset($diagnosisCounts[$diagnosisId])) {
                        $diagnosisCounts[$diagnosisId] = 0;
                    }
                    $diagnosisCounts[$diagnosisId]++;
                }
            }
        }

        $diagnosisDataFormatted = [];
        foreach ($diagnosisCounts as $diagnosisId => $count) {
            $diagnosis = DiagnosisCode::find($diagnosisId);
            if ($diagnosis) {
                $diagnosisDataFormatted[] = [
                    'diagnosis' => $diagnosis->diagnosis,
                    'count' => $count,
                ];
            }
        }

        // Data Point 4: Patients seen by workplace for pie chart (same as Data Point 1)
        $pieChartData = $workplaceDataFormatted;

        return response()->json([
            'data_point_1' => $workplaceDataFormatted,
            'data_point_2' => $reasonDataFormatted,
            'data_point_3' => $diagnosisDataFormatted,
            'data_point_4' => $pieChartData,
        ]);
    }
}
