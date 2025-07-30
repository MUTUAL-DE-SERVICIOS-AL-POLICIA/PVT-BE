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
                    <ret-fun-average-contributable-wage :procedures="procedures" :hierarchies="hierarchies"></ret-fun-average-contributable-wage>
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
        },
        hierarchies: {
            type: Array,
            required: true
        }
    },
}
</script>