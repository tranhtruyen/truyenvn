<?php

namespace App\Http\Controllers;

use Google\Cloud\Storage\StorageClient;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Exists;
use App\Models\Comic;

class ComicController extends Controller
{
    public function index()
    {
        $comics = Comic::withCount('chapters')->orderBy('created_at', 'DESC')->get();
        return view("pages/comic/index", compact('comics'));
    }

    public function create()
    {
        $categories = DB::table('categories')->get();
        $authors = DB::table('authors')->get();
        return view('pages.comic.create', compact('categories', 'authors'));
    }

    public function edit(Comic $comic){
        $categories = DB::table('categories')->get();
        $authors = DB::table('authors')->get();
        return view('pages.comic.edit', compact('comic', 'categories', 'authors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:comics,name',
            'origin_name' => 'nullable',
            'content' => 'nullable',
            'status' => 'nullable',
        ], [
            'name.required' => 'Tên truyện không được để trống',
            'name.unique' => 'Tên truyện đã tồn tại',
        ]);
        $data = $request->all();
        $slug = Str::slug($data['name'], '-');
        $data['slug'] = $slug;
        if($request->thumbnailOption == "file"){
            if($request->hasFile('thumbnail')){
                $image = $request->file('thumbnail');
                $fullPath = env('APP_URL'). '/storage/'. $image->storeAs('thumbnails', $image->getClientOriginalName());
                $data['thumbnail'] = $fullPath;
            }
        }else{
            if ($request->thumbnailUrl) {
                $data['thumbnail'] = $request->thumbnailUrl;
            }
        }
        $id = Comic::create([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'origin_name' => $data['origin_name'],
            'content' => $data['content'],
            'status' => $data['status'],
            'thumbnail' => $data['thumbnail'],
        ])->id;

        $categories = json_decode($request->categories, true);
        if(is_array($categories) && count($categories) > 0){
            foreach ($categories as $category) {
                DB::table('comic_categories')->insert([
                    'comic_id' => $id,
                    'category_id' => $category
                ]);
            }
        }
        $authors = json_decode($request->authors, true);
        if(is_array($authors) && count($authors) > 0){
            foreach ($authors as $author) {
                DB::table('author_comic')->insert([
                    'id_comic' => $id,
                    'id_author' => $author
                ]);
            }
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Thêm truyện thành công',
        ]);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $request->validate([
            'name' => 'required|unique:comics,name,' . $id . ',id',
            'slug' => 'required|unique:comics,slug,' . $id . ',id',
        ], [
            'name.required' => 'Tên truyện không được để trống',
            'name.unique' => 'Tên truyện đã tồn tại',
            'slug.required' => 'Slug không được để trống',
            'slug.unique' => 'Slug đã tồn tại',
        ]);
        $data = $request->all();
        Comic::where('id', $id)->update([
            'name' => $data['name'],
            'origin_name' => $data['origin_name'],
            'content' => $data['content'],
            'status' => $data['status'],
            'thumbnail' => $data['thumbnail'],
            'slug' => $data['slug'],
        ]);

        DB::table('comic_categories')->where('comic_id', $id)->delete();
        $categories = json_decode($request->categories, true);
        if(is_array($categories) && count($categories) > 0){
            foreach ($categories as $category) {
                DB::table('comic_categories')->insert([
                    'comic_id' => $id,
                    'category_id' => $category
                ]);
            }
        }
        DB::table('author_comic')->where('id_comic', $id)->delete();
        $authors = json_decode($request->authors, true);
        if(is_array($authors) && count($authors) > 0){
            foreach ($authors as $author) {
                DB::table('author_comic')->insert([
                    'id_comic' => $id,
                    'id_author' => $author
                ]);
            }
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Sửa truyện thành công',
        ]);
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        Comic::where('id', $id)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Xóa truyện thành công',
        ]);
    }


    // Chapter
    public function listChapter($slug)
    {
        $comic = DB::table('comics')->where('slug', $slug)->first();
        return view('admin/comic/chapter', compact('comic'));
    }

    public function showFormAddChapter($slug)
    {
        $comic = DB::table('comics')->where('slug', $slug)->first();
        return view('admin/comic/createChapter', compact('comic'));
    }

    public function addChapter(Request $request)
    {
        $request->validate([
            'chapter' => 'required',
        ], [
            'chapter.required' => 'Chương không được để trống.',
        ]);

        $check = DB::table('chapters')->where('comic_id', $request->comicId)
            ->where('server', $request->serverName)
            ->where('name', $request->chapter)->first();
        if($check != null) {
            return response()->json([
                'title' => 'Thất bại',
                'message' => 'Server đã tồn tại chương này!',
                'type' => 'error',
            ], 422);
        }

        $idChapter = DB::table('chapters')->insertGetId([
            'comic_id' => $request->comicId,
            'name' => $request->chapter,
            'server' => $request->serverName,
            'slug' => 'chuong-' .$request->chapter,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $contentArray = json_decode($request->contentArray, true);
        $page = 1;
        foreach ($contentArray as $image) {
            DB::table('chapterimgs')->insert([
                'chapter_id' => $idChapter,
                'page' => $page++,
                'link' => $image,
            ]);
        }

        return response()->json([
            'title' => 'Thành công',
            'message' => 'Thêm thành công!',
            'type' => 'success',
            'url' => redirect()->route('admin.listChapter', ['slug' => $request->slug])->getTargetUrl()
        ]);
    }

    public function deleteChapter(Request $request)
    {
        $idChapter = $request->dataInput['idChapter'];
        DB::table('chapters')->where('id', $idChapter)->delete();
        return response()->json([
            'title' => 'Thành công',
            'message' => 'Xóa thành công chương ' . $request->dataInput['chapter'],
            'type' => 'success',
            'url' => redirect()->route('admin.listChapter', ['slug' => $request->dataInput['slug']])->getTargetUrl()
        ]);
    }

    // Chapter Image
    public function listImgInChapter()
    {
        $slug = request()->slug;
        $comic = DB::table('comics')->where('slug', $slug)->first();
        $chapter = request()->chapter;
        $idChapter = request()->idChapter;
        $chapterImgs = DB::table('chapterimgs')->where('chapter_id', $idChapter)->orderBy('id')->get();
        return view('admin/comic/detailChapter', compact('slug', 'chapter', 'chapterImgs', 'comic', 'idChapter'));
    }
    public function deleteImgInChapter(Request $request)
    {
        $idImgChapter = $request->dataInput['idImgChapter'];
        DB::table('chapterimgs')->where('id', $idImgChapter)->delete();
        return response()->json([
            'title' => 'Thành công',
            'message' => 'Xóa thành công hình ảnh!',
            'type' => 'success',
        ]);
    }
    public function updateImgInChapter(Request $request)
    {
        $request->validate([
            'idChapter' => 'required',
            'value' => 'required',
        ], [
            'idChapter.required' => 'Chương không được để trống.',
            'value.required' => 'Hình ảnh không được để trống.'
        ]);
        $checkPage = DB::table('chapterimgs')->where('chapter_id', $request->idChapter)
        ->where('id', '!=', $request->idImg)
        ->where('page', $request->page)->first();
        if($checkPage != null) {
            return response()->json([
                'title' => 'Thất bại',
                'message' => 'Trang đã tồn tại!',
                'type' => 'error',
            ], 422);
        }

        DB::table('chapterimgs')->where('id', $request->idImg)->update([
            'page' => $request->page,
            'link' => $request->value,
        ]);

        return response()->json([
            'title' => 'Thành công',
            'message' => 'Sửa thành công!',
            'type' => 'success',
        ]);
    }

    public function storeImgInChapter(Request $request)
    {
        $request->validate([
            'idChapter' => 'required',
            'value' => 'required',
        ], [
            'idChapter.required' => 'Chương không được để trống.',
            'value.required' => 'Hình ảnh không được để trống.'
        ]);

        $checkPage = DB::table('chapterimgs')->where('chapter_id', $request->idChapter)->where('page', $request->page)->first();
        if($checkPage != null) {
            return response()->json([
                'title' => 'Thất bại',
                'message' => 'Trang đã tồn tại!',
                'type' => 'error',
            ], 422);
        }

        DB::table('chapterimgs')->insert([
            'chapter_id' => $request->idChapter,
            'page' => $request->page,
            'link' => $request->value,
        ]);

        return response()->json([
            'title' => 'Thành công',
            'message' => 'Thêm thành công!',
            'type' => 'success',
        ]);
    }
}
