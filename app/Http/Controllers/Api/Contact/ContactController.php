<?php

    namespace App\Http\Controllers\Api\Contact;

    use App\Http\Controllers\Controller;
    use App\Models\Contact;
    use App\Models\Student;
    use App\Models\StudentContact;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;

    class ContactController extends Controller
    {
        public function getAll()
        {

        }

        public function getAllByStudent(int $studentId): JsonResponse
        {
            $student = Student::findOrFail($studentId);

            $contacts = $student->contacts;

            return response()->json($contacts);
        }

        public function getAllWithoutStudent(int $studentId): JsonResponse
        {
            $student = Student::findOrFail($studentId);

            $ids = $student->contacts->select(['id'])->toArray();

            $contacts = Contact::whereNotIn('id', $ids)->get();

            return response()->json($contacts);
        }

        public function store(Request $request): JsonResponse
        {
            $validated = $this->validate($request);
            $contact = $this->isset($validated);

            if ($contact) {
                return response()->json('Contact already exists');
            }

            Contact::create($validated);

            return response()->json(['message' => 'Contact successfully created']);
        }

        public function update(Request $request, int $id): JsonResponse
        {
            $contact = Contact::findOrFail($id);

            $validated = $this->validate($request);
            $isset = $this->isset($validated);

            if ($isset && $isset->id != $contact->id) {
                return response()->json('Contact already exists');
            }

            $contact->update($validated);

            return response()->json(['message' => 'Contact successfully updated']);
        }

        public function delete(int $id): JsonResponse
        {
            $contact = Contact::findOrFail($id);
            $sContacts = StudentContact::where('contact_id', $id)->get();

            foreach ($sContacts as $sContact) {
                $sContact->delete();
            }

            $contact->delete();

            return response()->json('Contact successfully deleted');
        }

        public function validate(Request $request): array
        {
            return $request->validate([
                'fullname' => ['required', 'string'],
                'status' => ['required', 'string'],
                'profession' => ['nullable', 'string'],
                'email' => ['nullable', 'email'],
                'phone_1' => ['nullable', 'string'],
                'phone_2' => ['nullable', 'string'],
                'phone_3' => ['nullable', 'string'],
                'phone_4' => ['nullable', 'string'],
                'whatsapp' => ['nullable', 'string'],
            ]);
        }

        public function isset(array $validated)
        {
            return Contact::where('fullname', $validated['fullname'])
                ->where('status', $validated['status'])
                ->where('profession', $validated['profession'])
                ->where('email', $validated['email'])
                ->where('phone_1', $validated['phone_1'])
                ->where('phone_2', $validated['phone_2'])
                ->where('phone_3', $validated['phone_3'])
                ->where('phone_4', $validated['phone_4'])
                ->where('whatsapp', $validated['whatsapp'])
                ->first();
        }
    }
