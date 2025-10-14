<?php

    namespace App\Models;

    use Eloquent;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasOne;
    use Illuminate\Support\Carbon;

    /**
     *
     *
     * @property int $id
     * @property int $student_id
     * @property int $year_id
     * @property int $classroom_id
     * @property int $sold_out
     * @property string $scholarship
     * @property int $presence
     * @property int $discount
     * @property int $bourse
     * @property string $kind
     * @property int $periods
     * @property string|null $previous_school
     * @property int|null $brothers
     * @property int $redouble
     * @property Carbon $created_at
     * @property Carbon $updated_at
     * @property-read Classroom|null $classroom
     * @property-read Student $student
     * @property-read Year|null $year
     * @method static Builder<static>|Registration newModelQuery()
     * @method static Builder<static>|Registration newQuery()
     * @method static Builder<static>|Registration query()
     * @method static Builder<static>|Registration whereBourse($value)
     * @method static Builder<static>|Registration whereBrothers($value)
     * @method static Builder<static>|Registration whereClassroomId($value)
     * @method static Builder<static>|Registration whereCreatedAt($value)
     * @method static Builder<static>|Registration whereDiscount($value)
     * @method static Builder<static>|Registration whereId($value)
     * @method static Builder<static>|Registration whereKind($value)
     * @method static Builder<static>|Registration wherePeriods($value)
     * @method static Builder<static>|Registration wherePresence($value)
     * @method static Builder<static>|Registration wherePreviousSchool($value)
     * @method static Builder<static>|Registration whereRedouble($value)
     * @method static Builder<static>|Registration whereScholarship($value)
     * @method static Builder<static>|Registration whereSoldOut($value)
     * @method static Builder<static>|Registration whereStudentId($value)
     * @method static Builder<static>|Registration whereUpdatedAt($value)
     * @method static Builder<static>|Registration whereYearId($value)
     * @property-read Payment|null $payment
     * @property string $insurance
     * @method static Builder<static>|Registration whereInsurance($value)
     * @mixin Eloquent
     */
    class Registration extends Model
    {
        protected $fillable = [
            'student_id',
            'classroom_id',
            'year_id',
            'sold_out',
            'scholarship',
            'presence',
            'discount',
            'bourse',
            'kind',
            'periods',
            'previous_school',
            'brothers',
            'redouble',
            'insurance'
        ];

        public function student(): BelongsTo
        {
            return $this->belongsTo(Student::class);
        }

        public function classroom(): BelongsTo
        {
            return $this->belongsTo(Classroom::class);
        }

        public function year(): BelongsTo
        {
            return $this->belongsTo(Year::class);
        }

        public function payment(): HasOne
        {
            return $this->hasOne(Payment::class);
        }
    }
