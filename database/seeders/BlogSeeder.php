<?php

namespace Database\Seeders;

use Botble\ACL\Models\User;
use Botble\Base\Models\MetaBox as MetaBoxModel;
use Botble\Base\Supports\BaseSeeder;
use Botble\Blog\Models\Category;
use Botble\Blog\Models\CategoryTranslation;
use Botble\Blog\Models\Post;
use Botble\Blog\Models\PostTranslation;
use Botble\Blog\Models\Tag;
use Botble\Blog\Models\TagTranslation;
use Botble\Language\Models\LanguageMeta;
use Botble\Slug\Models\Slug;
use Faker\Factory;
use Html;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Language;
use RvMedia;
use SlugHelper;

class BlogSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->uploadFiles('news');

        Post::where('id', '>', 8)->delete();
        Category::where('id', '>', 8)->delete();
        Tag::where('id', '>', 3)->delete();
        PostTranslation::truncate();
        CategoryTranslation::truncate();
        TagTranslation::truncate();
        Slug::where('reference_type', Post::class)->where('reference_id', '>', 8)->delete();
        Slug::where('reference_type', Tag::class)->where('reference_id', '>', 3)->delete();
        Slug::where('reference_type', Category::class)->where('reference_id', '>', 8)->delete();
        MetaBoxModel::where('reference_type', Post::class)->where('reference_id', '>', 8)->delete();
        MetaBoxModel::where('reference_type', Tag::class)->where('reference_id', '>', 3)->delete();
        MetaBoxModel::where('reference_type', Category::class)->where('reference_id', '>', 8)->delete();
        LanguageMeta::where('reference_type', Post::class)->where('reference_id', '>', 8)->delete();
        LanguageMeta::where('reference_type', Tag::class)->where('reference_id', '>', 3)->delete();
        LanguageMeta::where('reference_type', Category::class)->where('reference_id', '>', 8)->delete();

        $faker = Factory::create();

        $posts = [
            [
                'name' => 'The Top 2020 Handbag Trends to Know',
            ],
            [
                'name' => 'Top Search Engine Optimization Strategies!',
            ],
            [
                'name' => 'Which Company Would You Choose?',
            ],
            [
                'name' => 'Used Car Dealer Sales Tricks Exposed',
            ],
            [
                'name' => '20 Ways To Sell Your Product Faster',
            ],
            [
                'name' => 'The Secrets Of Rich And Famous Writers',
            ],
            [
                'name' => 'Imagine Losing 20 Pounds In 14 Days!',
            ],
            [
                'name' => 'Are You Still Using That Slow, Old Typewriter?',
            ],
            [
                'name' => 'A Skin Cream That???s Proven To Work',
            ],
            [
                'name' => '10 Reasons To Start Your Own, Profitable Website!',
            ],
            [
                'name' => 'Simple Ways To Reduce Your Unwanted Wrinkles!',
            ],
            [
                'name' => 'Apple iMac with Retina 5K display review',
            ],
            [
                'name' => '10,000 Web Site Visitors In One Month:Guaranteed',
            ],
            [
                'name' => 'Unlock The Secrets Of Selling High Ticket Items',
            ],
            [
                'name' => '4 Expert Tips On How To Choose The Right Men???s Wallet',
            ],
            [
                'name' => 'Sexy Clutches: How to Buy & Wear a Designer Clutch Bag',
            ],
        ];

        $inserted = [];

        foreach ($posts as $index => $item) {
            $item['content'] =
                ($index % 3 == 0 ? Html::tag(
                    'p',
                    '[youtube-video]https://www.youtube.com/watch?v=SlPhMPnQ58k[/youtube-video]'
                ) : '') .
                Html::tag('p', $faker->realText(1000)) .
                Html::tag(
                    'p',
                    Html::image(RvMedia::getImageUrl('news/' . $faker->numberBetween(1, 5) . '.jpg'))
                        ->toHtml(),
                    ['class' => 'text-center']
                ) .
                Html::tag('p', $faker->realText(500)) .
                Html::tag(
                    'p',
                    Html::image(RvMedia::getImageUrl('news/' . $faker->numberBetween(6, 10) . '.jpg'))
                        ->toHtml(),
                    ['class' => 'text-center']
                ) .
                Html::tag('p', $faker->realText(1000)) .
                Html::tag(
                    'p',
                    Html::image(RvMedia::getImageUrl('news/' . $faker->numberBetween(11, 14) . '.jpg'))
                        ->toHtml(),
                    ['class' => 'text-center']
                ) .
                Html::tag('p', $faker->realText(1000));
            $item['author_id'] = 1;
            $item['author_type'] = User::class;
            $item['views'] = $faker->numberBetween(100, 2500);
            $item['is_featured'] = $index < 9;
            $item['image'] = 'news/' . ($index + 1) . '.jpg';
            $item['description'] = $faker->text();
            $item['content'] = str_replace(url(''), '', $item['content']);

            $post = Post::create($item);

            $post->categories()->sync([Arr::random([1, 2, 4, 6])]);

            $post->tags()->sync([1, 2, 3]);

            Slug::create([
                'reference_type' => Post::class,
                'reference_id' => $post->id,
                'key' => Str::slug($post->name),
                'prefix' => SlugHelper::getPrefix(Post::class),
            ]);

            $inserted[] = $post;
        }

        Post::where('id', '<', 8)->update(['created_at' => now(), 'updated_at' => now()]);

        $translations = [
            [
                'name' => 'Xu h?????ng t??i x??ch h??ng ?????u n??m 2020 c???n bi???t',
            ],
            [
                'name' => 'C??c Chi???n l?????c T???i ??u h??a C??ng c??? T??m ki???m H??ng ?????u!',
            ],
            [
                'name' => 'B???n s??? ch???n c??ng ty n??o?',
            ],
            [
                'name' => 'L??? ra c??c th??? ??o???n b??n h??ng c???a ?????i l?? ?? t?? ???? qua s??? d???ng',
            ],
            [
                'name' => '20 C??ch B??n S???n ph???m Nhanh h??n',
            ],
            [
                'name' => 'B?? m???t c???a nh???ng nh?? v??n gi??u c?? v?? n???i ti???ng',
            ],
            [
                'name' => 'H??y t?????ng t?????ng b???n gi???m 20 b???ng Anh trong 14 ng??y!',
            ],
            [
                'name' => 'B???n v???n ??ang s??? d???ng m??y ????nh ch??? c??, ch???m ?????',
            ],
            [
                'name' => 'M???t lo???i kem d?????ng da ???? ???????c ch???ng minh hi???u qu???',
            ],
            [
                'name' => '10 L?? do ????? B???t ?????u Trang web C?? L???i nhu???n c???a Ri??ng B???n!',
            ],
            [
                'name' => 'Nh???ng c??ch ????n gi???n ????? gi???m n???p nh??n kh??ng mong mu???n c???a b???n!',
            ],
            [
                'name' => '????nh gi?? Apple iMac v???i m??n h??nh Retina 5K',
            ],
            [
                'name' => '10.000 Kh??ch truy c???p Trang Web Trong M???t Th??ng: ???????c ?????m b???o',
            ],
            [
                'name' => 'M??? kh??a B?? m???t B??n ???????c v?? Cao',
            ],
            [
                'name' => '4 L???i khuy??n c???a Chuy??n gia v??? C??ch Ch???n V?? Nam Ph?? h???p',
            ],
            [
                'name' => 'Sexy Clutches: C??ch Mua & ??eo T??i Clutch Thi???t k???',
            ],
        ];

        foreach ($translations as $index => $item) {
            $item['lang_code'] = 'vi';
            $item['posts_id'] = $index + 9;
            $item['description'] = $inserted[$index]->description;
            $item['content'] = $inserted[$index]->content;

            PostTranslation::insert($item);
        }

        $posts = LanguageMeta::where('reference_type', Post::class)
            ->where('lang_meta_code', '!=', Language::getDefaultLocaleCode())
            ->get();

        foreach ($posts as $item) {
            $post = Post::find($item->reference_id);

            $originalId = LanguageMeta::where('lang_meta_origin', $item->lang_meta_origin)
                ->where('lang_meta_code', Language::getDefaultLocaleCode())
                ->value('reference_id');

            PostTranslation::insert([
                'posts_id' => $originalId,
                'lang_code' => $item->lang_meta_code,
                'name' => $post->name,
                'description' => $post->description,
                'content' => $post->content,
            ]);

            $post->delete();
        }

        LanguageMeta::where('reference_type', Post::class)->delete();
    }
}
