<template>
    <table class="table table-hover table-mail">
        <thead>
            <tr>
                <th></th>
                <th>name</th>
                <th>content</th>
                <th>file</th>
                <th>date</th>
            </tr>
        </thead>
        <tbody>
            <tr class="read" v-for="(row, index)  in rows" :key="`row-${index}`">
                <td class="check-mail">
                    <input class="iCheck-helper" type="checkbox">
                </td>
                <td class="mail-ontact">
                    <a :href="`/ret_fun/${row.id}`">{{ row.ci }}</a>
                    <span class="label label-danger pull-right">Documents</span>
                </td>
                <td class="mail-subject">
                    <a :href="`/ret_fun/${row.id}`">{{ row.name }}</a>
                </td>
                <td class="">
                    <i class="fa fa-paperclip"></i>
                </td>
                <td class="text-right mail-date">
                    <a :href="`/ret_fun/${row.id}`">
                        {{ row.code }}
                    </a>
                </td>
                <td class="text-right mail-date">{{ row.reception_date}}</td>
            </tr>
        </tbody>
    </table>
</template>
<script>
    export default {
        props:['workflowId', 'documents'],

        data() {
            return {
                rows:this.documents
            };
        }
    };
</script>
