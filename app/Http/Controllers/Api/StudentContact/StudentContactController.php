<?php

    namespace App\Http\Controllers\Api\StudentContact;

    use App\Http\Controllers\Controller;
    use App\Models\StudentContact;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;

    class StudentContactController extends Controller
    {
        public function store(Request $request): JsonResponse
        {
            $validated = $request->validate([
                'student_id' => 'required|exists:students,id',
                'contact_id' => 'required|exists:contacts,id',
            ]);

            $sContact = StudentContact::where('student_id', $validated['student_id'])
                ->where('contact_id', $validated['contact_id'])
                ->first();

            if ($sContact) {
                return response()->json(["message" => "Contact already exists!"], 422);
            }

            StudentContact::create($validated);

            return response()->json(["message" => "Contact saved!"]);
        }

        public function delete(REquest $request): JsonResponse
        {
            $validated = $request->validate([
                'student_id' => 'required|exists:students,id',
                'contact_id' => 'required|exists:contacts,id',
            ]);

            $sContact = StudentContact::where('student_id', $validated['student_id'])
                ->where('contact_id', $validated['contact_id'])
                ->first();

            if (!$sContact) {
                return response()->json(["message" => "Ce contact n'existe pas!"], 422);
            }

            $sContact->delete();

            return response()->json(["message" => "Contact supprimé"]);
        }
    }
