<?php
use lib\Auth\Auth;
$data->title = 'Dashboard';
$data->h1 = 'Dashboard: ' . Auth::username();
$data->username = Auth::username();
?>


<p>
    This page is protected
</p>

<p>
    Lorem ipsum dolor sit amet consectetur adipisicing elit. Quasi repudiandae odit tenetur doloribus facilis quod aspernatur earum, magnam ipsa amet!
</p>
