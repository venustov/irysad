<?php

/* SELECT COUNT(DISTINCT s.session_ip) as num_guests FROM phpbb_sessions s WHERE s.session_user_id = 1 AND s.session_time >= 1479491700 */

$expired = (time() > 1479492104) ? true : false;
if ($expired) { return; }

$this->sql_rowset[$query_id] = unserialize('a:1:{i:0;a:1:{s:10:"num_guests";s:1:"1";}}');

?>