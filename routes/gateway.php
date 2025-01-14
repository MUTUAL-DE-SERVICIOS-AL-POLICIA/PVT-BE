<?php

//RUTAS PERSONA
Route::get('/api/persons', 'PersonController@persons');

//RUTAS AFILIADO
Route::get('/api/affiliates/{affiliateId}/documents', 'AffiliateController@affiliateDocuments');
Route::get('/api/affiliates/{affiliateId}/modality/{procedureModalityId}/collate', 'AffiliateController@collateDocument');
Route::post('/api/affiliates/{affiliateId}/document/{procedureDocumentId}/createOrUpdate', 'AffiliateController@createDocument');
Route::get('/api/affiliates/{affiliateId}/documents/{procedureDocumentId}', 'AffiliateController@findDocument');