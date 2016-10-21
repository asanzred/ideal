<?php

Route::get('/ideal/issuers', 'IdealController@getissuers');
Route::get('/ideal/send', 'IdealController@send');
Route::get('/ideal/return', 'IdealController@returntx');