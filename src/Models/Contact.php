<?php

namespace PipIWYG\Roamler\Models;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * Contact Model Object to Capture and Query Contact Data, with additional relationship object model definitions
 *
 * @package PipIWYG\Roamler\Models
 * @author Quintin Stoltz <qstoltz@gmail.com>
 */
class Contact
    extends Model
{
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
    protected $fillable = ['first_name', 'last_name', 'group_id'];

    /**
     * Relationship Record on Address
     * @return mixed
     */
    public function addresses()
    {
        return $this->hasMany('PipIWYG\Roamler\Models\Address','contact_id','id');
    }

    /**
     * Relationship Record on Email Address
     * @return mixed
     */
    public function email_addresses()
    {
        return $this->hasMany('PipIWYG\Roamler\Models\EmailAddress','contact_id','id');
    }

    /**
     * Relationship Record on Phone Number
     * @return mixed
     */
    public function phone_numbers()
    {
        return $this->hasMany('PipIWYG\Roamler\Models\PhoneNumber','contact_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo('PipIWYG\Roamler\Models\ContactGroup','group_id','id');
    }
}
