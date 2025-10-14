<?php

    namespace App\Http\Controllers\Api\Dashboard;

    use App\Helpers\utitlities\Convert;
    use App\Http\Controllers\Controller;
    use App\Models\Registration;
    use App\Models\Year;
    use Illuminate\Http\Request;

    class DashboardController extends Controller
    {
        public function registrationSummary(Request $request)
        {
            $yearId = $request->input('year');

            $year = Year::findOrFail($yearId);

            $registrations = Registration::join('students', 'students.id', '=', 'registrations.student_id')
                ->where('year_id', $yearId)->get();

            $registeredCount = $registrations->count();
            $presence = $registrations->where('presence', 1)->count();
            $absence = $registrations->where('presence', 0)->count();
            $boy = $registrations->where('gender', 'Masculin')->count();
            $girl = $registrations->where('gender', 'Féminin')->count();

            return response()->json([
                'total' => $registeredCount,
                'presence' => $presence,
                'absence' => $absence,
                'boy' => $boy,
                'girl' => $girl,
            ]);
        }

        public function billingSummary(Request $request)
        {
            $yearId = $request->input('year');

            $year = Year::findOrFail($yearId);

            $payments = Registration::join('payments', 'registrations.id', '=', 'payments.registration_id')
                ->where('year_id', $yearId)
                ->select(['registrations.scholarship', 'payments.remain', 'payments.paid'])
                ->get();

            $scholarship = 0;
            $remain = 0;
            $paid = 0;
            $percentage = 0;

            foreach ($payments as $payment) {
                $scholarship += Convert::currencyToInt($payment->scholarship);
                $remain += Convert::currencyToInt($payment->remain);
                $paid += Convert::currencyToInt($payment->paid);
            }

            $percentage = $paid / $scholarship;
            $percentage = doubleval($percentage);

            return response()->json([
                'all' => $scholarship,
                'remain' => $remain,
                'paid' => $paid,
                'percentage' => $percentage,
            ]);
        }
    }
