<?php

    namespace App\Models;

    use Eloquent;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Support\Carbon;

    /**
 * 
 *
 * @property int $id
 * @property int $registration_id
 * @property string $paid
 * @property string $remain
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Registration $registration
 * @method static Builder<static>|Payment newModelQuery()
 * @method static Builder<static>|Payment newQuery()
 * @method static Builder<static>|Payment query()
 * @method static Builder<static>|Payment whereCreatedAt($value)
 * @method static Builder<static>|Payment whereId($value)
 * @method static Builder<static>|Payment wherePaid($value)
 * @method static Builder<static>|Payment whereRegistrationId($value)
 * @method static Builder<static>|Payment whereRemain($value)
 * @method static Builder<static>|Payment whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Receipt> $receipts
 * @property-read int|null $receipts_count
 * @mixin Eloquent
 */
    class Payment extends Model
    {
        protected $fillable = ['registration_id', 'paid', 'remain'];

        public function registration(): BelongsTo
        {
            return $this->belongsTo(Registration::class);
        }

        public function receipts(): HasMany
        {
            return $this->hasMany(Receipt::class);
        }
    }
