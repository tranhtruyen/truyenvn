<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Category;
use App\Models\Comic;
use Spatie\Sitemap\SitemapIndex;
use Illuminate\Support\Facades\File;
use App\Models\Author;
use App\Models\Chapter;
use Illuminate\Support\Str;


class ApiController extends Controller
{
    public function crawlOTruyen()
    {
        set_time_limit(10000);
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://otruyenapi.com/v1/api/danh-sach');
        $data = json_decode($response->getBody()->getContents(), true);
        $data = $data['data']['items'];
        $array = [];
        foreach($data as $item){
            $array[] = [
                'slug' => $item['slug'],
            ];
        }
        $comics = [];
        foreach($array as $item){
            $checkComic = DB::table('comics')->where('slug', $item['slug'])->first();
            $response = $client->request('GET', env('LINK_OTRUYEN_API').$item['slug']);
            $data = json_decode($response->getBody()->getContents(), true);
            $data = $data['data'];
            if($checkComic){
                $idComic = $checkComic->id;
                $comics[] = [
                    'idComic' => $idComic,
                    'chapters' => $data['item']['chapters'][0]['server_data'],
                ];
            }else{
                $idComic = DB::table('comics')->insertGetId([
                    'name' => $data['item']['name'],
                    'slug' => $data['item']['slug'],
                    'content' => $data['item']['content'],
                    'status' => $data['item']['status'],
                    'thumbnail' => $data['seoOnPage']['seoSchema']['image'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                foreach($data['item']['category'] as $category){
                    $checkCategory = DB::table('categories')->where('slug', $category['slug'])->first();
                    if(!$checkCategory){
                        $idCategory = DB::table('categories')->insertGetId([
                            'name' => $category['name'],
                            'slug' => $category['slug'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }else{
                        $idCategory = $checkCategory->id;
                    }
                    DB::table('comic_categories')->insert([
                        'comic_id' => $idComic,
                        'category_id' => $idCategory,
                    ]);
                }
                $comics[] = [
                    'idComic' => $idComic,
                    'chapters' => $data['item']['chapters'][0]['server_data'],
                ];
            }
        }

        $success = [];
        foreach($comics as $comic){
            $reversedChapters = array_reverse($comic['chapters']);
            $checkChapter = DB::table('chapters')
            ->where('name', $reversedChapters[0]['chapter_name'])
            ->where('server', 'VIP #1')
            ->where('comic_id', $comic['idComic'])->first();

            if($checkChapter){
                continue;
            }

            foreach($reversedChapters as $chapter){
                $response = $client->request('GET', $chapter['chapter_api_data']);
                $data = json_decode($response->getBody()->getContents(), true);
                $data = $data['data'];
                $idChapter = DB::table('chapters')->insertGetId([
                    'name' => $data['item']['chapter_name'],
                    'slug' => 'chuong-'.$data['item']['chapter_name'],
                    'comic_id' => $comic['idComic'],
                    'server' => 'VIP #1',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $path = $data['domain_cdn'] . '/' . $data['item']['chapter_path'];
                foreach($data['item']['chapter_image'] as $image){
                    DB::table('chapterimgs')->insert([
                        'chapter_id' => $idChapter,
                        'link' => $path . '/' . $image['image_file'],
                        'page' => $image['image_page'],
                    ]);
                }
            }
            DB::table('comics')->where('id', $comic['idComic'])->update([
                'updated_at' => now(),
            ]);
            $success[] = $comic['idComic'];
        }
        return response()->json(['message' => 'success', 'data' => $success], 200);
    }

    public function generateSitemap ()
    {
        File::deleteDirectory(public_path('sitemaps/category'));
        File::deleteDirectory(public_path('sitemaps/comic'));

        File::makeDirectory(public_path('sitemaps/category'), 0755, true, true);
        File::makeDirectory(public_path('sitemaps/comic'), 0755, true, true);

        $sitemapIndex = SitemapIndex::create();

        Category::query()->orderBy('id')->chunk(500, function ($categories) use ($sitemapIndex) {
            $sitemap = Sitemap::create();

            foreach ($categories as $category) {
                $sitemap->add(Url::create('/the-loai/' . $category->slug));
            }

            $sitemapPath = 'sitemaps/category/category_sitemap_' . $categories->first()->id . '.xml';
            $sitemap->writeToFile(public_path($sitemapPath));

            $sitemapIndex->add('/' . $sitemapPath);
        });

        Comic::query()->orderBy('id')->chunk(500, function ($comics) use ($sitemapIndex) {
            $sitemap = Sitemap::create();

            foreach ($comics as $comic) {
                $sitemap->add(Url::create('/' . $comic->slug));
            }

            $sitemapPath = 'sitemaps/comic/comic_sitemap_' . $comics->first()->id . '.xml';
            $sitemap->writeToFile(public_path($sitemapPath));

            $sitemapIndex->add('/' . $sitemapPath);
        });

        $sitemapIndex->writeToFile(public_path('sitemap.xml'));
    }

}
