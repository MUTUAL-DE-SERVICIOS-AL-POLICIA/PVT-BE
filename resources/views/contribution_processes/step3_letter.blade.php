<div class="col-lg-12">
    <contribution-create :afid="{{ $affiliate->id }}" 
        {{-- :last_quotable="{{$last_quotable}}"
        :commitment="{{ $commitment }}"
        :is_regional="`{{ $is_regional }}`" --}}
        v-if="contributionProcess.modality_id == 19"
        key="activo"
        ></contribution-create>
    <aid-contribution-create :afid="{{ $affiliate->id }}"
        v-if="contributionProcess.modality_id == 18"
        key="pasivo"
        ></aid-contribution-create>
</div>