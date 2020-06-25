<template>
    <main>
        <section>
            <h1 class="text-center mb-5">Gestión de Cursos</h1>
            <!-- El Filtro -->
            <b-form-group
                label="Filter"
                label-cols-sm="3"
                label-align-sm="right"
                label-size="sm"
                label-for="filterInput"
                class="mb-3"
            >
                <b-input-group size="sm">
                    <b-form-input
                        v-model="filter"
                        type="search"
                        id="filterInput"
                        placeholder="Type to Search"
                    ></b-form-input>
                </b-input-group>
            </b-form-group>
            <!-- La Tabla -->
            <b-table
                ref="table"
                :current-page="currentPage"
                id="tablaIncidencies"
                :per-page="perPage"
                :items="arrayCursos"
                :fields="columnasTabla"
                :filter="filter"
                :filterIncludedFields="filterOn"
                @filtered="onFiltered"
            >
                <template v-slot:cell(gestionar)="row">
                    <b-button
                        size="sm"
                        class="mr-1"
                        @click="mostrarEdit(row.item)"
                        >Editar</b-button
                    >
                    <b-button
                        size="sm"
                        class="mr-1 btn btn-danger"
                        @click="deleteCurso(row.item.id)"
                        >Borrar</b-button
                    >
                </template>
            </b-table>
            <!-- La Paginación -->
            <b-pagination
                v-model="currentPage"
                :per-page="perPage"
                :total-rows="rows"
                aria-controls="tablaIncidencies"
            ></b-pagination>
            <!-- Boton Añadir -->
            <button
                type="button"
                @click="mostrarAdd()"
                class="btn btn-primary btn-block"
            >
                Afegir Curso
            </button>
            <!-- Modal New  -->
            <b-modal
                id="newCursoModal"
                title="Nuevo Curso"
                @ok="newCurso(nuevoCurso)"
                @hidden="cancelar()"
            >
                <form action method="post">
                    <div class="row">
                        <!-- Numero Codigo -->
                        <div class="col-12">
                            Codigo:
                            <input
                                v-model.number="nuevoCurso.codigo"
                                class="form-control"
                                name="codigo"
                                type="text"
                                placeholder="Num.codigo"
                            />
                        </div>

                        <!-- Nombre Curso -->
                        <div class="col-5">
                            Nombre Curso:
                            <input
                                v-model="nuevoCurso.nombre"
                                class="form-control"
                                type="text"
                                name="nombre"
                                placeholder="Nom. Curs"
                            />
                        </div>
                        <!-- Usuaris-->
                        <div class="col-7">
                            Usuari:
                            <select
                                class="form-control"
                                name="id_usuario"
                                v-model="nuevoCurso.id_usuario"
                            >
                                <option :value="null" disabled hidden
                                    >Usuaris</option
                                >
                                <option
                                    v-for="usuari in arrayUsuarios"
                                    :key="usuari.id_usuario"
                                    :value="usuari.id_usuario"
                                    >{{ usuari.nom }}</option
                                >
                            </select>
                        </div>
                        <!-- Descripcion -->
                        <div class="col-12">
                            Descripció:
                            <textarea
                                v-model="nuevoCurso.descripcion"
                                class="form-control"
                                rows="3"
                                name="descripcion"
                                placeholder="Descripció del curs"
                            ></textarea>
                        </div>
                    </div>
                </form>
                <template v-slot:modal-footer>
                    <b-button
                        size="sm"
                        variant="outline-primary"
                        @click="cancelar()"
                        >Cancelar</b-button
                    >
                    <!-- Button with custom close trigger value -->
                    <b-button
                        size="sm"
                        variant="primary"
                        @click="newCurso(nuevoCurso)"
                        >Guardar</b-button
                    >
                </template>
            </b-modal>
            <!-- Modal Edit  -->
            <b-modal
                id="editCursoModal"
                title="Editar Curso"
                @ok="saveCurso(objectCurso.id)"
                @hidden="cancelar()"
            >
                <form action method="put">
                    <div class="row">
                        <!-- Numero Codigo -->
                        <div class="col-12">
                            Codigo:
                            <input
                                v-model.number="objectCurso.codigo"
                                class="form-control"
                                name="codigo"
                                type="text"
                                placeholder="Num.codigo"
                            />
                        </div>
                        <!-- Nombre Curso -->
                        <div class="col-5">
                            Nombre Curso:
                            <input
                                v-model="objectCurso.nombre"
                                class="form-control"
                                type="text"
                                name="nombre"
                                placeholder="Nom. Curs"
                            />
                        </div>
                        <!-- Usuaris-->
                        <div class="col-7">
                            Usuari:
                            <select
                                class="form-control"
                                name="id_usuario"
                                v-model.number="objectCurso.id_usuario"
                            >
                                <option :value="null" disabled hidden
                                    >Usuaris</option
                                >
                                <option
                                    v-for="usuari in arrayUsuarios"
                                    :key="usuari.id_usuario"
                                    :value="usuari.id_usuario"
                                    >{{ usuari.nom }}</option
                                >
                            </select>
                        </div>
                        <!-- Descripcion -->
                        <div class="col-12">
                            Descripció:
                            <textarea
                                v-model="objectCurso.descripcion"
                                class="form-control"
                                rows="3"
                                name="descripcion"
                                placeholder="Descripció del curs"
                            ></textarea>
                        </div>
                    </div>
                </form>
                <template v-slot:modal-footer>
                    <b-button
                        size="sm"
                        variant="outline-primary"
                        @click="cancelar()"
                        >Cancelar</b-button
                    >
                    <!-- Button with custom close trigger value -->
                    <b-button
                        size="sm"
                        variant="primary"
                        @click="saveCurso(objectCurso.id)"
                        >Guardar</b-button
                    >
                </template>
            </b-modal>
        </section>
    </main>
</template>

<script>
export default {
    data() {
        return {
            arrayUsuarios: [],
            objectCurso: {
                id: null,
                codigo: null,
                nombre: "",
                descripcion: "",
                id_usuario: null
            },
            nuevoCurso: {
                codigo: null,
                nombre: "",
                descripcion: "",
                id_usuario: null
            },
            arrayCursos: [],
            columnasTabla: [
                { key: "codigo", label: "Codigo" },
                { key: "nombre", label: "Nombre" },
                { key: "descripcion", label: "Descripción" },
                { key: "usuaris.nom", label: "Nombre Usuario" },
                "gestionar"
            ],
            perPage: 5,
            currentPage: 1,
            filter: null,
            filterOn: []
        };
    },
    mounted() {
        this.obtenerCursos();
        this.obtenerUsuarios();
    },
    methods: {
        obtenerCursos() {
            axios.get("/cursos").then(response => {
                this.arrayCursos = response.data.data;
            });
        },
        obtenerUsuarios() {
            axios
                .get("/usuarios")
                .then(response => {
                    this.arrayUsuarios = response.data.data;
                })
                .catch(e => console.log(e));
        },
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },

        mostrarAdd() {
            let me = this;
            this.$bvModal.show("newCursoModal");
        },
        mostrarEdit(item) {
            let me = this;
            me.objectCurso = item;
            this.$bvModal.show("editCursoModal");
        },

        deleteCurso(idObjecte) {
            let me = this;

            axios
                .delete("/cursos/" + idObjecte)
                .then(function(response) {
                    me.obtenerCursos();
                })
                .catch(error => console.log(error));
        },
        saveCurso(idObjecte) {
            let me = this;

            axios
                .put("/cursos/" + idObjecte, me.objectCurso)
                .then(function(response) {
                    console.log("Contingut del error", response.data);
                    me.cancelar();
                })
                .catch(error => console.log(error));
        },
        newCurso(nuevoElemento) {
            let me = this;

            axios
                .post("/cursos", nuevoElemento)
                .then(function(response) {
                    me.cancelar();
                })
                .catch(error => console.log(error));
        },
        cancelar() {
            this.obtenerCursos();
            this.$bvModal.hide("editCursoModal");
            this.$bvModal.hide("newCursoModal");
        }
    },
    computed: {
        rows() {
            return this.arrayCursos.length;
        }
    }
};
</script>

<style lang="scss" scoped>
section {
    padding-top: 20px;
    padding-bottom: 20px;
    height: 100vh;
}
.modal-content {
    width: 100% !important;
    position: absolute !important;
    top: 200px !important;
}
.mostrar {
    display: list-item !important;
    opacity: 1 !important;
    position: absolute !important;
    background-color: #00000077 !important;
}
form div {
    margin-bottom: 2rem;
}
</style>
