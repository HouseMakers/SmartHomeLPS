(function($, AdminLTE){
    
    "use strict";
    
    SmartHome.Actuators = {
        oTable: null,
        
        init: function() {
            this.attachEvents();
            
            this.loadActuators();
        },
        
        attachEvents: function() {
            $("#createActuator").on('click', function(){
                SmartHome.Actuators.showCreateActuatorModal();
            });
            
            $("#createActuatorForm").submit(function(e){
                e.preventDefault();
                SmartHome.Actuators.createActuator();
            });
            
            $("table").on('click', '.delete-actuator', function(){
                SmartHome.Actuators.showDeleteActuatorModal($(this).attr("data-id"), $(this).attr("data-name"));
            });
            
            $("#deleteActuatorForm").submit(function(e){
                e.preventDefault();
                SmartHome.Actuators.deleteActuator();
            });
        },
        
        showCreateActuatorModal: function() {
            $('#createActuatorModal').modal('show');
            
            $("#createActuatorModal").on("hidden.bs.modal", function () {
                $("#createActuatorForm").find("input").val("")
                $("#createActuatorForm").find("textarea").val("")
            });
        },
        
        createActuator: function() {
            $.post(
                SmartHome.baseUri + 'actuators/create', 
                $('#createActuatorForm').serialize()
            )
            .done(function(data) {
                $('#createActuatorModal').modal('hide');
                SmartHome.AlertManager.showAlertSuccess("Sucesso", "Atuador <strong>" + data.actuator.name + "</strong> cadastrado.");
                SmartHome.Actuators.loadActuators();
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                SmartHome.AlertManager.showAlertDanger("Opss", jqXHR.responseJSON.error.message);
            });
        },
        
        showDeleteActuatorModal: function(id, name) {
            $('#deleteActuatorModalTitle').find("span").text(name);
            $("#deleteActuatorId").val(id);
            $('#deleteActuatorModal').modal('show');
        },
        
        deleteActuator: function() {
            var id = $("#deleteActuatorId").val();
            
            $.get(
                SmartHome.baseUri + 'actuators/delete/'  + id
            )
            .done(function(data) {
                $('#deleteActuatorModal').modal('hide');
                
                SmartHome.AlertManager.showAlertSuccess("Sucesso", "Atuador <strong>" + data.actuator.name + "</strong> removido.");
                
                SmartHome.Actuators.loadActuators();
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                $('#deleteActuatorModal').modal('hide');
                SmartHome.AlertManager.showAlertDanger("Opss", jqXHR.responseJSON.error.message);
            });
        },
        
        loadActuators: function() {
            if (this.oTable) {
                this.oTable.fnDestroy();
            }
            
            this.oTable = $('#actuators').dataTable( {
                "drawCallback": function( settings ) {
                    $('[data-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });
                },
                "bAutoWidth": false,
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": SmartHome.baseUri + "actuators/search",
                "columns": [
                    {
                        "data": "id"
                    },
                    { 
                        "data": "name" 
                    },
                    { 
                        "data": "status" 
                    },
                    { 
                        "data": "opcoes", 
                        "bSortable": false,
                        "sDefaultContent": "",
                        "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                            var options = $("#actuatorsOptionsTemplates").clone().children();
                            
                            $(options[0]).attr({
                                "id": "actuator-" + oData.id,
                                "data-id": oData.id,
                                "data-name": oData.name
                            });
                            $(nTd).append($(options[0]).wrap("<div/>").parent().html());
                            
                            $(options[1]).attr({
                                "id": oData.id,
                                "data-id": oData.id,
                                "data-name": oData.name
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
        SmartHome.Actuators.init();
    });
    
})(jQuery, $.AdminLTE);