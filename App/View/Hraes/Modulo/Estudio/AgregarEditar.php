<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true" id="agregar_editar_estudio">
    <div class="modal-dialog modal-large modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header background-modal">
                <h5 class="modal-title text-modal-tittle"><label id="tituloEstudio" class="text-modal-tittle"></label>
                    nivel de estudio.</h5>
            </div>

            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <label class="text-input-form div-spacing text-input-rem">Seleccione el nivel de
                                estudio</label><label class="text-required">*</label>
                            <div class="custom-select-wrapper">
                                <select class="form-control" aria-label="Default select example"
                                    id="id_cat_nivel_estudios" required>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="div-spacing"></div>
                    <div class="row">
                        <div class="col-12">
                            <label class="text-input-form div-spacing text-input-rem">Carrera</label><label
                                class="text-required">*</label>
                            <div class="custom-select-wrapper">
                                <select class="form-control" aria-label="Default select example"
                                    id="id_cat_carrera_hraes" required>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="div-spacing"></div>
                    <div class="row">
                        <div class="col-12">
                            <label class="text-input-form text-input-rem">Cédula</label><label
                                class="text-required">*</label>
                            <input type="text" class="form-control" id="cedula_es_" placeholder="Cédula" maxlength="15">
                        </div>
                    </div>
                </div>
            </div>

            <div class="div-spacing"></div>
            <div class="modal-footer">
                <button onclick="salirAgregarEstudio();" type="button" class="btn btn-secondary" data-dismiss="modal"><i
                        class="fas fa-times"></i> Cancelar</button>
                <button type="button" class="btn btn-success save-botton-modal" onclick="return validarEstudio();"><i
                        class="fas fa-save"></i> Guardar</button>
                <input type="hidden" id="id_object">
            </div>

        </div>
    </div>
</div>