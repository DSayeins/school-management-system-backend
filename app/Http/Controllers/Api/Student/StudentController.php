<?php

    namespace App\Http\Controllers\Api\Student;

    use App\Helpers\Constants\AppText;
    use App\Http\Controllers\Controller;
    use App\Http\Requests\Api\Student\StudentRequestUpdate;
    use App\Http\Requests\Api\Student\StudentStoreRequest;
    use App\Models\Registration;
    use App\Models\Student;
    use App\Models\Year;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;
    use function Laravel\Prompts\select;

    class StudentController extends Controller
    {
        public function all(): JsonResponse
        {
            $students = Student::orderBy('id', 'desc')->get();
            return response()->json($students);
        }

        public function update(StudentRequestUpdate $request, int $id): JsonResponse
        {
            $data = $request->validated();
            $student = Student::find($id);

            $oldStudent = Student
                ::whereFirstname($data['firstname'])
                ->whereLastname($data['lastname'])
                ->first();

            if ($oldStudent && $oldStudent->id != $student->id) {
                return response()->json([
                    'message' => 'This value is also assigning to another student.',
                ], 404);
            }

            $student->update($data);

            return response()->json($student);
        }

        public function store(StudentStoreRequest $request): JsonResponse
        {
            $data = $request->validated();

            $student = Student
                ::whereFirstname($data['firstname'])
                ->whereLastname($data['lastname'])
                ->whereBirthday($data['birthday'])
                ->first();

            if ($student) {
                return response()->json(data: [
                    'message' => AppText::isset(),
                ], status: 422);
            }

            $last = Student::orderBy('id', 'desc')->get()->first();

            $value = 0;
            $matricule = "";

            if (!isset($last)) {
                $matricule = "LEO-1";
            } else {
                $tab = explode('-', $last->matricule);
                $value = intval($tab[1]) + 1;
                $matricule = "{$tab[0]}-$value";
            }

            $data['matricule'] = $matricule;

            Student::create($data);

            return response()->json([
                'message' => AppText::successfullyCreate(),
            ]);
        }

        public function compact()
        {
            $student = Student::orderBy('');
        }

        public function notRegistered(Request $request)
        {
            $yearId = $request->input('year');
            $year = Year::findOrFail($yearId);

            $students = Student::whereNotIn('id', function ($query) use ($year) {
                $query->select('student_id')->from('registrations')->where('year_id', $year->id);
            })
                ->with([
                    'registrations' => function ($query) use ($year) {
                        $query->where('year_id', '<', $year->id)->with([
                            'classroom',
                            'payment',
                            'year'
                        ]);
                    }
                ])
                ->get();

            $info = [];

            foreach ($students as $student) {
                $metaData = [];

                foreach ($student->registrations as $registration) {
                    $metaData [] = [
                        'classroom' => $registration->classroom->name,
                        'remain' => $registration->payment?->remain,
                        'year' => $registration->year->name,
                    ];
                }

                $info [] = [
                    'id' => $student->id,
                    'fullname' => $student->firstname . ' ' . $student->lastname,
                    'birthday' => $student->birthday,
                    'matricule' => $student->matricule,
                    'metaData' => $metaData,
                ];
            }

            return response()->json(data: $info);
        }
    }
