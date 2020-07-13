<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Usuarios</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo</button>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Listado de usuarios</h3>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th>Email</th>
                        <th>Nombre</th>
                        <th>Api Token</th>
                        <th class="text-right">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td>{{ row.email }}</td>
                        <td>{{ row.name }}</td>
                        <td>{{ row.api_token }}</td>
                        <td class="text-right">
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickCreate(row.id)">Editar</button>
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickDelete(row.id)" v-show="row.id!=1">Eliminar</button>
                        </td>
                    </tr>
                </data-table>
            </div>
            <users-form :showDialog.sync="showDialog"
                            :recordId="recordId"></users-form>
        </div>
    </div>
</template>

<script>

    import UsersForm from './form.vue'
    import DataTable from '../../components/DataTable.vue'
    import {deletable} from '../../mixins/deletable'

    export default {
        mixins: [deletable],
        components: {UsersForm, DataTable},
        data() {
            return {
                showDialog: false,
                resource: 'users',
                recordId: null,
            }
        },
        created() {
        },
        methods: {
            clickCreate(recordId = null) {
                this.recordId = recordId
                this.showDialog = true
            },
            clickDelete(id) {
                this.destroy(`/${this.resource}/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            }
        }
    }
</script>
