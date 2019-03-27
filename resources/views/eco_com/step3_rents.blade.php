<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="ibox-content">
            <legend>
                Registrar Rentas de
                <strong>@{{ namePensionEntity }}</strong>
            </legend>
            <div class="row">
                <input type="text" v-money v-model="aps_total_fsa">
                <input type="text" v-money v-model="aps_total_cc">
                <input type="text" v-money v-model="aps_total_ca">
                <p>@{{totalSumFractions}}</p>
            </div>
            <div class="row">
                <input type="text" v-money v-model="sub_total_rent">
                <input type="text" v-money v-model="reimbursement">
                <input type="text" v-money v-model="dignity_pension">
                <input type="text" v-money v-model="aps_disability">
                <p>@{{totalSumSenasir}}</p>
            </div>
        </div>
    </div>
</div>