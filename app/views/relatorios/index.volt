{#<script src="{{baseUri}}public/js/jquery.bootstrap-duallistbox.js"></script>#}
{#<script src="{{baseUri}}public/js/bootstrap-datepicker.min.js"></script>#}
{#<link href="{{baseUri}}public/css/bootstrap-datepicker.min.css" rel="stylesheet">#}
{#<script src="{{baseUri}}public/js/jquery.mask.min.js"></script>#}
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.4.0/moment.min.js"></script>
{% include "layouts/dashboardHeader.volt" %}

<div  id="bla" role="alert">
</div>

<div class="row" style="margin-left: 15px;margin-right: 0">
    <h2>Relatório</h2>
    <br/>

    {% if reportTypes | length > 0 %}
        <form method="POST" action="{{baseurl}}relatorios/generateReport/" id="main">
            <div class="row" style="">
                <div class='col-md-11'>
                    <legend>Tipo</legend>
                </div>

                <div class='col-md-11'>
                    <div class="form-group">
                        <div class='row'>
                            <div class='col-md-1' style="padding-top: 5px;">
                                <label for="sel1">Tipo</label>
                            </div>

                            <div class='col-md-5'>
                                <select class="form-control" id="sel1">
                                    <option selected disabled> Selecione um tipo </option>
                                    {% for reportType in reportTypes %}
                                        <option> {{ reportType['name'] }} </option>
                                    {% endfor %}
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <div class="row" style="">
            <div class='col-md-11'>
                <legend>Período</legend>
            </div>

            <div class='col-md-11'>
                <div class="form-group">
                    <div class='row'>
                        <div class='col-md-1' style="padding-top: 5px;">
                            <strong> De </strong>
                        </div>

                        <div class='col-md-5'>
                            <div class="input-group date">
                                <input name="startDate" id="startDate" type="text" class="form-control" placeholder="dd/mm/aaaa">
                                <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-calendar"></i>
                                </span>
                            </div>
                        </div>

                        <div class='col-md-1' style="padding-top: 5px;">
                            <strong> Até </strong>
                        </div>

                        <div class='col-md-5'>
                            <div class="input-group date">
                                <input name="endDate" id="endDate" type="text" class="form-control" placeholder="dd/mm/aaaa">
                                <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br/>

        <div class="form-group">
            <button type="button" class="btn btn-primary ladda-button gerar-relatorio" data-style="zoom-in">Gerar Relatório</button>
        </div>
    </form>
    {% else %}
        <h4> Seu produto não tem nenhum tipo de relatório disponível </h4>
    {% endif %}

    <br/><br/>
</div>

{% include "layouts/dashboardScripts.volt" %}

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.0/js/bootstrap-datepicker.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.4.0/moment.min.js"></script>

<script>
    $(document).ready(function () {
        function validate() {
            var currentDate = moment();
            var startDate = moment($('#startDate').val(), 'DD/MM/YYYY');
            var endDate = moment($('#endDate').val(), 'DD/MM/YYYY');


            if(
                    startDate.isValid() && endDate.isValid() && startDate.isBefore(endDate) &&
                    startDate.isBefore(currentDate) && endDate.isBefore(currentDate) && startDate.year() >= 2000
            ) {
                return true;
            }
            else {
                alert("Informe datas válidas.");
            }

            return false;
        }

        $(document).on("click", ".gerar-relatorio", function(){
            if (validate()) {
                $("#main").submit();
            }
        });

        $('.input-group.date').datepicker({
            format: "dd/mm/yyyy",
            language: "pt-BR"
        });
        $('#startDate, #endDate').mask('00/00/0000');
    });

</script>

{% include "layouts/dashboardFooter.volt" %}