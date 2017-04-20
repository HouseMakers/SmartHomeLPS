{% include "layouts/dashboardHeader.volt" %}

<section class="content">
    <!-- Items row -->
    <div class="row">
        <!-- Left col -->
        <section class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Sensores</h3>
                    
                    <button type="button" class="btn btn-primary pull-right" id="createSensor" data-toggle="tooltip" data-placement="left" title="Cadastrar um sensor">
                        <i class="fa fa-plus"></i> Adicionar Sensor
                    </button>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="sensors" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>nome</th>
                                <th>Tipo</th>
                                <th>Status</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                        
                        <tfoot>
                            <tr>
                                <th>id</th>
                                <th>nome</th>
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
        <!-- Create sensor modal form -->
        <div class="modal fade" id="createSensorModal" tabindex="-1" role="dialog" aria-labelledby="createSensorModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title">Novo Sensor</h4>
                    </div>

                    <div class="modal-body">
                        <form role="form" id="createSensorForm">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="type">Tipo</label>
                                    {{ form.render("name") }}  
                                </div>
                                
                                <div class="form-group">
                                    <label for="type">Tipo</label>
                                    {{ form.render("type") }}  
                                </div>

                                <div class="form-group">
                                    <label for="type">Descrição</label>
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
        
        <!-- Delete sensor modal form -->
        <div class="modal fade" id="deleteSensorModal" tabindex="-1" role="dialog" aria-labelledby="deleteSensorModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title" id="deleteSensorModalTitle">Remover Sensor - <span> </span></h4>
                    </div>

                    <div class="modal-body">
                        <div class="box-body">
                            <p>Tem certeza que deseja remover esse sensor?</p>
                        </div>
                        <!-- /.box-body -->

                        <form role="form" id="deleteSensorForm">
                            <input type="hidden" id="deleteSensorId" name="id">

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
    
    <div class="hidden" id="sensorsOptionsTemplates">
        <button class="btn btn-primary btn-circle edit-sensor" data-container="body" data-toggle="tooltip" data-html="true" data-placement="top" title="Editar">
            <i class="fa fa-pencil"></i>
        </button>

        <button class="btn btn-danger btn-circle delete-sensor" data-container="body" data-toggle="tooltip" data-html="true" data-placement="top" title="Remover">
            <i class="fa fa-trash"></i>
        </button>
    </div>
</section>
<!-- /.content -->

{% include "layouts/dashboardScripts.volt" %}

{{ javascript_include('js/sensors.js') }}

{% include "layouts/dashboardFooter.volt" %}