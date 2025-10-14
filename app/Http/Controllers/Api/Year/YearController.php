<?php

    namespace App\Http\Controllers\Api\Year;

    use App\Helpers\Constants\AppText;
    use App\Http\Controllers\Controller;
    use App\Http\Requests\Api\Year\YearRequest;
    use App\Models\Year;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;

    class YearController extends Controller
    {
        public function all(): JsonResponse
        {
            $years = Year::orderBy('id', 'desc')->get();

            return response()->json(data: $years);
        }

        public function first(): JsonResponse
        {
            $year = Year::all()->first();
            return response()->json(data: $year);
        }

        public function one(int $id): JsonResponse
        {
            /** @var Year $year */
            $year = Year::findOrFail($id);
            return response()->json(data: $year);
        }

        public function store(YearRequest $request): JsonResponse
        {
            $data = $request->validated();

            $isset = Year::whereName($data['name'])->first();

            if ($isset) {
                return response()->json([
                    'message' => AppText::isset()
                ], '422');
            }

            Year::create($data);
            return response()->json(['message' => AppText::successfullyCreate()]);
        }

        public function update(YearRequest $request, int $id): JsonResponse
        {
            /** @var Year $year */
            $year = Year::findOrFail($id);

            $validated = $request->validated();

            $_year = Year::whereName($validated['name'])->first();

            if ($_year && $year->id != $_year->id) {
                return response()->json(['message' => AppText::isset(), 422]);
            }

            $year->update($validated);

            return response()->json(['message' => AppText::successfullyUpdate()]);
        }

        public function delete(int $id)
        {
            /** @var Year $year */
            $year = Year::findOrFail($id);

            $year->delete();

            return response()->json(['message' => AppText::successfullyDelete()]);
        }
    }
