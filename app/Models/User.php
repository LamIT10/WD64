<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name', 'password', 'email', 'phone','status', 'address', 'position','note','gender', 'avatar', 'last_login_at', 'facebook', 'birthday', 'start_date', 'employee_code', 'identity_number','avatar'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    // protected $hidden = [
    //     'password',
    //     'remember_token',
    // ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

     public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'user_id');
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class,'user_id');
    }

    public function receiving()
    {
        return $this->hasMany(Receiving::class,'user_id');
    }

    public function shipping()
    {
        return $this->hasMany(Shipping::class,'user_id');
    }

    public function inventoryAudits()
    {
        return $this->hasMany(InventoryAudit::class,'user_id');
    }

    public function damagedExpiredProducts()
    {
        return $this->hasMany(DamagedExpiredProduct::class, 'reported_by');
    }
      public function approvedAudits()
    {
        return $this->hasMany(InventoryAudit::class, 'approved_by');
    }
    public function approvedGoodReceipt(){
        return  $this->hasOne(GoodReceipt::class, 'approved_by');
    }

}
