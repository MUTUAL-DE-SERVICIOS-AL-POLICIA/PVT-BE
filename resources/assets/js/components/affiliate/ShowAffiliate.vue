<template>
	<div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                Informacion Personal
            </div>
            <div class="panel-body">
                <table class="table small m-b-xs">
                    <tbody>
                    <tr>
                        <td>
                            <strong>142</strong> Projects
                        </td>
                        <td>
                            <strong>22</strong> Followers
                        </td>

                    </tr>
                    <tr>
                        <td>
                            <strong>61</strong> Comments
                        </td>
                        <td>
                            <strong>54</strong> Articles
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>154</strong> Tags
                        </td>
                        <td>
                            <strong>32</strong> Friends
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="col-md-6">
                    <dl class="dl-">
                        <dt>Cedula de identidad:</dt> <dd>{{ affiliate.identity_card }}  {{ !!affiliate.city_identity_card ?  affiliate.city_identity_card.first_shortened : '' }}</dd>
                        <dt>Primer Nombre:</dt> <dd>{{ affiliate.first_name }}</dd>
                        <dt>Segundo Nombre:</dt> <dd>{{ affiliate.second_name }}</dd>
                        <dt>Apellido Paterno:</dt> <dd>{{ affiliate.last_name }}</dd>
                        <dt>Apellido Materno:</dt> <dd>{{ affiliate.mothers_last_name }}</dd>
                        <dt v-show="affiliate.gender == 'F'">Apellido de Casada:</dt> <dd v-show="affiliate.gender == 'F'">{{ affiliate.surname_husband }}</dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl class="dl-">
                        <dt>Genero:</dt> <dd>{{ affiliate.gender }}</dd>
                        <dt>Estado Civil:</dt> <dd>{{ affiliate.civil_status }}</dd>
                        <dt>Fecha de Nacimiento:</dt> <dd>{{ affiliate.birth_date }}</dd>
                        <dt>Edad:</dt> <dd>{{ affiliate.birth_date }}</dd>
                        <dt>Lugar de Nacimiento:</dt> <dd>{{ !!affiliate.city_birth ? affiliate.city_birth.name : '' }}</dd>
                        <dt>Telefono:</dt> <dd>{{ affiliate.phone_number }}</dd>
                        <dt>Celular:</dt> <dd>{{ affiliate.cell_phone_number }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
	export default{
		props:[
			'affiliate'
		]
	}
</script>