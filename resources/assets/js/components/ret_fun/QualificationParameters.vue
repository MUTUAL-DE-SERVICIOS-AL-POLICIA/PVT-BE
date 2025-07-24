<template>
    <div class="row">
        <div class="col-md-3" style="padding-right: 3px">
            <div class="widget-head-color-box blue-bg p-lg text-center">
                <div class="m-b-md">
                    <h2 class="font-bold no-margins" data-toggle="tooltip" data-placement="top"
                        title="Parametros para la calificacion">
                        Parametros para la calificacion
                    </h2>
                </div>
            </div>
            <div class="widget-text-box">
                <ul class="list-group elements-list">
                    <li class="list-group-item active" data-toggle="tab" href="#procedures"><a href="#"><i
                                class="fa fa-puzzle-piece"></i>Gestiones</a></li>
                </ul>
            </div>
        </div>
        <br>
        <div class="col-md-9" style="padding-left: 6px">
            <div class="tab-content">
                <div class="tab-pane active" id="procedures">
                    <ret-fun-average-contributable-wage :procedures="procedures"></ret-fun-average-contributable-wage>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        procedures: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            // Define any data properties needed for this component
        }
    },
    mounted() {
        console.log('QualificationParameters component mounted');
        //console.log('Retirement Fund Procedures:', this.procedures);

    },
    methods: {
        // Define any methods needed for this component
    }
}
</script>