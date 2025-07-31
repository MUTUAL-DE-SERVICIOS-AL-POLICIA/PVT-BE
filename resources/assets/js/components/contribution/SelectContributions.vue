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
								Aportes
							</h3>
						</div>
						<div class="col-md-3" v-if="usedContributionsLimit > 0">
							<div>
								<div>
									<span>Limite de Aportes Máximo</span>
									<small style="float: right;">{{positiveContributions}}/{{usedContributionsLimit}}</small>
								</div>
								<div class="progress progress-small">
									<div :style="{ width: percentagePositiveContributions + '%' }" class="progress-bar"></div>
								</div>
							</div>
						</div>
						<div class="pull-right" style="padding-right:10px">
							<div class="form-inline">
								<div class="form-group" :class="{ 'has-error': errors.has('modal_contribution_type_id') }">
									<label class="label-control">Tipo de contribución</label>
									<select class="form-control" name="modal_contribution_type_id" v-model="modal.contribution_type_id" v-validate="'required'" @change="resetPrintButton()" >
										<option :value="null"></option>
										<option v-for="item in types" :value="item.id" :key="item.id" v-show="showPositiveType(item)"> {{item.name}}</option>
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
									<div :style="{background: getColor1(contribution.contribution_type_id), display: 'block', width: '100%', height: row_higth+'px'}" @click="selectRow(index)" data-toggle="tooltip" data-placement="left" :title="contribution.month_year | monthYear"></div>
								</div>
							</div>
							<label style="font-size:80%;"> {{ startDate | monthYear }} </label>
						</div>
						<div class="col-md-9">
							<table class="table table-fixed">
								<thead>
									<tr>
										<th class="col-md-2">Fecha </th>
										<th class="col-md-2">F.R.P.S.</th>
										<th class="col-md-2">Total</th>
										<th class="col-md-4">Tipo</th>
									</tr>
								</thead>
								<tbody id="contenedor">
									<tr v-for="(contribution,index) in contributions" :key="`contribution-${index}`" :style="{'background':getColor1(contribution.contribution_type_id)}" >
										<td class="col-md-2">{{ contribution.month_year | monthYear }}</td>
										<td class="col-md-2">{{ contribution.retirement_fund }}</td>
										<td class="col-md-2">{{ contribution.total }}</td>
										<td class="col-md-4">
											<select class="form-control" v-model="contribution.contribution_type_id" @change="resetPrintButton()">
												<option :value="null"></option>
												<option v-for="(ct, indexCt) in  types" :key="`ct-${indexCt}`" :value="ct.id" v-show="showPositiveType(ct)">{{ct.name}}</option>
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
						<div class="col-sm-2">
							<button class="btn btn-primary btn-sm" @click="save" :disabled="loadingButton">
								<i v-if="loadingButton" class="fa fa-spinner fa-spin fa-fw"></i>
								<i v-else class="fa fa-arrow-right"></i>
								{{ loadingButton ? 'Guardando Clasificaci&oacute;n...' :  'Guardar Clasificaci&oacute;n' }}
							</button>
						</div>
						<div class="form-inline col-sm-4" v-if="usedContributionsLimit > 0">
							<div class="form-group">
								<label class="label-control" for="limit-contribution">Número Máximo de Aportes</label>
								<input type="text" class="form-control" id="limit-contribution" :disabled="applyLimit" v-model="usedContributionsLimit">
							</div>
						</div>
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
	"contributionsLimit",
	"applyLimit"
  ],
  data() {
	return {
	  list_aportes: this.contributions,
	  order_aportes: true,
	  show_certification: false,
	  modal: { first_date: null, last_date: null, contribution_type_id: null },
	  row_higth: 0,
	  loadingButton: false,
	  showLoading: true,
	  usedContributionsLimit: null,
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

	this.usedContributionsLimit = this.contributionsLimit;
  },
  methods: {
	count1(id) {
	  return this.contributions.filter(item => item.contribution_type_id == id)
		.length;

	},
	countTotal(id) {
	  return this.contributions.filter(item => item.contribution_type_id)
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
		if (this.count1(null)) {
			flash("verifique que no existan aportes sin clasificar", "warning");
			return;
		}
		if(this.usedContributionsLimit > 0 && this.positiveContributions > this.usedContributionsLimit) {
			flash("Los aportes positivos no pueden superar el límite máximo", "warning");
			return;
		}
		this.loadingButton = true;
		this.showLoading = true;
		var data = {
		  ret_fun_id: this.retfunid,
		  contributions: this.contributions,
		  usedContributionsLimit: this.usedContributionsLimit
		};
		axios
		  .post("/ret_fun/savecontributions", data)
		  .then(resp => {
			console.log(resp);
			this.$store.commit("retFunForm/setContributionTypes", {
			  contributionTypes: resp.data.contribution_types,
			  id: this.retfunid
			});
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
		},
	getColor1(contribution_type_id) {
	  const colors = {
		1: "#ffffff",
		2: "#f7f097",
		3: "#f7a197",
		4: "#a1a7ff",
		5: "#b1e7fa",
		6: "#e0ad7d",
		7: "#80e9bd",
		8: "#50c1bd",
		9: "#30c1ed",
		10: "#aeda8a",
		12: "#CDDC39",
		13: "#B2FF59",
		14: "#FF80AB",
		15: "#c6a7fa",
		16: "#c2ac97",
		17: "#fc9003",
		18: "#fca503",
		null: "#bbbaad",
	  };
	  return colors[contribution_type_id] || "#bbbaad"; // color por defecto
	},
	clear() {
		this.modal.first_date = null;
	  	this.modal.last_date = null;
	  	this.modal.contribution_type_id = null;
	},
	applyRangeDate() {
		this.validateBeforeSubmit();
		if (this.validAll) {
			flash("Debe completar el formulario", "error");
			return;
		}
		if (this.isValid()) {
			let fi = moment("01/" + this.modal.first_date, "DD/MM/YYYY");
			let ff = moment("01/" + this.modal.last_date, "DD/MM/YYYY");
			let c_type_id = this.modal.contribution_type_id;
			
			let lote = this.contributions.filter(item => {
				let aporte_date = moment(item.month_year, "YYYY-MM-DD");
				return aporte_date >= fi && aporte_date <= ff;
			});
		
			if (this.hashTypes[c_type_id].operator === "+" && this.usedContributionsLimit > 0) {
				let newPlusType = lote.filter(item => this.hashTypes[item.contribution_type_id].operator !== "+");
				console.log(this.positiveContributions, newPlusType.length);
				
				if (this.positiveContributions + newPlusType.length > this.usedContributionsLimit) {
					flash(`Error: el total de aportes con clasificación positiva será ${this.positiveContributions + newPlusType.length} superando el limite de ${this.usedContributionsLimit} meses`, "error");
					return;
				}
			}

			lote.forEach(item => item.contribution_type_id = c_type_id);

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
	  if (!this.modal.contribution_type_id) {
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
	},
	showPositiveType(contributionType) {
		if(contributionType.operator === "-") return true;
		if(this.positiveContributions < this.usedContributionsLimit || this.usedContributionsLimit <= 0) {
			return true;
		}
		return false;
	}
  },
  computed: {
	validAll() {
		if (this.$validator.errors.collect() == {}) {
			return false;
		}
		return Object.keys(this.$validator.errors.collect()).length > 0;
	},
	hashTypes() {
		return this.types.reduce((acc, type) => {
			acc[type.id] = type;
			return acc;
		}, {});
	},
	positiveContributions() {
		let total = 0;
		this.contributions.forEach(item => {
			if (item.contribution_type_id && this.hashTypes[item.contribution_type_id].operator === "+") {
				total++;
			}
		});
	  return total;
	},
	percentagePositiveContributions() {
		return (this.positiveContributions / this.usedContributionsLimit) * 100;
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