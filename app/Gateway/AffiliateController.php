<?php

namespace Muserpol\Gateway;

use Illuminate\Http\Request;
use Muserpol\Gateway\GatewayService;
use Illuminate\Routing\Controller as BaseController;

class AffiliateController extends BaseController
{
  protected $gateway;

  public function __construct(GatewayService $gateway)
  {
      $this->gateway = $gateway;
  }

  public function affiliateDocuments($affiliateId)
  {
      return json_decode($this->gateway->send('GET', "/api/affiliates/{$affiliateId}/documents"),true);
  }

  public function createDocument(Request $request, $affiliateId, $procedureDocumentId)
  {
        $file = $request->file('documentPdf');

        return json_decode($this->gateway->send('POST', "/api/affiliates/{$affiliateId}/document/{$procedureDocumentId}/createOrUpdate",
            ['multipart' =>[
                [
                    'name'     => 'documentPdf',
                    'contents' => fopen($file->getRealPath(), 'r'),
                    'filename' => $file->getClientOriginalName(),
                ]]
            ]
        ),true);
  }

  public function collateDocument($affiliateId, $procedureModalityId)
  {
    return json_decode($this->gateway->send('GET', "/api/affiliates/{$affiliateId}/modality/{$procedureModalityId}/collate"),true);
  }

  public function findDocument($affiliateId, $procedureDocumentId)
  {
    $res = $this->gateway->send('GET',"/api/affiliates/{$affiliateId}/documents/{$procedureDocumentId}");
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="document.pdf"');
    header('Content-Length: ' . $res->getSize());
    echo $res;
    exit;
  }

  public function authDocuments()
  {
      return view('gateway.auth');
  }

  public function analysisDocuments(Request $request)
  {
    $response = $this->gateway->send('POST', "/api/affiliates/documents/analysis", [
        'form_params' => [
            'path' => $request->path,
            'user' => $request->user,
            'pass' => $request->pass,
        ]
    ]);

    if (is_array($response) && isset($response['status']) && $response['status'] === 'error') {
      $errorData = [
          'message' => 'NO EXISTEN CARPETAS O ARCHIVOS PARA ANALIZAR',
      ];
      return view('gateway.error', ['data' => $errorData]);
    }

    $data = json_decode($response, true);

    return view('gateway.analysis_documents_affiliate', ['data' => $data]);
  }

  public function importsDocuments(Request $request)
  {

    $data_import = json_decode($request->input('data'), true);

    $response = $this->gateway->send('POST', "/api/affiliates/documents/imports", [
      'json' => $data_import
    ]);

    if (is_array($response) && isset($response['status']) && $response['status'] === 'error') {
      $errorData = [
          'message' => 'NO EXISTEN CARPETAS O ARCHIVOS PARA ANALIZAR',
      ];
      return view('gateway.error', ['data' => $errorData]);
    }

    $data = json_decode($response, true);

    return view('gateway.import_documents_affiliate', ['data' => $data]);

  }

}
