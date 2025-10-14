<?php

    namespace App\Models;

    use Eloquent;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Support\Carbon;

    /**
 * 
 *
 * @property int $id
 * @property int $payment_id
 * @property int $number
 * @property string $amount
 * @property string $kind
 * @property string $date
 * @property string $auto_number
 * @property int $cancelled
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @method static Builder<static>|Receipt newModelQuery()
 * @method static Builder<static>|Receipt newQuery()
 * @method static Builder<static>|Receipt query()
 * @method static Builder<static>|Receipt whereAmount($value)
 * @method static Builder<static>|Receipt whereAutoNumber($value)
 * @method static Builder<static>|Receipt whereCancelled($value)
 * @method static Builder<static>|Receipt whereCreatedAt($value)
 * @method static Builder<static>|Receipt whereDate($value)
 * @method static Builder<static>|Receipt whereId($value)
 * @method static Builder<static>|Receipt whereKind($value)
 * @method static Builder<static>|Receipt whereNumber($value)
 * @method static Builder<static>|Receipt wherePaymentId($value)
 * @method static Builder<static>|Receipt whereUpdatedAt($value)
 * @property-read \App\Models\Payment $payment
 * @mixin Eloquent
 */
    class Receipt extends Model
    {
        protected $fillable = ['payment_id', 'amount', 'kind', 'date', 'auto_number', 'number', 'cancelled'];

        public function payment(): BelongsTo
        {
            return $this->belongsTo(Payment::class);
        }
    }
