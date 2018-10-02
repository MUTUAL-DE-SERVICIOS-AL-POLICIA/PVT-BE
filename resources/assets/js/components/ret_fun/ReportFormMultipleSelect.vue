<template>
    <!-- <div class="form-group">
        <div class="col-md-3 text-right">
            <label class="label-control ">{{ label }}:</label>
        </div>
        <div class="col-md-8"> -->
    <!-- multiple -->
    <!-- <select data-placeholder="Haz clic para seleccionar..."
            class="chosen-select"
            style="width:350px;"
            tabindex="4"
            @change="change()">
        <option v-for="(item, index) in list"
                :key="index"
                :selected="verify(item.id)"
                :value="item.id">{{ item.name }}</option>
    </select> -->
    <!-- </div>
    </div> -->
    <multiselect v-model="selected"
    :options="options"
    :multiple="true"
    :close-on-select="false"
    :clear-on-select="false"
    :hide-selected="true"
    :preserve-search="true"
    placeholder="Pick some"
    label="name"
    track-by="name"
    :preselect-first="true"
    >
  <template slot="tag" slot-scope="props"><span class="custom__tag"><span>{{ props.option.language }}</span><span class="custom__remove" @click="props.remove(props.option)">❌</span></span></template>
  </multiselect>
</template>
<script>
    import Multiselect from "vue-multiselect";
    export default {
      props: ["list", "label", "itemsSelected"],
      components: { Multiselect },
      mounted() {
        // $(".chosen-select").chosen({ width: "100%" });
      },
      data() {
        return {
          selected: null,
          options: [  { name: 'Vue.js', language: 'JavaScript' },
        { name: 'Adonis', language: 'JavaScript' },
        { name: 'Rails', language: 'Ruby' },
        { name: 'Sinatra', language: 'Ruby' },
        { name: 'Laravel', language: 'PHP' },
        { name: 'Phoenix', language: 'Elixir' }]
        };
      },
      methods: {
        verify(id) {
          if (this.itemsSelected.length) {
            return this.itemsSelected.some(element => element == id);
          }
          return false;
        },
        change() {
          console.log("changes");
        }
      }
    };
</script>