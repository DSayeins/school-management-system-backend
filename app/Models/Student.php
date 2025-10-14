<?php

    namespace App\Models;

    use Eloquent;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsToMany;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\Relations\HasOne;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Str;

    /**
     *
     *
     * @property int $id
     * @property string $matricule
     * @property string $firstname
     * @property string $lastname
     * @property string|null $birthday
     * @property string $birthday_place
     * @property string $gender
     * @property string $nationality
     * @property string|null $arrival
     * @property string|null $domicile
     * @property string|null $image
     * @property int $exist
     * @property Carbon $created_at
     * @property Carbon|null $updated_at
     * @method static Builder<static>|Student newModelQuery()
     * @method static Builder<static>|Student newQuery()
     * @method static Builder<static>|Student query()
     * @method static Builder<static>|Student whereArrival($value)
     * @method static Builder<static>|Student whereBirthday($value)
     * @method static Builder<static>|Student whereBirthdayPlace($value)
     * @method static Builder<static>|Student whereCreatedAt($value)
     * @method static Builder<static>|Student whereDomicile($value)
     * @method static Builder<static>|Student whereExist($value)
     * @method static Builder<static>|Student whereFirstname($value)
     * @method static Builder<static>|Student whereGender($value)
     * @method static Builder<static>|Student whereId($value)
     * @method static Builder<static>|Student whereImage($value)
     * @method static Builder<static>|Student whereLastname($value)
     * @method static Builder<static>|Student whereMatricule($value)
     * @method static Builder<static>|Student whereNationality($value)
     * @method static Builder<static>|Student whereUpdatedAt($value)
     * @property-read Collection<int, Contact> $contacts
     * @property-read int|null $contacts_count
     * @property-read Collection<int, Registration> $inscriptions
     * @property-read int|null $inscriptions_count
     * @mixin Eloquent
     */
    class Student extends Model
    {
        protected $fillable = [
            'matricule',
            'firstname',
            'lastname',
            'birthday',
            'birthday_place',
            'gender',
            'nationality',
            'domicile',
            'arrival',
            'exists'
        ];

        protected $hidden = ['created_at', 'updated_at'];

        public function fullname(): string
        {
            return "$this->firstname $this->lastname";
        }

        public function contacts(): BelongsToMany
        {
            return $this->belongsToMany(Contact::class, 'student_contact');
        }

        public function registrations(): HasMany
        {
            return $this->hasMany(Registration::class);
        }

        public function registration(): HasOne
        {
            return $this->hasone(Registration::class);
        }
    }
