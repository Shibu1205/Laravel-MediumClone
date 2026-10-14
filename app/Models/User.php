<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

#[Fillable(['name', 'username', 'image', 'bio', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, InteractsWithMedia;

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

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('avatar')
            ->width(128)
            ->crop(128,128);

        $this
            ->addMediaConversion('large')
            ->width(1200);
    }

    public function registerMediaCollections():void
    {
        $this->addMediaCollection('avatar')
        ->singleFile();
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }

    public function imageUrl()
    {
        $media=$this->getFirstMedia('avatar');
        if (!$media) {
            return null;

        }
        if ($media->hasGeneratedConversion('avatar')) {
             return $media->getUrl('avatar');
        }
       

        return $media->getUrl();
    }

    public function isFollowedBy(?User $user): bool
    {
        return $user !== null && $this->followers()->wherePivot('follower_id', $user->id)->exists();
    }

    public function claps(): HasMany
    {
        return $this->hasMany(Clap::class);
    }

    public function hasClapped(Post $post): bool
    {
        return $this->claps()->where('post_id', $post->getKey())->exists();
    }
}
