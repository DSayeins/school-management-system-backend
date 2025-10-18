<?php

    namespace App\Http\Controllers\Api\ScholarshipFeed;

    use App\Helpers\Constants\AppText;
    use App\Helpers\utitlities\Calculate;
    use App\Helpers\utitlities\Convert;
    use App\Http\Controllers\Api\Registration\RegistrationController;
    use App\Http\Controllers\Controller;
    use App\Http\Requests\Api\ScholarshipFeed\ScholarshipFeedRequest;
    use App\Models\Classroom;
    use App\Models\Configuration;
    use App\Models\Payment;
    use App\Models\Registration;
    use App\Models\ScholarshipFeed;
    use App\Models\Year;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;

    class ScholarshipFeedController extends Controller
    {
        public function all(Request $request): JsonResponse
        {
            $yearId = $request->input('year');

            if (!$yearId) {
                return response()->json(['message' => AppText::invalidUri()], 404);
            }

            $year = Year::findOrFail($yearId);

            $feeds = ScholarshipFeed::join('classrooms', 'scholarship_feeds.classroom_id', '=', 'classrooms.id')
                ->join('years', 'scholarship_feeds.year_id', '=', 'years.id')
                ->where('years.id', $year->id)
                ->select(['scholarship_feeds.*', 'classrooms.name as classroom', 'years.name as year'])
                ->orderBy('position')
                ->get();

            return response()->json($feeds);
        }

        public function store(ScholarshipFeedRequest $request): JsonResponse
        {
            $data = $request->validated();

            $ids = $data['classrooms_id'];

            foreach ($ids as $id) {
                ScholarshipFeed::create([
                    'classroom_id' => $id,
                    'year_id' => $data['year_id'],
                    'normal' => $data['normal'],
                    'subvention' => $data['subvention'],
                ]);
            }

            return response()->json(['message' => AppText::successfullyCreate()]);
        }

        public function update(ScholarshipFeedRequest $request, int $id): JsonResponse
        {
            /** @var ScholarshipFeed $feed */
            $feed = ScholarshipFeed::find($id);

            if (!$feed) return response()->json(['message', AppText::notFound()], 422);

            /** @var Configuration $configuration */
            $configuration = Configuration::where('year_id', $feed->year_id)->first();

            if (!$configuration) return response()->json(['message' => AppText::configNotFound()], status: 422);

            $data = $request->validated();

            $data['normal'] = Convert::intToCurrency($data['normal']);
            $data['subvention'] = Convert::intToCurrency($data['subvention']);

            $feed->update($data);

            $registrations = Registration::join('classrooms', 'registrations.classroom_id', '=', 'classrooms.id')
                ->join('years', 'registrations.year_id', '=', 'years.id')
                ->where('years.id', $feed->year_id)
                ->where('classrooms.id', $feed->classroom_id)
                ->select(['registrations.*'])
                ->get();

            foreach ($registrations as $registration) {
                $amount = 0;

                $amount = $registration->kind == 'Normal' ? $data['normal'] : $data['subvention'];
                $amount = Convert::currencyToInt($amount);

                $scholarship = Calculate::feed([
                    'discountValue' => $configuration->discount,
                    'bourseValue' => $configuration->bourse,
                    'registrationFees' => $configuration->registration_fees,
                    'periods' => $registration->periods,
                    'amount' => $amount,
                    'discountState' => $registration->discount,
                    'bourseState' => $registration->bourse,
                    'includeRegistrationFees' => $configuration->includeRegistrationFeed,
                ]);

                $registration->scholarship = Convert::intToCurrency($scholarship);
                $registration->save();

                $payment = Payment::whereRegistrationId($registration->id)->first();

                $remain = $scholarship - Convert::currencyToInt($payment->paid);
                $payment->remain = Convert::intToCurrency($remain);
                $payment->save();
            }

            return response()->json(['message' => AppText::successfullyUpdate()]);
        }

        public function delete(int $id): JsonResponse
        {
            /** @var ScholarshipFeed $feed */
            $feed = ScholarshipFeed::find($id);

            if (!$feed) {
                return response()->json(['message', AppText::notFound()], 422);
            }

            $feed->delete();
            return response()->json(['message' => AppText::successfullyDelete()]);
        }
    }
