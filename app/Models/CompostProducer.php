<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompostProducer extends Model
{
    protected $table = 'compost_producer';
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $fillable = [
        'Name',
        'user_id',
        'Location',
        'CompostTypesProduced',
        'AverageCompostAmountPerTerm',
        'WasteProcessingCapacity',
        'PointsBalance',
        'AmountBalance'
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'ProviderID');
    }

    public function pointsTransactions()
    {
        return $this->hasMany(PointsTransaction::class, 'ParticipantID');
    }

    public function farmers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            Farmer::class,
            'compost_producer_farmer',
            'CompostProducerID',
            'FarmerID'
        );
    }

    public function restaurants()
    {
        return $this->belongsToMany(RestaurantOwner::class, 'restaurant_owner_compost_producer', 'CompostProducerID', 'RestaurantOwnerID');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function compostEntries()
    {
        return $this->hasMany(CompostEntry::class, 'compost_producer_id', 'user_id');
    }

    public function PointsBalance()
    {
        return $this->pointsTransactions()->sum('points');
    }

}
