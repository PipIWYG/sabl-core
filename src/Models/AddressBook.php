<?php
namespace PipIWYG\SablCore\Models;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Contact Group or Address Book Model Object to manage CRUD operations for Address Book / Contact Group data
 *
 * @package PipIWYG\SablCore\Models
 * @author Quintin Stoltz <qstoltz@gmail.com>
 */
class AddressBook
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
    protected $table = 'address_book';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contacts()
    {
        return $this->hasMany('PipIWYG\SablCore\Models\Contact','ab_id','id');
    }
}
