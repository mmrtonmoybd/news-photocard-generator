<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Intervention\Image\Laravel\Facades\Image;
use Spatie\Image\Enums\AlignPosition;
use Spatie\Image\Enums\BorderType;
use Spatie\Image\Enums\Unit;
use Spatie\Image\Image as SpatieImage;
use Outerweb\Settings\Models\Setting;

class TestController extends Controller
{
    public function index()
    { /*
        
            ->save('example.jpg');

        $framePath = public_path('example.jpg');

        // Load frame
        $frame = Image::read($framePath);
        // $frame = Image::read($framePath);
        $frameWidth = $frame->width();
        $frameHeight = $frame->height();

        $text = 'বরিশালের চরবাড়ীয়ায় খাল দখল করে দোকান নির্মাণ this is also support english text';
        $maxTextWidth = intval($frameWidth * 0.8);

        $frame->text($text, $frameWidth / 2, $frameHeight * 0.70, function ($font) use ($maxTextWidth) {
            $font->filename(public_path('SolaimanLipi_Bold_10-03-12.ttf')); // Works for Bangla & English
            $font->size(intval($maxTextWidth / 15)); // Dynamic size based on width
            $font->color('#ffffff');
            $font->align('center');   // Horizontal center
            $font->valign('middle');  // Vertical center of text box
            $font->wrap(1200);
            $font->lineHeight(1.8);
        });

        // Date
        $frame->text($this->convertToBanglaDate('2025-06-15'), 40, 40, function ($font) {
            $font->filename(public_path('SolaimanLipi_Bold_10-03-12.ttf')); // Works for Bangla & English
            $font->size(40); // Dynamic size based on width
            $font->color('#554d4dff');
            // $font->stroke('#e92020ff');
            $font->align('left');   // Horizontal center
            $font->valign('top');  // Vertical center of text box
            $font->wrap(1200);
            $font->lineHeight(1.8);
        });

        // Comment

        $frame->text('বিস্তারিত কমেন্টে...', $frameWidth / 2, 1250, function ($font) {
            $font->filename(public_path('SolaimanLipi_Bold_10-03-12.ttf')); // Works for Bangla & English
            $font->size(50); // Dynamic size based on width
            $font->color('#554d4dff');
            // $font->stroke('#e92020ff');
            $font->align('center');   // Horizontal center
            $font->valign('bottom');  // Vertical center of text box
            $font->wrap(1200);
            $font->lineHeight(1.8);
        });

        
        $outputPath = public_path('example.jpg');
        $frame->save($outputPath, 100);

        return view('test');

        */

        if (Setting::where('key', 'SSLcommerz.sandbox')->value('value') === true) {
        $direct_api_url = 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php';
      } else {
        $direct_api_url = 'https://securepay.sslcommerz.com/gwprocess/v4/api.php';
      }

       dd( Setting::where('key', 'SSLcommerz.storeid')->value('value'), $direct_api_url);
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
