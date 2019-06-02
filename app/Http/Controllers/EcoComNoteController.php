<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Muserpol\Models\Note;
use Illuminate\Validation\ValidationException;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Carbon\Carbon;
use Muserpol\Helpers\Util;

class EcoComNoteController extends Controller
{
    public function create(Request $request)
    {
        try {
            $this->authorize('create', new Note());
        } catch (AuthorizationException $exception) {
            return response()->json([
                'status' => 'error',
                'errors' => ['No tiene permisos para crear notas'],
            ], 403);
        }
        try {
            $this->validate($request, [
                'message' => 'required|min:10|max:250',
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'errors' => $exception->errors(),
            ], 422);
        }
        $eco_com = EconomicComplement::find($request->ecoComId);
        $eco_com->notes()->create([
            'user_id' => auth()->user()->id,
            'date' => Carbon::now(),
            'message' => $request->message,
        ]);
        $eco_com->document_records()->create([
            'user_id' => auth()->user()->id,
            'record_type_id' => 13,
            'wf_state_id' => Util::getRol()->wf_states->first()->id,
            'date' => Carbon::now(),
            'message' => "El usuario " . auth()->user()->username  . " creó una nota."
        ]);
        return 'created';
    }
    public function update(Request $request)
    {
        try {
            $this->authorize('update', new Note());
        } catch (AuthorizationException $exception) {
            return response()->json([
                'status' => 'error',
                'errors' => ['No tiene permisos para editar notas'],
            ], 403);
        }
        try {
            $this->validate($request, [
                'message' => 'required|min:10|max:250',
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'errors' => $exception->errors(),
            ], 422);
        }
        $eco_com = EconomicComplement::find($request->ecoComId);
        $note = Note::find($request->id);
        $old_note = Note::find($request->id);
        if ($note) {
            $note->update(['message' => $request->message]);
            $message = "El usuario " . auth()->user()->username  . " modifico la nota ";
            if ($old_note->message != $request->message) {
                $message = $message . ' Mensaje de - ' . $old_note->message . ' - a - ' . $request->message . '.';
            }
            $eco_com->document_records()->create([
                'user_id' => auth()->user()->id,
                'record_type_id' => 13,
                'wf_state_id' => Util::getRol()->wf_states->first()->id,
                'date' => Carbon::now(),
                'message' => $message
            ]);
        } else {
            return response()->json(['errors' => ['El Trámite no tiene esa nota']], 422);
        }
        return "updated";
    }
    public function delete(Request $request)
    {
        try {
            $this->authorize('delete', new Note());
        } catch (AuthorizationException $exception) {
            return response()->json([
                'status' => 'error',
                'errors' => ['No tiene permisos para eliminar notas'],
            ], 403);
        }
        try {
            $this->validate($request, [
                'id' => 'required',
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'errors' => $exception->errors(),
            ], 422);
        }
        $eco_com = EconomicComplement::find($request->ecoComId);
        $note= Note::find($request->id);
        $message = $note->message;
        if ($note) {
            $note->delete();
            $eco_com->document_records()->create([
                'user_id' => auth()->user()->id,
                'record_type_id' => 13,
                'wf_state_id' => Util::getRol()->wf_states->first()->id,
                'date' => Carbon::now(),
                'message' => "El usuario " . auth()->user()->username  . " eliminó la nota ". $message,
            ]);
        } else {
            return response()->json(['errors' => ['El Trámite no tiene esa nota']], 422);
        }
    }
}
