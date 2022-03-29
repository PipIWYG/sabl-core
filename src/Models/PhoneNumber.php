<?php
namespace PipIWYG\SablCore\Models;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Phone Number Model Object to manage CRUD operations for Phone Number Data
 *
 * @package PipIWYG\SablCore\Models
 * @author Quintin Stoltz <qstoltz@gmail.com>
 */
class PhoneNumber
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
    protected $table = 'phone_number';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['contact_id', 'phone_number'];

    /**
     * The relationships that should be touched on save.
     * @var string[]
     */
    protected $touches = ['contact'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contact()
    {
        return $this->belongsTo('PipIWYG\SablCore\Models\Contact','contact_id','id');
    }
}
