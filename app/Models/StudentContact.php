<?php

    namespace App\Models;

    use Eloquent;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

    /**
 * 
 *
 * @property-read Contact|null $contact
 * @property-read Student|null $student
 * @method static Builder<static>|StudentContact newModelQuery()
 * @method static Builder<static>|StudentContact newQuery()
 * @method static Builder<static>|StudentContact query()
 * @property int $id
 * @property int $student_id
 * @property int $contact_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static Builder<static>|StudentContact whereContactId($value)
 * @method static Builder<static>|StudentContact whereCreatedAt($value)
 * @method static Builder<static>|StudentContact whereId($value)
 * @method static Builder<static>|StudentContact whereStudentId($value)
 * @method static Builder<static>|StudentContact whereUpdatedAt($value)
 * @mixin Eloquent
 */
    class StudentContact extends Model
    {
        protected $table = 'student_contact';
        
        protected $fillable = [
            'student_id',
            'contact_id',
        ];

        public function student(): BelongsTo
        {
            return $this->belongsTo(Student::class);
        }

        public function contact(): BelongsTo
        {
            return $this->belongsTo(Contact::class);
        }
    }
