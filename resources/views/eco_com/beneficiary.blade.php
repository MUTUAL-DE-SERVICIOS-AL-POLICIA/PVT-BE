<div class="ibox">
    <div class="ibox-content">
        <div class="text-right">
            {{-- @can('update',new Muserpol\Models\RetirementFund\RetFunBeneficiary) --}}
            {{-- <button data-animation="flip" class="btn btn-primary" :class="editing ? 'active': ''" @click="toggle_editing"><i class="fa" :class="editing ?'fa-edit':'fa-pencil'" ></i> Editar</button> --}}
            {{-- @else --}}
            <br> 
            {{-- @endcan --}}
        </div>
        <eco-com-beneficiary :beneficiary="{{ $eco_com_beneficiary }}" :cities="{{ $cities}}">
        </eco-com-beneficiary>
    </div>
</div>