<?php

    namespace App\Http\Controllers\Api\Reminder;

    use App\Helpers\Constants\AppText;
    use App\Helpers\utitlities\Convert;
    use App\Http\Controllers\Controller;
    use App\Models\Configuration;
    use App\Models\Student;
    use App\Models\Year;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;

    class ReminderController extends Controller
    {
        public function part(Request $request, int $value): JsonResponse
        {
            $yearId = $request->input('year');
            $presence = $request->get('presence') ?? 1;

            /** @var Year $year */
            $year = Year::findOrFail($yearId);

            /** @var Configuration $configuration */
            $configuration = Configuration::where('year_id', $yearId)->first();

            if (!$configuration) return response()->json(['message' => AppText::configNotFound()]);

            $reminders = Student::join('registrations', 'students.id', '=', 'registrations.student_id')
                ->join('classrooms', 'registrations.classroom_id', '=', 'classrooms.id')
                ->join('payments', 'registrations.id', '=', 'payments.registration_id')
                ->where('year_id', $yearId)
                ->where('presence', $presence)
                ->orderBy('classrooms.position')
                ->orderBy('classrooms.name')
                ->select(['students.id', 'students.matricule', 'students.firstname', 'students.lastname', 'students.birthday',
                    'classrooms.name as classroom', 'registrations.kind', 'registrations.periods', 'registrations.bourse',
                    'registrations.discount', 'registrations.scholarship', 'payments.paid', 'registrations.periods'])
                ->get();

            $data = [];
            $registrationFeed = Convert::currencyToInt($configuration->registration_fees);
            $includeFeed = $configuration->includeRegistrationFeed === 1;

            foreach ($reminders as $reminder) {
                $part = 0;
                $scholarship = Convert::currencyToInt($reminder->scholarship);
                $paid = Convert::currencyToInt($reminder->paid);
                $discount = $reminder->discount;
                $bourse = $reminder->bourse;
                $periods = $reminder->periods;

                $registrationValue = ($discount == 0 && $bourse == 0 && $includeFeed) ? $registrationFeed : 0;

                $part = ($scholarship - $registrationValue) / 3;
                $part = round($part);

                if ($value == 1) {
                    $part += $registrationValue;

                    if ($paid < $part) {
                        $data[] = [
                            'id' => $reminder->id,
                            'firstname' => $reminder->firstname,
                            'lastname' => $reminder->lastname,
                            'birthday' => $reminder->birthday,
                            'matricule' => $reminder->matricule,
                            'classroom' => $reminder->classroom,
                            'scholarship' => $scholarship,
                            'part' => $part,
                            'paid' => $paid,
                            'remain' => ($part - $paid)
                        ];
                    }
                } else if ($value == 2) {
                    $feed = $part * 2 + $registrationValue;
                    $newPart = $part + $registrationValue;

                    $newPaid = $paid > $newPart ? $paid - $newPart : 0;
                    $other = $paid < $newPart ? $newPart - $paid : 0;

                    if ($paid < $feed) {
                        $data[] = [
                            'id' => $reminder->id,
                            'firstname' => $reminder->firstname,
                            'lastname' => $reminder->lastname,
                            'birthday' => $reminder->birthday,
                            'matricule' => $reminder->matricule,
                            'classroom' => $reminder->classroom,
                            'scholarship' => $scholarship,
                            'part' => $part,
                            'paid' => $newPaid,
                            'other' => $other,
                            'remain' => ($part - $newPaid + $other)
                        ];
                    }
                } else {
                    $newPart = $part * 2 + $registrationValue;
                    $newPaid = $paid > $newPart ? $paid - $newPart : 0;
                    $other = $paid < $newPart ? $newPart - $paid : 0;

                    $total = $part + $other;

                    if ($paid < $scholarship) {
                        $data[] = [
                            'id' => $reminder->id,
                            'firstname' => $reminder->firstname,
                            'lastname' => $reminder->lastname,
                            'birthday' => $reminder->birthday,
                            'matricule' => $reminder->matricule,
                            'classroom' => $reminder->classroom,
                            'scholarship' => $scholarship,
                            'part' => $part,
                            'paid' => $newPaid,
                            'other' => $other,
                            'remain' => ($part - $newPaid + $other),
                            'total' => $part + $other,
                        ];
                    }
                }
            }

            return response()->json($data);
        }
    }
