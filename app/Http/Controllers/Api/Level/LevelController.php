<?php

    namespace App\Http\Controllers\Api\Level;

    use App\Helpers\Constants\AppText;
    use App\Http\Controllers\Controller;
    use App\Http\Requests\Api\Level\LevelRequest;
    use App\Models\Classroom;
    use App\Models\Level;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;

    class LevelController extends Controller
    {
        public function all(): JsonResponse
        {
            $levels = Level::orderBy('position', 'asc')->get();
            return response()->json($levels);
        }

        public function get(int $id): JsonResponse
        {
            /** @var Level $level */
            $level = Level::find($id);

            if (!$level) return response()->json(['message' => AppText::notFound("Level")]);

            return response()->json($level);
        }

        public function store(LevelRequest $request): JsonResponse
        {
            $data = $request->validated();

            $level = Level::whereName($data['name'])->first();

            if (isset($level)) {
                return response()->json(['message' => AppText::isset()], 404);
            }

            Level::create($data);

            return response()->json(['message' => AppText::successfullyCreate()]);
        }

        public function update(LevelRequest $request, int $id): JsonResponse
        {
            $data = $request->validated();

            /** @var Level $level */
            $level = Level::find($id);

            $other = Level::whereName($data['name'])->first();

            if (isset($other) && $other->id != $level->id) {
                return response()->json(['message' => AppText::isset()], 404);
            }

            $level->update($data);
            return response()->json(['message' => AppText::successfullyUpdate()]);
        }

        public function delete(int $id): JsonResponse
        {
            /** @var Level $level */
            $level = Level::find($id);

            if (!isset($level)) {
                return response()->json(['message' => AppText::notFound()], 404);
            }

            $classrooms = Classroom::whereLevelId($level->id)->get();

            if ($classrooms->isNotEmpty()) {
                return response()->json(['message' => AppText::impossibleToDelete()], 422);
            }

            $level->delete();

            return response()->json(['message' => AppText::successfullyDelete()]);
        }
    }
