{% include "layouts/dashboardHeader.volt" %}

<section class="content">
    <!-- Items row -->
    <div class="row">
        <!-- Left col -->
        <section class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Alertas</h3>
                    
                    <button type="button" class="btn btn-primary pull-right" id="createAlert" data-toggle="tooltip" data-placement="left" title="Cadastrar um atuador">
                        <i class="fa fa-plus"></i> Novo Alerta
                    </button>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="alertsTemplate" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Título</th>
                                <th>Espaço</th>
                                <th>Status</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                        
                        <tfoot>
                            <tr>
                                <th>id</th>
                                <th>Título</th>
                                <th>Espaço</th>
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
        <!-- Create Alert modal form -->
        <div class="modal fade" id="createAlertModal" tabindex="-1" role="dialog" aria-labelledby="createAlertModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title">Novo Alerta</h4>
                    </div>

                    <div class="modal-body">
                        <form role="form" id="createAlertForm">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="alertName">Título</label>
                                    <input type="text" class="form-control" id="alertTitle" name="title" placeholder="Informe o título do alerta" required>
                                </div>

                                <div class="form-group">
                                    <label for="alertDescription">Descrição</label>
                                    <textarea class="form-control" rows="3" id="alertDescription" name="description" placeholder="Descrição"></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label for="alertMessage">Mensagem</label>
                                    <textarea class="form-control" rows="3" id="alertMessage" name="message" placeholder="Mensagem"></textarea>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="alertSpace">Espaço</label>
                                            <select class="form-control" rows="3" id="alertSpace" name="space" style="width: 100%">
                                                <option></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="alertSensor">Sensor</label>
                                            <select type="text" class="form-control"  id="alertSensor" name="sensor" placeholder="Parâmetro" style="width: 100%"> 
                                                <option></option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div id="alertCondition" class="hidden">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="alertCondition">Condição</label>
                                                <select type="text" class="form-control"  id="alertCondition" name="condition" placeholder="Condição"> 
                                                    <option> < </option>
                                                    <option> > </option>
                                                    <option> = </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="alertValue">Valor</label>
                                                <input input type="text" class="form-control"  id="alertValue" name="value" placeholder="Valor">
                                            </div>
                                        </div>
                                    </div>
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
        
        <!-- Delete alert modal form -->
        <div class="modal fade" id="deleteAlertTemplateModal" tabindex="-1" role="dialog" aria-labelledby="deleteAlertTemplateModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title" id="deleteAlertTemplateModalTitle">Remover Alerta - <span> </span></h4>
                    </div>

                    <div class="modal-body">
                        <div class="box-body">
                            <p>Tem certeza que deseja remover esse alerta?</p>
                        </div>
                        <!-- /.box-body -->

                        <form role="form" id="deleteAlertTemplateForm">
                            <input type="hidden" name="id">

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
    
    <div class="hidden" id="alertsTemplatesOptionsTemplates">
        <button class="btn btn-primary btn-circle edit-alert-template" data-container="body" data-toggle="tooltip" data-html="true" data-placement="top" title="Editar">
            <i class="fa fa-pencil"></i>
        </button>

        <button class="btn btn-danger btn-circle delete-alert-template" data-container="body" data-toggle="tooltip" data-html="true" data-placement="top" title="Remover">
            <i class="fa fa-trash"></i>
        </button>
    </div>
</section>
<!-- /.content -->

{% include "layouts/dashboardScripts.volt" %}

{{ javascript_include('js/alertstemplate.js') }}

{% include "layouts/dashboardFooter.volt" %}