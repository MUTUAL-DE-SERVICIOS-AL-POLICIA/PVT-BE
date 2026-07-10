<template>
    <div class="ibox fadeInRight">
        <div class="ibox-title">
            <h5>{{ isHolder ? 'Cálculo del total' : 'Calculo de las cuotas partes para los derechohabientes' }}</h5>
        </div>
        <div class="ibox-content">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>{{ isHolder ? 'Nombre del titular' : 'Nombre del derechohabiente' }}</th>
                        <th>% de asignación</th>
                        <th>Monto</th>
                        <th>Parentesco</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th></th>
                        <th>{{ totalPercentage | percentage }}</th>
                        <th :class="colorClass(totalAmount, total)">
                            {{ totalAmount | currency }}</th>
                        <th></th>
                    </tr>
                </tfoot>
                <tbody>
                    <tr v-for="(beneficiary, index) in beneficiaries" :key="index">
                        <td>{{ beneficiary.full_name }}</td>
                        <td><input class="form-control" type="number" step="0.01"
                                v-model="beneficiary.percentage"></td>
                        <td>
                            <div :class="{ 'has-error': max(totalAmount, total) }">
                                <input class="form-control" type="number" step="0.01" v-model="beneficiary.amount">
                            </div>
                        </td>
                        <td>{{ beneficiary.kinship.name }}</td>
                    </tr>
                </tbody>
            </table>
            <button class="btn btn-primary" :class="{ 'btn-outline': !saved }" type="submit" @click="save()"><i
                    class="fa fa-save"></i> Guardar
                <transition name="fade" enter-active-class="animated bounceInRight"
                    leave-active-class="animated bounceOutLeft" v-if="saved">
                    <div>
                        <check-svg></check-svg>
                    </div>
                </transition>
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: 'BeneficiariesQualificationAmounts',

    props: {
        beneficiaries: {
            type: Array,
            default: () => [],
            validator(list) {                
                return Array.isArray(list) && list.every(item =>
                    item &&
                    typeof item.id === 'number' &&
                    typeof item.full_name === 'string' &&
                    typeof item.percentage === 'number' &&
                    typeof item.amount === 'number' &&
                    item.kinship &&
                    typeof item.kinship.id === 'number' &&
                    typeof item.kinship.name === 'string'
                )
            }
        },
        total: {
            type: Number,
            default: 0.0
        },
        isHolder: {
            type: Boolean,
            default: false
        },
    },

    data() {
        return {
            saved: false,
        }
    },

    mounted() {
        // se ejecuta cuando el componente está montado
    },

    computed: {
        totalPercentage() {
            const sum = this.beneficiaries.reduce((accumulator, current) => {
                return accumulator + parseFloat(current.percentage);
            }, 0.0);
            return sum;
        },
        totalAmount() {
            const sum = this.beneficiaries.reduce((accumulator, current) => {
                return accumulator + parseFloat(current.amount);
            }, 0.0);
            return sum;
        },
    },

    methods: {
        max(a, b) {
            return (parseFloat(a.toFixed(2)) > parseFloat(b.toFixed(2)) || isNaN(a));
        },
        colorClass(a, b) {
            if (isNaN(a)) {
                return;
            }
            if (parseFloat(a.toFixed(2)) === parseFloat(b.toFixed(2))) {
                return {
                    'text-info': true,
                };
            }
            if (parseFloat(a.toFixed(2)) > parseFloat(b.toFixed(2))) {
                return {
                    'text-danger': true,
                };
            }
            if (parseFloat(a.toFixed(2)) < parseFloat(b.toFixed(2))) {
                return {
                    'text-warning': true,
                }
            }
        },
        save() {
            this.saved = true;
            this.$emit('save');
        }
    },


}
</script>
