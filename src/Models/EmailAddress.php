<?php

namespace PipIWYG\Roamler\Models;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * Contact Email Address Model Object to Capture and Query Contact Email Address Data, with additional relationship object model definitions
 *
 * @package PipIWYG\Roamler\Models
 * @author Quintin Stoltz <qstoltz@gmail.com>
 */
class EmailAddress
    extends Model
{
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contact()
    {
        return $this->belongsTo('PipIWYG\Roamler\Models\Contact','id','contact_id');
    }
}
