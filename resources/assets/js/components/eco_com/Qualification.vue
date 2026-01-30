<template>
  <div class="col-lg-12">
    <div class="ibox float-e-margins">
      <div class="ibox-title">
        <h2 class="pull-left">
          Calificacion
          <strong>{{ namePensionEntity }}</strong>
        </h2>
        <div class="ibox-tools" v-if="$eco_com_helper.isEncargadoCalificacion(roleId)" >
            <!--Para borrar rentas calificación-->
          <button 
            class="btn btn-success"
            @click="edit('ceh')"
            data-toggle="tooltip"
            title="Replicar renta manual para calificación"
            :disabled="$eco_com_helper.isInclusion(ecoCom) || !$eco_com_helper.isAutomaticRent(ecoCom)"
          >
            <i class="fa fa-check"></i>Replicar renta fija para calificación
          </button>
          <!-- Para borrar rentas auxilio mortuorio--> 
           
          <template v-if="ecoCom.eco_com_updated_pension != null">
            <button  
              class="btn btn-danger" 
              @click="edit('amh')" 
              data-toggle="tooltip" 
              title="Habilitar renta manual para Aux. Mort."
              :disabled="isSenasir || !$eco_com_helper.isHabitual(ecoCom) || !$eco_com_helper.isAutomaticRent(ecoCom.eco_com_updated_pension)"
            >
              <i class="fa fa-check"></i>Habilitar renta manual para Aux. Mort.
            </button>
          </template>
        </div>
        <div class="ibox-tools" v-if="$eco_com_helper.isAreaTecnica(roleId) || $eco_com_helper.isJefatura(roleId)" >
          <button
            class="btn btn-primary"
            @click="refreshQualification()"
            data-toggle="tooltip"
            title="Actualizar Calificacion"
            :disabled="!can('qualify_economic_complement')"
          >
            <i class="fa fa-refresh"></i>
          </button>
          <!--Para calificación-->
          <button
            class="btn btn-primary"
            @click="edit('ce')"
            data-toggle="tooltip"
            title="Editar Rentas"
            :disabled="!can('update_economic_complement')"
          >
            <i class="fa fa-pencil"></i> {{ $eco_com_helper.isInclusion(ecoCom) ? 'Renta o Pension' : 'Renta o Pension para calificación' }}
          </button>
          <!-- Para auxilio mortuorio--> 
          <button v-if="!isSenasir && !$eco_com_helper.isInclusion(ecoCom)" 
            class="btn btn-primary" 
            @click="edit('am')" 
            data-toggle="tooltip" title="Editar Rentas"
            :disabled="!can('update_economic_complement')">
            <i class="fa fa-pencil"></i> Pension para descuento de Aux. Mort.
          </button>
        </div>
      </div>
      <div class="ibox-content">
        <div class="row">
          <div class="col-md-3">
            <eco-com-amortization :role_id=roleId :permissions="permissions" :observations="observations"></eco-com-amortization>
          </div>
          <div class="col-md-3" v-if="$eco_com_helper.isPaid(ecoCom) && $eco_com_helper.isAreaTecnica(roleId)">
            <button
              class="btn btn-primary"
              data-toggle="tooltip"
              title="Cuando se le a pagado menos"
              @click="recalificar()"
            >
            <i class="fa fa-cog"></i> Recalificar
            </button>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th colspan="3" style="text-align: center;">Parámetros Calificación</th>
                </tr>
                <tr>
                  <th>Detalle</th>
                  <th>Periodo</th>
                  <th>Monto</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="ecoCom.base_wage">
                  <td>Sueldo Base</td>
                  <td>{{ ecoCom.base_wage.month_year | monthYear}}</td>
                  <td style="text-align: right;">{{ ecoCom.base_wage.amount | currency }}</td>
                </tr>
                <template v-if="ecoCom.eco_com_rent">
                  <tr>
                    <td>Limite Referencial</td>
                    <td rowspan="2" style="vertical-align: middle;">{{ecoCom.eco_com_rent.semester}}/{{ecoCom.eco_com_rent.year | year}}</td>
                    <td style="text-align: right;">{{ecoCom.eco_com_rent.referential_limit | currency}}</td>
                  </tr>
                  <tr v-if="showAverage">
                    <td>Promedio</td>
                    <td style="text-align: right;">{{ecoCom.eco_com_rent.average | currency}}</td>
                  </tr>
                </template>
              </tbody>
            </table>
            <p>Datos de la boleta de Renta o Pensi&oacute;n de Jubilaci&oacute;n <strong>para la calificación.</strong></p>
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th style="text-align: center;">Detalle</th>
                  <th style="text-align: center;">Monto</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="! isSenasir">
                  <td>Fracción de Saldo Acumulado (FSA)</td>
                  <td style="text-align: right;">{{ ecoCom.aps_total_fsa | currency}}</td>
                </tr>
                <tr v-if="! isSenasir">
                  <td>Fracción Compensaci&oacute;n de Cotizaciones (CCM)</td>
                  <td style="text-align: right;">{{ ecoCom.aps_total_cc | currency}}</td>
                </tr>
                <tr v-if="! isSenasir">
                  <td>Fracción Solidaria de Vejéz (FSV)</td>
                  <td style="text-align: right;">{{ ecoCom.aps_total_fs | currency}}</td>
                </tr>
                <tr class="danger" v-if="ecoCom.aps_total_death > 0">
                  <td>Fracción por Muerte</td>
                  <td style="text-align: right;">{{ ecoCom.aps_total_death | currency}}</td>
                </tr>
                <tr class="danger" v-if="ecoCom.aps_disability > 0">
                  <td>Prestación por Invalidéz</td>
                  <td style="text-align: right;">{{ ecoCom.aps_disability | currency }}</td>
                </tr>
                <tr v-if="!isSenasir" class="success">
                  <td>Total Renta o Pensi&oacute;n</td>
                  <td style="text-align: right;">{{ ecoCom.total_rent | currency }}</td>
                </tr>
                <tr v-if="isSenasir">
                  <td>Total Ganado Renta o Pensi&oacute;n</td>
                  <td style="text-align: right;">{{ ecoCom.sub_total_rent | currency }}</td>
                </tr>
                <tr v-if="isSenasir">
                  <td>- Reintegro</td>
                  <td style="text-align: right;">{{ ecoCom.reimbursement | currency }}</td>
                </tr>
                <tr v-if="isSenasir">
                  <td>- Renta Dignidad</td>
                  <td style="text-align: right;">{{ ecoCom.dignity_pension | currency }}</td>
                </tr>
                <tr v-if="isSenasir" class="success">
                  <td>Total Renta o Pensi&oacute;n</td>
                  <td style="text-align: right;">{{ ecoCom.total_rent | currency }}</td>
                </tr>
              </tbody>
            </table>
            <!-- Tabla de pension actualizada - No mostrar si es inclusión -->
            <template v-if="ecoCom.eco_com_updated_pension && !$eco_com_helper.isInclusion(ecoCom)"> 
              <p>Datos de la boleta de Renta o Pensi&oacute;n de Jubilaci&oacute;n <strong>para descuento de Auxilio Mortuorio</strong></p>
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th style="text-align: center;">Detalle</th>
                    <th style="text-align: center;">Monto</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="!isSenasir">
                    <td>Fracción de Saldo Acumulado (FSA)</td>
                    <td style="text-align: right;">{{ ecoCom.eco_com_updated_pension.aps_total_fsa | currency }}</td>
                  </tr>
                  <tr v-if="!isSenasir">
                    <td>Fracción Compensaci&oacute;n de Cotizaciones (CCM)</td>
                    <td style="text-align: right;">{{ ecoCom.eco_com_updated_pension.aps_total_cc | currency }}</td>
                  </tr>
                  <tr v-if="!isSenasir">
                    <td>Fracción Solidaria de Vejéz (FSV)</td>
                    <td style="text-align: right;">{{ ecoCom.eco_com_updated_pension.aps_total_fs | currency }}</td>
                  </tr>
                  <tr class="danger" v-if="ecoCom.eco_com_updated_pension.aps_total_death > 0">
                    <td>Fracción por Muerte</td>
                    <td style="text-align: right;">{{ ecoCom.eco_com_updated_pension.aps_total_death | currency }}</td>
                  </tr>
                  <tr class="danger" v-if="ecoCom.eco_com_updated_pension.aps_disability > 0">
                    <td>Prestación por Invalidéz</td>
                    <td style="text-align: right;">{{ ecoCom.eco_com_updated_pension.aps_disability | currency }}</td>
                  </tr>
                  <tr v-if="!isSenasir" class="success">
                    <td>Total Renta o Pensi&oacute;n</td>
                    <td style="text-align: right;">{{ ecoCom.eco_com_updated_pension.total_rent | currency }}</td>
                  </tr>
                  <tr v-if="isSenasir">
                    <td>Total Ganado Renta o Pensi&oacute;n</td>
                    <td style="text-align: right;">{{ ecoCom.eco_com_updated_pension.sub_total_rent | currency }}</td>
                  </tr>
                  <tr v-if="isSenasir">
                    <td>- Reintegro</td>
                    <td style="text-align: right;">{{ ecoCom.eco_com_updated_pension.reimbursement | currency }}</td>
                  </tr>
                  <tr v-if="isSenasir">
                    <td>- Renta Dignidad</td>
                    <td style="text-align: right;">{{ ecoCom.eco_com_updated_pension.dignity_pension | currency }}</td>
                  </tr>
                  <tr v-if="isSenasir" class="success">
                    <td>Total Renta o Pensi&oacute;n</td>
                    <td style="text-align: right;">{{ ecoCom.eco_com_updated_pension.total_rent | currency }}</td>
                  </tr>
                </tbody>
              </table>
            </template>
            <h3 v-if="ecoCom.degree">Grado: {{ ecoCom.degree.name}}</h3>
            <h3 v-if="ecoCom.category">Categoria: {{ ecoCom.category.name}}</h3>
            <h3
              v-if="ecoCom.eco_com_modality"
            >Modalidad: {{ ecoCom.eco_com_modality.name }} ({{ ecoCom.eco_com_modality.shortened }})</h3>
          </div>
          <div class="col-md-6">
            <p>Datos del C&aacute;lculo del Complemento Econ&oacute;mico</p>
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th style="text-align: center;">Detalle</th>
                  <th style="text-align: center;">Monto</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Total Renta o Pensi&oacute;n</td>
                  <td style="text-align: right;">{{ ecoCom.total_rent | currency }}</td>
                </tr>
                <tr>
                  <td>Renta o Pensi&oacute;n Promedio (seg&uacute;n corresponda)  </td>
                  <td style="text-align: right;">{{ ecoCom.total_rent_calc | currency }}</td>
                </tr>
                <tr>
                  <td>Haber B&aacute;sico (Servicio Activo)</td>
                  <td style="text-align: right;">{{ ecoCom.salary_reference | currency }}</td>
                </tr>
                <tr>
                  <td>Categor&iacute;a (Seg&uacute;n Años de Servicio) </td>
                  <td style="text-align: right;">{{ ecoCom.seniority | currency }}</td>
                </tr>
                <tr>
                  <td>Antig&uuml;edad (Haber B&aacute;sico + Categor&iacute;a)</td>
                  <td style="text-align: right;">{{ ecoCom.salary_quotable | currency }}</td>
                </tr>
                <tr>
                  <td>Diferencia (Antig&uuml;edad - Renta o Pensi&oacute;n)</td>
                  <td style="text-align: right;">{{ ecoCom.difference | currency }}</td>
                </tr>
                <tr v-if="ecoCom.months_of_payment === null">
                  <td>Total Semestre (Diferencia * 6 meses)</td>
                  <td  style="text-align: right;">{{ ecoCom.total_amount_semester | currency }}</td>
                </tr>
                <tr v-else>
                  <td>Total Semestre (Diferencia * {{ecoCom.months_of_payment}} meses)</td>
                  <td style="text-align: right;">{{ ecoCom.total_amount_semester | currency }}</td>
                </tr>
                <tr>
                  <td>Factor de Complementación (%)</td>
                  <td style="text-align: right;">{{ ecoCom.complementary_factor }}</td>
                </tr>
                <tr class="warning">
                  <td>Total Complemento Econ&oacute;mico</td>
                  <td style="text-align: right;">{{ ecoCom.total_eco_com | currency }}</td>
                </tr>
                <tr v-for="d in ecoCom.discount_types" :key="d.id" class="danger">
                  <td><button class="btn btn-danger" type="button" v-if="d.id===7 && $eco_com_helper.isAreaTecnica(roleId)" title="Eliminar" @click="deleteDiscount()">
               <i class="fa fa-trash"></i>
                 </button> {{ d.shortened }}</td>
                  <td style="text-align: right;">{{ d.pivot.amount | currency}}</td>
                </tr>
                <tr class="success">
                  <td>
                    <strong>Total L&iacute;quido Pagable</strong>
                  </td>
                  <td style="text-align: right;">{{ ecoCom.total | currency }}</td>
                </tr>
                <tr v-if="ecoCom.total_repay">
                  <td>
                    <strong>Total pagado</strong>
                  </td>
                  <td style="text-align: right;">{{ (ecoCom.total - ecoCom.total_repay)   | currency }}</td>
                </tr>
                <tr class="success" v-if="ecoCom.total_repay">
                  <td>
                    <strong>Reintegro</strong>
                  </td>
                  <td style="text-align: right;">{{ ecoCom.total_repay | currency }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <modal name="rents-modal" class="p-sm" height="auto">
      <div cass="ibox-title">
        <h2 class="pull-left col-xs-offset-1">
          Edicion de Rentas
          <strong>{{ namePensionEntity }}</strong>
        </h2>
      </div>
      <div class="ibox-content">
        <div class="row" v-if="! isSenasir">
          <div class="col-md-10 col-xs-offset-2">
            <div class="row">
              <div class="form-group">
                <label class="col-sm-4 control-label">Fracción de Saldo Acumulado</label>
                <div class="col-sm-4">
                  <input
                    type="text"
                    class="form-control"
                    v-validate="'required'"
                    data-vv-as="Fracción de Saldo Acumulado"
                    v-money
                    name="aps_total_fsa"
                    v-model="ecoComModal.aps_total_fsa"
                    :disabled="!editing"
                  />
                  <small v-if="errors.has('aps_total_fsa')" class="text-danger">
                    {{ errors.first('aps_total_fsa') }}
                  </small>
                </div>
                <div class="col-sm-2">
                  <i class="fa fa-plus" style="font-size:20px"></i>
                </div>
              </div>
            </div>
            <br />
            <div class="row">
              <div class="form-group">
                <label class="col-sm-4 control-label">Fracción de Cotización</label>
                <div class="col-sm-4">
                  <input
                    type="text"
                    class="form-control"
                    v-validate="'required'"
                    data-vv-as="Fracción de Cotización"
                    v-money
                    name="aps_total_cc"
                    v-model="ecoComModal.aps_total_cc"
                    :disabled="!editing"
                  />
                  <small v-if="errors.has('aps_total_cc')" class="text-danger">
                    {{ errors.first('aps_total_cc') }}
                  </small>
                </div>
                <div class="col-sm-2">
                  <i class="fa fa-plus" style="font-size:20px"></i>
                </div>
              </div>
            </div>
            <br />
            <div class="row">
              <div class="form-group">
                <label class="col-sm-4 control-label">Fracción Solidaria</label>
                <div class="col-sm-4">
                  <input
                    type="text"
                    class="form-control"
                    v-validate="'required'"
                    data-vv-as="Fracción Solidaria"
                    v-money
                    name="aps_total_fs"
                    v-model="ecoComModal.aps_total_fs"
                    :disabled="!editing"
                  />
                  <small v-if="errors.has('aps_total_fs')" class="text-danger">
                    {{ errors.first('aps_total_fs') }}
                  </small>
                </div>
                <div class="col-sm-2">
                  <i class="fa fa-plus" style="font-size:20px"></i>
                </div>
              </div>
            </div>
            <br />
            <div class="row">
              <div class="form-group">
                <label class="col-sm-4 control-label">Renta Invalidez</label>
                <div class="col-sm-4">
                  <input
                    type="text"
                    class="form-control"
                    v-validate="'required'"
                    data-vv-as="Renta Invalidez"
                    v-money
                    name="aps_disability"
                    v-model="ecoComModal.aps_disability"
                    :disabled="!editing"
                  />
                  <small v-if="errors.has('aps_disability')" class="text-danger">
                    {{ errors.first('aps_disability') }}
                  </small>
                </div>
                <div class="col-sm-2">
                  <i class="fa fa-plus" style="font-size:20px"></i>
                </div>
              </div>
            </div>
            <br />
            <div class="row">
              <div class="form-group">
                <label class="col-sm-4 control-label">Renta Muerte</label>
                <div class="col-sm-4">
                  <input
                    type="text"
                    class="form-control"
                    v-validate="'required'"
                    data-vv-as="Renta Muerte"
                    v-money
                    name="aps_total_death"
                    v-model="ecoComModal.aps_total_death"
                    :disabled="!editing"
                  />
                  <small v-if="errors.has('aps_total_death')" class="text-danger">
                    {{ errors.first('aps_total_death') }}
                  </small>
                </div>
                <div class="col-sm-2">
                  <i class="fa fa-plus" style="font-size:20px"></i>
                </div>
              </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="row">
              <div class="form-group">
                <label class="col-sm-4 control-label">Total Fracciones</label>
                <div class="col-sm-4">
                  <strong>
                    <animated-integer v-bind:value="totalSumFractions"></animated-integer>
                  </strong>
                </div>
              </div>
            </div>
            <div class="hr-line-dashed"></div>
              <div class="row" v-if="!$eco_com_helper.isInclusion(ecoCom) && ecoComModal.type !='am' && ecoComModal.eco_com_fixed_pension != null">
                <label class="col-sm-4 control-label">Periodo que correponde la renta</label>
                  <select 
                  class="col-sm-6"
                  name="Periodo que correponde la renta"
                  v-model="ecoComModal.eco_com_fixed_pension.eco_com_procedure_id">
                  <option v-for="p in procedures" :value="p.id" :key="p.id">{{ p.semester + p.year.split('-')[0]+ ' ('+ p.rent_month+')' }}</option>
                  </select>
              </div>
          </div>
        </div>
        <div class="row" v-if="isSenasir">
          <div class="col-md-10 col-xs-offset-2">
            <div class="row">
              <div class="form-group">
                <label class="col-sm-4 control-label">Total Ganado Renta ó Pensión</label>
                <div class="col-sm-4">
                  <input
                    type="text"
                    class="form-control"
                    v-validate="'required'"
                    data-vv-as="Total Ganado Renta ó Pensión"
                    v-money
                    name="sub_total_rent"
                    v-model="ecoComModal.sub_total_rent"
                    :disabled="!editing"
                  />
                  <small v-if="errors.has('sub_total_rent')" class="text-danger">
                    {{ errors.first('sub_total_rent') }}
                  </small>
                </div>
                <div class="col-sm-2">
                  <i class="fa fa-plus" style="font-size:20px"></i>
                </div>
              </div>
            </div>
            <br />
            <div class="row">
              <div class="form-group">
                <label class="col-sm-4 control-label">Reintegro</label>
                <div class="col-sm-4">
                  <input
                    type="text"
                    class="form-control"
                    v-validate="'required'"
                    data-vv-as="Reintegro"
                    v-money
                    name="reimbursement"
                    v-model="ecoComModal.reimbursement"
                    :disabled="!editing"
                  />
                  <small v-if="errors.has('reimbursement')" class="text-danger">
                    {{ errors.first('reimbursement') }}
                  </small>
                </div>
                <div class="col-sm-2">
                  <i class="fa fa-minus" style="font-size:20px"></i>
                </div>
              </div>
            </div>
            <br />
            <div class="row">
              <div class="form-group">
                <label class="col-sm-4 control-label">Renta Dignidad</label>
                <div class="col-sm-4">
                  <input
                    type="text"
                    class="form-control"
                    v-validate="'required'"
                    data-vv-as="Renta Dignidad"
                    v-money
                    name="dignity_pension"
                    v-model="ecoComModal.dignity_pension"
                    :disabled="!editing"
                  />
                  <small v-if="errors.has('dignity_pension')" class="text-danger">
                    {{ errors.first('dignity_pension') }}
                  </small>
                </div>
                <div class="col-sm-2">
                  <i class="fa fa-minus" style="font-size:20px"></i>
                </div>
              </div>
            </div>
            <br />
            <div class="row">
              <div class="form-group">
                <label class="col-sm-4 control-label">Renta Invalidez</label>
                <div class="col-sm-4">
                  <input
                    type="text"
                    class="form-control"
                    v-validate="'required'"
                    data-vv-as="Renta Invalidez"
                    v-money
                    name="aps_disability"
                    v-model="ecoComModal.aps_disability"
                    :disabled="!editing"
                  />
                  <small v-if="errors.has('aps_disability')" class="text-danger">
                    {{ errors.first('aps_disability') }}
                  </small>
                </div>
                <div class="col-sm-2">
                  <i class="fa fa-plus" style="font-size:20px"></i>
                </div>
              </div>
            </div>
            <div class="hr-line-dashed"></div>

            <div class="row">
              <div class="form-group">
                <label class="col-sm-4 control-label">Total Renta ó Pensión</label>
                <div class="col-sm-4">
                  <strong>
                    <animated-integer v-bind:value="totalSumSenasir"></animated-integer>
                  </strong>
                </div>
              </div>
            </div>
            <div class="row" v-if="!$eco_com_helper.isInclusion(ecoCom) && ecoComModal.type !='am' && ecoComModal.eco_com_fixed_pension != null">
              <label class="col-sm-4 control-label">Periodo que correponde la renta</label>
                <select 
                class="col-sm-6"
                name="Periodo que correponde la renta"
                v-model="ecoComModal.eco_com_fixed_pension.eco_com_procedure_id">
                <option v-for="p in procedures" :value="p.id" :key="p.id">{{ p.semester + p.year.split('-')[0]+ ' ('+ p.rent_month+')' }}</option>
                </select>
            </div>
          </div>
        </div>
        <div class="row">
          <label class="col-sm-6 control-label">Periodo de Sueldo Base</label>
          <select class="col-sm-6" name="Periodo que correponde la renta" v-model="ecoComModal.base_wage_id">
            <option v-for="bw in baseWages" :value="bw.id" :key="'baseWage' + bw.id">{{ bw.month_year | year }} - {{
              bw.amount | currency }}</option>
          </select>
        </div>
        <div class="row">
          <label class="col-sm-6 control-label">Periodo de Promedio y Limite Referencial ({{ typeRent }})</label>
          <select class="col-sm-6" disabled name="Periodo que correponde la renta"
            v-model="ecoComModal.eco_com_rent_id">
            <option v-for="r in ecoComRents" :value="r.id" :key="'baseWage' + r.id">{{ r.year | year }} - {{ r.semester }}
            </option>
          </select>
        </div>
        <div class="row">
          <template v-if="ecoComModal.eco_com_rent_id && $eco_com_helper.isInclusion(ecoCom)">
            <p class="col-sm-6 control-label">Promedio {{ecoComRents.find(r => r.id ==
              ecoComModal.eco_com_rent_id).average | currency}}</p>
            <p class="col-sm-6 control-label">Limite Referencial {{ecoComRents.find(r => r.id ==
              ecoComModal.eco_com_rent_id).referential_limit | currency}}</p>
          </template>
        </div>
        <div class="col-md-12">
          <div class="text-center m-sm">
            <button class="btn btn-danger" type="button" @click="cancel()">
              <i class="fa fa-times-circle"></i>&nbsp;&nbsp;
              <span class="bold">Cancelar</span>
            </button>
            <button type="button" class="btn btn-primary" @click="save()" :disabled="loadingButton">
              <i v-if="loadingButton" class="fa fa-spinner fa-spin fa-fw" style="font-size:16px"></i>
              <i v-else class="fa fa-save"></i>
              &nbsp;
              {{ loadingButton ? 'Guardando...' : 'Guardar' }}
            </button>
          </div>
        </div>
      </div>
    </modal>
    <modal name="no-edit-rents-modal" class="p-sm" height="auto">
      <div cass="ibox-title">
        <h2 class="pull-left">
          Edicion de Rentas
          <strong>{{ namePensionEntity }}</strong>
        </h2>
      </div>
      <div class="ibox-content">
        <div class="row">
          <div class="col-md-10 col-xs-offset-2">
            <div class="row">
              <h3>Las Rentas Fueron Importadas Automaticamente</h3>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="text-center m-sm">
            <button
              class="btn btn-danger"
              type="button"
              @click="$modal.hide('no-edit-rents-modal')"
            >
              <i class="fa fa-times-circle"></i>&nbsp;&nbsp;
              <span class="bold">Cancelar</span>
            </button>
          </div>
        </div>
      </div>
    </modal>

    <modal name="edit-rents-modal" class="p-sm" height="auto">
      <div class="ibox-title">
        <h2 class="pull-left col-xs-offset-1" v-if="ecoComModal.type == 'ce'">
          Replicar Renta Fija 
          <strong>{{ namePensionEntity }}</strong>
        </h2>
        <h2 class="pull-left col-xs-offset-1" v-if="ecoComModal.type == 'am'">
          Habilitar edición de Rentas 
          <strong>{{ namePensionEntity }}</strong>
        </h2>
      </div>

      <div class="ibox-content">
        <div class="row">
          <div class="col-md-10 col-xs-offset-1">
            <h3>Las Rentas Fueron Importadas Automáticamente.<br/><br/></h3>

            <template v-if="ecoComModal.type == 'ce'">
              <h4 class="text-lg">Seleccione la renta fija que se usara para la réplica</h4>
              <select class="p-xxs" name="newFixedId" id=""  v-model="newFixedId">
                <option class="p-xxs" v-for="fixedPension in affiliate.eco_com_fixed_pensions" :value="fixedPension.id">{{ getProcedureSemester(fixedPension.eco_com_procedure_id) }}</option>
              </select>
              <h3 class="text-danger">
                ¿Esta seguro que realizar la replica de la renta fija para la Calificacion de este trámite?<br/><br/>
              </h3>
            </template>
            <template v-else-if="ecoComModal.type == 'am'">
              <h3 class="text-danger">
                Esta seguro que desea realizar la edición de forma manual de la renta para el Auxilio Mortuorio?<br/><br/>
              </h3>
            </template>
          </div>
        </div>

        <div class="col-md-12 text-center m-sm">
          <button class="btn btn-danger" type="button" @click="$modal.hide('edit-rents-modal')">
            <i class="fa fa-times-circle"></i>&nbsp;&nbsp;
            <span class="bold">Cancelar</span>
          </button>

          <template v-if="ecoComModal.type == 'ce'">
            <button 
              class="btn btn-primary"
              @click="changeRentType('ce')"
              data-toggle="tooltip"
              title="Editar Rentas"
              :disabled="!can('update_economic_complement')"
            >
              <i class="fa fa-check"></i> Replicar "Renta Fija"
            </button>
          </template>
          <template v-else-if="ecoComModal.type == 'am'">
            <button 
              class="btn btn-primary"
              @click="changeRentType('am')"
              data-toggle="tooltip"
              title="Editar Rentas"
              :disabled="!can('update_economic_complement')"
            >
              <i class="fa fa-check"></i> Habilitar registro Manual "Renta Aux. Mort."
            </button>
          </template>
        </div>
      </div>
    </modal>

  </div>
</template>

<script>
import {
  getNamePensionEntity,
  parseMoney,
  canOperation,
  flashErrors
} from "../../helper.js";
export default {
  props: ["ecoComId", "affiliate", "permissions","roleId", "observations"],
  data() {
    return {
      ecoComModal: {
        eco_com_fixed_pension: 
          {eco_com_procedure_id: null},
      },
      editing: false,
      loadingButton: false,
      eco_com_state_type_id:0,
      idEcoCom:0,
      show_spinner:false,
      procedures:[],
      newFixedId:null,
      baseWages: [],
      ecoComRents: [],
      typeRent: "",
    };
  },
  mounted() {
    document.querySelectorAll(".tab-eco-com-qualification")[0].addEventListener(
      "click",
      () => {
        this.getEcoCom();
        this.createFixedRent();
      },
      { passive: true }
    );
  },
  computed: {
    ecoCom() {
      return this.$store.state.ecoComForm.ecoCom;
    },
    isSenasir() {
      return this.$affiliate_helper.isSenasir(this.affiliate);
    },
    namePensionEntity() {
      return getNamePensionEntity(this.affiliate.pension_entity_id);
    },
    totalSumFractions() {
      return (
        parseFloat(parseMoney(this.ecoComModal.aps_total_fsa)) +
        parseFloat(parseMoney(this.ecoComModal.aps_total_cc)) +
        parseFloat(parseMoney(this.ecoComModal.aps_total_fs)) +
        parseFloat(parseMoney(this.ecoComModal.aps_total_death)) +
        parseFloat(parseMoney(this.ecoComModal.aps_disability))
      );
    },
    totalSumSenasir() {
      return (
        parseFloat(parseMoney(this.ecoComModal.sub_total_rent)) -
        parseFloat(parseMoney(this.ecoComModal.reimbursement)) -
        parseFloat(parseMoney(this.ecoComModal.dignity_pension)) +
        parseFloat(parseMoney(this.ecoComModal.aps_disability))
      );
    },
    showAverage() {
      let ecoCom = this.$store.state.ecoComForm.ecoCom;
      if (!ecoCom.eco_com_rent) {
        return false;
      }
      if (![4, 5, 10].includes(ecoCom.eco_com_modality.id) && ecoCom.total_rent < ecoCom.eco_com_rent.referential_limit) {
        return true;
      } else if([4, 5, 10].includes(ecoCom.eco_com_modality.id) && ecoCom.total_rent < ecoCom.eco_com_rent.average) {
        return true;
      } else {
        return false;
      }
    }
  },
  methods: {
    can(operation) {
      return canOperation(operation, this.permissions);
    },
    edit(type) {
      if (!this.can("update_economic_complement", this.permissions)) {
        return;
      }
      if (this.ecoCom.eco_com_updated_pension == null && this.affiliate.pension_entity_id != 5) {//Si no tiene renta de AM y no es senasir
        this.ecoCom.eco_com_updated_pension = {};
        this.ecoCom.eco_com_updated_pension.aps_total_fsa = null;
        this.ecoCom.eco_com_updated_pension.aps_total_cc = null;
        this.ecoCom.eco_com_updated_pension.aps_total_fs = null;
        this.ecoCom.eco_com_updated_pension.aps_disability = null;
        this.ecoCom.eco_com_updated_pension.aps_total_death = null;
        this.ecoCom.eco_com_updated_pension.sub_total_rent = null;
        this.ecoCom.eco_com_updated_pension.reimbursement = null;
        this.ecoCom.eco_com_updated_pension.dignity_pension = null;

        this.ecoCom.eco_com_updated_pension.rent_type = "Manual";
      }
      console.log(this.ecoCom);
      switch (type) {
        case 'ce': // Complemento Economico
          if (this.ecoCom.rent_type == "Automatico") {
            this.$modal.show("no-edit-rents-modal");
            return;
          }
          this.ecoComModal = JSON.parse(JSON.stringify(this.ecoCom));
          this.ecoComModal.type = "ce";
          break;
        case 'am': // Auxilio Mortuorio
          if (this.ecoCom.eco_com_updated_pension.rent_type == "Automatico") {
            this.$modal.show("no-edit-rents-modal");
            return;
          }
          this.ecoComModal = JSON.parse(JSON.stringify(this.ecoCom.eco_com_updated_pension));
          this.ecoComModal.id = this.ecoCom.id;
          this.ecoComModal.type = "am";
          break;
        case 'ceh': // Complemento Economico
          if (this.ecoCom.rent_type == "Automatico") {
            this.ecoComModal = JSON.parse(JSON.stringify(this.ecoCom));
            this.ecoComModal.type = "ce";
            this.$modal.show("edit-rents-modal");
            return;
          }
          break;
        case 'amh': // Auxilio Mortuorio
          if (this.ecoCom.eco_com_updated_pension.rent_type == "Automatico") {
            this.ecoComModal = JSON.parse(JSON.stringify(this.ecoCom));
            this.ecoComModal.type = "am"
            this.$modal.show("edit-rents-modal");
            return;
          }
          break;
        default:
          break;
      }
      this.$modal.show("rents-modal");
      this.editing = true;
    },
    cancel() {
      this.$modal.hide("rents-modal");
      this.editing = false;
    },
    async getEcoCom() {
      if (!this.can("read_economic_complement", this.permissions)) {
        return;
      }
      this.$scrollTo("#wrapper");
      await axios
        .get(`/get_eco_com/${this.ecoComId}`)
        .then(response => {          
          this.$store.commit("ecoComForm/setEcoCom", response.data);
          this.eco_com_state_type_id=response.data.eco_com_state.eco_com_state_type_id;
          this.getProcedures();
        })
        .catch(error => {
          flashErrors("Error al procesar: ", error.response.data.errors);
        });
    },
    async save() {
      if (!this.can("update_economic_complement", this.permissions)) {
        return;
      }
      this.$validator.validateAll().then(async valid => {
        if (!valid) return;

        this.loadingButton = true;
        this.editing = false;
        this.ecoComModal.pension_entity_id = this.affiliate.pension_entity_id;
        await axios
          .patch(`/eco_com_update_rents`, this.ecoComModal)
          .then(response => {
            this.$store.commit("ecoComForm/setEcoCom", response.data);
            this.$modal.hide("rents-modal");
            flash("Rentas Actualizadas con exito");
          })
          .catch(error => {
            flashErrors("Error al procesar: ", error.response.data.errors);
          });
        this.loadingButton = false;
        this.editing = true;
      });
    },
    async refreshQualification(){
      if (!this.can("qualify_economic_complement", this.permissions)) {
        flash("No tiene permisos para realizar la calificacion", 'error')
        return;
      }
      this.loadingButton = true;
      this.editing = false;
      this.ecoComModal = JSON.parse(JSON.stringify(this.ecoCom));
      this.ecoComModal.refresh = true;
      await axios
        .patch(`/eco_com/${this.ecoComId}/qualify`)
        .then(response => {
          this.$store.commit("ecoComForm/setEcoCom", response.data);          
          flash("Calificacion Actualizada");
        })
        .catch(error => {
          flashErrors("Error al procesar: ", error.response.data.errors);
        });
      this.loadingButton = false;
      this.editing = true;
    },
    getTotalSumFractions() {
      return (
        parseFloat(parseMoney(this.ecoCom.aps_total_fsa)) +
        parseFloat(parseMoney(this.ecoCom.aps_total_cc)) +
        parseFloat(parseMoney(this.ecoCom.aps_total_fs)) +
        parseFloat(parseMoney(this.ecoCom.aps_total_death)) +
        parseFloat(parseMoney(this.ecoCom.aps_disability))
      );
    },
    getTotalSumSenasir() {
      return (
        parseFloat(parseMoney(this.ecoCom.sub_total_rent)) -
        parseFloat(parseMoney(this.ecoCom.reimbursement)) -
        parseFloat(parseMoney(this.ecoCom.dignity_pension)) +
        parseFloat(parseMoney(this.ecoCom.aps_disability))
      );
    },
    async recalificar(){
      await this.$swal({
        title: "¿Está seguro de RECALIFICAR?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#59B75C",
        cancelButtonColor: "#EC4758",
        confirmButtonText: "<i class='fa fa-save'></i> Confirmar",
        cancelButtonText: "Cancelar <i class='fa fa-times'></i>",
        showLoaderOnConfirm: true,
        preConfirm: () => {
          return this.saveRecalificacion().catch(error => {
            this.$swal.showValidationError(
              `Recalificacion fallida: ${error.response.data.errors}`
            );
          });
        },
        allowOutsideClick: () => !this.$swal.isLoading()
      });
    },
    async saveRecalificacion() {
      await axios
        .patch(`/eco_com_recalificacion`,this.ecoCom)
        .then(response => {
          this.$store.commit("ecoComForm/setEcoCom", response.data);
          flash("Recalificacion Actualizada");
        })
        .catch(error => {
          flashErrors("Error al procesar: ", error.response.data.errors);
        });
    },
      deleteDiscount() {
                this.$swal({
                    title: 'Está seguro de eliminar el Aporte?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.value) {
                          console.log(result.value);
                            this.show_spinner = true;
                            axios.delete('/delete_discount_type_aid',{
                            params: { idEcoCom:this.ecoComId }
                          })
                        .then(response => {
                            console.log(response.data);
                            this.show_spinner = false;
                            location.reload();
                        })
                        .catch(error => {
                            this.show_spinner = false;
                            flashErrors("Error al procesar", error.response.data.errors);
                        })
                        }
                    })
            },

    async changeRentType(type) {
      if (!this.can("update_economic_complement", this.permissions)) {
        return;
      }
      this.loadingButton = true;
      this.editing = false;

      this.ecoComModal.id = this.ecoCom.id;
      
      switch (type) {
        case 'ce': // Complemento Economico
          if (this.ecoCom.rent_type == "Automatico") {
            this.ecoComModal.type = "ce";
            this.ecoComModal.new_fixed_id = this.newFixedId;
          }
          break;
        case 'am': // Auxilio Mortuorio
          if (this.ecoCom.eco_com_updated_pension.rent_type == "Automatico") {
            this.ecoComModal.type = "am";
          }
          break;
      }
        await axios
          .patch(`/eco_com_change_rent_type`, this.ecoComModal)
          .then(response => {
            this.$store.commit("ecoComForm/setEcoCom", response.data);
            this.$modal.hide("edit-rents-modal");
            location.reload();
            flash("Rentas Actualizadas con exito");
          })
          .catch(error => {
            flashErrors("Error al procesar: ", error.response.data.errors);
            this.$modal.hide("edit-rents-modal");
          });
        this.loadingButton = false;
        this.editing = true;
    }, 
    async getProcedures() {
      this.$scrollTo("#wrapper");
      await axios
        .get(`/eco_com_procedures_regulation`)
        .then(response => {
          this.procedures = response.data
        })
        .catch(error => {
          flashErrors("Error al procesar: ", error.response.data.errors);
        });
    },
    getProcedureSemester(id) {
      const p = this.procedures.find(e => e.id == id);      
      return p.semester + p.year.split('-')[0] + '(' + p.rent_month + ')'
    },
    async createFixedRent() {
      await axios
        .get(`/affiliate/${this.affiliate.id}/eco_com_fixed_pensions/create`)
        .then(response => {
          console.log(response.data);
          this.baseWages = response.data.base_wages;
          this.ecoComRents = response.data.eco_com_rents;
          this.type = response.data.type;
        })
        .catch(error => {
          flashErrors("Error al procesar: ", error.response.data.errors);
        });
    },
  }
};
</script>