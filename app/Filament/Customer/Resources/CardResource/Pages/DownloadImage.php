<?php

namespace App\Filament\Customer\Resources\CardResource\Pages;

use App\Filament\Customer\Resources\CardResource;
use App\Models\Package;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;
use Spatie\Image\Enums\AlignPosition;
use Spatie\Image\Enums\BorderType;
use Spatie\Image\Enums\Unit;
use Spatie\Image\Image as SpatieImage;

class DownloadImage extends Page
{
    use InteractsWithRecord;

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
        $this->generateImage();
    }

    protected static string $resource = CardResource::class;

    public function generateImage()
    {

        if (! empty(auth()->user()->package_id)) {

            $package = Package::find(auth()->user()->package_id);

            if ($package->is_credit === 0) {
                $isValid = Carbon::parse($package->package_expiry_date)->startOfDay()
                            >= Carbon::parse($this->record->created_at)->startOfDay();
                if (! $isValid) {
                    Notification::make()->title('Package is expired')->danger()
                        ->body('Your package is allready expired, buy new package to generate new card.')->seconds(10)->send();

                    return redirect()->back();
                }
            } else {
                if (! empty($this->record->generated_image)) {

                } else {
                    if (auth()->user()->credit <= 0) {
                        Notification::make()->title('Package is expired')->danger()
                            ->body('Your package is allready expired, buy new package to generate new card.')->seconds(10)->send();

                        return redirect()->back();
                    }
                }
            }
        } else {
            Notification::make()->title('Package is expired')->danger()
                ->body('Your package is allready expired, buy new package to generate new card.')->seconds(10)->send();

            return redirect()->back();
        }

        if (! empty($this->record->before_text_img) && File::exists(public_path($this->record->before_text_img))) {
            File::delete(public_path($this->record->before_text_img));
        }

        if (! empty($this->record->generated_image) && File::exists(public_path($this->record->generated_image))) {
            File::delete(public_path($this->record->generated_image));
        }

        $frame = SpatieImage::load(storage_path('app/public/'.$this->record->frame->image));

        $insert = SpatieImage::load(storage_path('app/public/'.$this->record->image));
        if ($this->record->image_border !== null) {
            $insert->border(4, BorderType::Expand, str_replace('#', '', $this->record->image_border));
        }

        $img = $frame
            ->watermark($insert, AlignPosition::CenterCenter, paddingY: -190, width: 100,
                widthUnit: Unit::Percent,
                height: 50,
                heightUnit: Unit::Percent);
        /*
            ->text($this->convertToBanglaDate('2025-06-15'), fontSize: 50, color: 'FFBF00',
        y: 70, fontPath: public_path('SolaimanLipi_Bold_10-03-12.ttf'), x: 25, width: 3000)
        */

        if ($this->record->is_logo && ! empty($this->record->logo_id)) {
            $insert2 = SpatieImage::load(storage_path('app/public/'.$this->record->logo->logo));

            $img->watermark($insert2, AlignPosition::RightTop, paddingX: 40, paddingY: 20, width: 30,
                widthUnit: Unit::Percent,
                height: 15,
                heightUnit: Unit::Percent);
        }

        $beforeTextDir = public_path('before_text');
        if (! is_dir($beforeTextDir)) {
            mkdir($beforeTextDir, 0777, true);
        }

        $pathName = 'before_text/'.md5(rand()).'.'.$this->record->format;
        $img->save(public_path($pathName));

        $this->record->update([
            'before_text_img' => $pathName,
        ]);

        $framePath = public_path($pathName);

        // Load frame
        $frame = Image::read($framePath);
        // $frame = Image::read($framePath);
        $frameWidth = $frame->width();
        $frameHeight = $frame->height();

        $text = $this->record->title;
        $maxTextWidth = intval($frameWidth * 0.8);
        $titleColor = $this->record->title_color;

        $frame->text($text, $frameWidth / 2, $frameHeight * 0.73, function ($font) use ($maxTextWidth, $titleColor) {
            $font->filename(public_path('SolaimanLipi_Bold_10-03-12.ttf')); // Works for Bangla & English
            $font->size(intval($maxTextWidth / 12)); // Dynamic size based on width
            $font->color($titleColor);
            $font->align('center');   // Horizontal center
            $font->valign('middle');  // Vertical center of text box
            $font->wrap(1200);
            $font->lineHeight(1.8);
        });

        // Date
        if ($this->record->is_date) {
            $dateColor = $this->record->date_color;
            $date = $this->record->is_bangla_date ? $this->convertToBanglaDate($this->record->date) : Carbon::parse($this->record->date)->format('j F, Y');

            $frame->text($date, 40, 40, function ($font) use ($dateColor) {
                $font->filename(public_path('SolaimanLipi_Bold_10-03-12.ttf')); // Works for Bangla & English
                $font->size(40); // Dynamic size based on width
                $font->color($dateColor);
                // $font->stroke('#e92020ff');
                $font->align('left');   // Horizontal center
                $font->valign('top');  // Vertical center of text box
                $font->wrap(1200);
                $font->lineHeight(1.8);
            });

        }

        // Comment

        if ($this->record->footer_text_id !== null) {
            $footer_short_textColor = $this->record->footer_text_color;
            $frame->text($this->record->footerText->text, $frameWidth / 2, 1250, function ($font) use ($footer_short_textColor) {
                $font->filename(public_path('SolaimanLipi_Bold_10-03-12.ttf')); // Works for Bangla & English
                $font->size(50); // Dynamic size based on width
                $font->color($footer_short_textColor);
                // $font->stroke('#e92020ff');
                $font->align('center');   // Horizontal center
                $font->valign('bottom');  // Vertical center of text box
                $font->wrap(1200);
                $font->lineHeight(1.8);
            });

        }

        /*
         * STEP 4: Save final
         */

        $finalDir = public_path('final');
        if (! is_dir($finalDir)) {
            mkdir($finalDir, 0777, true);
        }

        $finalPath = 'final/'.md5(rand()).'.'.$this->record->format;

        $outputPath = public_path($finalPath);
        $frame->save($outputPath, 100);

        if (! empty(auth()->user()->package_id)) {

            $package = Package::find(auth()->user()->package_id);

            if ($package->is_credit === 0) {

            } else {
                if (empty($this->record->generated_image)) {
                    if (auth()->user()->credit > 0) {
                    auth()->user()->decrement('credit');
                    }
                }
            }
        }

        $this->record->update([
            'generated_image' => $finalPath,
        ]);

    }

    public function convertToBanglaDate($date)
    {
        $banglaMonths = [
            'January' => 'জানুয়ারি',
            'February' => 'ফেব্রুয়ারি',
            'March' => 'মার্চ',
            'April' => 'এপ্রিল',
            'May' => 'মে',
            'June' => 'জুন',
            'July' => 'জুলাই',
            'August' => 'আগস্ট',
            'September' => 'সেপ্টেম্বর',
            'October' => 'অক্টোবর',
            'November' => 'নভেম্বর',
            'December' => 'ডিসেম্বর',
        ];

        $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $banglaNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];

        // Format the date first
        $formattedDate = Carbon::parse($date)->format('j F, Y');

        // Replace English month with Bangla month
        $banglaDate = strtr($formattedDate, $banglaMonths);

        // Replace English numbers with Bangla numbers
        $banglaDate = str_replace($englishNumbers, $banglaNumbers, $banglaDate);

        return $banglaDate;
    }

    protected static string $view = 'filament.customer.resources.card-resource.pages.download-image';
}
