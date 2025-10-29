<template>
	<div>
		<div class="col-md-12 " style="padding-top: 5px;padding-left: 0px" >
			<div class="ibox float-e-margins ibox-primary my-content-select-contributions" :class="{'sk-loading': showLoading}">
				<div class="ibox-content cont">
					<div class="sk-folding-cube" v-show="showLoading">
						<div class="sk-cube1 sk-cube"></div>
						<div class="sk-cube2 sk-cube"></div>
						<div class="sk-cube4 sk-cube"></div>
						<div class="sk-cube3 sk-cube"></div>
					</div>
					<div class="row">
						<div class="col-md-1">
							<h3>
							<button class="btn btn-sm btn-info"  @click="orderList" ><i :class="order_aportes?'fa fa-sort-amount-desc':'fa fa-sort-amount-asc'"></i></button>
								Aportes QA
							</h3>
						</div>
						<div class="pull-right" style="padding-right:10px">
							<div class="form-inline">
								<div class="form-group" :class="{ 'has-error': errors.has('contribution_type_mortuary_id') }">
									<label class="label-control">Tipo de contribución</label>
									<select class="form-control" name="contribution_type_mortuary_id" v-model="modal.contribution_type_mortuary_id" v-validate="'required'" @change="resetPrintButton()">
										<option :value="null"></option>
										<option v-for="item in types" :value="item.id" :key="item.id"> {{item.name}}</option>
									</select>
								</div>
								<div class="form-group">
									<div class="input-daterange input-group" :class="{ 'has-error': errors.has('modal_first_date')||errors.has('modal_last_date') }">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										<input type="text" name="modal_first_date" class="form-control-sm form-control" placeholder="mm/yyyy" data-month-year="true" v-model="modal.first_date" v-validate="'required|date_format:MM/yyyy|maxDate|minDate'">
										<span class="input-group-addon"> <i class="fa fa-arrow-right"></i> </span>
										<input type="text" name="modal_last_name" class="form-control-sm form-control" placeholder="mm/yyyy" data-month-year="true" v-model="modal.last_date" v-validate="'required|date_format:MM/yyyy|maxDate|minDate'">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									</div>
								</div>
								<div class="form-group">
									<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" @click="applyRangeDate" :disabled="validAll" > <i class="fa fa-save"></i> Guardar</button>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-1">
							<label style="font-size:80%;"> {{ endDate | monthYear }} </label>
							<div style="border-style: solid; border-width: 1px; ">
								<div v-for="(contribution,index) in contributions" :key="index">
									<div :style="{background: getColor1(contribution.contribution_type_mortuary_id), display: 'block', width: '100%', height: row_higth+'px'}" @click="selectRow(index)" data-toggle="tooltip" data-placement="left" :title="contribution.month_year | monthYear"></div>
								</div>
							</div>
							<label style="font-size:80%;"> {{ startDate | monthYear }} </label>
						</div>
						<div class="col-md-9">
							<table class="table table-fixed">
								<thead>
									<tr>
										<th class="col-md-2">Fecha </th>
										<th class="col-md-2">{{retfund_procedure_modality.procedure_type_id == '3' ? "Cuota mortuoria":"Auxilio Mortuorio"}}</th>
										<th class="col-md-2">Total</th>
										<th class="col-md-4">Tipo</th>
									</tr>
								</thead>
								<tbody id="contenedor">
									<tr v-for="(contribution,index) in contributions" :key="`contribution-${index}`" :style="{'background':getColor1(contribution.contribution_type_mortuary_id)}" >
										<td class="col-md-2">{{ contribution.month_year | monthYear }}</td>
										<td class="col-md-2">{{retfund_procedure_modality.procedure_type_id == '3' ? contribution.mortuary_quota : contribution.total }}</td>
										<td class="col-md-2">{{ contribution.total }}</td>
										<td class="col-md-4">
											<select class="form-control" v-model="contribution.contribution_type_mortuary_id" @change="resetPrintButton()">
												<option :value="null"></option>
												<option v-for="(ct, indexCt) in  types" :key="`ct-${indexCt}`" :value="ct.id">{{ct.name}}</option>
											</select>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="col-md-2" style="padding-left:0px;padding-right: 2px">
							<h3>Tipos de Aportes</h3>
							<ul class="list-group">
								<transition-group
									name="custom"
									enter-active-class="animated bounceInRight"
									leave-active-class="animated bounceOutRight"
								>
									<li v-for="(ct, index) in types" :key="`ct-ul-${index}`" v-if="count1(ct.id)" class="list-group-item comando" :style="{background: getColor1(ct.id)}"><span class="badge">{{ count1(ct.id) }}</span> {{ ct.name }} </li>
								</transition-group>
							</ul>
						</div>
					</div>
					<div class="ibox-footer">
						<button class="btn btn-primary btn-sm" @click="save" :disabled="loadingButton">
							<i v-if="loadingButton" class="fa fa-spinner fa-spin fa-fw"></i>
							<i v-else class="fa fa-arrow-right"></i>
							{{ loadingButton ? 'Guardando Clasificaci&oacute;n...' :  'Guardar Clasificaci&oacute;n' }}
						</button>
						<div class="pull-right">
							<strong class=" text-info m-r-md"> Clasificados: {{ countTotal()}} </strong>
							<strong class=" text-danger m-r-md"> Faltantes: {{ count1(null)}} </strong>
							<strong> Cantidad Total: {{contributions.length}} </strong>
						</div>
						<br>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import { monthYearInputMaskAll } from "../../helper.js";

export default {
  name: "clasificacion-aportes",

  props: [
	"contributions",
	"types",
	"retfunid",
	"startDate",
	"endDate",
	"retfund_procedure_modality"
  ],
  data() {
	return {
	  list_aportes: this.contributions,
	  order_aportes: true,
	  show_certification: false,
	  modal: { first_date: null, last_date: null, contribution_type_mortuary_id: null },
	  row_higth: 0,
	  loadingButton: false,
	  showLoading: true,
	  contribution_types: [],
	};
  },
  created: function() {
	setTimeout(() => {
	  monthYearInputMaskAll();
	}, 500);
	this.showLoading = false;
	this.row_higth = 386 / this.contributions.length;
	this.$validator.extend('maxDate', (value) => {
		return moment(this.endDate).diff(moment('01/'+value, "DD/MM/YYYY"), "months") >= 0;
	});
	this.$validator.extend('minDate', (value) => {
		return moment('01/'+value, "DD/MM/YYYY").diff(moment(this.startDate), "months") >= 0;
	});
  },
  methods: {
	count1(id) {
	  return this.contributions.filter(item => item.contribution_type_mortuary_id == id)
		.length;

	},
	countTotal(id) {
	  return this.contributions.filter(item => item.contribution_type_mortuary_id)
		.length;
	},

	async validateBeforeSubmit() {
	  try {
		await this.$validator.validateAll();
	  } catch (error) {
		console.log("some error");
	  }
	},
	orderList() {
	  if (this.order_aportes) {
		this.list_aportes = this.list_aportes.sort((one, two) => {
		  return new Date(one.month_year) - new Date(two.month_year);
		});
	  } else {
		this.list_aportes = this.list_aportes.sort((one, two) => {
		  return new Date(two.month_year) - new Date(one.month_year);
		});
	  }
	  this.order_aportes = !this.order_aportes;
	},
	selectRow(index) {
	  $("#contenedor").scrollTop(index * 51);
	},
	save() {
	  if (!this.count1(null)) {
		this.loadingButton = true;
		this.showLoading = true;
		var data = {
		  ret_fun_id: this.retfunid,
		  contributions: this.contributions
		};
		axios
		  .post("/quota_aid/savecontributions", data)
		  .then(resp => {
			console.log(resp);
			// this.$store.commit("retFunForm/setContributionTypes", {
			//   contributionTypes: resp.data.contribution_types,
			//   id: this.retfunid
			// });
			this.loadingButton = false;
			this.showLoading = false;
			flash("Informacion Clasificada");
		  })
		  .catch(resp => {
			console.log(resp);
			this.showLoading = false;
			this.loadingButton = false;
			flash("Error: " + resp.message, "error");
		  });
	  } else {
		flash("verifique que no existan aportes sin clasificar", "warning");
	  }
	},
	getColor1(contribution_type_mortuary_id) {
	  let color;
	  switch (contribution_type_mortuary_id) {
		case 1:
		  color = "#C5E1A5";
		  break;
		case 2:
		  color = "#FFCDD2";
		  break;
		case null:
		  color = "#bbbaadfd";
		  break;
		default:
		  console.log("no se encontro");
		  break;
	  }
	  return color;
	},
	clear() {
		this.modal.first_date = null;
	  	this.modal.last_date = null;
	  	this.modal.contribution_type_mortuary_id = null;
	},
	applyRangeDate() {
		this.validateBeforeSubmit();
		if (this.validAll) {
			flash("Debe completar el formulario", "error");
			return;
		}
		if (this.isValid()) {
			let fi = moment("01/" + this.modal.first_date, "DD/MM/YYYY").toDate();
			let ff = moment("01/" + this.modal.last_date, "DD/MM/YYYY").toDate();
			let c_type_id = this.modal.contribution_type_mortuary_id;
			this.contributions.forEach(item => {
			let aporte_date = moment(item.month_year, "YYYY-MM-DD").toDate();
			if (
				aporte_date.getTime() >= fi.getTime() &&
				aporte_date.getTime() <= ff.getTime()
			) {
				item.contribution_type_mortuary_id = c_type_id;
			}
			});
			this.clear();
		}
	},
	isValid() {
	  let response = true;

	  if (!this.modal.first_date) {
		flash('Error: verifique que la fecha "De:" no este vacia', "error");
		response = false;
	  }
	  if (!this.modal.last_date) {
		flash('Error: verifique que la fecha "Hasta:" no este vacia', "error");
		response = false;
	  }
	  if (!this.modal.contribution_type_mortuary_id) {
		flash(
		  "Error: debe seleccionar al menos un tipo de contribución",
		  "error"
		);
		response = false;
	  }
	  if (response) {
		if (
		  new Date("01/" + this.modal.first_date).getTime() <
		  new Date("01/" + this.startDate.month_year).getTime()
		) {
		  flash(
			"Error: la fecha " +
			  this.modal.first_date +
			  " no debe ser menor a " +
			  this.startDate.month_year,
			"warning"
		  );
		  response = false;
		  console.log(
			"Error: la fecha " +
			  this.modal.first_date +
			  " no debe ser menos a " +
			  this.startDate.month_year
		  );
		}
		if (
		  new Date("01/" + this.modal.last_date).getTime() >
		  new Date("01/" + this.endDate.month_year).getTime()
		) {
		  flash(
			"Error: la fecha " +
			  this.modal.last_date +
			  " no debe ser mayor a " +
			  this.endDate.month_year,
			"warning"
		  );
		  response = false;
		  console.log(
			"Error: la fecha " +
			  this.modal.last_date +
			  " no debe ser mayor a " +
			  this.endDate.month_year
		  );
		}
		if (
		  new Date("01/" + this.modal.first_date).getTime() >
		  new Date("01/" + this.modal.last_date).getTime()
		) {
		  flash(
			"Error: la fecha " +
			  this.modal.first_date +
			  " no debe ser mayor a " +
			  this.modal.last_date,
			"warning"
		  );
		  response = false;
		  console.log(
			"Error: la fecha " +
			  this.modal.first_date +
			  " no debe ser mayor a " +
			  this.modal.last_date
		  );
		}
	  }
	  return response;
	},
	resetPrintButton() {
	  this.$store.commit("retFunForm/resetContributionTypes", []);
	}
  },
  computed: {
	validAll() {
		if (this.$validator.errors.collect() == {}) {
			return false;
		}
		return Object.keys(this.$validator.errors.collect()).length > 0;
	},
  }
};
</script>
<style>
.table-fixed {
    width:100%;
    table-layout:fixed;
    margin:auto;
}
.table-fixed th, .table-fixed td {
    padding:5px 10px;

}
.table-fixed thead {
    background:#f9f9f9;
    display:table;
    width:100%;
}
.table-fixed tbody {
    height:400px;
    overflow:auto;
    overflow-x:hidden;
    display:block;
    width:100%;
}
.table-fixed tbody tr {
    display:table;
    width:100%;
    table-layout:fixed;
}
</style>|