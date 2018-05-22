<?php

namespace App\Library;


class StrParse
{
    /***
     * 将其他类型转换为字符串
     * @param array $data
     * @param array $except
     * @return array
     */
    public static function strval(array $data, array $except = []): array
    {
        foreach ($data as $key => &$v) {
            if (array_key_exists($key, $except)) {
                $v = $except[$key]($v);
                continue;
            }
            if (is_array($v)) {
                $v = self::strval($v, $except);
            } elseif (is_object($v)) {
                $v = json_decode(json_encode($v), true);
                $v = (object)self::strval($v, $except);
            } elseif (is_bool($v)) {
                $v = (true === $v ? 'true' : 'false');
            } else {
                $v = strval($v);
            }

            unset($v);
        }

        return $data;
    }

    /**
     * 将表情符号替换成文本
     * @param $text
     * @param $replace [符號]
     * @return string
     */
    public static function emoji(string $text, string $replace = '[符號]'): string
    {
        $text = json_encode($text);
        preg_match_all("/(\\\\ud83c\\\\u[0-9a-f]{4})|(\\\\ud83d\\\u[0-9a-f]{4})|(\\\\u[0-9a-f]{4})/", $text, $matchs);
        if (!isset($matchs[0][0])) {
            return json_decode($text, true);
        }

        $emoji = $matchs[0];
        foreach ($emoji as $ec) {
            $hex = substr($ec, -4);
            $data = self::isemoji($hex);
            if (!empty($data)) {
                $text = str_replace($ec, $replace, $text);
            }
            if (strlen($ec) == 6) {
                if ($hex >= '2600' and $hex <= '27ff') {
                    $text = str_replace($ec, $replace, $text);
                }
            } else {
                if ($hex >= 'dc00' and $hex <= 'dfff') {
                    $text = str_replace($ec, $replace, $text);
                }
            }
        }

        return json_decode($text, true);
    }

    /**
     * 判断是否为表情
     * @param $hex
     * @return bool
     */
    public static function isemoji($hex) : bool
    {
        return ($hex == 0x0);
    }

    /**
     * @param $result
     * @return array
     */
    public static function stdToArray($result) : array
    {
        $array = [];
        foreach ($result as $key => $value) {
            if (is_object($value)) {
                switch (get_class($value)) {
                    case 'MongoDB\BSON\ObjectID'   :
                        $value = strval($value);
                        break;
                    case 'stdClass'                :
                        $value = static::{__FUNCTION__}($value);
                        break;
                    case 'MongoDB\BSON\Timestamp'  :
                        $time = strval($value);
                        $value = intval(substr($time, strpos($time, ':') + 1, -1));
                        break;
                    case 'MongoDB\BSON\UTCDateTime':
                        $value = strval($value);
                        break;
                    case 'MongoDB\BSON\Regex'      :
                        $value = strval($value);
                        break;
                    case 'MongoDB\BSON\Binary'     :
                        /**@var $value  \MongoDB\BSON\Binary;* */
                        $value = $value->getData();
                        break;
                }
            }

            $array[$key] = $value;
        }

        return $array;
    }

    /**
     * 符号转义
     * @param string 中文字符串
     * @return string 转换后的json格式查询字符串
     */
    public static function symbolEscape($str) : string
    {
        $str = trim(json_encode($str), '"');

        return str_replace('\\', '\\\\', $str);
    }

    /**
     * 将字符 \r\n \n \t 替换为真实的字符
     * @param $str
     * @return string
     */
    public static function specialStrDecode($str) : string
    {
        return str_replace(
            ['\r\n', '\n', '\t'],
            ["\r\n", "\n", "\t"],
            $str
        );
    }

    /**
     * 将字符 \r\n \n \t 替换为空字符窜
     * @param $str
     * @return string
     */
    public static function specialStrEncode($str) : string
    {
        return str_replace(
            ["\r\n", "\n", "\t", '&nbsp;'],
            ['', '', '', ' '],
            $str
        );
    }

    /**
     * ck文字消息匹配
     * @param String $msg
     * @return string
     */
    public static function replaceCKContentSendOnlineMsgContent(String $msg = '') : string
    {
        //把html p 标签换成 \n
        if (preg_match_all('/<p.*?>(.*?)<\/p>/i', $msg, $matches)) {
            unset($matches[0]);
            if (isset($matches[1])) {
                $matches = $matches[1];
            }

            //在替换表情
            foreach ($matches as &$item) {
                $item = preg_replace('/<img.*?(?:\/public\/symbol\/([a-z]{1,15}).png).*?\/?>/i', '[$1]', $item);
                unset($item);
            }

            return implode("\n", $matches);
        }

        return $msg;
    }

    /**
     * 替换vue文字消息匹配
     * @param String $msg
     * @return string
     */
    public static function replaceVueContentSendOnlineMsgContent(String $msg = '') : string
    {
        //替换img表情
        $msg = preg_replace('/<img.*?(?:\/public\/symbol\/([a-z]{1,15}).png).*?\/?>/i', '[$1]', $msg);
        $msg = strip_tags($msg, '<br>');

        //去掉空格
        $msg = str_replace('&nbsp;', ' ', $msg);
        $msg = preg_replace('/<br.*?\/?>/i', "\n", $msg);

        return rtrim($msg);
    }

    /**
     * 贴图匹配
     * @param String $sticker
     * @return string
     */
    public static function replaceSendOnlineSticker(String $sticker = '')
    {
        if (false !== strrpos($sticker, 'default')) {
            return preg_replace('/.+?\d{5,}\/(default\_\d{5,}).*+/i', '$1', $sticker);
        }

        return preg_replace('/.+?(\d{5,})\/(\d{5,}).*+/i', '$1/$2', $sticker);
    }

    /**
     * 解析json字符串
     * @param $string
     * @param bool $assoc
     * @return mixed
     */
    public static function parseJsonDecode($string, $assoc = true)
    {
        return json_decode($string, $assoc);
    }

    /**
     * 返回当前url
     * @return string
     */
    public static function getCurrentURL()
    {
        return static::getScheme() . '://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    }

    /**
     * 返回当前访问的scheme
     * @return string
     */
    public static function getScheme()
    {
        if (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
            return 'https';
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            return 'https';
        } elseif (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
            return 'https';
        }

        return 'http';
    }

    /**
     * 请求域名
     * @param $arr
     * @return string
     */
    public static function getDomain($arr)
    {
        return "{$arr['scheme']}://" .
            $arr['host'] .
            $arr['path'];
    }

    /**
     * 獲取指定長度的文本 並移除HTML標籤
     * @param string $string
     * @param int $subLen
     * @return string
     */
    public static function getStrInHtml(string $string, int $subLen = 30)
    {
        $string = strip_tags($string);
        if ($string !== 0 and !empty($string)) {
            $len = mb_strlen($string);
            if ($len < $subLen) {
                return $string;
            }
            $string = trim(str_replace('\n', '', self::emoji($string, '')));
            $string = str_replace("\\\\n", '', $string);
            return mb_substr($string, 0, $subLen, 'utf-8');
        }
        return '';
    }


    /**
     * 下劃線轉駝峯
     * @param string $unCame
     * @param string $separator
     * @return string
     */
    public static function underToCame(string $unCame, string $separator = '_'): string
    {
        $unCame = $separator . str_replace($separator, " ", strtolower($unCame));
        return ltrim(str_replace(" ", "", ucwords($unCame)), $separator);
    }

    /**
     * 駝峯轉下劃線
     * @param string $camelCaps
     * @param string $separator
     * @return string
     */
    public static function CameToUnder(string $camelCaps, string $separator = '_')
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
    }


}