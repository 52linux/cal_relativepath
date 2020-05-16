<?php
// 相对于 $base值为 $rel的 绝对路径
function rel2abs($rel, $base)
{
    /* return if already absolute URL */
    if (parse_url($rel, PHP_URL_SCHEME) != '') return $rel;

    /* queries and anchors */
    if ($rel[0]=='#' || $rel[0]=='?') return $base.$rel;

    /* parse base URL and convert to local variables:
       $scheme, $host, $path */
    // var_dump(parse_url($base));
    extract(parse_url($base));

    /* remove non-directory element from path */
    $path = preg_replace('#/[^/]*$#', '', $path);

    /* destroy path if relative url points to root */
    if ($rel[0] == '/') $path = '';

    /* dirty absolute URL */
    $abs = "$host$path/$rel";

    /* replace '//' or '/./' or '/foo/../' with '/' */
    $re = array('#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#');
    for($n=1; $n>0; $abs=preg_replace($re, '/', $abs, -1, $n)) {}

    /* absolute URL is ready! */
 
    return $scheme ? $scheme.'://'.$abs : $abs;
}


var_dump(rel2abs("../d/x.php","/abcd/a/b/c/a.php"));

var_dump(rel2abs("../d/x.php","http://www.baidu.com/abcd/a/b/c/a.php"));

var_dump(rel2abs("../d/x.php","abcd/a/b/c/a.php"));

 
// and  one 

$a = '/a/e.php';
$b = '/a/b/c/d/1/2/c.php';

// 求newPath相对于basePath的相对路径
function getRelativePath($basePath, $newPath){
    $relative = '';
    $base_pathArr = explode("/", $basePath);
    $new_pathArr = explode("/", $newPath);
    $cnt_base = count($base_pathArr);
    for($i = 0; $i < $cnt_base;$i++){
        if($base_pathArr[$i] != $new_pathArr[$i]){
            break;
        }
    }
    if($i == $cnt_base){ //全匹配的话，单独做处理，其实当前文件夹下也可以不用
        $relative = './';
    }else{
        $n = $cnt_base - $i;
        for($j=0;$j<$n;$j++){
            $relative = $relative."../";
        }
    }

    $extraPath = implode("/", array_slice($new_pathArr, $i));
    $relative_path = $relative.$extraPath;
    return $relative_path;
}

$res = getRelativePath($a, $b);
var_dump($res);


$res = getRelativePath($b, $a);
var_dump($res);
