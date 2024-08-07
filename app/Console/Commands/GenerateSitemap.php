<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url as SitemapUrl; // Alias for the Url class
use App\Models\Product;
use App\Models\Music;

class GenerateSitemap extends Command
{
    protected $signature = 'generate:sitemap';

    protected $description = 'Generate sitemap for products and music';

    public function handle()
    {
        $sitemap = Sitemap::create();

        Product::all()->each(function (Product $product) use ($sitemap) {
            $sitemap->add(SitemapUrl::create("/products/{$product->slug}"));
        });

        $latestAboutPost = Product::where('category_name', 'About')->orderBy('created_at', 'desc')->first();
        if ($latestAboutPost) {
            $sitemap->add(SitemapUrl::create("/about")->setLastModificationDate($latestAboutPost->updated_at));
        }

        Music::all()->each(function (Music $music) use ($sitemap) {
            $sitemap->add(SitemapUrl::create("/msingle/{$music->slug}"));
        });

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully.');
    }
}
