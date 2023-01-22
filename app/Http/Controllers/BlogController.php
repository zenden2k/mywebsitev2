<?php

namespace App\Http\Controllers;

use App\Helpers\LocaleHelper;
use App\Helpers\StringHelper;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\SidebarBlock;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;

class BlogController extends SiteController
{
    private string $currentCategoryAlias = '';
    private string $selectedMonth = '';

    #[ArrayShape([
        '__blog_categories' => "mixed",
        '__categories_post_count' => "array",
        'archives' => "array",
        'currentTab' => "string",
        'leftPageBlocks' => "array"
    ])]
    private function getCommonViewData(): array
    {
        $lang = LocaleHelper::getCurrentLanguage();

        $db_query = \DB::table('blog_posts')->selectRaw('COUNT(id) as cnt, category_id');
        if ($lang === 'en') {
            $db_query->where('content_en', '!=', '');
        }
        $db_query->where('status', '=', 1);
        $categories_post_count = $db_query->groupBy('category_id')->get()->all();
        $categories_post_count = \Arr::pluck($categories_post_count, 'cnt', 'category_id');

        $categories = BlogCategory::where('active', '=', '1')->orderBy('title_' . $lang)->get();

        $archives = [];
        $lang_ins = $lang === 'en' ? 'and `content_en`!="" ' : '';
        $months = \DB::select('SELECT COUNT(id) as cnt,CONCAT(YEAR(created_at),"-",MONTH(created_at)) as mon
                    from blog_posts where `status`=1 ' . $lang_ins . ' group by mon order by mon');

        setlocale(LC_ALL, 'ru_RU.utf-8');

        if (!empty($months)) {
            foreach ($months as $month_arr) {
                $month_str = $month_arr->mon;
                $time = strtotime($month_str . '-01');
                list($year,$month) = explode('-', $month_str);
                $archives[] = array(
                    'title' => /*date("F Y",$time)*/strftime('%B %Y', $time),
                    'month' => "$year/$month",
                    'url' => $this->urlPrefix . "/blog/$year/$month",
                    'count' => $month_arr->cnt
                );
            }
        } else {
            $time = strtotime(date("01.m.y"));
            for ($i = 0; $i < 12; $i++) {
                $archives[] = array(
                    'title' => date("F Y", $time),
                    'url' => $this->urlPrefix . '/blog/' . date("Y/m", $time),
                    'month' =>  date("Y/m", $time),
                    'count' => 0
                );
                $time = strtotime("-1 month", $time);
            }
        }

        $res = setlocale(LC_ALL, 'ru_RU.utf-8');

        $leftPageBlocks = SidebarBlock::whereIn('alias', ['myprograms','links'])->get()->all();

        return [
            '__blog_categories' => $categories,
            '__categories_post_count' => $categories_post_count,
            'archives' => array_reverse($archives),
            'currentTab' => 'blog',
            'leftPageBlocks' => $leftPageBlocks
        ];
    }
    public function index(Request $request)
    {
        $category = $request->route('category');

        $postsOrm = BlogPost::with('category');
        $postsOrm->where('status', '=', '1');

        $this->postFilter($request, $postsOrm);
        $title = '';
        if ($this->currentCategoryAlias) {
            $category = BlogCategory::where('alias', '=', $this->currentCategoryAlias)->first();
            if ($category) {
                $title = __("Blog posts in the category") . ' ' . $category->title;
            } else {
                abort(404);
            }
        }
        if ($this->selectedMonth) {
            $title = __("Blog posts in") . ' ' . $this->selectedMonth;
        }
        $data = [
            'current_category_alias' => $this->currentCategoryAlias,
            'title' => $title,
            'selected_month' => $this->selectedMonth,
            'posts' => $postsOrm->orderBy('id', 'desc')->paginate(6)
        ];
        $data = array_merge($this->getCommonViewData(), $data);
        return view('blog.post_list', $data);
    }

    private function postFilter(Request $req, Builder $obj)
    {
        $lang = LocaleHelper::getCurrentLanguage();
        $category = $req->route('category');

        $year = $req->route('year');
        $month = $req->route('month');

        if (!empty($category)) {
            $obj->whereHas('category', function ($q) use ($category) {
                $q->where('alias', '=', $category);
            });
            $this->currentCategoryAlias = $category;
        }

        if (!empty($year) && !empty($month)) {
            $obj->where(\DB::raw('MONTH(created_at)'), '=', $month)
                ->where(\DB::raw('YEAR(created_at)'), '=', $year);
            $this->selectedMonth = $year . '/' . $month;
        }
        $obj->where('status', '=', '1');
        $obj->whereNotNull('category_id');
        if ($lang === 'en') {
            $obj->where('content_en', '!=', '');
        }
    }

    public function show(Request $request)
    {
        $alias =  $request->route('alias');
        $postId = substr($alias, strrpos($alias, '-') + 1);
        $post = BlogPost::where('id', '=', $postId)->where('status', '=', 1)->firstOrFail();
        if ($post->alias . '-' . $postId !== $alias) {
            abort(404);
        }

        if ($request->isMethod('POST') && $post->enable_comments) {
            if ($request->post('name') != '' || $request->post('checkB') !== 'checkA') {
                return abort(400);
            }
            $validated = $request->validate([
                'eman' => 'required|max:255',
                'email' => 'email',
                'text' => 'required|max:2000'
            ]);

            $validated['name'] = $validated['eman'];

            $comment = new BlogComment($validated);
            $comment->blog_post_id = $postId;
            $comment->ip = sprintf('%u', ip2long($request->ip()));
            $comment->save();
        }

        $comments = $post->comments()->orderBy('createdAt', 'desc')->paginate(10);
        $data = [
            'post' => $post,
            'comments' => $comments,
            'title' => $post->title
        ];
        $data = array_merge($this->getCommonViewData(), $data);
        return view('blog.post_details', $data);
    }

    public function rss()
    {
        $orm = BlogPost::with('category')->where('status', '=', '1')
            ->where('alias', '!=', 'about');

        $lang = LocaleHelper::getCurrentLanguage();
        if ($lang === 'en') {
            $orm->andWhere('content_en', '!=', '');
        }
        $posts = $orm->orderBy('created_at', 'DESC')->get();
        $info = [
            'title' => 'Sergey Svistunov\'s blog',
            'pubDate' => date("D, d M Y H:i:s T"),
            'description' => 'Sergey Svistunov\'s blog',
            'link' => $lang === 'en' ? url('/blog/') : url('/ru/blog/'),
            'language' => $lang === 'en' ? 'en-en' : 'ru-ru',
            'ttl' => '7200',
        ];
        $items = [];
        foreach ($posts as $post) {
            $title = $post->title;
            $descr = $post->content;
            // item description must not contain relative urls
            $descr = str_replace('src="/', 'src="' . url('/') . '/', $descr);
            $descr = trim(html_entity_decode($descr, null, 'UTF-8'));
            $descr  = StringHelper::autoP(StringHelper::widont(Str::words(strip_tags($descr), 50)));
            $url = $post->getUrl(true);
            $items[] = array(
                'title' => $title,
                'link' => $url,
                'description' => $descr,
                'category' => $post->category?->title,
                'pubdate' => date('r', strtotime($post->created_at)),
                'guid' => $url
            );
        }

        return response(view('blog.rss_feed', [
            'posts' => $items,
            'info' => $info
        ]), 200, [
            'Content-Type' => 'text/xml; charset=utf-8'
        ]);
    }
}
