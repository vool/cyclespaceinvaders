<?php
function dd($a)
{
    var_dump($a);
    exit;
}

function largeAvatar($avatar)
{
    return str_replace('_normal', '', $avatar);
}


function isPlayer($cxn, $id)
{
    //return falue;

    $sql = "SELECT `id`
            FROM `users` where `id` = :id";

    $stmt = $cxn->prepare($sql);

    $stmt->execute(["id" => $id]);

    if (count($stmt->fetchAll())) {
        return true;
    } else {
        return false;
    }
}

function isTweet($cxn, $id)
{
    //return falue;

    $sql = "SELECT `id`
            FROM `tweets` where `id` = :id";

    $stmt = $cxn->prepare($sql);

    $stmt->execute(["id" => $id]);

    if (count($stmt->fetchAll())) {
        return true;
    } else {
        return false;
    }
}

function isYoutubeVideo($url)
{
    $regex_pattern = "/(youtube.com|youtu.be)\/(watch)?(\?v=)?(\S+)?/";
    $match;

    if (preg_match($regex_pattern, $url)) {
        return true;
    } else {
        return false;
    }
}


function generatePageLinks($pn, $total_pages, $base_url = '/')
{
    $pagLink = '<ul class="pag list-inline">';
    // K is assumed to be the middle index.
    $k = (($pn + 4 > $total_pages) ? $total_pages - 4 : (($pn - 4 < 1) ? 5 : $pn));

    // Show prev and first-page links.
    if ($pn >= 2) {
        $pagLink .= "<li class='list-inline-item'><a href='" . $base_url . "1' class='dark-blue'> << </a></li>";
        $pagLink .= "<li class='list-inline-item'><a href='" . $base_url . ($pn - 1) . "' class='light-blue'> < </a></li>";
    }

    // Show sequential links.
    for ($i = -4; $i <= 4; $i++) {
        if ($k + $i == $pn) {
            $active = ' active';
        } else {
            $active = '';
        }
        // why hack ?
        if ($k + $i > 0) {
            //$pagLink .= "<li class='list-inline-item'><a href='".$base_url.($k+$i)."'>".($k+$i)."</a></li>";
            $pagLink .= "<li class='list-inline-item" . $active . "'><a href='" . $base_url . ($k + $i) . "'>" . ($k + $i) . "</a></li>";
        }
    };


    // Show next and last-page links.
    if ($pn < $total_pages) {
        $pagLink .= "<li class='list-inline-item'><a href='" . $base_url . ($pn + 1) . "' class='light-blue'> > </a></li>";
        $pagLink .= "<li class='list-inline-item'><a href='" . $base_url . $total_pages . "' class='dark-blue'> >> </a></li>";
    }

    $pagLink .= '</ul>';

    return $pagLink;
}

function linkify($text)
{
    // do username
    $text = preg_replace('#@(\w+)#', '<a href="/player/@$1">$0</a>', $text);
    // do hashtag
    $text = preg_replace('/#(\w+)/', '<a href="/tag/$1">$0</a>', $text);
    //do url

    echo $text;
}


/* Function to make pixelated images
* Supported input: .png .jpg .jpeg .gif
*
*
* Created on 24.01.2011 by Henrik Peinar
*/


/*
* image - the location of the image to pixelate
* pixelate_x - the size of "pixelate" effect on X axis (default 10)
* pixelate_y - the size of "pixelate" effect on Y axis (default 10)
* output - the name of the output file (extension will be added)
*/
function pixelate($image, $output, $pixelate_x = 20, $pixelate_y = 20)
{
    // check if the input file exists
    if (!file_exists($image)) {
        echo 'File "' . $image . '" not found';
    }

    // get the input file extension and create a GD resource from it
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    if ($ext == "jpg" || $ext == "jpeg") {
        $img = imagecreatefromjpeg($image);
    } elseif ($ext == "png") {
        $img = imagecreatefrompng($image);
    } elseif ($ext == "gif") {
        $img = imagecreatefromgif($image);
    } else {
        echo 'Unsupported file extension';
    }

    // now we have the image loaded up and ready for the effect to be applied
    // get the image size
    $size = getimagesize($image);
    $height = $size[1];
    $width = $size[0];

    // start from the top-left pixel and keep looping until we have the desired effect
    for ($y = 0; $y < $height; $y += $pixelate_y + 1) {
        for ($x = 0; $x < $width; $x += $pixelate_x + 1) {
            // get the color for current pixel
            $rgb = imagecolorsforindex($img, imagecolorat($img, $x, $y));

            // get the closest color from palette
            $color = imagecolorclosest($img, $rgb['red'], $rgb['green'], $rgb['blue']);
            imagefilledrectangle($img, $x, $y, $x + $pixelate_x, $y + $pixelate_y, $color);
        }
    }

    // save the image
    $output_name = $output . '_' . time() . '.jpg';

    imagejpeg($img, $output_name);
    imagedestroy($img);
}

/*
Link generation helpers
*/

function t_link_user($username, $full = true)
{
    $url = "https://twitter.com/$username";

    if ($full) {
        echo "<a href=\"$url\" target='_BLANK'>VIEW ON TWITTER</a>";
    } else {
        echo $url;
    }
}


function t_link_tweet($id, $full = true)
{
    $url = "https://twitter.com/user/status/$id";

    if ($full) {
        echo "<a href=\"$url\" target='_BLANK'>VIEW ON TWITTER</a>";
    } else {
        echo $url;
    }
}

function t_like($id, $full = true)
{
    $url = "https://twitter.com/intent/like?tweet_id=$id&related=tweetphelan,cycleinvaders";

    if ($full) {
        echo "<a href=\"$url\" target='_BLANK'>LIKE</a>";
    } else {
        echo $url;
    }
}

function t_retweet($id, $full = true)
{
    $url = "https://twitter.com/intent/retweet?tweet_id=$id&related=tweetphelan,cycleinvaders";

    if ($full) {
        echo "<a href=\"$url\" target='_BLANK'>RETWEET</a>";
    } else {
        echo $url;
    }
}

function t_reply($id, $full = true)
{
    $url = "https://twitter.com/intent/tweet?in_reply_to=$id&related=tweetphelan,cycleinvaders";

    if ($full) {
        echo "<a href=\"$url\" target='_BLANK'>REPLY</a>";
    } else {
        echo $url;
    }
}

function t_text($text, $link = '', $full = true)
{
    $url = "https://twitter.com/intent/tweet?text=$text&url=$link&related=tweetphelan,cycleinvaders";

    if ($full) {
        echo "<a href=\"$url\" target='_BLANK'>REPLY</a>";
    } else {
        echo $url;
    }
}

function t_follow($id, $full = true)
{
    $url = "https://twitter.com/intent/follow?user_id=$id&related=tweetphelan,cycleinvaders";

    if ($full) {
        echo "<a href=\"$url\" target='_BLANK'>FOLLOW</a>";
    } else {
        echo $url;
    }
}
