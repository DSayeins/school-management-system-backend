<?php

    namespace App\Http\Controllers\Api\Classroom;

    use App\Helpers\Constants\AppText;
    use App\Http\Controllers\Controller;
    use App\Http\Requests\Api\Classroom\ClassroomRequest;
    use App\Models\Classroom;
    use App\Models\ScholarshipFeed;
    use App\Models\Year;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;

    class ClassroomController extends Controller
    {
        public function all(Request $request): JsonResponse
        {
            $classrooms = [];

            $levelId = $request->input('level');

            if ($levelId) {
                $classrooms = Classroom::whereLevelId($levelId)->get();
            } else {
                $classrooms = Classroom::orderBy('position')->orderBy('name')->get();
            }

            return response()->json($classrooms);
        }

        public function notInScholarshipFeed(Request $request): JsonResponse
        {
            $yearId = $request->input('year');
            $year = Year::findOrFail($yearId);

            $ids = ScholarshipFeed::whereYearId($year->id)->select(['classroom_id'])->get()->toArray();
            $classrooms = Classroom::whereNotIn('id', $ids)
                ->orderBy('position')
                ->orderBy('name')
                ->get();

            return response()->json($classrooms);
        }

        public function allByLevel(int $levelId): JsonResponse
        {
            $classrooms = Classroom::where('level_id', $levelId)->orderBy('position')->get();
            return response()->json($classrooms);
        }

        public function get(int $id): JsonResponse
        {
            /** @var Classroom $classroom */
            $classroom = Classroom::find($id);

            if (!$classroom) return response()->json(['message' => AppText::notFound()], 404);

            return response()->json($classroom);
        }

        public function update(ClassroomRequest $request, int $id): JsonResponse
        {
            $data = $request->validated();

            /** @var Classroom $classroom */
            $classroom = Classroom::find($id);

            if (!isset($classroom)) {
                return response()->json(['message' => 'Cette classe n\'existe pas'], status: 422);
            }

            $old = Classroom::whereName($data['name'])->first();

            if (isset($old) && $old->id != $classroom->id) {
                return response()->json(['message' => 'Cet intitulé est deja attribuer à une autre classe.']);
            }

            $classroom->update($data);

            return response()->json(['message' => AppText::successfullyUpdate()]);
        }

        public function store(ClassroomRequest $request): JsonResponse
        {
            $data = $request->validated();

            $classroom = Classroom::whereName($data['name'])->first();

            if (isset($classroom)) {
                return response()->json(['message' => AppText::isset()], status: 422);
            }

            Classroom::create($data);

            return response()->json(['message' => AppText::successfullyCreate()]);
        }
    }
