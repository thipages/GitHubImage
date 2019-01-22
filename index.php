<?php
/*
 * 	GitHubImage
 *	Version 0.0.1
 * 	https://github.com/thipages/GitHubImage
 *
 * 	Licensed under the MIT license:
 * 	http://www.opensource.org/licenses/MIT
 *
 *	Create images from Github API
 */
class ImageRenderer {
    private $text;
    private $textColor;
    private $bgColor;
    /**
     * ImageRenderer constructor.
     * @param string $text
     * @param array $textColor
     * @param array $bgColor
     */
    public function __construct($text, $textColor,$bgColor) {
        $this->text=$text;
        $this->textColor=$textColor;
        $this->bgColor=$bgColor;
        $this->getImage();
    }
    private function getImage() {
        $font   = 4;
        header ("Content-type: image/png");
        $width  = ImageFontWidth($font) * strlen($this->text);
        $height = ImageFontHeight($font)+2;
        $im = @imagecreate ($width,$height);
        imagecolorallocate ($im, $this->bgColor[0], $this->bgColor[1], $this->bgColor[2]); //white background
        $text_color = imagecolorallocate ($im, $this->textColor[0], $this->textColor[1], $this->textColor[2]);//black text
        imagestring ($im, $font, 0, 0,  $this->text, $text_color);
        imagepng ($im);
        imagedestroy($im); // Free memory
    }
    public static function hex2RGB($hexColor) {
        list($r, $g, $b) = sscanf($hexColor, "#%02x%02x%02x");
        return [$r,$g,$b];
    }
}
function get_content_from_github($url) {
    $ch = curl_init();
    $ch_version = curl_version();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'curl/' . $ch_version['version'] );
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,1);
    $content = curl_exec($ch);
    curl_close($ch);
    return $content;
}
$textColor = isset($_GET["text_color"]) ? ImageRenderer::hex2RGB($_GET["text_color"]) : [0,0,0];
$bgColor = isset($_GET["bg_color"]) ? ImageRenderer::hex2RGB($_GET["bg_color"]) : [255,255,255];
//
$owner = isset($_GET["owner"]) ? $_GET["owner"] : false;
$repo = isset($_GET["repo"]) ? $_GET["repo"] : false;
$field = isset($_GET["field"]) ? $_GET["field"] : false;
if ($owner && $repo && $field) {
    $content = get_content_from_github("https://api.github.com/repos/$owner/$repo");
    $content = json_decode($content);
    if (property_exists ($content,$field)) {
        $text = $content->$field;
    } else {
        $text="error1";
    }
} else {
    $text="error2";
}
new ImageRenderer($text,$textColor,$bgColor);
