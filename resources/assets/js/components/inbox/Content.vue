<template>
    <v-card>
        <v-card-title>
            <div class="form-group m-md">

                <label class="label-control">Buscar:</label>
                <input type="text"
                    v-model="search"
                    class="form-control input-sm"
                    name="search"
                    placeholder="Escribe el número de Trámite, Nombre del titular, etc...">
            </div>
        </v-card-title>
        <v-data-table :headers="headers"
                      :items="documents"
                      :search="search"
                      hide-actions
                      select-all
                      item-key="ci"
                      class="elevation-1">
            <template slot="headers"
                      slot-scope="props">
                <tr>
                    <th v-if="inboxState == 'edited'">
                        <input
                            type="checkbox"
                            :checked="false"
                            v-model="checkedAllStatus"
                            @change="checkedAll()"
                            class="mediumCheckbox"
                        >
                            Seleccionar Todos
                    </th>
                    <th v-for="header in props.headers"
                        :key="header.text">
                        <!-- <v-icon small>arrow_upward</v-icon> -->
                        {{ header.text }}
                    </th>
                </tr>
            </template>
            <template slot="items"
                      slot-scope="props">
                <tr class="row-click" :class="{'success': props.item.status}">
                    <td v-if="inboxState == 'edited'" class="row-click-first">
                        <input
                            type="checkbox"
                            :checked="false"
                            :id="props.item.id"
                            v-model="props.item.status"
                            @change="checkChange(props.item.id, props.item.status)"
                            class="mediumCheckbox"
                        >
                    </td>
                    <td @click="rowClick(props.item.path)">
                        <!-- <a :href="`${props.item.path}`"> -->
                            {{ props.item.code }}
                        <!-- </a> -->
                    </td>
                    <td @click="rowClick(props.item.path)">
                        <!-- <a :href="`${props.item.path}`"> -->
                            {{ props.item.ci }}
                        <!-- </a> -->
                    </td>
                    <td @click="rowClick(props.item.path)">
                        <!-- <a :href="`${props.item.path}`"> -->
                            {{ props.item.name }}
                        <!-- </a> -->
                        <ul class="tag-list"
                            style="padding: 0"
                            v-if="props.item.tags.length"
                            key="saved">
                            <li v-for="(tag, tagIndex) in props.item.tags"
                                :key="`tag-${tagIndex}`">
                                <a href="#"
                                   style="">
                                    <i class="fa fa-tag"></i> {{tag.name}}</a>
                            </li>
                        </ul>
                    </td>
                    <td @click="rowClick(props.item.path)">
                        <!-- <a :href="`${props.item.path}`"> -->
                            {{ props.item.modality }}
                        <!-- </a> -->
                    </td>
                    <td @click="rowClick(props.item.path)">
                        <!-- <a :href="`${props.item.path}`"> -->
                            {{ props.item.city }}
                        <!-- </a> -->
                    </td>
                    <td @click="rowClick(props.item.path)">
                        {{ props.item.reception_date | formatDateInbox | uppercase}}
                    </td>
                </tr>
            </template>
            <template slot="no-data">
                <div class="alert alert-warning">
                    No hay registros
                </div>
            </template>
            <template slot="no-results">
                <div class="alert alert-danger">
                    Su búsqueda de <strong>{{search}}</strong> no encontró ningún resultado.
                </div>
            </template>
        </v-data-table>
    </v-card>
</template>
<script>
    import { mapState, mapMutations } from "vuex";
    export default {
      props: ["workflowId", "documents", "inboxState"],
      data() {
        return {
          checkedAllStatus: false,
          search: "",
          headers: [
            { text: "# Trámite", value: "code" },
            { text: "CI Titular", align: "left", value: "ci" },
            { text: "Nombre del titular", value: "name" },
            { text: "Regional", value: "city" },
            { text: "Modalidad", value: "modality" },
            { text: "Fecha de Recepción", value: "date_reception" }
          ]
        };
      },
      mounted() {
        // remove class of vuetify and added class of inspinia
        $(".datatable").each(function(i, obj) {
          $(obj).removeClass("datatable table datatable--select-all");
          $(obj).addClass("table table-hover table-mail");
          $(".datatable__progress").remove();
        });
        $(".table__overflow").addClass("table-responsive");
      },
      methods: {
        /* TODO
                correguir al momento de checkall unchecked uno por uno

                 */
        checkChange(id, status) {
          let object = {
            workflow_id: this.workflowId,
            doc: {
              id: id,
              status: status
            }
          };
          this.$store.commit("inbox/pushDoc", object);
        },
        checkedAll() {
          if (this.checkedAllStatus) {
            this.documents.forEach(d => {
              if (!d.status) {
                d.status = true;
                this.checkChange(d.id, d.status);
              }
            });
          } else {
            this.documents.forEach(d => {
              if (d.status) {
                d.status = false;
                this.checkChange(d.id, d.status);
              }
            });
          }
        },
        rowClick(link){
            window.location=link;
        }
        // changeSort (column) {
        //     if (this.pagination.sortBy === column) {
        //     this.pagination.descending = !this.pagination.descending
        //     } else {
        //     this.pagination.sortBy = column
        //     this.pagination.descending = false
        //     }
        // }
      }
    };
</script>
