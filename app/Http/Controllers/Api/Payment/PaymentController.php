<?php

    namespace App\Http\Controllers\Api\Payment;

    use App\Helpers\Constants\AppText;
    use App\Helpers\utitlities\Convert;
    use App\Http\Controllers\Controller;
    use App\Models\Configuration;
    use App\Models\Payment;
    use App\Models\Receipt;
    use App\Models\Registration;
    use App\Models\ScholarshipFeed;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;

    class PaymentController extends Controller
    {
        public function getAll(Request $request): JsonResponse
        {
            $id = $request->input('year');
            $presence = $request->input('presence') ?? 1;

            if (!$id) return response()->json(['message' => AppText::parameterNotFound()], 404);

            $payments = Payment::join('registrations', 'payments.registration_id', '=', 'registrations.id')
                ->join('students', 'registrations.student_id', '=', 'students.id')
                ->join('classrooms', 'registrations.classroom_id', '=', 'classrooms.id')
                ->where('registrations.year_id', $id)
                ->where('presence', $presence)
                ->select([
                    'payments.id',
                    'registrations.discount',
                    'registrations.bourse',
                    'registrations.sold_out',
                    'registrations.scholarship',
                    'registrations.presence',
                    'registrations.periods',
                    'payments.paid',
                    'payments.remain',
                    'payments.registration_id',
                    'students.matricule',
                    'classrooms.name as classroomName',
                    'students.firstname',
                    'students.lastname',
                ])
                ->orderBy('classrooms.position')
                ->orderBy('classrooms.name')
                ->orderBy('students.firstname')
                ->orderBy('students.lastname')
                ->get();

            return response()->json($payments);
        }

        public function reset(int $id): JsonResponse
        {
            /** @var Payment $payment */
            $payment = Payment::findOrFail($id);

            $registration = $payment->registration;
            $fullname = $registration->student->fullname();

            $payment->remain = $registration->scholarship;
            $payment->paid = Convert::intToCurrency(0);
            $payment->save();

            $payment->receipts()->delete();

            return response()->json(['message' => AppText::paymentResetSuccessfully($fullname)]);
        }
    }
