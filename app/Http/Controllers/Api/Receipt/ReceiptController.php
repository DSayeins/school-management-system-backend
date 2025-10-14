<?php

    namespace App\Http\Controllers\Api\Receipt;

    use App\Helpers\Constants\AppText;
    use App\Helpers\utitlities\Convert;
    use App\Http\Controllers\Controller;
    use App\Http\Requests\Api\Receipt\ReceiptRequest;
    use App\Models\Classroom;
    use App\Models\Configuration;
    use App\Models\Payment;
    use App\Models\Receipt;
    use App\Models\Registration;
    use App\Models\Student;
    use App\Models\Year;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\DB;

    class ReceiptController extends Controller
    {
        public function all(Request $request)
        {
            $yearId = $request->input('year');
            $presence = $request->input('presence') ?? 1;

            /** @var Year $year */
            $year = Year::findOrFail($yearId);

            $receipts = Receipt::join('payments', 'receipts.payment_id', '=', 'payments.id')
                ->join('registrations', 'payments.registration_id', '=', 'registrations.id')
                ->join('students', 'registrations.student_id', '=', 'students.id')
                ->where('registrations.year_id', $year->id)
                ->where('presence', $presence)
                ->select(['students.firstname', 'students.lastname', 'receipts.*'])
                ->orderBy('id', 'desc')
                ->get();

            return response()->json($receipts);
        }

        public function store(ReceiptRequest $request): JsonResponse
        {
            $data = $request->validated();

            /** @var Payment $payment */
            $payment = Payment::findOrFail($data['payment_id']);

            /** @var Registration $registration */
            $registration = Registration::findOrFail($payment->registration_id);

            /** @var Configuration $configuration */
            $configuration = Configuration::where('year_id', $registration->year_id)->first();

            if (!$configuration) return response()->json(['message' => AppText::configNotFound()], 404);

            if ($data['number'] == 0) {
                /** @var Receipt $last */
                $last = Receipt::orderBy('id')->get()->last();
                $data['auto_number'] = $this->setAutoNumber($last);
            }

            $data['amount'] = Convert::intToCurrency($data['amount']);
            $amount = Convert::currencyToInt($data['amount']);

            $newPaid = 0;
            $oldPaid = Convert::currencyToInt($payment->paid);
            $oldRemain = Convert::currencyToInt($payment->remain);
            $newRemain = 0;

            $receipt = null;

            DB::transaction(function () use (
                $data,
                $registration,
                $configuration,
                $payment,
                &$receipt,
                &$newPaid,
                &$newRemain
            ) {
                /** @var Receipt $last */
                $receipt = Receipt::create($data);

                $amount = Convert::currencyToInt($data['amount']);
                $paid = Convert::currencyToInt($payment->paid);
                $remain = Convert::currencyToInt($payment->remain);

                $newPaid = $amount + $paid;
                $newRemain = $remain - $amount;

                // on met a jour le paiement
                $payment->paid = Convert::intToCurrency($newPaid);
                $payment->remain = Convert::intToCurrency($newRemain);
                $payment->save();
            });

            /** @var Student $student */
            $student = Student::find($registration->student_id);

            /** @var Classroom $classroom */
            $classroom = Classroom::find($registration->classroom_id);

            /** @var Year $year */
            $year = Year::find($registration->year_id);

            $feed = Convert::currencyToInt($configuration->registration_fees);
            $scholarship = Convert::currencyToInt($registration->scholarship);

            if (($registration->bourse == 0 && $registration->discount == 0) && $configuration->includeRegistrationFeed) {
                $scholarship -= $feed;
            }

            $period = $registration->periods;
            $part = intval($scholarship / $period);

            $firstPart = ($registration->bourse == 0 && $registration->discount == 0
                && $configuration->includeRegistrationFeed) ? $part + $feed : $part;

            $title = '';
            $slice = 0;
            $toPaid = 0;
            $detailRemain = 0;
            $detailToPaid = 0;

            if ($newPaid <= $firstPart) {
                $title = '1ere tranche';
                $slice = $firstPart;
                $toPaid = $slice - $oldPaid;

                $detailRemain = $firstPart - $newPaid;
            } else if ($newPaid <= ($firstPart + $part)) {
                $title = '2eme tranche';
                $slice = $part;
                $toPaid = ($firstPart + $part) - $oldPaid;


                $detailRemain = ($firstPart + $part) - $newPaid;
            } else {
                $title = '3eme tranche';
                $slice = $part;
                $toPaid = $scholarship - $oldPaid;

                $detailRemain = $scholarship - $newPaid;
            }


            if ($newRemain <= 0) {
                $registration->sold_out = 1;
                $registration->save();
            }

            $response = [
                'number' => $receipt->number == 0 ? $receipt->auto_number : $receipt->number,
                'matricule' => $student->matricule,
                'fullname' => $student->fullname(),
                'classroom' => $classroom->name,
                'year' => $year->name,
                'toPaid' => Convert::intToCurrency($oldRemain),
                'amount' => Convert::intToCurrency($amount),
                'remain' => Convert::intToCurrency($newRemain),
                'discount' => $registration->discount,
                'periods' => $registration->periods,
                'regime' => $registration->kind,
                'kind' => $receipt->kind,
                'soldOut' => $registration->sold_out,
                'detail' => [
                    'title' => $title,
                    'slice' => Convert::intToCurrency($slice),
                    'toPaid' => Convert::intToCurrency($toPaid),
                    'amount' => Convert::intToCurrency($amount),
                    'remain' => Convert::intToCurrency($detailRemain),
                ]
            ];

            return response()->json($response);
        }

        private function setAutoNumber(?Receipt $receipt): string
        {
            $autoNumber = '';

            $currentYear = Carbon::now()->year;
            $currentMonth = Carbon::now()->month;

            if ($receipt) {
                $_autoNumber = $receipt->auto_number;
                $tab = explode('/', $_autoNumber);
                $subTab = explode('-', $tab[1]);

                if (count($subTab) == 3) {
                    $lastYear = intval($subTab[0]);
                    $lastMonth = intval($subTab[1]);
                    $lastValue = intval($subTab[2]);

                    $value = ($currentYear != $lastYear || $currentMonth != $lastMonth) ? 1 : $lastValue + 1;

                    $autoNumber = "REF/$currentYear-$currentMonth-$value";
                }

            } else {
                $autoNumber = "REF/$currentYear-$currentMonth-1";
            }

            return $autoNumber;
        }
    }
