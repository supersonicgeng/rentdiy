<?php

Route::prefix('notice')->namespace('Notice')->name('notice.')->group(function () {

    //反馈列表
    Route::get('/feedback', 'FeedbackController@index')->name('feedback.index');
    Route::get('/feedfack/show/{feedback}', 'FeedbackController@show')->name('feedback.show');//反馈详情

    //公告
    Route::resource('ment', 'MentController');
});