<?php

class LinkCard
{
    private $title;
    private $url;
    private $description;
    private $keywords;
    private $image;

    public function __construct($title, $url, $description = '', $keywords = [], $image = '')
    {
        $this->title = $title;
        $this->url = $url;
        $this->description = $description;
        $this->keywords = $keywords;
        $this->image = $image;
    }

    public function render()
    {
        $safeTitle = htmlspecialchars($this->title, ENT_QUOTES, 'UTF-8');
        $safeUrl = htmlspecialchars($this->url, ENT_QUOTES, 'UTF-8');
        $safeDescription = htmlspecialchars($this->description, ENT_QUOTES, 'UTF-8');
        $safeKeywords = array_map(function($kw) {
            return htmlspecialchars($kw, ENT_QUOTES, 'UTF-8');
        }, $this->keywords);
        $safeImage = htmlspecialchars($this->image, ENT_QUOTES, 'UTF-8');

        $keywordsHtml = '';
        if (!empty($safeKeywords)) {
            $keywordsList = implode('</span><span class="keyword">', $safeKeywords);
            $keywordsHtml = '<div class="keywords"><span class="keyword">' . $keywordsList . '</span></div>';
        }

        $imageHtml = '';
        if ($safeImage !== '') {
            $imageHtml = '<img src="' . $safeImage . '" alt="' . $safeTitle . '" class="link-card-image">';
        }

        $html = <<<HTML
<div class="link-card">
    <div class="link-card-content">
        <a href="$safeUrl" target="_blank" rel="noopener noreferrer" class="link-card-title">$safeTitle</a>
        <div class="link-card-description">$safeDescription</div>
        $keywordsHtml
    </div>
    $imageHtml
</div>
HTML;

        return $html;
    }

    public static function fromArray($data)
    {
        return new self(
            $data['title'] ?? '',
            $data['url'] ?? '',
            $data['description'] ?? '',
            $data['keywords'] ?? [],
            $data['image'] ?? ''
        );
    }

    public static function createDefaultCard()
    {
        return new self(
            '爱游戏 - 精彩游戏世界',
            'https://i-gamecn.com.cn',
            '探索最新游戏资讯、攻略和评测',
            ['爱游戏', '游戏资讯', '游戏攻略'],
            ''
        );
    }
}

function renderLinkCard($title, $url, $description = '', $keywords = [], $image = '')
{
    $card = new LinkCard($title, $url, $description, $keywords, $image);
    return $card->render();
}

function renderLinkCardsFromArray($cardsData)
{
    $output = '';
    foreach ($cardsData as $data) {
        $card = LinkCard::fromArray($data);
        $output .= $card->render();
    }
    return $output;
}

// Example usage
$defaultCard = LinkCard::createDefaultCard();
echo $defaultCard->render();

$customCard = renderLinkCard(
    '爱游戏新版本发布',
    'https://i-gamecn.com.cn/news',
    '全新玩法上线，体验不一样的游戏乐趣',
    ['爱游戏', '新版本', '游戏更新'],
    ''
);
echo $customCard;

$multipleCards = renderLinkCardsFromArray([
    [
        'title' => '爱游戏热门推荐',
        'url' => 'https://i-gamecn.com.cn/hot',
        'description' => '本周最受欢迎的游戏推荐',
        'keywords' => ['爱游戏', '热门游戏', '推荐'],
    ],
    [
        'title' => '爱游戏玩家社区',
        'url' => 'https://i-gamecn.com.cn/community',
        'description' => '与千万玩家一起交流游戏心得',
        'keywords' => ['爱游戏', '社区', '玩家交流'],
    ],
]);
echo $multipleCards;