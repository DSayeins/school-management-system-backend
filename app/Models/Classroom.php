<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $level_id
 * @property string $name
 * @property int $place
 * @property int $position
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Classroom newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Classroom newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Classroom query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Classroom whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Classroom whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Classroom whereLevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Classroom whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Classroom wherePlace($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Classroom wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Classroom whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Classroom extends Model
{
    protected $fillable = ['level_id', 'name', 'position', 'place'];
}
