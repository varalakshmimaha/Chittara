<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Nominee;
use App\Models\JuryMember;
use App\Models\Partner;
use App\Models\Video;
use App\Models\Gallery;
use App\Models\NavLink;
use App\Models\AdminUser;
use App\Models\Vote;
use App\Models\VoteDetail;

class AdminController extends Controller
{
    // --- Auth ---
    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $user = AdminUser::where('username', $username)->first();
        if (!$user) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // Handle bcryptjs ($2a$) and PHP bcrypt ($2y$) compatibility
        $storedHash = $user->password;
        $compatHash = preg_replace('/^\$2a\$/', '$2y$', $storedHash);

        if (!password_verify($password, $compatHash)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        session(['admin_logged_in' => true, 'admin_user' => $username]);
        return response()->json(['success' => true]);
    }

    public function logout()
    {
        session()->flush();
        return redirect('/admin/login');
    }

    // --- Settings ---
    public function getSettings()
    {
        return response()->json(Setting::first() ?? []);
    }

    public function updateSettings(Request $request)
    {
        $fields = ['site_title', 'tagline', 'social_twitter', 'social_instagram', 'social_youtube', 'social_facebook', 'vote_button_text', 'footer_text', 'about_text'];
        $data = $request->only($fields);
        Setting::where('id', 1)->update($data);
        return response()->json(['success' => true]);
    }

    public function upload(Request $request, $type)
    {
        $file = $request->file('file');
        if (!$file) {
            return response()->json(['error' => 'No file uploaded'], 400);
        }

        $allowed = ['jpeg', 'jpg', 'png', 'gif', 'webp', 'svg'];
        $ext = strtolower($file->getClientOriginalExtension());
        if (!in_array($ext, $allowed)) {
            return response()->json(['error' => 'Invalid file type'], 400);
        }

        $filename = time() . '-' . mt_rand(100000000, 999999999) . '.' . $ext;
        $file->move(public_path('uploads/' . $type), $filename);
        $filePath = '/uploads/' . $type . '/' . $filename;

        $field = $request->input('field', '');
        $allowedFields = ['logo1', 'logo2', 'logo3', 'logo_top_left', 'banner_image', 'banner_bg', 'about_bg'];
        if ($field && in_array($field, $allowedFields)) {
            Setting::where('id', 1)->update([$field => $filePath]);
        }

        return response()->json(['success' => true, 'path' => $filePath]);
    }

    // --- Categories ---
    public function getCategories()
    {
        return response()->json(Category::orderBy('display_order')->get());
    }

    public function createCategory(Request $request)
    {
        $cat = Category::create([
            'name' => $request->input('name'),
            'display_order' => $request->input('display_order', 0),
        ]);
        return response()->json(['success' => true, 'id' => $cat->id]);
    }

    public function updateCategory(Request $request, $id)
    {
        Category::where('id', $id)->update([
            'name' => $request->input('name'),
            'display_order' => $request->input('display_order', 0),
            'is_active' => $request->input('is_active', 1),
        ]);
        return response()->json(['success' => true]);
    }

    public function deleteCategory($id)
    {
        Nominee::where('category_id', $id)->delete();
        Category::where('id', $id)->delete();
        return response()->json(['success' => true]);
    }

    // --- Nominees ---
    public function getNominees(Request $request)
    {
        $query = Nominee::select('nominees.*', 'categories.name as category_name')
            ->leftJoin('categories', 'nominees.category_id', '=', 'categories.id');

        if ($request->has('category_id') && $request->category_id) {
            $query->where('nominees.category_id', $request->category_id);
        }

        return response()->json($query->orderBy('nominees.category_id')->orderBy('nominees.display_order')->get());
    }

    public function createNominee(Request $request)
    {
        $image = $this->handleImageUpload($request, 'nominees');
        $nominee = Nominee::create([
            'category_id' => $request->input('category_id'),
            'name' => $request->input('name'),
            'subtitle' => $request->input('subtitle', ''),
            'image' => $image,
            'display_order' => $request->input('display_order', 0),
        ]);
        return response()->json(['success' => true, 'id' => $nominee->id]);
    }

    public function updateNominee(Request $request, $id)
    {
        $image = $this->handleImageUpload($request, 'nominees');
        $data = [
            'category_id' => $request->input('category_id'),
            'name' => $request->input('name'),
            'subtitle' => $request->input('subtitle', ''),
            'display_order' => $request->input('display_order', 0),
            'is_active' => $request->input('is_active', 1),
        ];
        if ($image) {
            $data['image'] = $image;
        }
        Nominee::where('id', $id)->update($data);
        return response()->json(['success' => true]);
    }

    public function deleteNominee($id)
    {
        Nominee::where('id', $id)->delete();
        return response()->json(['success' => true]);
    }

    // --- Jury ---
    public function getJury()
    {
        return response()->json(JuryMember::orderBy('display_order')->get());
    }

    public function createJury(Request $request)
    {
        $image = $this->handleImageUpload($request, 'jury');
        JuryMember::create([
            'name' => $request->input('name'),
            'designation' => $request->input('designation', ''),
            'image' => $image,
            'display_order' => $request->input('display_order', 0),
        ]);
        return response()->json(['success' => true]);
    }

    public function updateJury(Request $request, $id)
    {
        $image = $this->handleImageUpload($request, 'jury');
        $data = [
            'name' => $request->input('name'),
            'designation' => $request->input('designation', ''),
            'display_order' => $request->input('display_order', 0),
            'is_active' => $request->input('is_active', 1),
        ];
        if ($image) {
            $data['image'] = $image;
        }
        JuryMember::where('id', $id)->update($data);
        return response()->json(['success' => true]);
    }

    public function deleteJury($id)
    {
        JuryMember::where('id', $id)->delete();
        return response()->json(['success' => true]);
    }

    // --- Partners ---
    public function getPartners()
    {
        return response()->json(Partner::orderBy('display_order')->get());
    }

    public function createPartner(Request $request)
    {
        $image = $this->handleImageUpload($request, 'partners');
        Partner::create([
            'name' => $request->input('name'),
            'image' => $image,
            'website' => $request->input('website', ''),
            'display_order' => $request->input('display_order', 0),
        ]);
        return response()->json(['success' => true]);
    }

    public function deletePartner($id)
    {
        Partner::where('id', $id)->delete();
        return response()->json(['success' => true]);
    }

    // --- Videos ---
    public function getVideos()
    {
        return response()->json(Video::orderBy('display_order')->get());
    }

    public function createVideo(Request $request)
    {
        Video::create([
            'title' => $request->input('title'),
            'youtube_url' => $request->input('youtube_url'),
            'display_order' => $request->input('display_order', 0),
        ]);
        return response()->json(['success' => true]);
    }

    public function deleteVideo($id)
    {
        Video::where('id', $id)->delete();
        return response()->json(['success' => true]);
    }

    // --- Gallery ---
    public function createGallery(Request $request)
    {
        $image = $this->handleImageUpload($request, 'gallery');
        Gallery::create([
            'title' => $request->input('title', ''),
            'image' => $image,
            'display_order' => $request->input('display_order', 0),
        ]);
        return response()->json(['success' => true]);
    }

    public function deleteGallery($id)
    {
        Gallery::where('id', $id)->delete();
        return response()->json(['success' => true]);
    }

    // --- Nav Links ---
    public function getNavLinks()
    {
        return response()->json(NavLink::orderBy('display_order')->get());
    }

    public function createNavLink(Request $request)
    {
        NavLink::create([
            'label' => $request->input('label'),
            'url' => $request->input('url', '#'),
            'display_order' => $request->input('display_order', 0),
        ]);
        return response()->json(['success' => true]);
    }

    public function deleteNavLink($id)
    {
        NavLink::where('id', $id)->delete();
        return response()->json(['success' => true]);
    }

    // --- Reports ---
    public function stats()
    {
        return response()->json([
            'totalVotes' => Vote::count(),
            'totalCategories' => Category::count(),
            'totalNominees' => Nominee::count(),
        ]);
    }

    public function byCategory()
    {
        $cats = Category::orderBy('display_order')->get();
        $report = [];
        foreach ($cats as $cat) {
            $nominees = Nominee::select('nominees.id', 'nominees.name', 'nominees.image')
                ->selectRaw('COUNT(vote_details.id) as vote_count')
                ->leftJoin('vote_details', function ($join) use ($cat) {
                    $join->on('vote_details.nominee_id', '=', 'nominees.id')
                         ->where('vote_details.category_id', '=', $cat->id);
                })
                ->where('nominees.category_id', $cat->id)
                ->groupBy('nominees.id', 'nominees.name', 'nominees.image')
                ->orderByDesc('vote_count')
                ->get();

            $totalVotes = $nominees->sum('vote_count');
            $report[] = ['category' => $cat, 'nominees' => $nominees, 'total_votes' => $totalVotes];
        }
        return response()->json($report);
    }

    public function voters(Request $request)
    {
        $page = (int)$request->input('page', 1);
        $limit = (int)$request->input('limit', 50);
        $offset = ($page - 1) * $limit;

        $total = Vote::count();
        $voters = Vote::orderByDesc('voted_at')->skip($offset)->take($limit)->get();

        return response()->json([
            'voters' => $voters,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
        ]);
    }

    public function voterDetail($id)
    {
        $voter = Vote::find($id);
        $selections = VoteDetail::select('vote_details.*', 'categories.name as category_name', 'nominees.name as nominee_name', 'nominees.image as nominee_image')
            ->join('categories', 'categories.id', '=', 'vote_details.category_id')
            ->join('nominees', 'nominees.id', '=', 'vote_details.nominee_id')
            ->where('vote_details.vote_id', $id)
            ->get();

        return response()->json(['voter' => $voter, 'selections' => $selections]);
    }

    // --- Change Password ---
    public function changePassword(Request $request)
    {
        $adminUser = session('admin_user');
        $user = AdminUser::where('username', $adminUser)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 400);
        }

        $compatHash = preg_replace('/^\$2a\$/', '$2y$/', $user->password);
        if (!password_verify($request->input('current_password'), $compatHash)) {
            return response()->json(['error' => 'Current password is incorrect'], 400);
        }

        $user->password = Hash::make($request->input('new_password'));
        $user->save();
        return response()->json(['success' => true]);
    }

    // --- Helper ---
    private function handleImageUpload(Request $request, $type)
    {
        $file = $request->file('image');
        if (!$file) return '';

        $allowed = ['jpeg', 'jpg', 'png', 'gif', 'webp', 'svg'];
        $ext = strtolower($file->getClientOriginalExtension());
        if (!in_array($ext, $allowed)) return '';

        $filename = time() . '-' . mt_rand(100000000, 999999999) . '.' . $ext;
        $file->move(public_path('uploads/' . $type), $filename);
        return '/uploads/' . $type . '/' . $filename;
    }
}
