<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

class ScrapeController extends Controller
{
    private $results = [];

    public function index($location)
    {
        $url = 'https://lichcupdien.org/lich-cup-dien-' . $location . '-dong-thap';
        $html = file_get_contents($url);

        // Kiểm tra xem có lỗi khi tải trang không
        if ($html === false) {
            return response()->json(['error' => 'Unable to fetch the page.']);
        }

        // Sử dụng DomCrawler để phân tích cú pháp HTML
        $crawler = new Crawler($html);

        // Lọc tất cả các phần tử có class
        $crawler->filter('.lcd_detail_wrapper')->each(function (Crawler $element) {
            $titleArray = $element->filter('.title_item_lcd_wrapper')->extract(['_text']);
            $contentArray = $element->filter('.content_item_content_lcd_wrapper')->extract(['_text']);
            $this->results[] =  array_combine($titleArray, $contentArray);
        });

        $data = $this->results;
        return compact('data');
    }
}
