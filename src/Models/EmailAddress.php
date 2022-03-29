<?php
namespace PipIWYG\SablCore\Models;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Email Address Model Object to manage CRUD operations for Email Address Data
 *
 * @package PipIWYG\SablCore\Models
 * @author Quintin Stoltz <qstoltz@gmail.com>
 */
class EmailAddress
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
    protected $table = 'email_address';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['contact_id', 'email_address'];

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
