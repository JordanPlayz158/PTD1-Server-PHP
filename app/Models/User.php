<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable;

     /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    //protected $dateFormat = 'U';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /*
    //public string $email;
    //public string $pass;

    public array $saves = array();

    //public ?string $accNickname = null;
    public ?string $dex1 = null;
    public ?string $dex1Shiny = null;
    public ?string $dex1Shadow = null;

    public function parse(array $account) {
        //$this->email = $account['email'];
        //$this->pass = $account['pass'];
        //$this->accNickname = $account['accNickname'];
        $this->dex1 = $account['dex1'];
        $this->dex1Shiny = $account['dex1Shiny'];
        $this->dex1Shadow = $account['dex1Shadow'];
    }
    */
}
