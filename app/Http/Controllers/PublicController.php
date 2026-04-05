<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Nominee;
use App\Models\JuryMember;
use App\Models\Partner;
use App\Models\Video;
use App\Models\Gallery;
use App\Models\NavLink;
use App\Models\Vote;
use App\Models\VoteDetail;

class PublicController extends Controller
{
    public function data()
    {
        return response()->json([
            'settings' => Setting::first() ?? (object)[],
            'categories' => Category::where('is_active', 1)->orderBy('display_order')->get(),
            'nominees' => Nominee::where('is_active', 1)->orderBy('display_order')->get(),
            'jury' => JuryMember::where('is_active', 1)->orderBy('display_order')->get(),
            'partners' => Partner::where('is_active', 1)->orderBy('display_order')->get(),
            'videos' => Video::where('is_active', 1)->orderBy('display_order')->get(),
            'gallery' => Gallery::where('is_active', 1)->orderBy('display_order')->get(),
            'navLinks' => NavLink::where('is_active', 1)->orderBy('display_order')->get(),
        ]);
    }

    public function captcha(Request $request)
    {
        $num1 = rand(1, 20);
        $num2 = rand(1, 20);
        $answer = $num1 + $num2;
        session(['captcha' => (string)$answer]);

        $text = "$num1 + $num2 = ?";
        $width = 180;
        $height = 50;

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="'.$width.'" height="'.$height.'">';
        $svg .= '<rect width="100%" height="100%" fill="#f5f5f5"/>';
        for ($i = 0; $i < 5; $i++) {
            $x1 = rand(0, $width); $y1 = rand(0, $height);
            $x2 = rand(0, $width); $y2 = rand(0, $height);
            $color = sprintf('#%06X', rand(0, 0xCCCCCC));
            $svg .= '<line x1="'.$x1.'" y1="'.$y1.'" x2="'.$x2.'" y2="'.$y2.'" stroke="'.$color.'" stroke-width="1"/>';
        }
        $colors = ['#c9a84c', '#8b1a1a', '#5c0e0e', '#a17d1a'];
        $svg .= '<text x="'.($width/2).'" y="35" font-size="24" font-weight="bold" fill="'.$colors[array_rand($colors)].'" text-anchor="middle" font-family="Arial">'.$text.'</text>';
        $svg .= '</svg>';

        return response($svg)->header('Content-Type', 'image/svg+xml');
    }

    public function vote(Request $request)
    {
        $data = $request->all();
        $captcha = trim($data['captcha'] ?? '');
        $sessionCaptcha = session('captcha', '');

        if (empty($captcha) || $captcha !== $sessionCaptcha) {
            return response()->json(['error' => 'Invalid captcha. Please try again.'], 400);
        }

        $name = trim($data['name'] ?? '');
        $mobile = trim($data['mobile'] ?? '');
        $location = trim($data['location'] ?? '');
        $selections = $data['selections'] ?? [];
        $ip = $request->ip();

        if (empty($name)) return response()->json(['error' => 'Name is required.'], 400);
        if (!preg_match('/^\d{10}$/', $mobile)) return response()->json(['error' => 'Valid 10-digit mobile number is required.'], 400);
        if (empty($location)) return response()->json(['error' => 'Location is required.'], 400);
        if (empty($selections)) return response()->json(['error' => 'Please vote in at least one category.'], 400);

        if (Vote::where('voter_mobile', $mobile)->exists()) {
            return response()->json(['error' => 'This mobile number has already voted. One vote per person.'], 400);
        }

        if (Vote::where('ip_address', $ip)->count() >= 4) {
            return response()->json(['error' => 'Maximum 4 votes have been submitted from this device/network. No more votes allowed.'], 400);
        }

        $vote = Vote::create([
            'voter_name' => $name,
            'voter_mobile' => $mobile,
            'voter_location' => $location,
            'ip_address' => $ip,
        ]);

        foreach ($selections as $categoryId => $nomineeId) {
            VoteDetail::create([
                'vote_id' => $vote->id,
                'category_id' => (int)$categoryId,
                'nominee_id' => (int)$nomineeId,
            ]);
        }

        session()->forget('captcha');
        return response()->json(['success' => true, 'message' => 'Thank you for voting!']);
    }
}
