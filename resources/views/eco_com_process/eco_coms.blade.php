<div class="col-md-12">
    <div class="ibox">
        <div class="ibox-content">
            <div class="pull-left">
                <legend>Complemento Economicos</legend>
            </div>
            {{-- @can('update',new Muserpol\Models\RetirementFund\RetirementFund) --}} {{--
            <div class="text-right" v-if="!read">
                <button data-animation="flip" class="btn btn-primary" :class="editing ? 'active': ''" @click="toggle_editing"><i class="fa" :class="editing ?'fa-edit':'fa-pencil'" ></i> Editar </button>
            </div> --}} {{-- @else
            <div class="text-right">
                <button data-animation="flip" class="btn btn-primary" disabled><i class="fa fa-edit" ></i> Editar </button>
            </div>
            @endcan --}}
            <br>
            <div class="row">
                <v-card>
                    <v-card-title>
                        <div class="form-group m-md">
                            <label class="label-control">Buscar:</label>
                            <input type="text" v-model="search" class="form-control input-sm" name="search" placeholder="Escribe el número de Trámite, Nombre del titular, etc...">
                        </div>
                    </v-card-title>
                    <v-data-table :headers="headers" :items="ecoComs" :search="search" hide-actions select-all item-key="ci" :disable-initial-sort="true"
                        class="elevation-1">
                        <template slot="headers" slot-scope="props">
                                    <tr>
                                        <th v-for="header in props.headers"
                                            :key="header.text">
                                            <!-- <v-icon small>arrow_upward</v-icon> -->
                                            @{{ header.text }}
                                        </th>
                                    </tr>
                                </template>
                        <template slot="items" slot-scope="props">
                                    <tr class="row-click" :class="{'success': props.item.status}" @click="rowClick(props.item.id)">
                                        {{-- <td v-if="inboxState == 'edited'" class="row-click-first">
                                            <input
                                                type="checkbox"
                                                :checked="false"
                                                :id="props.item.id"
                                                v-model="props.item.status"
                                                @change="checkChange(props.item.id, props.item.status)"
                                                class="mediumCheckbox"
                                            >
                                        </td> --}}
                                        <td>
                                            <!-- <a :href="`${props.item.path}`"> -->
                                                @{{ props.item.code }}
                                            <!-- </a> -->
                                        </td>
                            
                                        <td>
                                            <!-- <a :href="`${props.item.path}`"> -->
                                                {{-- @{{ props.item.name }} --}}
                                            <!-- </a> -->
                                            {{-- <ul class="tag-list"
                                                style="padding: 0"
                                                v-if="props.item.tags.length"
                                                key="saved">
                                                <li v-for="(tag, tagIndex) in props.item.tags"
                                                    :key="`tag-${tagIndex}`">
                                                    <a href="#"
                                                       style="">
                                                        <i class="fa fa-tag"></i> @{{tag.name}}</a>
                                                </li>
                                            </ul> --}}
                                        </td>
                                        <td>
                                            <!-- <a :href="`${props.item.path}`"> -->
                                                @{{ props.item.eco_com_modality.name }}
                                            <!-- </a> -->
                                        </td>
                                        <td>
                                            <!-- <a :href="`${props.item.path}`"> -->
                                                @{{ props.item.city.name }}
                                            <!-- </a> -->
                                        </td>
                                        <td>
                                            @{{ props.item.reception_date | formatDateInbox | uppercase}}
                                        </td>
                                        <td>
                                            <!-- <a :href="`${props.item.path}`"> -->
                                                @{{ props.item.wf_state.name }}
                                            <!-- </a> -->
                                        </td>
                                        <td>
                                            <!-- <a :href="`${props.item.path}`"> -->
                                                @{{ props.item.procedure_state.name }}
                                            <!-- </a> -->
                                        </td>
                                        <td>
                                            <!-- <a :href="`${props.item.path}`"> -->
                                                @{{ props.item.total }}
                                            <!-- </a> -->
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
                                        Su búsqueda de <strong>@{{search}}</strong> no encontró ningún resultado.
                                    </div>
                                </template>
                    </v-data-table>
                </v-card>
            </div>
            <br>
        </div>
    </div>
</div>