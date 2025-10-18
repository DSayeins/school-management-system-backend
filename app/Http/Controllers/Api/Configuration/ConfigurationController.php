<?php

    namespace App\Http\Controllers\Api\Configuration;

    use App\Helpers\Constants\AppText;
    use App\Helpers\utitlities\Convert;
    use App\Http\Controllers\Controller;
    use App\Http\Requests\Api\Configuration\ConfigurationRequest;
    use App\Models\Configuration;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;

    class ConfigurationController extends Controller
    {
        public function one(Request $request): JsonResponse
        {
            $id = $request->input('year');
            if (!$id) return response()->json(['message' => AppText::yearNotFound()]);

            $config = Configuration::where('year_id', $id)->first();
            return response()->json($config);
        }

        public function update(ConfigurationRequest $request, int $id): JsonResponse
        {
            /** @var Configuration $config */
            $config = Configuration::find($id);

            if (!$config) return response()->json(['message' => AppText::notFound()], 404);

            $data = $request->validated();

            $config->update($data);

            return response()->json(['message' => AppText::successfullyUpdate()]);
        }

        public function delete(int $id): JsonResponse
        {
            /** @var Configuration $config */
            $config = Configuration::find($id);

            if (!$config) return response()->json(['message' => AppText::notFound()], 404);

            $config->delete();
            return response()->json(['message' => AppText::successfullyDelete()]);
        }

        public function store(ConfigurationRequest $request): JsonResponse
        {
            $data = $request->validated();
            Configuration::create($data);

            return response()->json(['message' => AppText::successfullyCreate()]);
        }
    }
