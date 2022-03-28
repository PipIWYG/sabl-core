<?php

namespace PipIWYG\Roamler\Models;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * Phone Number Model Object to Capture and Query Contact Phone Number Data, with additional relationship object model definitions
 *
 * @package PipIWYG\Roamler\Models
 * @author Quintin Stoltz <qstoltz@gmail.com>
 */
class PhoneNumber
    extends Model
{
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contact()
    {
        return $this->belongsTo('PipIWYG\Roamler\Models\Contact','id','contact_id');
    }
}
