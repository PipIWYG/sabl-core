<?php
namespace PipIWYG\SablCore\Models;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Contact Model Object to manage CRUD operations for Contact Data
 *
 * @package PipIWYG\SablCore\Models
 * @author Quintin Stoltz <qstoltz@gmail.com>
 */
class Contact
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
    protected $table = 'contact';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ab_id', 'first_name', 'last_name'];

    /**
     * The relationships that should be touched on save.
     * @var string[]
     */
    protected $touches = ['address_book'];

    /**
     * Relationship Record on Address
     * @return mixed
     */
    public function addresses()
    {
        return $this->hasMany('PipIWYG\SablCore\Models\Address','contact_id','id');
    }

    /**
     * Relationship Record on Email Address
     * @return mixed
     */
    public function email_addresses()
    {
        return $this->hasMany('PipIWYG\SablCore\Models\EmailAddress','contact_id','id');
    }

    /**
     * Relationship Record on Phone Number
     * @return mixed
     */
    public function phone_numbers()
    {
        return $this->hasMany('PipIWYG\SablCore\Models\PhoneNumber','contact_id','id');
    }

    /**
     * Relationship Record on Address Book
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function address_book()
    {
        return $this->belongsTo('PipIWYG\SablCore\Models\AddressBook','ab_id','id');
    }

    /**
     * Contacts can have multiple groups
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        // Return Model Relation
        return $this->belongsToMany('PipIWYG\SablCore\Models\Groups');
    }
}
