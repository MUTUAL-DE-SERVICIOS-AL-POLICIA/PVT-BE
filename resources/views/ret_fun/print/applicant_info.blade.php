<div class="title2">
   <strong class="code">Trámite Nº:  </strong>
</div>
<table class="table" style="width:100%;">
   <tr>
       <td colspan="6" class="grand info_title">
           <center>INFORMACIÓN DEL BENEFICIARIO</center>
       </td>
   </tr>
   <tr >
       <td colspan="1"><strong>NOMBRE:</strong></td>
       <td colspan="5" nowrap>{!! $applicant->last_name." ".$applicant->first_name !!}</td>
   </tr>
   <tr>
       <td><strong>C.I.:</strong></td>
       <td nowrap>{!! $applicant->identity_card !!} {{$applicant->city_identity_card->first_shortened ?? ''}}</td>
       <td><strong>DOMICILIO:</strong></td>
        <td> {!! $applicant->cell_phone_number !!}</td>
   </tr>
   <tr>
       <td><strong>TELÉFONO:</strong></td>
       <td>{!! $applicant->phone_number !!}<br/></td>
       <td><strong>CELULAR:</strong></td>
       <td>{!! $applicant->cell_phone_number !!}<br/></td>
   </tr>
</table>
