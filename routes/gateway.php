<?php

//RUTAS PERSONA
Route::get('/api/persons', 'PersonController@persons');

//RUTAS AFILIADO
Route::get('/api/affiliates/{affiliateId}/documents', 'AffiliateController@affiliateDocuments');
Route::get('/api/affiliates/{affiliateId}/modality/{procedureModalityId}/collate', 'AffiliateController@collateDocument');
Route::post('/api/affiliates/{affiliateId}/document/{procedureDocumentId}/createOrUpdate', 'AffiliateController@createDocument');
Route::get('/api/affiliates/{affiliateId}/documents/{procedureDocumentId}', 'AffiliateController@findDocument');

//RUTAS PARA IMPORTACIÓN DE DOCUMENTOS
Route::get('/api/affiliatesDocuments/auth', 'AffiliateController@authDocuments')->name('gateway.auth');
Route::post('/api/affiliatesDocuments/analysis', 'AffiliateController@analysisDocuments')->name('gateway.analysisDocuments');
Route::post('/api/affiliatesDocuments/imports', 'AffiliateController@importsDocuments')->name('gateway.importsDocuments');
