<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsToMany;

    /**
 * 
 *
 * @property int $id
 * @property string $fullname
 * @property string $profession
 * @property string $status
 * @property string|null $email
 * @property string|null $phone_1
 * @property string|null $phone_2
 * @property string|null $phone_3
 * @property string|null $phone_4
 * @property string|null $whatsapp
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Student> $students
 * @property-read int|null $students_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereFullname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact wherePhone1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact wherePhone2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact wherePhone3($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact wherePhone4($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereProfession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereWhatsapp($value)
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Contact extends Model
    {
        protected $fillable = [
            'fullname',
            'email',
            'status',
            'profession',
            'phone_1',
            'phone_2',
            'phone_3',
            'phone_4',
            'whatsapp',
        ];


        public function students(): BelongsToMany
        {
            return $this->belongsToMany(Student::class, 'student_contact');
        }
    }
