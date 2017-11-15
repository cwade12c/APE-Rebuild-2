<?php
include('../config.php');
initCAS();
enforceAuthentication();
redirect('home');