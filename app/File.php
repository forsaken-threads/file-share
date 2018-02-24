<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    /**
     * @return float
     */
    public function getDirAttribute()
    {
        return floor($this->id / 500);
    }

    /**
     * @return string
     */
    public function getPathAttribute()
    {
        return storage_path('app/' . $this->dir . '/' . $this->id);
    }

    /**
     * @return bool
     */
    public function isMine()
    {
        return auth()->check() && auth()->user()->id == $this->user_id;
    }

    /**
     * @return null|string
     */
    public function preview()
    {
        if ($this->force_download) {
            return null;
        }

        switch (mime_content_type($this->path)) {
            case 'application/pdf':
                // fall through
            case 'image/jpeg':
                // fall through
            case 'image/png':
                // fall through
            case 'image/gif':
                return 'preview';
        }

        return null;
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeDownloadable($query)
    {
        return $query->where(function ($q) {
            $q->orWhereNull('expiration')
                ->orWhere('expiration', '>=', Carbon::now()->toDateTimeString());
        });
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeMine($query)
    {
        return $query->where('user_id', auth()->user()->id);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeNamed($query, $name)
    {
        return $query->where('name', $name);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopePublic($query)
    {
        return $query->whereIn('visibility', [Visibility::PUBLIC_WITHOUT_PASSWORD, Visibility::PUBLIC_WITH_PASSWORD]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
