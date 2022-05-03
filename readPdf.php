<?php

 $content = file_get_contents('test.pdf');

 $regexp = '#ByteRange\[\s*(\d+) (\d+) (\d+)#'; // subexpressions are used to extract b and c

 $result = [];
 preg_match_all($regexp, $content, $result);

 // $result[2][0] and $result[3][0] are b and c
 if (isset($result[2]) && isset($result[3]) && isset($result[2][0]) && isset($result[3][0]))
 {
     $start = $result[2][0];
     $end = $result[3][0];
     if ($stream = fopen('test.pdf', 'rb')) {
         $signature = stream_get_contents($stream, $end - $start - 2, $start + 1); // because we need to exclude < and > from start and end

         fclose($stream);
     }

     file_put_contents('signature.pkcs7', hex2bin($signature));
}