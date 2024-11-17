<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use http\QueryString;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Comic;
use App\Models\Story;
use App\Models\History;
use App\Models\Category;
use App\Models\Author;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Level;
use App\Models\Comment;
use App\Models\Vote;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\SEOMeta;
use App\Models\Chapter;


class HomeController extends Controller
{
    public function index(){
        $seo = DB::table('seo')->get();
        SEOTools::setTitle($seo[0]->value);
        SEOTools::setDescription($seo[4]->value);
        SEOMeta::addKeyword($seo[2]->value);
        SEOTools::opengraph()->addProperty('type', 'website');
        SEOTools::opengraph()->addImage($seo[5]->value);
        $metaHtml = $seo[6]->value;
        SEOMeta::addMeta('article:published_time', now(), 'property');

        $comicsHot = Comic::limit(10)->orderByDesc('total_views')->get();
        $comicsRecentUpdate = Comic::limit(42)->orderByDesc('updated_at')->get();
        return view("/users/index", compact(
            'comicsRecentUpdate',
            'metaHtml',
            'comicsHot'
        ));
    }

    public function showDetailComic($slug){
        $comic = Comic::where('slug', $slug)->first();
        if(!$comic){
            abort(404);
        }
        $comic = Comic::where('slug', $slug)->with('categories', 'author', 'votes')->withCount('votes', 'follows', 'chapters', 'comments')->first();
        $replacements = [
            '{{$comic->name}}' => $comic->name,
            '{{$comic->status}}' => $comic->status,
            '{{$comic->origin_name}}' => $comic->origin_name,
        ];
        $seo = DB::table('seo')->get();
        $title = str_replace(array_keys($replacements), array_values($replacements), $seo[7]->value);
        SEOTools::setTitle($title);
        SEOTools::setDescription("❶✔️ Đọc truyện {$comic->name} Tiếng Việt bản dịch Full mới nhất");
        SEOMeta::addKeyword("{$comic->name},{$comic->name} tiếng việt,đọc truyện {$comic->name},truyện {$comic->name}");
        SEOTools::opengraph()->addProperty('type', 'article');
        SEOTools::opengraph()->addImage($comic->thumbnail);
        SEOTools::opengraph()->setSiteName(env('APP_NAME'));
        SEOTools::opengraph()->setUrl(url()->current());
        SEOMeta::setCanonical(url()->current());
        $metaHtml = $seo[6]->value;
        SEOMeta::addMeta('article:published_time', now(), 'property');
        $follow = false;
        if(Auth::check()){
            $user = Auth::user();
            $check = DB::table('follows')->where('user_id', $user->id)->where('comic_id', $comic->id)->first();
            if($check){
                $follow = true;
            }
            $history = DB::table('histories')->where('user_id', $user->id)->where('comic_id', $comic->id)->first();
            if($history){
                $history = explode(",", $history->chapterComics_id);
                $comic->history = $history;
                $conti = DB::table('chapters')->where('id', end($history))->select('slug')->first();
                $comic->conti = $conti ? $conti->slug : null;
            }
        }
        $chapters = Chapter::where('comic_id', $comic->id)->select(DB::raw('MAX(id) as id'), 'name', 'slug', DB::raw('MAX(updated_at) as updated_at'))->groupBy('name', 'slug')->orderByDesc('chapter_number')->get();
        $comments = $comic->comments()->paginate(5);
        $this->upView('comic', $comic->id);
        return view("/users/detail", compact(
            'comic',
            'follow',
            'comments',
            'metaHtml',
            'chapters',
        ));
    }

    public function showReadComicPage($slug, $chapter)
    {
        $comic = Comic::where('slug', $slug)->first();
        if(!$comic){
            abort(404);
        }
        $chapterSelected = Chapter::where('comic_id', $comic->id)
            ->where('slug', $chapter)
            ->first();
        if(!$chapterSelected){
            abort(404);
        }
        $comic = Comic::where('slug', $slug)->with('comments')->withCount('comments')->first();
        $servers = DB::table('chapters')->where('comic_id', $comic->id)->select('server')->groupBy('server')->get();
        $comic->prevChap = Chapter::where('comic_id', $comic->id)
            ->where('chapter_number', '<', $chapterSelected->chapter_number)
            ->select('name','slug')
            ->orderByDesc('chapter_number')->first();
        $comic->nextChap = Chapter::where('comic_id', $comic->id)
            ->where('chapter_number', '>', $chapterSelected->chapter_number)
            ->select('name','slug')
            ->orderBy('chapter_number')->first();
        $chapters = Chapter::where('comic_id', $comic->id)
        ->select('name', 'slug', 'chapter_number')
        ->orderByDesc('chapter_number')
        ->groupBy('name', 'slug', 'chapter_number')->get();
        $this->upView('comic', $comic->id);
        $images = DB::table('chapterimgs')->where('chapter_id', $chapterSelected->id)->orderBy('page')->get();
        $follow = false;
        if(Auth::check()){
            $user = Auth::user();
            $check = DB::table('follows')->where('user_id', $user->id)->where('comic_id', $comic->id)->first();
            if($check){
                $follow = true;
            }
        }
        $comments = $comic->comments()->orderBy('created_at', 'desc')->paginate(5);
        $replacements = [
            '{{$comic->name}}' => $comic->name,
            '{{$comic->status}}' => $comic->status,
            '{{$comic->origin_name}}' => $comic->origin_name,
            '{{$chapterSelected->name}}' => $chapterSelected->name,
        ];
        $seo = DB::table('seo')->get();
        $title = str_replace(array_keys($replacements), array_values($replacements), $seo[8]->value);
        SEOTools::setTitle($title);
        $description = "Đọc truyện tranh " . $comic->name . ($comic->origin_name ? " - " . $comic->origin_name : "") . " chap " . $chapterSelected->name . " tiếng việt. Mới nhất nhanh nhất tại " . env('APP_NAME');
        SEOTools::setDescription($description);
        SEOMeta::addKeyword("{$comic->name},{$comic->name} tiếng việt,đọc truyện {$comic->name},truyện {$comic->name}, truyện {$comic->name} chap {$chapterSelected->name}");
        SEOTools::opengraph()->addProperty('type', 'article');
        SEOTools::opengraph()->addImage($comic->thumbnail);
        SEOTools::opengraph()->setSiteName(env('APP_NAME'));
        SEOTools::opengraph()->setUrl(url()->current());
        SEOMeta::setCanonical(url()->current());
        $metaHtml = $seo[6]->value;
        SEOMeta::addMeta('article:published_time', now(), 'property');
        $this->saveHistory('comic', $comic->id, $chapterSelected->id);
        return view("/users/read", compact('comic', 'chapterSelected', 'servers', 'chapters', 'images', 'follow', 'comments', 'metaHtml'));
    }

    public function searchComic(Request $request)
    {
        $query = $request->get('search');
        if(!$query || $query == ""){
            return response()->json("");
        }
        $comics = Comic::where('name', 'like', '%' . $query . '%')->orWhere('slug', 'like', '%' . $query . '%')->orWhere('origin_name', 'like', '%' . $query . '%')->limit(10)->get();
        $msg = "";
        if(count($comics) == 0){
            $msg = "<li><div class='no_result'>Không Tìm Thấy Kết Quả Nào!</div></li>";
        }else{
            foreach($comics as $comic){
                $msg .= "<li><a href='" . route('detail', $comic->slug) . "'><div class='search_avatar'><img src='" . $comic->thumbnail . "' alt='" . $comic->name . "'></div><div class='search_info'>
                    <p class='name'>". $comic->name ."</p>
                    <p class='name_other'>".$comic->origin_name."</p>
                </div>
                <div class='clear'></div></a></li>";
            }
        }
        return response()->json($msg);
    }

    public function showSearchComic(Request $request){
        $query = $request->get('q');
        $comics = Comic::where('name', 'like', '%' . $query . '%')->orWhere('slug', 'like', '%' . $query . '%')->orWhere('origin_name', 'like', '%' . $query . '%')->paginate(42);
        return view("/users/searchPage", compact('comics', 'query'));
    }

    public function vote(Request $request){
        if(!Auth::check()){
            return response()->json(['status' => "error", 'message' => 'Bạn cần đăng nhập để sử dụng chức năng này.'], 200);
        }
        $type = $request->type;
        $user = Auth::user();
        if($type == "comic"){
            $check = Vote::where('user_id', $user->id)->where('comic_id', $request->id)->first();
            if($check){
                DB::table('voting')->where('user_id', $user->id)->where('comic_id', $request->id)->update([
                    'vote' => $request->vote,
                ]);
                return response()->json(['status' => "success", 'message' => 'Bạn đã thích bộ này rồi.'], 200);
            }
            DB::table('voting')->insert([
                'user_id' => $user->id,
                'comic_id' => $request->id,
                'vote' => $request->vote,
            ]);
        }else if($type == "story"){
            $check = Vote::where('user_id', $user->id)->where('story_id', $request->id)->first();
            if($check){
                DB::table('voting')->where('user_id', $user->id)->where('story_id', $request->id)->update([
                    'vote' => $request->vote,
                ]);
                return response()->json(['status' => "success", 'message' => 'Đánh giá thành công.'], 200);
            }

            DB::table('voting')->insert([
                'user_id' => $user->id,
                'story_id' => $request->id,
                'vote' => $request->vote,
            ]);
        }
        return response()->json(['status' => "success", 'message' => 'Thích truyện thành công.'], 200);
    }

    public function follow(Request $request){
        if(!Auth::check()){
            return response()->json(['message' => 'Bạn cần đăng nhập để theo dõi.'], 200);
        }
        $user = Auth::user();
        if($request->type == "story"){
            $col = "story_id";
        }else{
            $col = "comic_id";
        }
        $check = DB::table('follows')->where('user_id', $user->id)->where($col, $request->id)->first();
        if($check){
            DB::table('follows')->where('user_id', $user->id)->where($col, $request->id)->delete();
            return response()->json(['message' => 'Bỏ theo dõi thành công.'], 200);
        }
        DB::table('follows')->insert([
            'user_id' => $user->id,
            $col => $request->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return response()->json(['message' => 'Theo dõi thành công.'], 200);
    }

    public function comment(Request $request){
        if(!Auth::check()){
            return response()->json(['message' => 'Bạn cần đăng nhập để bình luận.'], 401);
        }
        $user = Auth::user();
        $table = $request->table;
        if($table == "comic"){
            $col = "comic_id";
        }else{
            $col = "story_id";
        }
        if($request->type == "comment"){
            Comment::create([
                'user_id' => $user->id,
                $col => $request->id,
                'content' => $request->content,
                'chapter_id' => $request->chapter_id,
            ]);
            $html = "<article class='info-comment child_5276162 parent_0 comment-main-level'>
                        <div class='outsite-comment comment-main-level'>
                            <figure class='avartar-comment'>
                                <img src='{$user->avatar}' alt='{$user->name}'>
                            </figure>
                             <div class='item-comment'>
                                <div class='outline-content-comment'>
                                    <div>
                                        <strong class='level' style='background-image: url({$user->level->image});background-size:auto;color:transparent;-webkit-background-clip: text;background-position: center;'>{$user->name}</strong>
                                        <span class='title-user-comment title-member level_6'>{$user->level->level}</span>
                                    </div>
                                    <div class='content-comment'>
                                        {$request->content}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>";
        }else{
            DB::table('replies')->insert([
                'user_id' => $user->id,
                'comment_id' => $request->comment_id,
                'content' => $request->content,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $html = "<div class='border-t border-zinc-700 text-base mt-2 pt-2'>
                        <div class='border-l border-dashed border-zinc-700 px-4 py-2 text-sm ml-10 lg:ml-14'>
                            <div class='flex justify-between items-center mb-2'>
                                <div class='flex items-center flex-wrap'>
                                    <p class='inline-flex items-center mr-3 text-[14px] text-zinc-300 font-semibold'>
                                        <img class='mr-2 w-10 h-10 rounded-full' src='{$user->avatar}' alt=''>
                                        <strong style='background-image: url('{$user->level->image}'); color:transparent; -webkit-background-clip: text; background-position: center;'>{$user->name}</strong>
                                    </p>
                                    <span class='text-sm mr-3 py-1 px-3 rounded' style='background-image: url('{$user->level->image}')'>
                                        {$user->level->level}
                                    </span>
                                    <p class='text-zinc-400 text-sm'>
                                        <span>1 giây trước</span>
                                    </p>
                                </div>
                            </div>
                            <p class='text-[13px]'>{$request->content}</p>
                        </div>
                    </div>";
        }
        return response()->json([
            'status' => 'success',
            'message' => $html
        ], 200);
    }

    public function like(Request $request){
        if(!Auth::check()){
            return response()->json(['message' => 'Bạn cần đăng nhập để thích.'], 401);
        }
        $like = 0;
        if($request->type == "comment"){
            DB::table('comments')->where('id', $request->id)->update([
                'like' => DB::raw('`like` + 1')
            ]);
            $like = DB::table('comments')->where('id', $request->id)->first()->like;
        }else{
            DB::table('replies')->where('id', $request->id)->update([
                'like' => DB::raw('`like` + 1')
            ]);
            $like = DB::table('replies')->where('id', $request->id)->first()->like;
        }
        return response()->json(['message' => 'Thích thành công.', 'like' => $like], 200);
    }

    public function upView($table, $id)
    {
        if($table == 'comic'){
            $col = "comic_id";
            DB::table('comics')->where('id', $id)->update([
                'total_views' => DB::raw('total_views + 1')
            ]);
        }else{
            $col = "story_id";
            DB::table('stories')->where('id', $id)->update([
                'total_views' => DB::raw('total_views + 1')
            ]);
        }

        $checkDaily = DB::table('daily_ranking')->where($col, $id)->whereDate('created_at', now())->first();
        if($checkDaily){
            DB::table('daily_ranking')->where($col, $id)->whereDate('created_at', now())->update([
                'total_views' => DB::raw('total_views + 1'),
                'updated_at' => now()
            ]);
        }else{
            DB::table('daily_ranking')->insert([
                $col => $id,
                'total_views' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $firstDayOfWeek = Carbon::now()->startOfWeek();
        $endDayOfWeek = Carbon::now()->endOfWeek();
        $checkWeekly = DB::table('weekly_ranking')->where($col, $id)->whereDate('created_at', '>', $firstDayOfWeek)->whereDate('created_at', '<=', $endDayOfWeek)->first();
        if($checkWeekly){
            DB::table('weekly_ranking')->where($col, $id)->whereDate('created_at', '>', $firstDayOfWeek)->whereDate('created_at', '<=', $endDayOfWeek)->update([
                'total_views' => DB::raw('total_views + 1'),
                'updated_at' => now()
            ]);
        }else{
            DB::table('weekly_ranking')->insert([
                $col => $id,
                'total_views' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $firstDayOfMonth = Carbon::now()->startOfMonth();
        $endDayOfMonth = Carbon::now()->endOfMonth();
        $checkMonthly = DB::table('monthly_ranking')->where($col, $id)->whereDate('created_at', '>', $firstDayOfMonth)->whereDate('created_at', '<=', $endDayOfMonth)->first();
        if($checkMonthly){
            DB::table('monthly_ranking')->where($col, $id)->whereDate('created_at', '>', $firstDayOfMonth)->whereDate('created_at', '<=', $endDayOfMonth)->update([
                'total_views' => DB::raw('total_views + 1'),
                'updated_at' => now()
            ]);
        }else{
            DB::table('monthly_ranking')->insert([
                $col => $id,
                'total_views' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    public function upExp(Request $request)
    {
        if(!$request->id){
            return;
        }

        $user = User::where('id', $request->id)->first();
        $user->exp += 10;
        $user->save();
    }

    public function showProfile(){
        if(!Auth::check()){
            return redirect()->route('home');
        }
        $user = Auth::user();

        return view("/users/profile", compact('user'));
    }

    public function updateProfile(Request $request){
        if(!Auth::check()){
            return redirect()->route('home');
        }
        $user = Auth::user();
        $user->name = $request->name;
        $user->save();
        return response()->json([
            'message' => 'Cập nhật thông tin thành công.'
        ], 200);
    }

    public function showChangePass(){
        return view("users.changePass");
    }

    public function updatePassword(Request $request){
        $oldPass = $request->oldPass;
        $newPass = $request->newPass;
        $user = Auth::user();
        if(!password_verify($oldPass, $user->password)){
            return response()->json([
                'message' => 'Mật khẩu cũ không đúng.'
            ], 200);
        }
        $user->password = bcrypt($newPass);
        $user->save();
        return response()->json([
            'message' => 'Đổi mật khẩu thành công.'
        ], 200);
    }

    public function showCategory($slug, Request $request){
        $status = $request->input('status') ?? "all";
        $sort = $request->input('sort') ?? "0";
        $category = Category::where('slug', $slug)->first();
        if(!$category){
            abort(404);
        }
        $replacements = [
            '{{$category->name}}' => $category->name,
            '{{$category->slug}}' => $category->slug,
        ];
        $seo = DB::table('seo')->get();
        $title = str_replace(array_keys($replacements), array_values($replacements), $seo[9]->value);
        SEOTools::setTitle($title);
        $description = str_replace(array_keys($replacements), array_values($replacements), $seo[10]->value);
        SEOTools::setDescription($description);
        $keyword = str_replace(array_keys($replacements), array_values($replacements), $seo[11]->value);
        SEOMeta::addKeyword($keyword);
        SEOTools::opengraph()->addProperty('type', 'article');
        SEOTools::opengraph()->setSiteName(env('APP_NAME'));
        SEOTools::opengraph()->setUrl(url()->current());
        SEOMeta::setCanonical(url()->current());
        SEOMeta::addMeta('article:published_time', now(), 'property');

        $comics = Comic::whereHas('categories', function ($query) use ($category) {
            $query->where('category_id', $category->id);
        });

        if($status == "1"){
            $comics = $comics->where('status', 'ongoing');
        }else if ($status == "2"){
            $comics = $comics->where('status', 'completed');
        }

        if($sort == "1"){
            $comics = $comics->orderBy('created_at');
        }else if($sort == "2"){
            $comics = $comics->orderByDesc('updated_at');
        }else if($sort == "3"){
            $comics = $comics->orderBy('updated_at');
        }else if($sort == "4"){
            $comics = $comics->orderByDesc('total_views');
        }else if($sort == "5"){
            $comics = $comics->orderByDesc('total_views');
        }else{
            $comics = $comics->orderByDesc('created_at');
        }

        $comics = $comics->paginate(42);

        $categories = Category::all();
        return view("/users/category", compact('categories', 'comics', 'category'));
    }

    public function showAuthor($slug, Request $request){
        $status = $request->input('status') ?? "all";
        $author = Author::where('slug', $slug)->first();
        if(!$author){
            abort(404);
        }
        $replacements = [
            '{{$author->name}}' => $author->name,
            '{{$author->slug}}' => $author->slug,
        ];
        $seo = DB::table('seo')->get();
        $title = str_replace(array_keys($replacements), array_values($replacements), $seo[12]->value);
        SEOTools::setTitle($title);
        $description = str_replace(array_keys($replacements), array_values($replacements), $seo[13]->value);
        SEOTools::setDescription($description);
        $keyword = str_replace(array_keys($replacements), array_values($replacements), $seo[14]->value);
        SEOMeta::addKeyword($keyword);
        SEOTools::opengraph()->addProperty('type', 'article');
        SEOTools::opengraph()->setSiteName(env('APP_NAME'));
        SEOTools::opengraph()->setUrl(url()->current());
        SEOMeta::setCanonical(url()->current());
        SEOMeta::addMeta('article:published_time', now(), 'property');

        $comics = Comic::whereHas('author', function ($query) use ($author) {
            $query->where('id_author', $author->id);
        });

        if($status == "1"){
            $comics = $comics->where('status', 'ongoing');
        }else if ($status == "2"){
            $comics = $comics->where('status', 'completed');
        }

        $comics = $comics->paginate(42);
        return view("/users/author", compact('comics', 'author'));
    }

    public function showList($slug, Request $request){
        $status = $request->input('status') ?? "all";
        $sort = $request->input('sort') ?? "0";

        if($status == "1"){
            $comics = $comics->where('status', 'ongoing');
        }else if ($status == "2"){
            $comics = $comics->where('status', 'completed');
        }

        if($slug == "top-ngay"){
            $comics = Comic::leftJoin('daily_ranking', 'daily_ranking.comic_id', '=', 'comics.id')
            ->select('comics.*', 'daily_ranking.total_views')
            ->orderByDesc('daily_ranking.total_views');
            $name = "Top Ngày";
        }else if($slug == "top-tuan"){
            $comics = Comic::leftJoin('weekly_ranking', 'weekly_ranking.comic_id', '=', 'comics.id')
            ->select('comics.*', 'weekly_ranking.total_views')
            ->orderByDesc('weekly_ranking.total_views');
            $name = "Top Tuần";
        }else if($slug == "top-thang"){
            $comics = Comic::leftJoin('monthly_ranking', 'monthly_ranking.comic_id', '=', 'comics.id')
            ->select('comics.*', 'monthly_ranking.total_views')
            ->orderByDesc('monthly_ranking.total_views');
            $name = "Top Tháng";
        }else if($slug == "truyen-yeu-thich"){
            $comics = Comic::withCount('votes')->orderByDesc('votes_count');
            $name = "Truyện Yêu Thích";
        }else if($slug == "truyen-moi-cap-nhat"){
            $comics = Comic::orderByDesc('updated_at');
            $name = "Truyện Mới Cập Nhật";
        }else if($slug == "truyen-tranh-moi"){
            $comics = Comic::orderByDesc('created_at');
            $name = "Truyện Mới";
        }else if($slug == "truyen-full"){
            $comics = Comic::where('status', 'completed');
            $name = "Truyện Full";
        }else if($slug == "truyen-ngau-nhien"){
            $comics = Comic::inRandomOrder();
            $name = "Truyện Ngẫu Nhiên";
        }else if($slug == "truyen-con-gai"){
            $comics = Comic::whereHas('categories', function ($query) {
                $query->where('categories.slug', 'comedy')->orWhere('categories.slug', 'romance')->orWhere('categories.slug', 'shoujo');
            });
            $name = "Truyện Con Gái";
        }else if ($slug == "truyen-con-trai"){
            $comics = Comic::whereHas('categories', function ($query) {
                $query->where('categories.slug', 'action')->orWhere('categories.slug', 'adventure')->orWhere('categories.slug', 'shounen');
            });
            $name = "Truyện Con Trai";
        }else{
            abort(404);
        }
        $comics = $comics->paginate(42);

        return view("/users/list", compact('comics', 'name', 'slug'));
    }

    public function showSearch(Request $request){
        $category = $request->input('category') ?? null;
        $nocategory = $request->input('nocategory') ?? null;
        $status = $request->input('status') ?? 0;
        $minchapter = $request->input('minchapter') ?? 0;
        $sort = $request->input('sort') ?? 0;
        $comics = Comic::query();
        if($category){
           $comics = $comics->whereHas('categories', function ($query) use ($category) {
                $query->whereIn('category_id', explode(",", $category));
            });
        }
        if($nocategory){
            $comics = $comics->whereDoesntHave('categories', function ($query) use ($nocategory) {
                $query->whereIn('category_id', explode(",", $nocategory));
            });
        }
        if($status){
            if($status == '1'){
                $comics = $comics->where('status', 'ongoing');
            }else if($status == '2'){
                $comics = $comics->where('status', 'completed');
            }
        }
        if($minchapter){
            $comics = $comics->whereHas('chapters', function ($query) use ($minchapter) {
                $query->havingRaw('COUNT(*) >= ?', [$minchapter]);
            });
        }
        if($sort){
            if($sort == '1'){
                $comics = $comics->orderBy('created_at');
            }else if($sort == '2'){
                $comics = $comics->orderByDesc('updated_at');
            }else if($sort == '3'){
                $comics = $comics->orderBy('updated_at');
            }else if($sort == '4'){
                $comics = $comics->orderByDesc('total_views');
            }else if($sort == '5'){
                $comics = $comics->orderBy('total_views');
            }else{
                $comics = $comics->orderByDesc('created_at');
            }
        }
        $comics = $comics->paginate(42);
        $categories = Category::all();
        return view("users.advancedFilter", compact('categories', 'comics'));
    }

    public function showFollow(){
        $comics = [];
        if(Auth::check()){
            $user = Auth::user();
            $comics = Comic::join('follows', 'follows.comic_id', '=', 'comics.id')
            ->where('follows.user_id', $user->id)
            ->select('comics.*')
            ->paginate(42);
        }
        return view("users.followPage", compact('comics'));
    }

    public function showHistory(){
        $comics = [];
        if(Auth::check()){
            $user = Auth::user();
            $comics = Comic::join('histories', 'histories.comic_id', '=', 'comics.id')
            ->where('histories.user_id', $user->id)
            ->select('comics.*')
            ->paginate(42);
            foreach($comics as $comic){
                $history = DB::table('histories')->where('user_id', $user->id)->where('comic_id', $comic->id)->first();
                $history = explode(",", $history->chapterComics_id);
                $comic->history = $history;
                $conti = DB::table('chapters')->where('id', end($history))->first();
                $comic->conti = $conti ?? null;
            }
        }
        return view("users.historyPage", compact('comics'));
    }

    public function changeServer(){
        $server = $_POST['server'];
        $chapterName = $_POST['chapter'];
        $comic = $_POST['comic'];
        $chapter = DB::table('chapters')->where('comic_id', $comic)->where('server', $server)
        ->where('name', $chapterName)->first();
        $images = DB::table('chapterimgs')->where('chapter_id', $chapter->id)->orderByRaw('CAST(page AS UNSIGNED) ASC')->get();
        $serverSelected = $server;
        return response()->json([
            'images' => $images,
            'server' => $serverSelected
        ]);
    }

    public function saveHistory($table, $id, $chap){
        if($table == "story"){
            $col = "story_id";
        }else{
            $col = "comic_id";
        }
        if(Auth::check()){
            $user = Auth::user();
            $check = DB::table('histories')->where('user_id', $user->id)->where($col, $id)->first();
            if($check){
                $chaptersWatched =  explode(",", $check->chapterComics_id);
                if(!in_array($chap, $chaptersWatched)){
                    $chaptersWatched[] = $chap;
                    DB::table('histories')->where('user_id', $user->id)->where($col, $id)->update([
                        'chapterComics_id' => implode(",", $chaptersWatched),
                        'updated_at' => now()
                    ]);
                }else{
                    $key = array_search($chap, $chaptersWatched);
                    unset($chaptersWatched[$key]);
                    array_push($chaptersWatched, $chap);
                    DB::table('histories')->where('user_id', $user->id)->where($col, $id)->update([
                        'chapterComics_id' => implode(",", $chaptersWatched),
                        'updated_at' => now()
                    ]);
                }
            }else{
                DB::table('histories')->insert([
                    $col => $id,
                    'user_id' => $user->id,
                    'chapterComics_id' => $chap,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }

    public function removeHistory(Request $request){
        $id = $request->id;
        if(Auth::check()){
            $user = Auth::user();
            History::where('comic_id', $id)->where('user_id', $user->id)->delete();
        }
        return response()->json([
            'message' => 'Xóa lịch sử thành công.'
        ], 200);
    }
}
