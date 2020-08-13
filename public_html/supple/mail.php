<?php
function send_mime_mail($name_from, // ��� �����������
                        $email_from, // email �����������
                        $name_to, // ��� ����������
                        $email_to, // email ����������
                        $data_charset, // ��������� ���������� ������
                        $send_charset, // ��������� ������
                        $subject, // ���� ������
                        $body // ����� ������
                        ){
$to = mime_header_encode($name_to, $data_charset, $send_charset).'<'.$email_to.'>';
$subject = mime_header_encode($subject, $data_charset, $send_charset);
$from =  mime_header_encode($name_from, $data_charset, $send_charset).'<'.$email_from.'>';
	if($data_charset!=$send_charset) {
    $body = iconv($data_charset, $send_charset, $body);
	}
$headers = "From: $from\r\n";
$headers .= "Content-type: text/plain; charset=$send_charset\r\n";

return mail($to, $subject, $body, $headers);
}

function mime_header_encode($str, $data_charset, $send_charset){
	if($data_charset != $send_charset) {
    $str = iconv($data_charset, $send_charset, $str);
	}
return '=?'.$send_charset.'?B?'.base64_encode($str).'?=';
}
?>