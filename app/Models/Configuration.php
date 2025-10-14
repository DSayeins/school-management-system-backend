<?php

    namespace App\Models;

    use Eloquent;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Carbon;

    /**
 * 
 *
 * @property int $id
 * @property int $year_id
 * @property int $discount
 * @property string $registration_fees
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $bourse
 * @property int $includeRegistrationFeed
 * @method static Builder<static>|Configuration newModelQuery()
 * @method static Builder<static>|Configuration newQuery()
 * @method static Builder<static>|Configuration query()
 * @method static Builder<static>|Configuration whereBourse($value)
 * @method static Builder<static>|Configuration whereCreatedAt($value)
 * @method static Builder<static>|Configuration whereDiscount($value)
 * @method static Builder<static>|Configuration whereId($value)
 * @method static Builder<static>|Configuration whereIncludeRegistrationFeed($value)
 * @method static Builder<static>|Configuration whereRegistrationFees($value)
 * @method static Builder<static>|Configuration whereUpdatedAt($value)
 * @method static Builder<static>|Configuration whereYearId($value)
 * @property int $slices
 * @method static Builder<static>|Configuration whereSlices($value)
 * @mixin Eloquent
 */
    class Configuration extends Model
    {
        protected $fillable = ['year_id', 'discount', 'registration_fees', 'bourse', 'includeRegistrationFeed', 'slices'];
    }
