<?php

Route::prefix('operate')->namespace('Operate')->name('operate.')->group(function () {

    Route::resource('locate', 'LocateController');//运营位置


    Route::get('/subject/create', 'SubjectController@create')->name('subject.create');

    Route::get('/subject/{subject}', 'SubjectController@index')->name('subject.index');//运营位下的运营内容


    Route::post('/subject', 'SubjectController@store')->name('subject.store');//保存专题
    Route::get('/subject/edit/{subject}', 'SubjectController@edit')->name('subject.edit');
    Route::put('/subject/{subject}', 'SubjectController@update')->name('subject.update');
    Route::delete('/subject/{subject}','SubjectController@destroy')->name('subject.destroy');//专题删除
    Route::patch('/subject/change_attr','SubjectController@change_attr')->name('subject.change_attr');//修改状态
});