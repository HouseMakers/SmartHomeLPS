(function($, AdminLTE){
    
    "use strict";
    
    SmartHome.Spaces = {
        oTable: null,
        
        init: function() {
            this.attachEvents();
            
            this.loadSpaces();
            
            this.initSensorsDualListBox();
        },
        
        attachEvents: function() {
            $("#createSpace").on('click', function(){
                SmartHome.Spaces.showCreateSpaceModal();
            });
            
            $("#createSpaceForm").submit(function(e){
                e.preventDefault();
                SmartHome.Spaces.createSpace();
            });
            
            $("table").on('click', '.delete-space', function(){
                SmartHome.Spaces.showDeleteSpaceModal($(this).attr("data-id"), $(this).attr("data-name"));
            });
            
            $("#deleteSpaceForm").submit(function(e){
                e.preventDefault();
                SmartHome.Spaces.deleteSpace();
            });
            
            $("table").on('click', '.space-sensors', function(){
                SmartHome.Spaces.showSpaceSensorsModal($(this).attr("data-id"), $(this).attr("data-name"));
            });
            
            $("#spaceSensorsForm").submit(function(e){
                e.preventDefault();
                SmartHome.Spaces.saveSensors();
            });
        },
        
        showCreateSpaceModal: function() {
            $('#createSpaceModal').modal('show');
            
            $("#createSpaceModal").on("hidden.bs.modal", function () {
                $("#createSpaceForm").find("input").val("")
                $("#createSpaceForm").find("textarea").val("")
            });
        },
        
        createSpace: function() {
            $.post(
                SmartHome.baseUri + 'spaces/create', 
                $('#createSpaceForm').serialize()
            )
            .done(function(data) {
                $('#createSpaceModal').modal('hide');
                SmartHome.AlertManager.showAlertSuccess("Sucesso", "Espaço <strong>" + data.space.name + "</strong> cadastrado.");
                SmartHome.Spaces.loadSpaces();
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                SmartHome.AlertManager.showAlertDanger("Opss", jqXHR.responseJSON.error.message);
            });
        },
        
        showDeleteSpaceModal: function(id, name) {
            $('#deleteSpaceModalTitle').find("span").text(name);
            $("#deleteSpaceId").val(id);
            $('#deleteSpaceModal').modal('show');
        },
        
        deleteSpace: function() {
            var id = $("#deleteSpaceId").val();
            
            $.get(
                SmartHome.baseUri + 'spaces/delete/'  + id
            )
            .done(function(data) {
                $('#deleteSpaceModal').modal('hide');
                
                SmartHome.AlertManager.showAlertSuccess("Sucesso", "Spaço <strong>" + data.space.name + "</strong> removido.");
                
                SmartHome.Spaces.loadSpaces();
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                $('#deleteSpaceModal').modal('hide');
                SmartHome.AlertManager.showAlertDanger("Opss", jqXHR.responseJSON.error.message);
            });
        },
        
        showSpaceSensorsModal: function(id, name) {
            $("#spaceSensorsDuallistbox").html("");
            
            $.get(
                SmartHome.baseUri + 'spaces/sensors/' + id
            )
            .done(function(data) {
                for(var i = 0; i < data.available_sensors.length; i++) {
                    $("#spaceSensorsDuallistbox").append(
                        '<option value="' + data.available_sensors[i].id + '"name="sensors[]">' + data.available_sensors[i].name + ' (' + data.available_sensors[i].type +')' + '</option>'
                    );
                }
                
                for(var i = 0; i < data.mapped_sensors.length; i++) {
                    $("#spaceSensorsDuallistbox").append(
                        '<option value="' + data.mapped_sensors[i].id + '" selected="selected" "name="sensors[]">' + data.mapped_sensors[i].name + ' (' + data.available_sensors[i].type +')' + '</option>'
                    );
                }
                
                SmartHome.Spaces.refreshtSensorsDualListBox();
                
                $('#spaceSensorsModalTitle').find("span").text(name);
                $("#spaceSensorsId").val(id);
                $('#spaceSensorsModal').modal('show');
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                
            });
        },
        
        saveSensors: function() {
            var selectedOptions = $("#bootstrap-duallistbox-selected-list_spaceSensorsDuallistbox option");
            
            var sensors = new Array();
            for(var i = 0; i < selectedOptions.length; i++) {
                sensors.push(selectedOptions[i].value);
            }
            
            $.post(
                SmartHome.baseUri + 'spaces/saveSensors/', 
                { id: $("#spaceSensorsId").val(), sensors: sensors }
            )
            .done(function(data) {
                $('#spaceSensorsModal').modal('hide');
                SmartHome.AlertManager.showAlertSuccess("Sucesso", "Sensores do espaço <strong>" + data.space.name + "</strong> atualizados.");
                SmartHome.Spaces.loadSpaces();
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                SmartHome.AlertManager.showAlertDanger("Opss", jqXHR.responseJSON.error.message);
            });
        },
        
        initSensorsDualListBox: function() {
            $('#spaceSensorsDuallistbox').bootstrapDualListbox({
                nonSelectedListLabel: 'Sensores Disponíveis',
                selectedListLabel: 'Sensores Mapeados',
                preserveSelectionOnMove: 'moved',
                moveOnSelect: false,
                filterTextClear: 'Mostrar todos',
                infoText: 'Mostrando todos {0}',
                infoTextEmpty: 'Lista vazia',
                infoTextFiltered: '<span class="label label-warning">Filtrado</span> {0} de {1}',
                moveAllLabel: "Mover todos",
                moveSelectedLabel: "Mover selecionados",
                filterPlaceHolder: "Filtrar",
            });
        },
        
        refreshtSensorsDualListBox: function() {
            $('#spaceSensorsDuallistbox').bootstrapDualListbox("refresh");
        },
        
        loadSpaces: function() {
            if (this.oTable) {
                this.oTable.fnDestroy();
            }
            
            this.oTable = $('#spaces').dataTable( {
                "drawCallback": function( settings ) {
                    $('[data-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });
                },
                "bAutoWidth": false,
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": SmartHome.baseUri + "spaces/search",
                "columns": [
                    {
                        "data": "id"
                    },
                    { 
                        "data": "name" 
                    },
                    { 
                        "data": "opcoes", 
                        "bSortable": false,
                        "sDefaultContent": "",
                        "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                            var options = $("#spacesOptionsTemplates").clone().children();
                            
                            $(options[0]).attr({
                                "id": "space-" + oData.id,
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
                            
                            $(options[2]).attr({
                                "id": oData.id,
                                "data-id": oData.id,
                                "data-name": oData.name
                            });
                            $(nTd).append("     " + $(options[2]).wrap("<div/>").parent().html());
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
        SmartHome.Spaces.init();
    });
    
})(jQuery, $.AdminLTE);