<?php
namespace App\Models;

use App\Enums\Role;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $dex
 * @property string|null $shinyDex
 * @property string|null $shadowDex
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Save[] $saves
 * @property-read int|null $saves_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereShadowDex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereShinyDex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @property-read \App\Models\Achievement|null $achievement
 * @property int|null $role_id
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRoleId($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements MustVerifyEmail {
    use HasApiTokens, HasFactory, Notifiable;

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
        'password' => 'hashed',
    ];

    public function getNameAttribute(): string {
        return $this->name ?? $this->email;
    }

    public function getEmailForVerification(): string {
        return Cache::get('email-change-verification-email:' . $this->id) ?? $this->email;
    }

    /**
     * Get the ptd1 saves for the user.
     */
    public function saves(): HasMany
    {
        return $this->hasMany(Save::class);
    }

    public function selectedSave(): Save
    {
        return $this->saves()->where('num', '=', session('save', 0))->first() ?? Save::factory()->makeOne();
    }

    public function achievement(): HasOne
    {
        return $this->hasOne(Achievement::class);
    }

    public function role(): Role
    {
        return $this->role_id === null ? Role::USER() : Role::fromValue($this->role_id);
    }

    public function ownsAchievement(int $achievementId): bool
    {
        return Achievement::whereId($achievementId)->get('user_id')->first()->user_id === $this->id;
    }

    public function ownsSave(int $saveId): bool
    {
        return $this->saves()->get('id')->contains($saveId);
    }

    public function ownsPokemon(int $pokemonId): bool
    {
        return $this->ownsSave(Pokemon::whereId($pokemonId)->get('save_id')->first()->save_id);
    }

    public function isParticipatingInOffer(int $offerId): bool
    {
        $offerBuilder = Offer::whereId($offerId);

        if($offerBuilder->count() === 0) return false;

        $offer = $offerBuilder->with(Collection::make(['offerPokemon', 'offerPokemon.pokemon', 'requestPokemon', 'requestPokemon.pokemon'])->undot()->toArray())->get()->first();

        foreach ($offer->offerPokemon as $pokemon) {
            if($this->ownsSave($pokemon->pokemon->save_id)) {
                return true;
            }
        }

        foreach ($offer->requestPokemon as $pokemon) {
            if($this->ownsSave($pokemon->pokemon->save_id)) {
                return true;
            }
        }

        return false;
    }
}
