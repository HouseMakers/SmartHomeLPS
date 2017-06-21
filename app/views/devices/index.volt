{% include "layouts/dashboardHeader.volt" %}

<section class="content">
    <!-- Items row -->
    <div class="row">
        <!-- Left col -->
        <section class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Dispositivos</h3>
                    
                    <button type="button" class="btn btn-primary pull-right" id="createDevice" data-toggle="tooltip" data-placement="left" title="Cadastrar um dispositivo">
                        <i class="fa fa-plus"></i> Adicionar Dispositivo
                    </button>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="devices" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Nome</th>
                                <th>Tipo</th>
                                <th>Status</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                        
                        <tfoot>
                            <tr>
                                <th>id</th>
                                <th>Nome</th>
                                <th>Tipo</th>
                                <th>Status</th>
                                <th>Opções</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </section>
        <!-- /.Left col -->
    </div>
    <!-- /.row (items row) -->
    
    <div id="menuModals">
        <!-- Create device modal form -->
        <div class="modal fade" id="createDeviceModal" tabindex="-1" role="dialog" aria-labelledby="createDeviceModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title">Novo Dispositivo</h4>
                    </div>

                    <div class="modal-body">
                        <form role="form" id="createDeviceForm">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="name">Nome</label>
                                    {{ form.render("name") }}  
                                </div>

                                <div class="form-group">
                                    <label for="type">Tipo</label>
                                    {{ form.render("type") }}  
                                </div>

                                <div class="form-group">
                                    <label for="description">Descrição</label>
                                    {{ form.render("description") }}  
                                </div>
                            </div>
                            <!-- /.box-body -->

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Cadastrar</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        
        <!-- Edit category modal form -->
        <div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title" id="editCategoryModalTitle">Editar Categoria - <span> </span></h4>
                    </div>

                    <div class="modal-body">
                        <form role="form" id="editCategoryForm">
                            <div class="box-body">
                                <input type="hidden" id="editCategoryId" name="id">

                                <div class="form-group">
                                    <label for="editCategoryName">Nome da Categoria</label>
                                    <input type="text" class="form-control" id="editCategoryName" name="name" placeholder="Informe o nome da categoria" required>
                                </div>

                                <div class="form-group">
                                    <label for="editCategoryDescription">Descrição</label>
                                    <textarea class="form-control" rows="3" id="editCategoryDescription" name="description" placeholder="Fale um pouco sobre a categoria..."></textarea>
                                </div>
                            </div>
                            <!-- /.box-body -->

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        
        <!-- Delete device modal form -->
        <div class="modal fade" id="deleteDeviceModal" tabindex="-1" role="dialog" aria-labelledby="deleteDeviceModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title" id="deleteDeviceModalTitle">Remover Dispositivo - <span> </span></h4>
                    </div>

                    <div class="modal-body">
                        <div class="box-body">
                            <p>Tem certeza que deseja remover esse dispositivo?</p>
                        </div>
                        <!-- /.box-body -->

                        <form role="form" id="deleteDeviceForm">
                            <input type="hidden" id="deleteDeviceId" name="id">

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-danger">Remover</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        
        <!-- Act device modal form -->
        <div class="modal fade" id="deviceActModal" tabindex="-1" role="dialog" aria-labelledby="deviceActModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title" id="deviceActModalTitle">Controlar Dispositivo - <span> </span></h4>
                    </div>

                    <div class="modal-body">
                        <form role="form" id="deviceActForm">
                            <div class="box-body">
                                <input type="hidden" id="device" name="device">
                                <input type="hidden" id="action" name="action">
                                
                                <div id="actParameters">
                                </div>
                            </div>
                            <!-- /.box-body -->
                            
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-danger">Atuar</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </div>
    <!-- /.modals -->
    
    <div class="hidden" id="devicesOptionsTemplates">
        <button class="btn btn-primary btn-circle edit-device" data-container="body" data-toggle="tooltip" data-html="true" data-placement="top" title="Editar">
            <i class="fa fa-pencil"></i>
        </button>

        <button class="btn btn-danger btn-circle delete-device" data-container="body" data-toggle="tooltip" data-html="true" data-placement="top" title="Remover">
            <i class="fa fa-trash"></i>
        </button>
        
        <div class="dropdown act-device" style="display: inline">
            <button class="btn btn-warning dropdown-toggle" type="button" data-toggle="dropdown">
                <i class="fa fa-magic"></i>
                <span class="caret"></span>
            </button>
            
            <ul class="dropdown-menu actions">
            </ul>
        </div>
    </div>
</section>
<!-- /.content -->

{% include "layouts/dashboardScripts.volt" %}

{{ javascript_include('js/devices.js') }}

{% include "layouts/dashboardFooter.volt" %}