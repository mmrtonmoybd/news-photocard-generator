<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
        'frame_id', 'user_id', 'title', 'title_color', 'is_date', 'date', 'date_color', 'is_logo', 'logo', 'image', 'image_border', 'footer_short_text', 'footer_text_color', 'is_bangla_date', 'generated_image', 'format', 'before_text_img', 'logo_id', 'footer_text_id', 'hash',
    ];

    protected $casts = [
        'is_date' => 'boolean',
        'is_logo' => 'boolean',
        'is_bangla_date' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Card $card) {
            $card->user_id = auth()->user()->id;

            // Booleans
            $card->is_date = $card->is_date ?? 0;
            $card->is_logo = $card->is_logo ?? 0;
            $card->is_bangla_date = $card->is_bangla_date ?? 0;

            // Colors - set DB defaults if null
            if ($card->is_date) {
                $card->date_color = $card->date_color ?? '#000000';
            }
            $card->title_color = $card->title_color ?? '#ffffff';
            $card->image_border = $card->image_border ?? '#000000';

            if (! empty($card->footer_short_text)) {
                $card->footer_text_color = $card->footer_text_color ?? '#000000';
            }
        });

        static::addGlobalScope('user_scope', function (Builder $query) {
            if (auth()->hasUser()) {
                $query->where('user_id', auth()->user()->id);
            }
        });

        static::creating(function (Card $card) {
            $card->hash = hash('sha256', uniqid('', true));
        });
    }

    public function frame()
    {
        return $this->belongsTo(Frame::class);
    }

    public function logo()
    {
        return $this->belongsTo(Logo::class);
    }

    public function footerText()
    {
        return $this->belongsTo(FooterText::class);
    }
}
