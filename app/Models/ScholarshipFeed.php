<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $classroom_id
 * @property int $year_id
 * @property string $normal
 * @property string $subvention
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScholarshipFeed newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScholarshipFeed newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScholarshipFeed query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScholarshipFeed whereClassroomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScholarshipFeed whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScholarshipFeed whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScholarshipFeed whereNormal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScholarshipFeed whereSubvention($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScholarshipFeed whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScholarshipFeed whereYearId($value)
 * @mixin \Eloquent
 */
class ScholarshipFeed extends Model
{
    protected $fillable = ['classroom_id', 'year_id', 'normal', 'subvention'];
}
