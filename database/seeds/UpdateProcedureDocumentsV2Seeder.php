<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Muserpol\Models\ProcedureDocument;
use Muserpol\Models\ProcedureRequirement;

class UpdateProcedureDocumentsV2Seeder extends Seeder
{
    public function run()
    {

         // 1. MODIFICAR DOCUMENTOS EXISTENTES  

        $this->editarDocumento(249,"Boleta de pensión de invalidez,muerte o riesgos, en fotocopia.");
        $this->editarDocumento(272,"Formulario de Registro de Beneficiario SIGEP.(Titular)");

         // 2. CREAR NUEVOS DOCUMENTOS  

        $forSigepd = $this->crearDocumento("Formulario de Registro de Beneficiario SIGEP.(otros derechohabientes)","FOR_SIGEPD");

        $certPens = $this->crearDocumento("Certificación de otorgación de pensión de la Gestora Pública o Entidad Aseguradora","CERT_PENS");

        $brpJub = $this->crearDocumento( "Boleta de renta o pensión de Jubilación para la verificación de prestaciones en curso de pago, en fotocopia", "BRP_JUB");

         // 3. MODALIDADES


        // Fondo Retiro Fallecimiento
        $fondoRetiroFallecimientoId = 4;

        // Cuota Mortuoria
        $riesgoComunId = 9;
        $cumplimientoDeFuncionesId = 8;
        $fallecimientoConyugeId =99;  

        // Auxilio Mortuorio
        $titularFallecidoId = 13;
        $conyugueFallecidaId = 14;
        $viudaFallecidaId = 15;

        // Complemento Económico
        $ceVejezId = 29;
        $ceViudedadId = 30;
        $ceOrfandadId = 31;

 
         // 4. RELACIONES DEL DOCUMENTO FOR_SIGEPD

        $this->agregarRelacionDocumento($forSigepd->id, $fondoRetiroFallecimientoId, 0);
        $this->agregarRelacionDocumento($forSigepd->id, $riesgoComunId, 0);
        $this->agregarRelacionDocumento($forSigepd->id, $cumplimientoDeFuncionesId, 0);
        $this->agregarRelacionDocumento($forSigepd->id, $fallecimientoConyugeId, 0);
        $this->agregarRelacionDocumento($forSigepd->id, $conyugueFallecidaId, 0);
        $this->agregarRelacionDocumento($forSigepd->id, $titularFallecidoId, 0);
        $this->agregarRelacionDocumento($forSigepd->id, $conyugueFallecidaId, 0);
        $this->agregarRelacionDocumento($forSigepd->id,  $viudaFallecidaId,  0);

         // 5. RELACIONES DEL DOCUMENTO CERT_PENS


        $this->agregarRelacionDocumento($certPens->id, $ceVejezId, 0);
        $this->agregarRelacionDocumento($certPens->id, $ceViudedadId, 0);
        $this->agregarRelacionDocumento($certPens->id, $ceOrfandadId, 0);

         // 6. RELACIONES DEL DOCUMENTO BRP_JUB

        $this->agregarRelacionDocumento($brpJub->id, $ceVejezId, 0);
        $this->agregarRelacionDocumento($brpJub->id, $ceViudedadId, 0);
        $this->agregarRelacionDocumento($brpJub->id, $ceOrfandadId, 0);
    }

    /**
     * Modifica un documento existente.
     */
    private function editarDocumento($id, $name)
    {
        $document = ProcedureDocument::find($id);

        if ($document == null) {
            return;
        }
        $document->name = $name;

        if ($document->isDirty()) {
            $document->save();
        }
    }

    /**
     * Crea un documento si todavía no existe.
     *
     * Se utiliza shortened como identificador para evitar
     * crear duplicados si el seeder se ejecuta nuevamente.
     */
    private function crearDocumento($name, $shortened)
    {
        $document = ProcedureDocument::where(
            'shortened',
            $shortened
        )->first();

        if ($document == null) {
            $document = new ProcedureDocument();

            $document->name = $name;
            $document->shortened = $shortened;

            $document->save();
        }

        return $document;
    }


    /**
     * Agrega una relación documento-modalidad si no existe.
     *
     * No elimina las relaciones existentes de la modalidad.
     */
    private function agregarRelacionDocumento(
        $documentId,
        $modalityId,
        $number = 0
    ) {
        $requirement = ProcedureRequirement::where(
            'procedure_document_id',
            $documentId
        )
        ->where(
            'procedure_modality_id',
            $modalityId
        )
        ->first();

        if ($requirement != null) {
            return;
        }

        $now = Carbon::now();

        ProcedureRequirement::insert([
            'procedure_document_id' => $documentId,
            'procedure_modality_id' => $modalityId,
            'number' => $number,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}