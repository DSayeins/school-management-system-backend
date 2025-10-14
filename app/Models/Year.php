<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Year newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Year newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Year query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Year whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Year whereName($value)
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Year whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Year whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Year extends Model
{
    protected $fillable = ['id', 'name'];
}
