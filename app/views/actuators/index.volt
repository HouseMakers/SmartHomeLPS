{% include "layouts/dashboardHeader.volt" %}

<section class="content">
    <!-- Items row -->
    <div class="row">
        <!-- Left col -->
        <section class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Atuadores</h3>
                    
                    <button type="button" class="btn btn-primary pull-right" id="createActuator" data-toggle="tooltip" data-placement="left" title="Cadastrar um atuador">
                        <i class="fa fa-plus"></i> Adicionar Atuador
                    </button>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="actuators" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Nome</th>
                                <th>Status</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                        
                        <tfoot>
                            <tr>
                                <th>id</th>
                                <th>Nome</th>
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
        <!-- Create actuator modal form -->
        <div class="modal fade" id="createActuatorModal" tabindex="-1" role="dialog" aria-labelledby="createActuatorModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title">Novo Atuador</h4>
                    </div>

                    <div class="modal-body">
                        <form role="form" id="createActuatorForm">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="actuatorName">Nome</label>
                                    <input type="text" class="form-control" id="actuatorName" name="name" placeholder="Informe o nome do atuador" required>
                                </div>

                                <div class="form-group">
                                    <label for="actuatorDescription">Descrição</label>
                                    <textarea class="form-control" rows="3" id="actuatorDescription" name="description" placeholder="Descrição"></textarea>
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
        
        <!-- Delete actuator modal form -->
        <div class="modal fade" id="deleteActuatorModal" tabindex="-1" role="dialog" aria-labelledby="deleteActuatorModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title" id="deleteActuatorModalTitle">Remover Atuador - <span> </span></h4>
                    </div>

                    <div class="modal-body">
                        <div class="box-body">
                            <p>Tem certeza que deseja remover esse atuador?</p>
                        </div>
                        <!-- /.box-body -->

                        <form role="form" id="deleteActuatorForm">
                            <input type="hidden" id="deleteActuatorId" name="id">

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
    </div>
    <!-- /.modals -->
    
    <div class="hidden" id="actuatorsOptionsTemplates">
        <button class="btn btn-primary btn-circle edit-actuator" data-container="body" data-toggle="tooltip" data-html="true" data-placement="top" title="Editar">
            <i class="fa fa-pencil"></i>
        </button>

        <button class="btn btn-danger btn-circle delete-actuator" data-container="body" data-toggle="tooltip" data-html="true" data-placement="top" title="Remover">
            <i class="fa fa-trash"></i>
        </button>
    </div>
</section>
<!-- /.content -->

{% include "layouts/dashboardScripts.volt" %}

{{ javascript_include('js/actuators.js') }}

{% include "layouts/dashboardFooter.volt" %}