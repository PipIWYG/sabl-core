<?php
namespace PipIWYG\SablCore\Models;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Groups Model Object to manage CRUD operations for Groups Data
 *
 * @package PipIWYG\SablCore\Models
 * @author Quintin Stoltz <qstoltz@gmail.com>
 */
class Groups
    extends Model
{
    // Use SoftDeletes
    use SoftDeletes;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Contacts can have multiple groups
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function contacts()
    {
        // Return Model Relation
        return $this->belongsToMany('PipIWYG\SablCore\Models\Contact');
    }
}
