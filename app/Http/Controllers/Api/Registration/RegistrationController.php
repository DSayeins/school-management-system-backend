<?php

    namespace App\Http\Controllers\Api\Registration;

    use App\Helpers\Constants\AppText;
    use App\Helpers\utitlities\Calculate;
    use App\Helpers\utitlities\Convert;
    use App\Http\Controllers\Controller;
    use App\Http\Requests\Api\Registration\RegistrationRequest;
    use App\Models\Classroom;
    use App\Models\Configuration;
    use App\Models\Level;
    use App\Models\Payment;
    use App\Models\Registration;
    use App\Models\ScholarshipFeed;
    use App\Models\Student;
    use App\Models\Year;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;

    class RegistrationController extends Controller
    {
        public function all(Request $request): JsonResponse
        {
            $id = $request->input('year');
            $presence = $request->input('presence') ?? 1;

            /** @var Year $year */
            $year = Year::findOrFail($id);

            $registrations = Student::join('registrations', 'students.id', '=', 'registrations.student_id')
                ->join('classrooms', 'registrations.classroom_id', '=', 'classrooms.id')
                ->join('levels', 'classrooms.level_id', '=', 'levels.id')
                ->where('year_id', $id)
                ->where('presence', $presence)
                ->select([
                    'registrations.id as registration_id',
                    'registrations.presence',
                    'registrations.sold_out',
                    'registrations.discount',
                    'registrations.bourse',
                    'registrations.kind',
                    'registrations.periods',
                    'registrations.redouble',
                    'registrations.insurance',
                    'registrations.brothers',
                    'registrations.previous_school',
                    'registrations.redouble',
                    'registrations.kind',
                    'registrations.scholarship',
                    'registrations.classroom_id',
                    'registrations.student_id',
                    'levels.id as level_id',
                    'classrooms.name as classroom',
                    'students.id',
                    'students.matricule',
                    'students.firstname',
                    'students.lastname',
                    'students.gender',
                    'students.nationality',
                    'students.birthday',
                    'students.birthday_place',
                    'students.arrival'
                ])
                ->with('contacts:id,fullname,status,profession,email,phone_1,phone_2,phone_3,phone_4,whatsapp')
                ->orderBy('classrooms.position')
                ->orderBy('classrooms.name')
                ->orderBy('students.firstname')
                ->orderBy('students.lastname')
                ->get();

            $table = [];

            foreach ($registrations as $registration) {
                $row = [];

                $row = [
                    'id' => $registration->registration_id,
                    'matricule' => $registration->matricule,
                    'firstname' => $registration->firstname,
                    'lastname' => $registration->lastname,
                    'gender' => $registration->gender,
                    'nationality' => $registration->nationality,
                    'birthday' => $registration->birthday,
                    'birthdayPlace' => $registration->birthday_place,
                    'arrival' => intval($registration->arrival),
                    'presence' => $registration->presence,
                    'discount' => $registration->discount,
                    'periods' => $registration->periods,
                    'bourse' => $registration->bourse,
                    'kind' => $registration->kind,
                    'previousSchool' => $registration->previous_school,
                    'insurance' => $registration->insurance,
                    'redouble' => $registration->redouble,
                    'classroom' => $registration->classroom,
                    'scholarship' => $registration->scholarship,
                    'classroomId' => $registration->classroom_id,
                    'studentId' => $registration->student_id,
                    'levelId' => $registration->level_id,
                    'nomPere' => '',
                    'professionPere' => '',
                    'emailPere' => '',
                    'phone1Pere' => '',
                    'phone2Pere' => '',
                    'phone3Pere' => '',
                    'phone4Pere' => '',
                    'whatsappPere' => '',
                    'nomMere' => '',
                    'professionMere' => '',
                    'emailMere' => '',
                    'phone1Mere' => '',
                    'phone2Mere' => '',
                    'phone3Mere' => '',
                    'phone4Mere' => '',
                    'whatsappMere' => '',
                ];

                foreach ($registration->contacts as $contact) {
                    $status = strtolower($contact->status);

                    if ($status == 'mère') $status = 'Mere';
                    if ($status == 'père') $status = 'Pere';

                    $row["nom{$status}"] = $contact->fullname;
                    $row["profession{$status}"] = $contact->profession;
                    $row["email{$status}"] = $contact->email;
                    $row["phone1{$status}"] = $contact->phone_1;
                    $row["phone2{$status}"] = $contact->phone_2;
                    $row["phone3{$status}"] = $contact->phone_3;
                    $row["phone4{$status}"] = $contact->phone_4;
                    $row["whatsapp{$status}"] = $contact->whatsapp;
                }

                $table [] = $row;
            }

            return response()->json($table);
        }

        public function store(RegistrationRequest $request): JsonResponse
        {
            $data = $request->validated();

            /** @var ScholarshipFeed $feed */
            $feed = ScholarshipFeed::where('classroom_id', $data['classroom_id'])
                ->where('year_id', $data['year_id'])
                ->first();

            if (!$feed) return response()->json(['message' => AppText::feedNotfound()], status: 422);

            /** @var Configuration $configuration */
            $configuration = Configuration::where('year_id', $data['year_id'])->first();

            if (!$configuration) return response()->json(['message' => AppText::configNotFound()], status: 422);

            $amount = $data["kind"] == 'Normal' ? $feed->normal : $feed->subvention;

            $scholarship = Calculate::feed([
                'discountValue' => $configuration->discount,
                'bourseValue' => $configuration->bourse,
                'registrationFees' => $configuration->registration_fees,
                'periods' => $data['periods'],
                'amount' => $amount,
                'discountState' => $data['discount'],
                'bourseState' => $data['bourse'],
                'includeRegistrationFees' => $configuration->includeRegistrationFeed,
            ]);

            $data['scholarship'] = Convert::intToCurrency($scholarship);

            /** @var Registration $isset */
            $isset = Registration::where('year_id', $data['year_id'])
                ->where('student_id', $data['student_id'])
                ->first();

            if ($isset) {
                $student = Student::find($isset->student_id);
                $classroom = Classroom::find($isset->classroom_id);

                return response()->json([
                    'message' => "L'élève {$student->fullname()} est déja inscrit en classe de $classroom->name pour l'année en cours."
                ], 422);
            }

            DB::transaction(function () use ($data) {
                $registration = Registration::create($data);

                Payment::create([
                    'registration_id' => $registration->id,
                    'paid' => '0 CFA',
                    'remain' => $registration->scholarship,
                ]);
            });

            return response()->json(['message' => AppText::successfullyCreate()]);
        }

        public function update(RegistrationRequest $request, int $id): JsonResponse
        {
            /** @var Registration $registration */
            $registration = Registration::findOrFail($id);

            $data = $request->validated();

            /** @var ScholarshipFeed $feed */
            $feed = ScholarshipFeed::where('classroom_id', $data['classroom_id'])
                ->where('year_id', $data['year_id'])
                ->first();

            if (!$feed) return response()->json(['message' => AppText::feedNotfound()], status: 422);

            /** @var Configuration $configuration */
            $configuration = Configuration::where('year_id', $data['year_id'])->first();

            $amount = $data['kind'] == 'Normal' ? $feed->normal : $feed->subvention;

            $scholarship = Calculate::feed([
                'discountValue' => $configuration->discount,
                'bourseValue' => $configuration->bourse,
                'registrationFees' => $configuration->registration_fees,
                'periods' => $data['periods'],
                'amount' => $amount,
                'discountState' => $data['discount'],
                'bourseState' => $data['bourse'],
                'includeRegistrationFees' => $configuration->includeRegistrationFeed,
            ]);

            $data['scholarship'] = Convert::intToCurrency($scholarship);

            DB::transaction(function () use ($registration, $data, $scholarship) {
                $registration->update($data);

                $payment = Payment::where('registration_id', $registration->id)->first();

                if (!$payment) {
                    $payment = payment::create([
                        'registration_id' => $registration->id,
                        'paid' => '0 CFA',
                        'remain' => $registration->scholarship,
                    ]);
                }

                $remain = Convert::currencyToInt($payment->remain);
                $paid = Convert::currencyToInt($payment->paid);

                $remain = $scholarship - $paid;
                $paid = $scholarship - $remain;

                $payment->remain = Convert::intToCurrency($remain);
                $payment->paid = Convert::intToCurrency($paid);

                $payment->save();
            });

            return response()->json(['message' => AppText::successfullyCreate()]);
        }

        public function verify(Request $request): JsonResponse
        {
            $yearId = $request->input('year');
            $studentId = $request->input('student');

            if (!$yearId || !$studentId) return response()->json(['message' => AppText::parameterNotFound()], 422);

            $registration = Registration::where('year_id', $yearId)
                ->where('student_id', $studentId)
                ->first();

            return response()->json($registration);
        }

        public function getInformation(int $id): JsonResponse
        {
            /** @var Registration $registration */
            $registration = Registration::findOrFail($id);

            $classroom = Classroom::find($registration->classroom_id);
            $classrooms = Classroom::whereLevelId($classroom->level_id)->get();

            $levels = Level::all();
            $level = Level::find($classroom->level_id);

            return response()->json([
                'classroom' => $classroom,
                'classrooms' => $classrooms,
                'levels' => $levels,
                'level' => $level,
            ]);
        }
    }
