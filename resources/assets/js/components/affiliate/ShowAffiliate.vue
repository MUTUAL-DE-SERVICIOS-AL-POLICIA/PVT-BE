<template>
	<div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                Informacion Personal
            </div>
            <div class="panel-body">
                <div class="col-md-6">
                    <dl class="dl-horizontal">
                        <dt>Cedula de identidad:</dt> <dd>{{ affiliate.identity_card }} {{ affiliate.city_identity_card_id }}</dd>
                        <dt>Hola:</dt> <dd>{{ affiliate.first_name }}</dd>
                        <dt>Segundo Nombre:</dt> <dd>{{ affiliate.second_name }}</dd>
                        <dt>Apellido Paterno:</dt> <dd>{{ affiliate.last_name }}</dd>
                        <dt>Apellido Materno:</dt> <dd>{{ affiliate.mothers_last_name }}</dd>
                        <dt v-show="affiliate.gender == 'F'">Apellido de Casada:</dt> <dd v-show="affiliate.gender == 'F'">{{ affiliate.surname_husband }}</dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl class="dl-horizontal">
                        <dt>Genero:</dt> <dd>16.08.2014 12:15:57</dd>
                        <dt>Created:</dt> <dd> 	10.07.2014 23:36:57 </dd>
                        <dt>Participants:</dt>
                        <dd class="project-people">
                        <a href=""><img alt="image" class="img-circle" src="img/a3.jpg"></a>
                        <a href=""><img alt="image" class="img-circle" src="img/a1.jpg"></a>
                        <a href=""><img alt="image" class="img-circle" src="img/a2.jpg"></a>
                        <a href=""><img alt="image" class="img-circle" src="img/a4.jpg"></a>
                        <a href=""><img alt="image" class="img-circle" src="img/a5.jpg"></a>
                        </dd>
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