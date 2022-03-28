<?php
namespace PipIWYG\SablCore\Models;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Address Model Object to manage CRUD operations for Address Data
 *
 * @package PipIWYG\SablCore\Models
 * @author Quintin Stoltz <qstoltz@gmail.com>
 */
class Address
    extends Model
{
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
    protected $table = 'address';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['contact_id', 'street_address_primary', 'street_address_secondary', 'city', 'country'];

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
