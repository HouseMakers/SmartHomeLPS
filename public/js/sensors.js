(function($, AdminLTE){
    
    "use strict";
    
    SmartHome.Sensors = {
        oTable: null,
        
        init: function() {
            this.attachEvents();
            
            this.loadSensors();
        },
        
        attachEvents: function() {
            $("#createSensor").on('click', function(){
                SmartHome.Sensors.showCreateSensorModal();
            });
            
            $("#createSensorForm").submit(function(e){
                e.preventDefault();
                SmartHome.Sensors.createSensor();
            });
            
            $("table").on('click', '.delete-sensor', function(){
                SmartHome.Sensors.showDeleteSensorModal($(this).attr("data-id"), $(this).attr("data-name"));
            });
            
            $("#deleteSensorForm").submit(function(e){
                e.preventDefault();
                SmartHome.Sensors.deleteSensor();
            });
        },
        
        showCreateSensorModal: function() {
            $('#createSensorModal').modal('show');
            
            $("#createSensorModal").on("hidden.bs.modal", function () {
                $("#createSensorForm").find("input").val("")
                $("#createSensorForm").find("textarea").val("")
            });
        },
        
        createSensor: function() {
            $.post(
                SmartHome.baseUri + 'sensors/create', 
                $('#createSensorForm').serialize()
            )
            .done(function(data) {
                $('#createSensorModal').modal('hide');
                SmartHome.AlertManager.showAlertSuccess("Sucesso", "Sensor <strong>" + data.sensor.type + "</strong> cadastrado.");
                SmartHome.Sensors.loadSensors();
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                SmartHome.AlertManager.showAlertDanger("Opss", jqXHR.responseJSON.error.message);
            });
        },
        
        showDeleteSensorModal: function(id, name) {
            $('#deleteSensorModalTitle').find("span").text(name);
            $("#deleteSensorId").val(id);
            $('#deleteSensorModal').modal('show');
        },
        
        deleteSensor: function() {
            var id = $("#deleteSensorId").val();
            
            $.get(
                SmartHome.baseUri + 'sensors/delete/'  + id
            )
            .done(function(data) {
                $('#deleteSensorModal').modal('hide');
                
                SmartHome.AlertManager.showAlertSuccess("Sucesso", "Sensor <strong>" + data.sensor.type + "</strong> removido.");
                
                SmartHome.Sensors.loadSensors();
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                $('#deleteSensorModal').modal('hide');
                SmartHome.AlertManager.showAlertDanger("Opss", jqXHR.responseJSON.error.message);
            });
        },
        
        loadSensors: function() {
            if (this.oTable) {
                this.oTable.fnDestroy();
            }
            
            this.oTable = $('#sensors').dataTable( {
                "drawCallback": function( settings ) {
                    $('[data-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });
                },
                "bAutoWidth": false,
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": SmartHome.baseUri + "sensors/search",
                "columns": [
                    {
                        "data": "id"
                    },
                    { 
                        "data": "name" 
                    },
                    { 
                        "data": "type" 
                    },
                    { 
                        "data": "status" 
                    },
                    { 
                        "data": "opcoes", 
                        "bSortable": false,
                        "sDefaultContent": "",
                        "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                            var options = $("#sensorsOptionsTemplates").clone().children();
                            
                            $(options[0]).attr({
                                "id": "sensor-" + oData.id,
                                "data-id": oData.id,
                                "data-name": oData.type
                            });
                            $(nTd).append($(options[0]).wrap("<div/>").parent().html());
                            
                            $(options[1]).attr({
                                "id": oData.id,
                                "data-id": oData.id,
                                "data-name": oData.type
                            });
                            $(nTd).append("     " + $(options[1]).wrap("<div/>").parent().html());
                        }
                    }
                ],
                 "oLanguage": {
                    "sProcessing": "Aguarde enquanto os dados são carregados ...",
                    "sLengthMenu": "Mostrar _MENU_ registros por pagina",
                    "sZeroRecords": "Nenhum registro correspondente ao criterio encontrado",
                    "sInfoEmpty": "Exibindo 0 a 0 de 0 registros",
                    "sInfo": "Exibindo de _START_ a _END_ de _TOTAL_ registros",
                    "sInfoFiltered": "",
                    "sSearch": '',
                    "sSearchPlaceholder": "Pesquisar",
                    "oPaginate": {
                       "sFirst":    "Primeiro",
                       "sPrevious": "Anterior",
                       "sNext":     "Próximo",
                       "sLast":     "Último"
                    },
                }
            });
        }
    };
    
    $(document).ready(function() {
        SmartHome.Sensors.init();
    });
    
})(jQuery, $.AdminLTE);