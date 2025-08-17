<?php

namespace App\Http\Controllers;

use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Typography\FontFactory;

use Spatie\Image\Enums\AlignPosition;
use Spatie\Image\Enums\BorderType;
use Spatie\Image\Enums\Unit;
use Spatie\Image\Image as SpatieImage;

use Carbon\Carbon;


class TestController extends Controller
{
    public function index()
    {
$frame = SpatieImage::load('frame.jpg');

        $insert = SpatieImage::load('yyy.jpg')->border(4, BorderType::Expand, '007698');
        $insert2 = SpatieImage::load('logo.png');
        $img = $frame
            ->watermark($insert, AlignPosition::CenterCenter, paddingY: -190, width: 100,
                widthUnit: Unit::Percent,
                height: 50,
                heightUnit: Unit::Percent)
            ->watermark($insert2, AlignPosition::RightTop, paddingX: 40, paddingY: 20, width: 30,
                widthUnit: Unit::Percent,
                height: 15,
                heightUnit: Unit::Percent)
                /*
            ->text($this->convertToBanglaDate('2025-06-15'), fontSize: 50, color: 'FFBF00',
                y: 70, fontPath: public_path('SolaimanLipi_Bold_10-03-12.ttf'), x: 25, width: 3000)
                */
            ->save('example.jpg');

        $framePath = public_path('example.jpg');

        // Load frame
        $frame = Image::read($framePath);
           // $frame = Image::read($framePath);
    $frameWidth  = $frame->width();
    $frameHeight = $frame->height();

        $text = "বরিশালের চরবাড়ীয়ায় খাল দখল করে দোকান নির্মাণ this is also support english text";
    $maxTextWidth = intval($frameWidth * 0.8);

    $frame->text($text, $frameWidth / 2, $frameHeight * 0.70, function ($font) use ($maxTextWidth) {
        $font->filename(public_path('SolaimanLipi_Bold_10-03-12.ttf')); // Works for Bangla & English
        $font->size( intval($maxTextWidth / 15) ); // Dynamic size based on width
        $font->color('#ffffff');
        $font->align('center');   // Horizontal center
        $font->valign('middle');  // Vertical center of text box
        $font->wrap(1200);
        $font->lineHeight(1.8);
    });

    // Date 
    $frame->text($this->convertToBanglaDate('2025-06-15'), 40, 40, function ($font) use ($maxTextWidth) {
        $font->filename(public_path('SolaimanLipi_Bold_10-03-12.ttf')); // Works for Bangla & English
        $font->size(40 ); // Dynamic size based on width
        $font->color('#554d4dff');
        //$font->stroke('#e92020ff');
        $font->align('left');   // Horizontal center
        $font->valign('top');  // Vertical center of text box
        $font->wrap(1200);
        $font->lineHeight(1.8);
    });

    // Comment 

    $frame->text('বিস্তারিত কমেন্টে...', $frameWidth / 2, 1250, function ($font) use ($maxTextWidth) {
        $font->filename(public_path('SolaimanLipi_Bold_10-03-12.ttf')); // Works for Bangla & English
        $font->size(50 ); // Dynamic size based on width
        $font->color('#554d4dff');
        //$font->stroke('#e92020ff');
        $font->align('center');   // Horizontal center
        $font->valign('bottom');  // Vertical center of text box
        $font->wrap(1200);
        $font->lineHeight(1.8);
    });

    /*
     * STEP 4: Save final
     */
    $outputPath = public_path('example.jpg');
    $frame->save($outputPath, 100);

    return view('test');
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

}
