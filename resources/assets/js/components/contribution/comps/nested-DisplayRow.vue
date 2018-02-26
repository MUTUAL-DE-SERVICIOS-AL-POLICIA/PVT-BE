<template>
  <div class="-nested-dsp-row-comp">
    <button class="btn btn-xs btn-link -nested-dsp-row-close-btn"
      @click="nested.$toggle(false)">
      <i class="fa fa-times fa-lg"></i>Guardar
    </button>
      
    <dl class="dl-horizontal">
      <template >
        <table class="col-md-12">
            <tr v-for="item in data">
                <td class="col-md-3"></td>
                <td class="col-md-3">Alfreds Futterkiste</td>
                <td class="col-md-3">Maria Anders</td>
                <td class="col-md-3">Germany</td>
            </tr>
        </table>          
      </template>
    </dl>
  </div>
</template>
<script>
export default {
  props: ['row', 'nested']
}
</script>
<style>
.-nested-dsp-row-comp {
  position: relative;
  padding: 10px;
}
.-nested-dsp-row-close-btn {
  position: absolute;
  top: 5px;
  right: 5px;
}
</style>
