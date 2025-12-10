<?// 한글을 UTF-8로 변환하는 함수 (PHP 4.4.9 호환)
function convert_to_utf8($str) {
    if (function_exists('mb_convert_encoding')) {
        return mb_convert_encoding($str, "UTF-8", "EUC-KR");
    } elseif (function_exists('iconv')) {
        return iconv("EUC-KR", "UTF-8", $str);
    }
    return $str; // 변환 함수가 없으면 원본 유지
}

// JSON 변환 함수 (PHP 4 호환)
if (!function_exists('json_encode_php4')) {
    function json_encode_php4($a) {
        if (is_null($a)) {
            return 'null';
        }
        if ($a === false) {
            return 'false';
        }
        if ($a === true) {
            return 'true';
        }
        if (is_scalar($a)) {
            if (is_float($a)) {
                // 소수점 처리
                return floatval(str_replace(',', '.', strval($a)));
            }
            if (is_string($a)) {
                static $jsonReplaces = array(
                    array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'),
                    array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"')
                );
                return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
            }
            return $a;
        }
        
        $isList = true;
        for ($i = 0, reset($a); $i < count($a); $i++, next($a)) {
            if (key($a) !== $i) {
                $isList = false;
                break;
            }
        }
        
        $result = array();
        if ($isList) {
            foreach ($a as $v) {
                $result[] = json_encode_php4($v);
            }
            return '[' . join(',', $result) . ']';
        } else {
            foreach ($a as $k => $v) {
               // 키가 숫자 형태의 문자열이더라도 항상 문자열로 처리
				$result[] = '"' . $k . '":' . json_encode_php4($v);
            }
            return '{' . join(',', $result) . '}';
        }
    }
}

?>

