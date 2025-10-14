<?php

    namespace App\Models;

    use Eloquent;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Carbon;

    /**
 * 
 *
 * @method static Builder<static>|ReceiptDetail newModelQuery()
 * @method static Builder<static>|ReceiptDetail newQuery()
 * @method static Builder<static>|ReceiptDetail query()
 * @property int $id
 * @property int $receipt_id
 * @property string $toPaid
 * @property string $remain
 * @property string $detailRemain
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder<static>|ReceiptDetail whereCreatedAt($value)
 * @method static Builder<static>|ReceiptDetail whereDetailRemain($value)
 * @method static Builder<static>|ReceiptDetail whereId($value)
 * @method static Builder<static>|ReceiptDetail whereReceiptId($value)
 * @method static Builder<static>|ReceiptDetail whereRemain($value)
 * @method static Builder<static>|ReceiptDetail whereToPaid($value)
 * @method static Builder<static>|ReceiptDetail whereUpdatedAt($value)
 * @mixin Eloquent
 */
    class ReceiptDetail extends Model
    {
        protected $fillable = ['receipt_id', 'toPaid', 'remain', 'detailRemain'];
    }
