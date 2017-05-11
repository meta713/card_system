<?php

//ログアウト処理
session_start();
session_reset();
session_destroy();

header("Location: index.html");

exit;
