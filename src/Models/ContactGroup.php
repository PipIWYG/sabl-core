<?php

namespace PipIWYG\Roamler\Models;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * Contact Group Model Object to Capture and Query Contact Group Data, with additional relationship object model definitions
 *
 * @package PipIWYG\Roamler\Models
 * @author Quintin Stoltz <qstoltz@gmail.com>
 */
class ContactGroup
    extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'contact_group';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['group_name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contacts()
    {
        return $this->hasMany('PipIWYG\Roamler\Models\Contact','id','group_id');
    }
}
