(function($, AdminLTE){
    
    "use strict";
    
    SmartHome.AlertsTemplate = {
        oTable: null,
        
        init: function() {
            this.attachEvents();
            
            this.loadAlertsTemplate();
        },
        
        attachEvents: function() {
            $("#createAlert").on('click', function(){
                SmartHome.AlertsTemplate.showCreateAlertTemplateModal();
            });
            
            $("#alertSpace").on("select2:select", function (e) {
                SmartHome.AlertsTemplate.loadSpaceSensors(e.params.data.id);
            });
            
            $("#alertSensor").on("select2:select", function (e) {
                SmartHome.AlertsTemplate.handleSpaceSensorSelection(e.params.data.id);
            });
            
            $("#createAlertForm").submit(function(e){
                e.preventDefault();
                SmartHome.AlertsTemplate.createAlertTemplate();
            });
            
            $(document).on('switchChange.bootstrapSwitch','.status-changer', function(event, state) {
                SmartHome.AlertsTemplate.changeAlertTemplateStatus($(this).data("id"), state);
            });
            
            $("table").on('click', '.delete-alert-template', function(){
                SmartHome.AlertsTemplate.showDeleteAlertTemplateModal($(this).attr("data-id"), $(this).attr("data-title"));
            });
            
            $("#deleteAlertTemplateForm").submit(function(e){
                e.preventDefault();
                SmartHome.AlertsTemplate.deleteAlertTemplate();
            });
        },
        
        showCreateAlertTemplateModal: function() {
            $.get(
                SmartHome.baseUri + 'spaces/list/'
            )
            .done(function(data) {
                var spaces = data.spaces;
                
                var options = new Array();
                for (var i = 0; i < spaces.length; ++i) {
                    options.push({id: spaces[i].id, text: spaces[i].name});
                }
                
                $("#alertSpace").select2({
                    data: options,
                    placeholder: 'Por favor, selecione um espaço...'
                });
                
                $('#createAlertModal').modal('show');
                
                $("#createAlertModal").on("hidden.bs.modal", function () {
                    $("#createAlertForm").find("input").val("")
                    $("#createAlertForm").find("textarea").val("")
                });
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                $("#alertSpace").select2({
                    data: [],
                    placeholder: 'Por favor, selecione um espaço...'
                });
            });
        },
        
        createAlertTemplate: function() {
            $.post(
                SmartHome.baseUri + 'alertstemplate/create', 
                $('#createAlertForm').serialize()
            )
            .done(function(data) {
                $('#createAlertModal').modal('hide');
                SmartHome.AlertManager.showAlertSuccess("Sucesso", "Alerta <strong>" + data.alert.title + "</strong> cadastrado.");
                SmartHome.AlertsTemplate.loadAlertsTemplate();
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                SmartHome.AlertManager.showAlertDanger("Opss", jqXHR.responseJSON.error.message);
            });
        },
        
        showDeleteAlertTemplateModal: function(id, title) {
            $('#deleteAlertTemplateModalTitle').find("span").text(title);
            $("#deleteAlertTemplateForm input[name='id']").val(id);
            $('#deleteAlertTemplateModal').modal('show');
        },
        
        deleteAlertTemplate: function() {
            var id = $("#deleteAlertTemplateForm input[name='id']").val();
            
            $.get(
                SmartHome.baseUri + 'alertstemplate/delete/' + id
            )
            .done(function(data) {
                $('#deleteAlertTemplateModal').modal('hide');
                SmartHome.AlertManager.showAlertSuccess("Sucesso", "Alerta <strong>" + data.alert.title + "</strong> removido.");
                SmartHome.AlertsTemplate.loadAlertsTemplate();
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                $('#deleteAlertTemplateModal').modal('hide');
                SmartHome.AlertManager.showAlertDanger("Opss", jqXHR.responseJSON.error.message);
            });
        },
        
        loadSpaceSensors: function(id) {
            $("#alertSensor").find('option').remove();
            $("#alertSensor").append('<option></option>');
            
            $.get(
                SmartHome.baseUri + 'spaces/features/' + id
            )
            .done(function(data) {
                var features = data.features;
                
                var options = new Array();
                for (var i = 0; i < features.length; ++i) {
                    options.push({id: features[i].type, text: features[i].name});
                }
                
                $("#alertSensor").select2({
                    data: options,
                    placeholder: 'Sensor',
                    language: "pt-BR"
                });
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                $("#alertSensor").select2({
                    data: [],
                    placeholder: 'Sensor'
                });
            });
        },
        
        handleSpaceSensorSelection: function(sensor) {
            $.get(
                SmartHome.baseUri + 'sensors/info/' + sensor
            )
            .done(function(data) {
                console.log(data);
                var dataType = data.sensor.dataType;
                
                if (dataType == "Boolean") {
                    $("#alertCondition").addClass("hidden");
                }
                else {
                    $("#alertCondition").removeClass("hidden");
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                alert("Ocorreu um problema, tente recarregar a página.");
            });
        },
        
        changeAlertTemplateStatus: function(id, status)
        {
            $.post(
                SmartHome.baseUri + 'alertstemplate/changeStatus', 
                {id: id, status: status}
            );
        },
        
        loadAlertsTemplate: function() {
            if (this.oTable) {
                this.oTable.fnDestroy();
            }
            
            this.oTable = $('#alertsTemplate').dataTable( {
                "drawCallback": function( settings ) {
                    $('[data-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });
                    
                    $(".status-changer").bootstrapSwitch({onColor: "success", offColor: "danger"});
                },
                "bAutoWidth": false,
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": SmartHome.baseUri + "alertstemplate/search",
                "columns": [
                    {
                        "data": "id"
                    },
                    { 
                        "data": "title" 
                    },
                    { 
                        "data": "space" 
                    },
                    { 
                        "data": "status",
                        "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                            $(nTd).empty();
                            if(oData.status == "ON") {
                                $(nTd).append('<input type="checkbox" class="status-changer" data-id="' + oData.id + '" checked>');
                            }
                            else {
                                $(nTd).append('<input type="checkbox" class="status-changer" data-id="' + oData.id + '">');
                            }
                        }
                    },
                    { 
                        "data": "opcoes", 
                        "bSortable": false,
                        "sDefaultContent": "",
                        "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                            var options = $("#alertsTemplatesOptionsTemplates").clone().children();
                            
                            $(options[0]).attr({
                                "id": "alert-template-" + oData.id,
                                "data-id": oData.id,
                                "data-title": oData.title
                            });
                            $(nTd).append($(options[0]).wrap("<div/>").parent().html());
                            
                            $(options[1]).attr({
                                "id": oData.id,
                                "data-id": oData.id,
                                "data-title": oData.title
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
        SmartHome.AlertsTemplate.init();
    });
    
})(jQuery, $.AdminLTE);