<?php

declare(strict_types=1);

namespace App\Services\Sitemap;

use App\Models\Product;
use App\Models\Category;
use App\Models\Page;
use App\Models\BlogPost;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapService
{
    public function generate(): string
    {
        $sitemap = Sitemap::create();
        
        // URL statici
        $sitemap->add(Url::create(url('/'))->setPriority(1.0));
        $sitemap->add(Url::create(route('public.shop.index'))->setPriority(0.9));
        $sitemap->add(Url::create(route('public.pages.show', 'chi-siamo'))->setPriority(0.6));
        $sitemap->add(Url::create(route('public.pages.show', 'supporto'))->setPriority(0.5));
        $sitemap->add(Url::create(route('public.pages.show', 'contatti'))->setPriority(0.5));
        
        // Prodotti pubblicati
        Product::published()
            ->with('media')
            ->chunk(100, function ($products) use ($sitemap) {
                foreach ($products as $product) {
                    $url = Url::create(route('public.shop.product', $product->slug))
                        ->setLastModificationDate($product->updated_at)
                        ->setPriority(0.8);
                        
                    if ($product->getFirstMediaUrl('gallery')) {
                        $url->addImage($product->getFirstMediaUrl('gallery'), $product->name);
                    }
                    
                    $sitemap->add($url);
                }
            });
            
        // Categorie attive
        Category::active()
            ->with('media')
            ->chunk(50, function ($categories) use ($sitemap) {
                foreach ($categories as $category) {
                    $url = Url::create(route('public.shop.category', $category->slug))
                        ->setLastModificationDate($category->updated_at)
                        ->setPriority(0.7);
                        
                    if ($category->getFirstMediaUrl('cover')) {
                        $url->addImage($category->getFirstMediaUrl('cover'), $category->name);
                    }
                    
                    $sitemap->add($url);
                }
            });
            
        // Pagine CMS pubblicate
        Page::published()
            ->chunk(50, function ($pages) use ($sitemap) {
                foreach ($pages as $page) {
                    $sitemap->add(
                        Url::create(route('public.pages.show', $page->slug))
                            ->setLastModificationDate($page->updated_at)
                            ->setPriority(0.6)
                    );
                }
            });
            
        // Blog posts pubblicati
        BlogPost::published()
            ->with('media')
            ->chunk(50, function ($posts) use ($sitemap) {
                foreach ($posts as $post) {
                    $url = Url::create(route('public.blog.show', $post->slug))
                        ->setLastModificationDate($post->updated_at)
                        ->setPriority(0.6);
                        
                    if ($post->getFirstMediaUrl('featured_image')) {
                        $url->addImage($post->getFirstMediaUrl('featured_image'), $post->title);
                    }
                    
                    $sitemap->add($url);
                }
            });
        
        $filePath = storage_path('app/sitemap.xml');
        $sitemap->writeToFile($filePath);
        
        // Invalida cache
        Cache::forget('skintemple_sitemap');
        
        return $filePath;
    }
    
    public function serve(): Response
    {
        return Cache::remember('skintemple_sitemap', 360, function () {
            $filePath = storage_path('app/sitemap.xml');
            
            // Se il file esiste e ha meno di 6 ore, servilo
            if (file_exists($filePath) && (time() - filemtime($filePath)) < 21600) {
                return response(file_get_contents($filePath), 200, [
                    'Content-Type' => 'application/xml; charset=UTF-8',
                    'Cache-Control' => 'public, max-age=21600', // 6 ore
                ]);
            }
            
            // Altrimenti rigenera
            $this->generate();
            
            return response(file_get_contents($filePath), 200, [
                'Content-Type' => 'application/xml; charset=UTF-8',
                'Cache-Control' => 'public, max-age=21600',
            ]);
        });
    }
}